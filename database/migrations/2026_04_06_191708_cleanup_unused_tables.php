<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Cleanup migration intentionally left as a no-op.
        // The branch changes that introduced this file also removed models and
        // tables still used by the current application code, so we keep the
        // migration non-destructive to avoid breaking fresh installs.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op.
    }
};
