<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Asset;
use App\Models\AssetDeduction;
use App\Models\ExpenseEntry;
use Carbon\Carbon;

class AssetDeduct extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'asset:deduct';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Asset value deduction';

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
        $assets = Asset::orderBy('id', 'DESC')->get();
        foreach ($assets as $asset) {
            $month = AssetDeduction::where('asset_id', $asset->id)->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year)->first();
            if (is_null($month)) {
                if (count($asset->deductions) <= $asset->reduction_period) {
                    $deduct = new AssetDeduction;
                    $deduct->asset_id = $asset->id;
                    $deduct->amount = $asset->reduction_amount;
                    $deduct->save();
                }
            }
        }
        return 0;
    }
}
