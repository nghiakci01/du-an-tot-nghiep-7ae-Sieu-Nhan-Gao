<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\VtonModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Str;

use App\Models\VtonHistory;
use Illuminate\Support\Facades\File;

class VtonController extends Controller
{
    /**
     * Process Virtual Try-On request
     */
    public function tryOn(Request $request)
    {
        try {
            // Validate request
            $request->validate([
                'user_image' => 'nullable|mimes:jpeg,jpg,png,webp|max:5120|dimensions:min_width=400,min_height=400',
                'product_id' => 'required|exists:products,id',
                'vton_model_id' => 'nullable|exists:vton_models,id'
            ]);

            $product = Product::findOrFail($request->product_id);
            if (!$product->image) {
                return response()->json(['success' => false, 'message' => 'Sản phẩm này chưa có ảnh.'], 400);
            }

            // 1. Get Human Image (User Upload vs Pre-defined Model)
            $humanData = $this->getHumanData($request, $product);
            if (!$humanData['success']) {
                return response()->json(['success' => false, 'message' => $humanData['message']], 400);
            }

            $humanImageBase64 = $humanData['base64'];
            $humanCacheKey = $humanData['cache_key'];

            // 2. Get Product Image Base64
            $productPath = storage_path('app/public/' . $product->image);
            if (!file_exists($productPath)) {
                return response()->json(['success' => false, 'message' => 'Không tìm thấy ảnh sản phẩm.'], 404);
            }
            $productImageBase64 = 'data:' . mime_content_type($productPath) . ';base64,' . base64_encode(file_get_contents($productPath));

            // 3. Check Cache
            $cacheKey = 'vton_' . md5($humanCacheKey . '_' . $product->id);
            if (Cache::has($cacheKey)) {
                return response()->json([
                    'success' => true,
                    'image_url' => Cache::get($cacheKey),
                    'message' => 'Thử đồ thành công! (Cached)'
                ]);
            }

            // 4. Select VTON Category
            $category = $this->mapCategory($product);

            // 5. Call Hugging Face API (OOTDiffusion)
            // URL: https://levihsu-ootdiffusion.hf.space/call/process_hd
            $hfUrl = 'https://levihsu-ootdiffusion.hf.space/call/process_hd';
            
            Log::info('VTON HF Request', ['product_id' => $product->id, 'category' => $category]);

            $payload = [
                'data' => [
                    ['url' => $humanImageBase64, 'meta' => ['_type' => 'gradio.FileData']],
                    ['url' => $productImageBase64, 'meta' => ['_type' => 'gradio.FileData']],
                    $category,
                    1,   // Model select
                    20,  // Steps
                    2.0, // Guidance scale
                    -1   // Seed
                ]
            ];

            $response = Http::timeout(60)->post($hfUrl, $payload);
            $eventId = $response->json('event_id');

            if (!$eventId) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Hệ thống AI đang bận (Queue Full). Vui lòng đợi 30 giây và thử lại.',
                    'debug' => $response->body()
                ], 503);
            }

            // 6. Polling for result via stream endpoint
            // HF Spaces use Server-Sent Events or long-polling on the event ID
            $startTime = time();
            $maxWait = 120; // 2 minutes max
            $resultUrl = null;

            while ((time() - $startTime) < $maxWait) {
                $statusRes = Http::timeout(120)->get($hfUrl . '/' . $eventId);
                
                if ($statusRes->successful()) {
                    $body = $statusRes->body();
                    
                    // Gradio response format for finished event:
                    // data: [{"url": "...", ...}]
                    // event: complete
                    if (Str::contains($body, 'event: complete') || Str::contains($body, '"url"')) {
                        // Extract URL using regex for simplicity in this stream format
                        if (preg_match('/"url":\s*"([^"]+)"/', $body, $matches)) {
                            $resultUrl = $matches[1];
                            // If it's a relative URL from HF, prepend the space URL
                            if (Str::startsWith($resultUrl, '/')) {
                                $resultUrl = 'https://levihsu-ootdiffusion.hf.space' . $resultUrl;
                            }
                            break;
                        }
                    }
                    
                    if (Str::contains($body, 'event: error') || Str::contains($body, '"error"')) {
                        break;
                    }
                }
                sleep(2);
            }

            if ($resultUrl) {
                // 7. Save Result Locally for History/Gallery
                $resultFilename = 'vton_res_' . Str::random(10) . '_' . time() . '.jpg';
                $resultFolder = 'vton/results';
                $resultPath = $resultFolder . '/' . $resultFilename;
                
                try {
                    $imageContent = file_get_contents($resultUrl);
                    if ($imageContent) {
                        Storage::disk('public')->put($resultPath, $imageContent);
                        $finalImageUrl = asset('storage/' . $resultPath);
                        
                        // Save to History
                        VtonHistory::create([
                            'user_id' => auth()->id(),
                            'product_id' => $product->id,
                            'vton_model_id' => $request->vton_model_id ?: $product->vton_model_id,
                            'user_image' => $humanData['saved_path'] ?? null,
                            'result_image' => $resultPath,
                            'session_id' => session()->getId(),
                        ]);
                    } else {
                        $finalImageUrl = $resultUrl; // Fallback to HF URL if download fails
                    }
                } catch (\Exception $saveEx) {
                    Log::error('VTON Save Result Error: ' . $saveEx->getMessage());
                    $finalImageUrl = $resultUrl;
                }

                // Cache the result for 7 days
                Cache::put($cacheKey, $finalImageUrl, now()->addDays(7));

                return response()->json([
                    'success' => true,
                    'image_url' => $finalImageUrl,
                    'message' => 'Thử đồ thành công!'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Hệ thống AI xử lý quá lâu hoặc gặp lỗi. Hãy thử lại sau.'
            ], 504);

        } catch (\Exception $e) {
            Log::error('VTON HF Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Lỗi hệ thống: ' . $e->getMessage()
            ], 500);
        }
    }

    private function getHumanData(Request $request, Product $product)
    {
        // Case 1: User uploaded image
        if ($request->hasFile('user_image')) {
            $userImageFile = $request->file('user_image');
            $imageData = file_get_contents($userImageFile->getRealPath());
            
            // Basic rotation correction (same as original logic)
            $imageResource = @imagecreatefromstring($imageData);
            if ($imageResource !== false) {
                $isRotated = false;
                if (in_array(strtolower($userImageFile->getClientOriginalExtension()), ['jpg', 'jpeg']) && function_exists('exif_read_data')) {
                    $exif = @exif_read_data($userImageFile->getRealPath());
                    if (!empty($exif['Orientation'])) {
                        switch ($exif['Orientation']) {
                            case 3: $imageResource = imagerotate($imageResource, 180, 0); $isRotated = true; break;
                            case 6: $imageResource = imagerotate($imageResource, -90, 0); $isRotated = true; break;
                            case 8: $imageResource = imagerotate($imageResource, 90, 0); $isRotated = true; break;
                        }
                    }
                }
                
                if ($isRotated) {
                    ob_start();
                    imagejpeg($imageResource, null, 90);
                    $imageData = ob_get_contents();
                    ob_end_clean();
                }
                imagedestroy($imageResource);
            }

            // Save user image locally if it's new
            $userImageFilename = 'vton_user_' . Str::random(10) . '_' . time() . '.jpg';
            $userImageFolder = 'vton/uploads';
            $userImagePath = $userImageFolder . '/' . $userImageFilename;
            Storage::disk('public')->put($userImagePath, $imageData);

            return [
                'success' => true,
                'base64' => 'data:' . $userImageFile->getMimeType() . ';base64,' . base64_encode($imageData),
                'cache_key' => 'user_upload_' . md5($imageData),
                'saved_path' => $userImagePath
            ];
        }

        // Case 2: Specific Model requested
        $modelId = $request->vton_model_id ?: $product->vton_model_id;
        $model = null;

        if ($modelId) {
            $model = VtonModel::find($modelId);
        }

        // Case 3: Fallback to Default Model based on product/category
        if (!$model) {
            // Very basic gender detection based on category name or parent category
            $gender = 'female'; // Default to female as it's most common for VTON
            $categoryName = strtolower($product->category->name ?? '');
            if (Str::contains($categoryName, ['nam', 'man', 'men'])) $gender = 'male';
            elseif (Str::contains($categoryName, ['bé', 'kid', 'child'])) $gender = 'kid';

            $model = VtonModel::where('gender', $gender)->where('is_default', true)->first();
            
            // Absoulte fallback to any default or any model
            if (!$model) $model = VtonModel::where('is_default', true)->first();
            if (!$model) $model = VtonModel::first();
        }

        if ($model && $model->image) {
            $path = storage_path('app/public/' . $model->image);
            if (file_exists($path)) {
                return [
                    'success' => true,
                    'base64' => 'data:' . mime_content_type($path) . ';base64,' . base64_encode(file_get_contents($path)),
                    'cache_key' => 'model_' . $model->id
                ];
            }
        }

        return [
            'success' => false,
            'message' => 'Vui lòng tải ảnh của bạn lên hoặc chọn người mẫu để thử đồ.'
        ];
    }

    private function mapCategory(Product $product)
    {
        $catName = strtolower($product->category->name ?? '');
        
        if (Str::contains($catName, ['quần', 'pant', 'trouser', 'jean', 'short', 'skirt', 'chân váy'])) {
            return 'Lower-body';
        }
        
        if (Str::contains($catName, ['váy', 'đầm', 'dress', 'jumpsuit'])) {
            return 'Dress';
        }
        
        // Default to Upper-body
        return 'Upper-body';
    }
}
