<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Auth;

$credentials = ['email' => 'admin@tkit.com', 'password' => 'admin123'];
$attempt = Auth::attempt($credentials);
if ($attempt) {
    echo "ATTEMPT_OK\n";
    // logout to avoid leaving logged-in state in session store
    Auth::logout();
} else {
    echo "ATTEMPT_FAIL\n";
}
