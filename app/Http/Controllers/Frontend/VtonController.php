<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\ConnectionException;

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
                'user_image' => 'required|mimes:jpeg,jpg,png,webp|max:5120|dimensions:min_width=600,min_height=600',
                'product_id' => 'required|exists:products,id'
            ], [
                'user_image.mimes'      => 'Định dạng không hợp lệ. Chỉ cho phép file .jpg, .png, .webp.',
                'user_image.max'        => 'File quá lớn. Vui lòng tải ảnh bé hơn 5MB.',
                'user_image.dimensions' => 'Ảnh quá mờ hoặc có kích thước quá thấp. Vui lòng sử dụng ảnh sắc nét hơn (Tối thiểu 600x600px).',
            ]);

            $apiToken = env('REPLICATE_API_TOKEN');

            // --- CHẾ ĐỘ GIẢ LẬP (MOCK) CHO ĐỒ ÁN (TRÁNH MẤT PHÍ API) ---
            if (empty($apiToken) || $apiToken === 'demo' || str_starts_with($apiToken, 'nhap_api')) {
                // Lấy ảnh người dùng tải lên để trả về (giả lập kết quả VTON thành công)
                $userImageFile = $request->file('user_image');
                $userImageMime = $userImageFile->getMimeType();
                $userImageBase64 = base64_encode(file_get_contents($userImageFile->getRealPath()));
                $userDataUri = 'data:' . $userImageMime . ';base64,' . $userImageBase64;

                // Giả lập thời gian AI đang xử lý (5 giây)
                sleep(5);

                return response()->json([
                    'success' => true,
                    'image_url' => $userDataUri, // Trả về ảnh giả lập để báo cáo
                    'message' => 'Thử đồ thành công! (Chạy ở chế độ Demo)'
                ]);
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
            $filePath = $userImageFile->getRealPath();
            
            // Pre-processing: Handle rotation based on EXIF or Ratio using GD
            $imageData = file_get_contents($filePath);
            $imageResource = @imagecreatefromstring($imageData);
            
            if ($imageResource !== false) {
                $isRotated = false;
                
                // Rotation based on EXIF Orientation (JPEG only)
                if (in_array(strtolower($userImageFile->getClientOriginalExtension()), ['jpg', 'jpeg']) && function_exists('exif_read_data')) {
                    $exif = @exif_read_data($filePath);
                    if (!empty($exif['Orientation'])) {
                        switch ($exif['Orientation']) {
                            case 3:
                                $imageResource = imagerotate($imageResource, 180, 0);
                                $isRotated = true;
                                break;
                            case 6:
                                $imageResource = imagerotate($imageResource, -90, 0);
                                $isRotated = true;
                                break;
                            case 8:
                                $imageResource = imagerotate($imageResource, 90, 0);
                                $isRotated = true;
                                break;
                        }
                    }
                }
                
                // Fallback rotation: If width > height and not rotated via EXIF, assume landscape photo meant to be portrait
                $width = imagesx($imageResource);
                $height = imagesy($imageResource);
                
                if (!$isRotated && $width > $height) {
                    $imageResource = imagerotate($imageResource, -90, 0);
                    $isRotated = true;
                }

                if ($isRotated) {
                    ob_start();
                    if (strtolower($userImageFile->getClientOriginalExtension()) === 'png') {
                        imagepng($imageResource);
                    } elseif (strtolower($userImageFile->getClientOriginalExtension()) === 'webp') {
                        imagewebp($imageResource);
                    } else {
                        imagejpeg($imageResource, null, 90);
                    }
                    $imageData = ob_get_contents();
                    ob_end_clean();
                }
                
                imagedestroy($imageResource);
            }

            $userImageMime = $userImageFile->getMimeType();
            $userImageBase64 = base64_encode($imageData);
            $userDataUri = 'data:' . $userImageMime . ';base64,' . $userImageBase64;

            // 3. Call Replicate API to create prediction
            // Using Kolors Virtual Try-On model via official models prediction endpoint
            // Endpoint: POST https://api.replicate.com/v1/models/cuiapp/kolors-virtual-try-on/predictions
            
            $response = Http::withToken($apiToken)
                ->retry(3, 1000, function ($exception, $request) {
                    return $exception instanceof ConnectionException;
                })
                ->timeout(30)
                ->post('https://api.replicate.com/v1/models/cuiapp/kolors-virtual-try-on/predictions', [
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
                        $errorMsg = $pollStatus['error'] ?? 'Unknown error';
                        Log::error('Replicate Model Failed: ' . json_encode($errorMsg));
                        
                        // Xử lý lỗi đặc biệt: AI không tìm thấy người
                        if (stripos(json_encode($errorMsg), 'No human detected') !== false || stripos(json_encode($errorMsg), 'human') !== false) {
                            return response()->json([
                                'success' => false,
                                'error_code' => 'NO_HUMAN_DETECTED',
                                'message' => 'Hệ thống AI không nhận diện được người trong ảnh. Vui lòng xem hướng dẫn chụp ảnh mẫu bên dưới và thử lại.'
                            ], 422);
                        }

                        return response()->json([
                            'success' => false,
                            'message' => 'AI xử lý thất bại: ' . $errorMsg
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

        } catch (\Illuminate\Validation\ValidationException $e) {
            $firstError = collect($e->errors())->flatten()->first();
            return response()->json([
                'success' => false,
                'message' => $firstError
            ], 422);
        } catch (ConnectionException $e) {
            Log::error('VTON API Timeout/Connection Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Hệ thống đang bận, vui lòng thử lại sau ít phút.'
            ], 504); // 504 Gateway Timeout
        } catch (\Exception $e) {
            Log::error('VTON Controller Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi hệ thống: ' . $e->getMessage()
            ], 500);
        }
    }
}
