<?php

namespace App\Console\Commands;

use App\Models\Product;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update products from 1C';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $channel = Log::build([
            'driver' => 'single',
            'path' => storage_path('logs/updates.log'),
        ]);

        try{
            $client = new Client();
            $res = $client->get('http://178.160.203.146:1728/Promas/hs/eshop/eshopitems/GET_ITEMS_DATA', ['auth' =>  ['Eshop', '5tfEKwP9']]);
            $res->getStatusCode(); // 200
            $response = $res->getBody();
            $result = json_decode($response);

            if(!empty($result->ErrorMessage)){
                if($result->ErrorMessage == 200){
                    $counter = 0;
                    foreach($result->Items as $item){
                        $product = Product::where('item_id', $item->ItemID)->first();
                        if($product){
                            if(empty($product->old_price)){
                                $counter++;
//                            $this->info('ID: ' . $product->item_id. ' Price: ' . $product->price . ' Qty: '. $product->quantity);
//                            $this->info('ID: ' . $product->item_id. ' Price: ' . $item->Price . ' Qty: '. $item->Quantity);
                                $product->update([
                                    'quantity' => $item->Quantity,
                                    'price' => $item->Price,
//                                    'adgt' => $item->adgt,
                                ]);
                            }else{
                                $product->update([
                                    'quantity' => $item->Quantity
                                ]);
                            }
                        }else{
                            $this->line('ID: ' . $item->ItemID. ' not found');
                        }
                    }
                    Log::stack(['product updates', $channel])->alert($counter . ' products updated.');
                    echo 'Success';
                }else if($result->ErrorMessage == 'Item does not exist'){
                    Log::stack(['productUpdates', $channel])->alert($result->ErrorMessage);
                    echo $result->ErrorMessage;
                }
            }else{
                Log::stack(['product updates', $channel])->alert('Invalid request');
                echo 'Invalid request';
            }

        }catch (\Exception $ex){
            Log::alert($ex->getMessage());
            echo $ex->getMessage();
        }
    }
}
