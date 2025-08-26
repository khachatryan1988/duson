<?php

namespace App\Console\Commands;

use App\Imports\ImportProductPrices;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class UpdateAvgPrices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:action-avg-prices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Excel::import(new ImportProductPrices(), public_path('json/Product_Avg_Prices.xlsx'));
    }
}
