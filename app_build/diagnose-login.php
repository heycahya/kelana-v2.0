<?php

// Diagnose trip leader login issue by booting Laravel and testing the dynamic Auth attempt
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Auth;
use App\Models\TripLeader;
use Illuminate\Support\Facades\Hash;

echo "=== TRIP LEADER DYNAMIC AUTH DIAGNOSTIC START ===\n";

$credentials = [
    'email' => 'adi.wijaya@kelana.com',
    'password' => 'PasswordLeader123!'
];

try {
    $leader = TripLeader::where('email', $credentials['email'])->first();
    echo "1. Fetching TripLeader model manually: " . ($leader ? "SUCCESS (" . $leader->email . ")" : "FAILED") . "\n";
    
    if ($leader) {
        $hashCheck = Hash::check($credentials['password'], $leader->password);
        echo "   - Hash::check() manual test: " . ($hashCheck ? "✅ MATCH" : "❌ MISMATCH") . "\n";
        echo "   - Hash in DB: " . $leader->password . "\n";
    }

    // Attempt login via the trip_leader guard
    $attemptResult = Auth::guard('trip_leader')->attempt([
        'email' => $credentials['email'],
        'password' => $credentials['password']
    ]);
    
    echo "2. Auth::guard('trip_leader')->attempt(): " . ($attemptResult ? "✅ SUCCESS (Logged in)" : "❌ FAILED") . "\n";
    
    if (!$attemptResult) {
        // Let's inspect the active guard config
        $guardConfig = config('auth.guards.trip_leader');
        echo "   - trip_leader guard config: " . json_encode($guardConfig) . "\n";
        
        $providerConfig = config('auth.providers.' . ($guardConfig['provider'] ?? ''));
        echo "   - trip_leader provider config: " . json_encode($providerConfig) . "\n";
    }
} catch (\Exception $e) {
    echo "❌ Exception encountered: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}

echo "=== TRIP LEADER DYNAMIC AUTH DIAGNOSTIC END ===\n";
