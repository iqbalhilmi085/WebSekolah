<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Auth;

// make sure logged out first
Auth::logout();

$attempt = Auth::attempt(['no_hp' => '0812000001', 'password' => 'admin123']);
echo $attempt ? 'PHONE_OK' : 'PHONE_FAIL';
