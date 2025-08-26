<?php

namespace App\Console\Commands;

use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ResetExpiredActions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reset:expired-actions';

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
        $products = Product::whereNotNull('price_end')->whereDate('price_end', '<', Carbon::today())->get();
        echo $products->count();
        if($products->count()){
            foreach($products as $product){
                $product->update([
                    'old_price' => null,
                    'avg_price' => null,
                    'price_start' => null,
                    'price_end' => null,
                    'price' => $product->old_price,
                ]);
            }
        }
    }
}
