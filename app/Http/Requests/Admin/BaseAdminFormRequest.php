<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

/**
 * Base FormRequest cho tất cả Admin requests.
 *
 * Override validateResolved() để bắt ValueError (Path cannot be empty)
 * xảy ra trong PHP 8.2 khi file upload có path rỗng — lỗi xảy ra trong
 * ValidatesAttributes trước khi controller chạy.
 */
abstract class BaseAdminFormRequest extends FormRequest
{
    /**
     * Intercept toàn bộ lifecycle validation để catch ValueError.
     * Nếu lỗi do file path rỗng, xóa các file lỗi và validate lại.
     */
    public function validateResolved(): void
    {
        // Lần đầu: thử validate bình thường
        try {
            parent::validateResolved();
            return;
        } catch (\ValueError $e) {
            // Chỉ retry nếu lỗi do empty path
            if (! str_contains($e->getMessage(), 'Path cannot be empty')) {
                throw $e;
            }

            Log::warning(static::class . ': ValueError caught — removing broken file uploads and retrying.', [
                'error' => $e->getMessage(),
            ]);
        }

        // Retry sau khi xóa file lỗi
        $this->removeInvalidFiles();

        try {
            parent::validateResolved();
        } catch (\ValueError $e) {
            // Nếu vẫn lỗi, log và fail-safe (không crash)
            Log::error(static::class . ': ValueError persists after cleanup — aborting file validation.', [
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Xóa tất cả file upload có path rỗng hoặc invalid khỏi request.
     */
    protected function removeInvalidFiles(): void
    {
        foreach ($this->files->all() as $key => $file) {
            $files = is_array($file) ? $file : [$file];
            foreach ($files as $f) {
                if (! $f) continue;
                try {
                    if ($f->getError() !== UPLOAD_ERR_OK) {
                        $this->files->remove($key);
                    }
                } catch (\Throwable $e) {
                    $this->files->remove($key);
                }
            }
        }
    }
}
