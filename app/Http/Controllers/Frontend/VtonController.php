<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\VtonHistory;
use App\Models\VtonModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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
                'user_image' => 'nullable|mimes:jpeg,jpg,png,webp|max:5120',
                'product_id' => 'required|exists:products,id',
                'vton_model_id' => 'nullable|exists:vton_models,id'
            ]);

            $product = Product::findOrFail($request->product_id);

            // 1. Get Human Data (Upload or Model)
            $humanData = $this->getHumanData($request->file('user_image'), $request->vton_model_id, $product);
            
            if (!$humanData) {
                return response()->json([
                    'success' => false,
                    'message' => 'Lỗi không tìm được hoặc không thể lưu ảnh người mẫu.'
                ]);
            }

            // 2. Prepare Product Image Base64
            if (empty($product->image)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sản phẩm không có ảnh để thử.'
                ]);
            }

            $productPath = storage_path('app/public/' . $product->image);
            if (!file_exists($productPath) || is_dir($productPath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ảnh sản phẩm không tồn tại.'
                ]);
            }

            $productImageBase64 = 'data:' . mime_content_type($productPath) . ';base64,' . base64_encode(file_get_contents($productPath));
            
            // 3. Map Category
            $category = $this->mapCategory($product);
            
            // 4. Create History Record (Pending)
            $history = VtonHistory::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'vton_model_id' => $request->vton_model_id,
                'user_image' => $humanData['relative_path'],
                'status' => 'pending'
            ]);

            // 5. Dispatch Background Job
            \App\Jobs\ProcessVtonJob::dispatch(
                $history->id,
                $humanData['base64'],
                $productImageBase64,
                $category
            );

            return response()->json([
                'success' => true,
                'history_id' => $history->id,
                'status' => 'pending',
                'message' => 'Đang xử lý thử đồ AI. Vui lòng đợi trong giây lát...'
            ]);

        } catch (\Exception $e) {
            Log::channel('vton')->error('VTON Controller Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Lỗi hệ thống: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check status of VTON process
     */
    public function checkStatus($id)
    {
        $history = VtonHistory::findOrFail($id);

        return response()->json([
            'success' => true,
            'status' => $history->status,
            'image_url' => $history->result_image ? asset('storage/' . $history->result_image) : null,
            'message' => match($history->status) {
                'completed' => 'Thử đồ thành công!',
                'failed' => 'Hệ thống AI đang bận hoặc quá tải. Vui lòng thử lại sau.',
                'processing' => 'AI đang xử lý ảnh của bạn...',
                default => 'Đang chờ xử lý...'
            }
        ]);
    }

    private function attemptTryOn($humanBase64, $productBase64, $category)
    {
        $spaces = [
            // Official OOTDiffusion - High Quality
            [
                'url' => 'https://levihsu-ootdiffusion.hf.space/call/process_dc',
                'payload' => [
                    'data' => [
                        ['url' => $humanBase64, 'meta' => ['_type' => 'gradio.FileData']],
                        ['url' => $productBase64, 'meta' => ['_type' => 'gradio.FileData']],
                        $category, 1, 20, 2.0, -1
                    ]
                ]
            ],
            // Alternative IDM-VTON
            [
                'url' => 'https://yisol-idm-vton.hf.space/call/tryon',
                'payload' => [
                    'data' => [
                        ['url' => $humanBase64, 'meta' => ['_type' => 'gradio.FileData']],
                        ['url' => $productBase64, 'meta' => ['_type' => 'gradio.FileData']],
                        "Fashion garment", true, true, 30, 42
                    ]
                ]
            ]
        ];

        foreach ($spaces as $space) {
            try {
                $response = Http::timeout(30)->post($space['url'], $space['payload']);
                $eventId = $response->json('event_id');

                if ($eventId) {
                    Log::info('VTON Polling Start: ' . $space['url'] . ' ID: ' . $eventId);
                    $startTime = time();
                    while ((time() - $startTime) < 90) { // Max 90 seconds
                        $statusRes = Http::timeout(60)->get($space['url'] . '/' . $eventId);
                        $body = $statusRes->body();
                        
                        if (Str::contains($body, 'event: complete')) {
                            if (preg_match('/"url":\s*"([^"]+)"/', $body, $matches)) {
                                $resUrl = $matches[1];
                                // Handle relative URLs
                                if (Str::startsWith($resUrl, '/')) {
                                    $resUrl = rtrim(dirname(dirname($space['url'])), '/') . $resUrl;
                                }
                                return $resUrl;
                            }
                        }
                        
                        if (Str::contains($body, 'event: error') || Str::contains($body, '"error"')) break;
                        
                        sleep(3);
                    }
                }
            } catch (\Exception $e) {
                Log::warning('VTON Space Failed: ' . $space['url'] . ' - ' . $e->getMessage());
            }
        }

        return null;
    }

    private function getHumanData($userImageFile = null, $modelId = null, $product = null)
    {
        if ($userImageFile) {
            try {
                $imageData = file_get_contents($userImageFile->getRealPath());
                $filename = 'user_' . time() . '_' . Str::random(10) . '.jpg';
                $relativePath = 'vton/user_uploads/' . $filename;
                
                Storage::disk('public')->put($relativePath, $imageData);
                
                return [
                    'path' => storage_path('app/public/' . $relativePath),
                    'relative_path' => $relativePath,
                    'base64' => 'data:' . $userImageFile->getMimeType() . ';base64,' . base64_encode($imageData),
                ];
            } catch (\Exception $e) {
                Log::error('VTON Human Upload Error: ' . $e->getMessage());
            }
        }

        $model = null;
        if ($modelId) {
            $model = VtonModel::find($modelId);
        }

        if (!$model) {
            $gender = 'female';
            $catName = strtolower($product->category->name ?? '');
            if (Str::contains($catName, ['nam', 'man', 'men'])) $gender = 'male';
            
            $model = VtonModel::where('gender', $gender)->where('status', 'active')->first() 
                  ?: VtonModel::where('status', 'active')->first();
        }

        if ($model && !empty($model->image)) {
            $path = storage_path('app/public/' . $model->image);
            if (file_exists($path) && !is_dir($path)) {
                return [
                    'path' => $path,
                    'relative_path' => $model->image,
                    'base64' => 'data:' . mime_content_type($path) . ';base64,' . base64_encode(file_get_contents($path)),
                ];
            }
        }

        return null;
    }

    private function mapCategory($product)
    {
        $catName = strtolower($product->category->name ?? '');
        
        if (Str::contains($catName, ['quần', 'pant', 'trouser', 'jean', 'short', 'skirt', 'chân váy'])) {
            return 'Lower-body';
        }
        
        if (Str::contains($catName, ['váy', 'đầm', 'dress', 'jumpsuit'])) {
            return 'Dress';
        }
        
        return 'Upper-body';
    }
}
