<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Asset;
use App\Models\AssetDeduction;
use App\Models\ExpenseEntry;
use Carbon\Carbon;

class AssetDepreciation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'asset:depreciate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Asset Value Depreciation';

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
            if ($asset->disposal_amount == null) {
                $month = AssetDeduction::where('asset_id', $asset->id)->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year)->first();
                $total_depreciated = $asset->deductions->sum('amount');
                $net_value = $asset->amount - $total_depreciated;

                if (is_null($month) && $net_value > 0 && !$asset->disposal_amount) {
                    if ($asset->depreciation_date == Carbon::today()->format('d')) {
                        $deduct = new AssetDeduction;
                        $deduct->asset_id = $asset->id;

                        $expense = new ExpenseEntry;
                        $expense->expense_id = 13;
                        $expense->bank_id = $asset->bank_id;
                        $expense->date = Carbon::today();
                        $expense->note = 'Depreciation of ' . $asset->name;

                        if ($asset->depreciation_value <= $net_value) {
                            $deduct->amount = $asset->depreciation_value;

                            $expense->amount = $asset->depreciation_value;
                        } else {
                            $deduct->amount = $net_value;

                            $expense->amount = $net_value;
                        }
                        $expense->save();
                        $deduct->save();
                    }
                }
            }

        }
        return 0;
    }
}
