<?php
namespace App\Imports;

use App\Models\Product;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductsTitleUpdate implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row)
        {
            $arr = $row->toArray();
            Product::where('item_id', $row['code'])->update([
                'title' => ['hy' => $arr['title_hy'], 'ru' => $arr['title_ru'], 'en' => $arr['title_en']],
            ]);
        }
    }
}
