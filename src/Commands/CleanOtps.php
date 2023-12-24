<?php

namespace Ichtrojan\Otp\Commands;

use Ichtrojan\Otp\Models\Otp;
use Illuminate\Console\Command;

class CleanOtps extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'otp:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean Otp database, remove all old otps that is expired or used.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $otps = Otp::where('valid', 0)->delete();

            $this->info("Found {$otps} expired otps.");
            $this->info($otps ? "Expired tokens deleted" : "No tokens were deleted");
        } catch (\Exception $e) {
            $this->error("Error:: {$e->getMessage()}");
        }

        return 0;
    }
}
