<?php

namespace App\Support;

use Illuminate\Filesystem\Filesystem;

class SafeFilesystem extends Filesystem
{
    public function replace($path, $content, $mode = null)
    {
        clearstatcache(true, $path);

        $path = realpath($path) ?: $path;

        $tempPath = tempnam(dirname($path), basename($path));

        if (! is_null($mode)) {
            chmod($tempPath, $mode);
        } else {
            chmod($tempPath, 0777 - umask());
        }

        file_put_contents($tempPath, $content);

        if (@rename($tempPath, $path)) {
            return;
        }

        if (copy($tempPath, $path)) {
            @unlink($tempPath);

            return;
        }

        rename($tempPath, $path);
    }
}
