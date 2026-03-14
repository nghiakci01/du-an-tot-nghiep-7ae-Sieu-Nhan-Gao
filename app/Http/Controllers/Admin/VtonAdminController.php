<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Jobs\ProcessVtonJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class VtonAdminController extends Controller
{
    /**
     * Trigger VTON generation for a product
     */
    public function generate(Request $request)
    {
        try {
            $product = Product::findOrFail($request->product_id);

            if (empty($product->image)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sản phẩm không có ảnh để thử.'
                ]);
            }

            $productPath = storage_path('app/public/' . $product->image);
            if (!file_exists($productPath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ảnh sản phẩm không tồn tại trên hệ thống.'
                ]);
            }

            $productBase64 = 'data:' . mime_content_type($productPath) . ';base64,' . base64_encode(file_get_contents($productPath));

            $model = $product->getEffectiveVtonModel();
            if (!$model || empty($model->image)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sản phẩm chưa được gán người mẫu mặc định.'
                ]);
            }

            $modelPath = storage_path('app/public/' . $model->image);
            if (!file_exists($modelPath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ảnh người mẫu không tồn tại.'
                ]);
            }

            $modelBase64 = 'data:' . mime_content_type($modelPath) . ';base64,' . base64_encode(file_get_contents($modelPath));

            $category = $this->mapCategory($product);

            // Clear any old vton_image so polling starts fresh
            $product->update(['vton_image' => null]);

            // Dispatch job to queue
            ProcessVtonJob::dispatch(
                null,
                $modelBase64,
                $productBase64,
                $category,
                $product->id
            );

            // Auto-start queue worker in background (Windows-compatible)
            $phpPath = PHP_BINARY;
            $artisanPath = base_path('artisan');
            $cmd = "start /B {$phpPath} {$artisanPath} queue:work --once --timeout=300";
            pclose(popen($cmd, 'r'));

            return response()->json([
                'success' => true,
                'message' => 'Yêu cầu tạo mẫu AI đã được gửi. Đang xử lý...'
            ]);

        } catch (\Exception $e) {
            Log::channel('vton')->error('Admin VTON Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Lỗi hệ thống: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check status for admin generation
     */
    public function status($id)
    {
        $product = Product::find($id);
        
        if (!$product) {
            return response()->json(['status' => 'failed', 'message' => 'Không tìm thấy sản phẩm.']);
        }

        if ($product->vton_image) {
            return response()->json([
                'status' => 'completed',
                'result_url' => asset('storage/' . $product->vton_image)
            ]);
        }

        // We check the log or queue status? 
        // For simplicity, we just return 'processing' until vton_image is updated
        return response()->json(['status' => 'processing', 'message' => 'Đang chờ xử lý từ AI...']);
    }

    private function mapCategory($product)
    {
        $catName = strtolower($product->category->name ?? '');
        if (\Illuminate\Support\Str::contains($catName, ['quần', 'pant', 'trouser', 'jean', 'short', 'skirt', 'chân váy'])) {
            return 'Lower-body';
        }
        if (\Illuminate\Support\Str::contains($catName, ['váy', 'đầm', 'dress', 'jumpsuit'])) {
            return 'Dress';
        }
        return 'Upper-body';
    }
}
