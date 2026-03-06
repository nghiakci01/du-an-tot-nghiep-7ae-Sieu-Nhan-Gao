<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

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
                'user_image' => 'required|image|max:10240', // Max 10MB
                'product_id' => 'required|exists:products,id'
            ]);

            // Get API Token
            $apiToken = env('REPLICATE_API_TOKEN');
            if (empty($apiToken)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Lỗi hệ thống: Chưa cấu hình REPLICATE_API_TOKEN trong file .env!'
                ], 500);
            }

            $product = Product::findOrFail($request->product_id);
            if (!$product->image) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sản phẩm này chưa có ảnh đại diện để thử đồ.'
                ], 400);
            }

            // 1. Process Product Image -> Base64 URI
            $productImagePath = storage_path('app/public/' . $product->image);
            if (!file_exists($productImagePath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy ảnh sản phẩm trong kho lưu trữ.'
                ], 404);
            }
            $productImageMime = mime_content_type($productImagePath);
            $productImageBase64 = base64_encode(file_get_contents($productImagePath));
            $productDataUri = 'data:' . $productImageMime . ';base64,' . $productImageBase64;

            // 2. Process User Image -> Base64 URI
            $userImageFile = $request->file('user_image');
            $userImageMime = $userImageFile->getMimeType();
            $userImageBase64 = base64_encode(file_get_contents($userImageFile->getRealPath()));
            $userDataUri = 'data:' . $userImageMime . ';base64,' . $userImageBase64;

            // 3. Call Replicate API to create prediction
            // Using Kolors Virtual Try-On model
            $modelVersion = "c871fc9b1d5f228e48b5309d17ecce3dfcdbed731abaf3b08e2f0ac0b3e6c0c2";
            
            $response = Http::withToken($apiToken)
                ->post('https://api.replicate.com/v1/predictions', [
                    'version' => $modelVersion,
                    'input' => [
                        'human_image' => $userDataUri,
                        'garment_image' => $productDataUri,
                        'garment_des' => 'clothing',
                        'seed' => rand(1, 4294967295),
                        'steps' => 30
                    ]
                ]);

            if (!$response->successful()) {
                Log::error('Replicate API Error: ' . $response->body());
                return response()->json([
                    'success' => false,
                    'message' => 'Lỗi kết nối với API AI. Vui lòng thử lại sau.',
                    'details' => $response->json()
                ], 500);
            }

            $prediction = $response->json();
            $pollUrl = $prediction['urls']['get'];

            // 4. Poll the result
            // AI Try-on usually takes 10-30 seconds, but cold boots can take longer. We'll poll up to 75 times (150s).
            $maxAttempts = 75;
            $attempt = 0;
            $resultUrl = null;

            while ($attempt < $maxAttempts) {
                sleep(2); // Wait 2s between polls
                $pollResponse = Http::withToken($apiToken)->get($pollUrl);
                
                if ($pollResponse->successful()) {
                    $pollStatus = $pollResponse->json();
                    
                    if ($pollStatus['status'] === 'succeeded') {
                        // The output is an array of image URLs (or a single URL depending on model)
                        $resultUrl = is_array($pollStatus['output']) ? $pollStatus['output'][0] : $pollStatus['output'];
                        break;
                    } elseif ($pollStatus['status'] === 'failed') {
                        Log::error('Replicate Model Failed: ' . json_encode($pollStatus['error']));
                        return response()->json([
                            'success' => false,
                            'message' => 'AI xử lý thất bại: ' . ($pollStatus['error'] ?? 'Unknown error')
                        ], 500);
                    }
                }
                $attempt++;
            }

            if ($resultUrl) {
                return response()->json([
                    'success' => true,
                    'image_url' => $resultUrl,
                    'message' => 'Thử đồ thành công!'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Hệ thống AI xử lý quá thời gian (Timeout). Hãy thử lại.'
            ], 408);

        } catch (\Exception $e) {
            Log::error('VTON Controller Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi hệ thống: ' . $e->getMessage()
            ], 500);
        }
    }
}
