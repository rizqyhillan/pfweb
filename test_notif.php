<?php

use App\Models\User;
use App\Notifications\SystemNotification;
use Illuminate\Support\Facades\Log;

$admin = User::where('role', 'admin')->first();

echo "Admin user ID: {$admin->id}, Name: {$admin->nama}\n";
echo "Broadcast connection: " . config('broadcasting.default') . "\n";

try {
    $notification = new SystemNotification(
        'Debug Test',
        'Ini pesan debug test!',
        'info',
        '#'
    );

    echo "Notification class: " . get_class($notification) . "\n";
    echo "Via channels: " . implode(', ', $notification->via($admin)) . "\n";
    echo "Implements ShouldBroadcastNow: " . (($notification instanceof \Illuminate\Contracts\Broadcasting\ShouldBroadcastNow) ? 'YES' : 'NO') . "\n";

    echo "Sending notification...\n";
    $admin->notify($notification);
    echo "SUCCESS - Notification sent!\n";
} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}
