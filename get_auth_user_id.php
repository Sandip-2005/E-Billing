<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$userId = Auth::guard('cuser')->id();

echo "Authenticated User ID: ". ($userId ?? 'N/A') . "\n";

$kernel->terminate($app, 0);
