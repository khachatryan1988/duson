<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Imports\ProductsTitleUpdate;
use Excel;

class UpdateProductsTitle extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-products-title';

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
        Excel::import(new ProductsTitleUpdate, public_path('json/Product_title_Update.xlsx'));
    }
}
