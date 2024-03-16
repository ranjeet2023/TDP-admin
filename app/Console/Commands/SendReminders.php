<?php

namespace App\Console\Commands;

use App\Models\LeadsComment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Mail\MyMail;
use Illuminate\Console\Scheduling\Schedule;


class SendReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email reminders for pending tasks';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $reminders = LeadsComment::where('follow_up_date', '<=', Carbon::now())->get();

        if (count($reminders) > 0) {
            foreach ($reminders as $reminder) {
                $reminder_date = Carbon::parse($reminder->reminder_date);
                $delay = $reminder_date->diffInMinutes(Carbon::now());
                Mail::to($reminder->to_email)->later(now()->addMinutes($delay), new MyMail($reminder->comment, $reminder->email_body, $reminder->from_email));
                // $reminder->delete();
            }
        }
    }

}
