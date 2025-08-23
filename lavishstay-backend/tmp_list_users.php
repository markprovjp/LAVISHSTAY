<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$users = App\Models\User::select('id','name','email')->limit(20)->get();
foreach($users as $u) {
    echo "{$u->id} \t {$u->name} \t {$u->email}\n";
}
