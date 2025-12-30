<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Vehicle;
use App\Models\User;
use Carbon\Carbon;
use Mail;

class SendFitnessExpiryReminders extends Command
{
    protected $signature = 'reminders:fitness-expiry';
    protected $description = 'Send reminder emails for fitness certificate expiry';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $today = Carbon::now();

        // Define the reminder intervals (days before the maturity date)
        $reminderDays = [40, 10, 7, 3, 2, 1];

        foreach ($reminderDays as $daysBefore) {
            $maturityDate = $today->copy()->addDays($daysBefore);

            $renewals = Vehicle::whereNotNull('fitness_expiry_date')->get()->filter(function ($vehicle) use ($maturityDate) {
                // Convert the varchar field into a Carbon date and compare it
                $expiryDateStr = $vehicle->fitness_expiry_date;

                if (!$expiryDateStr || !strtotime($expiryDateStr)) {
                    $expiryDate = null; 
                } else {
                    $expiryDate = Carbon::parse($expiryDateStr);
                }

                if ($expiryDate !== null && $maturityDate !== null) {
                    return $expiryDate->isSameDay($maturityDate);
                }
                return false;
            });
            foreach ($renewals as $renewal) {
                $user = User::find($renewal->user_id);

                if ($user) {
                    Mail::send('email.fitness_reminder', ['renewal' => $renewal, 'daysBefore' => $daysBefore], function ($message) use ($user, $daysBefore) {
                        $message->to($user->email);
                        $message->subject("Reminder: Your fitness certificate expires in $daysBefore days");
                    });
                }
            }
        }

        $this->info('Fitness certificate expiry reminder emails have been sent.');
    }
}
