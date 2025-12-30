<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Vehicle;
use App\Models\User;
use Carbon\Carbon;
use Mail;

class SendLicenseRenewalReminders extends Command
{
    protected $signature = 'reminders:license-renewal';
    protected $description = 'Send reminder emails for License renewals';

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

            $renewals = Vehicle::whereNotNull('due_renewal_date')->get()->filter(function ($vehicle) use ($maturityDate) {
                // Convert the varchar field into a Carbon date and compare it
                $expiryDateStr = $vehicle->due_renewal_date;

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
                    Mail::send('email.license_reminder', ['renewal' => $renewal, 'daysBefore' => $daysBefore], function ($message) use ($user, $daysBefore) {
                        $message->to($user->email);
                        $message->subject("Reminder: Your License expires in $daysBefore days");
                    });
                }
            }
        }

        $this->info('License renewal reminder emails have been sent successfully.');
    }
}
