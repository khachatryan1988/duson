<?php

namespace App\Imports;

use App\Models\Product;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use \PhpOffice\PhpSpreadsheet\Shared\Date;

class ImportProductPrices implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $product = Product::where('item_id', $row['Code'] ?? $row['code'] ?? null)->first();

        if($product){
            $product->update([
                'price' => $row['price'],
                'old_price' => $row['old_price'],
                'avg_price' => $row['avg_price'],
                'unit' => $row['unit'],
                'adgt' => $row['adgt'],
                'name_arm' => $row['name_arm'],
                'cashback_price' => $row['cashback_price'],
//                'gift_mat' => $row['gift_mat'],
                'price_start' => Carbon::parse($row['start_date'])->format('Y-m-d'),
                'price_end' => Carbon::parse($row['end_date'])->format('Y-m-d'),
            ]);
        }
    }
}
