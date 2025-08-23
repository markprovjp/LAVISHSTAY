<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$u = App\Models\User::find(5);
if(!$u) { echo "User 5 not found\n"; exit(1); }
$t = $u->createToken('dev-test-token');
// token is stored hashed in DB; plainTextToken is available on creation
if(isset($t->plainTextToken)) {
    echo "TOKEN:" . $t->plainTextToken . "\n";
} else {
    echo "No plainTextToken available\n";
}
