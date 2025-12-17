<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

$users = User::all();
foreach ($users as $u) {
    if (empty($u->no_hp)) {
        $u->no_hp = '0812' . str_pad($u->id, 6, '0', STR_PAD_LEFT);
        $u->save();
    }
    echo $u->id . ':' . $u->email . ':' . $u->no_hp . PHP_EOL;
}
