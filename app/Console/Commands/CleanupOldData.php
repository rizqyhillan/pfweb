<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Otp;
use App\Models\DoctorBooking;
use App\Models\Grooming;
use App\Models\Boarding;

class CleanupOldData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pawpet:cleanup {--days=90 : The number of days to retain canceled/expired bookings}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up old, stale, and expired data from the database to optimize size and performance.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = (int) $this->option('days');
        $this->info("Starting PawPet database cleanup (retaining canceled/expired bookings from last {$days} days)...");

        // 1. Clean up old Carts (inactive for 30 days)
        $cartCutoff = Carbon::now()->subDays(30);
        
        $oldCartIds = Cart::where('updated_at', '<', $cartCutoff)
            ->where('status', 'aktif')
            ->pluck('id');

        if ($oldCartIds->isNotEmpty()) {
            $deletedItems = CartItem::whereIn('id_keranjang', $oldCartIds)->delete();
            $deletedCarts = Cart::whereIn('id', $oldCartIds)->delete();
            $this->info("✓ Cleaned up {$deletedCarts} abandoned carts and {$deletedItems} cart items (older than 30 days).");
        } else {
            $this->info("✓ No abandoned carts to clean up.");
        }

        // 2. Clean up old OTP records (older than 24 hours)
        $otpCutoff = Carbon::now()->subHours(24);
        $deletedOtps = Otp::where('created_at', '<', $otpCutoff)->delete();
        $this->info("✓ Cleaned up {$deletedOtps} expired OTP records (older than 24 hours).");

        // 3. Clean up expired Sanctum Personal Access Tokens (older than 30 days)
        $tokenCutoff = Carbon::now()->subDays(30);
        $deletedTokens = DB::table('personal_access_tokens')
            ->where('last_used_at', '<', $tokenCutoff)
            ->orWhere(function ($query) use ($tokenCutoff) {
                $query->whereNull('last_used_at')
                      ->where('created_at', '<', $tokenCutoff);
            })
            ->delete();
        $this->info("✓ Cleaned up {$deletedTokens} expired personal access tokens (inactive/older than 30 days).");

        // 4. Clean up read notifications (older than 30 days)
        $notifCutoff = Carbon::now()->subDays(30);
        $deletedNotifs = DB::table('notifications')
            ->whereNotNull('read_at')
            ->where('read_at', '<', $notifCutoff)
            ->delete();
        $this->info("✓ Cleaned up {$deletedNotifs} read notifications (older than 30 days).");

        // 5. Clean up canceled/expired Doctor Bookings (older than $days days)
        $bookingCutoff = Carbon::now()->subDays($days);
        
        $deletedDocBookings = DoctorBooking::where('updated_at', '<', $bookingCutoff)
            ->where(function ($query) {
                $query->where('status', 'batal')
                      ->orWhere(function ($q) {
                          $q->where('status', 'pending')
                            ->where('tanggal_booking', '<', Carbon::today());
                      });
            })
            ->delete();
        $this->info("✓ Cleaned up {$deletedDocBookings} canceled/expired doctor bookings (older than {$days} days).");

        // 6. Clean up canceled/expired Groomings (older than $days days)
        $deletedGroomings = Grooming::where('updated_at', '<', $bookingCutoff)
            ->where(function ($query) {
                $query->where('status', 'batal')
                      ->orWhere(function ($q) {
                          $q->where('status', 'pending')
                            ->where('tanggal_grooming', '<', Carbon::today());
                      });
            })
            ->delete();
        $this->info("✓ Cleaned up {$deletedGroomings} canceled/expired grooming bookings (older than {$days} days).");

        // 7. Clean up canceled/expired Boardings (penitipan) (older than $days days)
        $deletedBoardings = Boarding::where('updated_at', '<', $bookingCutoff)
            ->where(function ($query) {
                $query->where('status', 'batal')
                      ->orWhere(function ($q) {
                          $q->where('status', 'pending')
                            ->where('tanggal_masuk', '<', Carbon::today());
                      });
            })
            ->delete();
        $this->info("✓ Cleaned up {$deletedBoardings} canceled/expired boardings (older than {$days} days).");

        $this->info("Database cleanup completed successfully.");
        return self::SUCCESS;
    }
}
