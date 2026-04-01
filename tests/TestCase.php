<?php

namespace Tests;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        if (app()->environment('testing')) {
            $testToken = Str::slug(str_replace(['::', '\\'], '-', static::class.'-'.$this->name()));
            $compiledPath = storage_path('framework/testing/views/'.$testToken.'-'.Str::random(8));
            File::ensureDirectoryExists($compiledPath);
            config()->set('view.compiled', $compiledPath);
            app()->forgetInstance('blade.compiler');
            app()->forgetInstance('view.engine.resolver');
            app()->forgetInstance('view');
        }

        // Global CSRF bypass for all tests
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
    }
}
