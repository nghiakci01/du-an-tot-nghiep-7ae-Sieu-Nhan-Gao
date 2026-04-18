<?php

/**
 * Elite Deployment Helper
 * Since InfinityFree has no SSH, run this via your browser to execute Artisan commands.
 * SECURITY: Delete this file immediately after successful deployment.
 */

use Illuminate\Support\Facades\Artisan;

// 1. Load Laravel Bootstrap
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

header('Content-Type: text/plain');

function run_command($command) {
    echo "\n> php artisan $command\n";
    try {
        Artisan::call($command);
        echo Artisan::output();
        echo "[SUCCESS]\n";
    } catch (\Exception $e) {
        echo "[ERROR]: " . $e->getMessage() . "\n";
    }
}

// Security Check (Simple Token - update this or use manually)
if (!isset($_GET['token']) || $_GET['token'] !== 'elite_setup') {
    die('Unauthorized. Use: deploy-helper.php?token=elite_setup');
}

echo "--- Elite Deployment Helper ---\n";

$action = $_GET['action'] ?? 'status';

switch ($action) {
    case 'link':
        run_command('storage:link');
        break;
    case 'migrate':
        run_command('migrate --force');
        break;
    case 'cache':
        run_command('config:cache');
        run_command('route:cache');
        run_command('view:cache');
        break;
    case 'clear':
        run_command('config:clear');
        run_command('route:clear');
        run_command('view:clear');
        break;
    case 'status':
    default:
        echo "Laravel Version: " . app()->version() . "\n";
        echo "Environment: " . app()->environment() . "\n";
        echo "\nAvailable actions:\n";
        echo "?action=link    - Run storage:link\n";
        echo "?action=migrate - Run migrations\n";
        echo "?action=cache   - Optimization (config, route, view)\n";
        echo "?action=clear   - Clear caches\n";
        break;
}

echo "\n--- Done ---\n";
