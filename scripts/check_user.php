<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

$user = User::where('email', 'admin@tkit.com')->first();
if (! $user) {
    echo "NO_USER\n";
    exit(0);
}
$output = [
    'email' => $user->email,
    'password' => $user->password,
    'role' => $user->role ?? null,
];
echo json_encode($output) . PHP_EOL;

$ok = password_verify('admin123', $user->password) ? 'PASSWORD_OK' : 'PASSWORD_FAIL';
echo $ok . PHP_EOL;
