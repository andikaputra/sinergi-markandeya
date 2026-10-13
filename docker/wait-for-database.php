<?php

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\DB;

require dirname(__DIR__).'/vendor/autoload.php';
$app = require dirname(__DIR__).'/bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

$attempts = max(1, (int) (getenv('DB_WAIT_ATTEMPTS') ?: 12));
$delay = max(1, (int) (getenv('DB_WAIT_INTERVAL') ?: 5));

for ($attempt = 1; $attempt <= $attempts; $attempt++) {
    try {
        DB::connection()->getPdo();
        fwrite(STDOUT, "[startup] Database connection ready.\n");
        exit(0);
    } catch (Throwable $error) {
        DB::purge();
        fwrite(STDERR, "[startup] Database unavailable ($attempt/$attempts): {$error->getMessage()}\n");
        if ($attempt < $attempts) {
            sleep($delay);
        }
    }
}

fwrite(STDERR, "[startup] Database connection failed. Verify DB_HOST, credentials, and the container network in Coolify.\n");
exit(1);
