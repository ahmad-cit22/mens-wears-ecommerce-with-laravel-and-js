<?php

namespace App\Console\Commands;

use App\Models\Accessory;
use Illuminate\Console\Command;
use Mail;
use App\Mail\AccessoryMinBalanceMail;

class AccessoryMinBalance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'accessory:min-balance-warning';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Accessory Minimum Balance Warning';

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
        $accessories = Accessory::all();
        foreach ($accessories as $accessory) {
            $balance = $accessory->stock->sum('credit') - $accessory->stock->sum('debit');
            $min_balance = $accessory->min_quantity;

            if ($balance < $min_balance) {
                // $business
                Mail::send(new AccessoryMinBalanceMail($accessory));
            }
        }
        return 0;
    }
}
