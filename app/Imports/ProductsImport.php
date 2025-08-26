<?php
namespace App\Imports;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAttribute;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductsImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        $generalFields = ['code', 'parent_code', 'title_hy', 'title_en', 'title_ru', 'price', 'category_hy', 'category_en', 'category_ru', 'size'];

        $keys = array_keys($rows->toArray()[0]);
        $options = [];
        $fields = [];
        foreach($keys as $key => $keyValue){
            if(!in_array($keyValue, $generalFields)){
                $exp = explode("_", $keyValue);
                $key = $exp[0];
                $options[$key] = [];
            }
        }

        foreach($options as $key => $option){
            $fields[] = [
                'type' => 'category-field',
                'fields' => [
                    'key' => $key,
                    'type' => 'text',
                    'title' => $key
                ]
            ];
        }


        foreach ($rows as $row)
        {
            $arr = $row->toArray();
            $category = Category::firstOrCreate([
                'title->hy' => $arr['category_hy']
            ], [
                'title' => ['hy' => $arr['category_hy'], 'en' => $arr['category_en'], 'ru' => $arr['category_ru']]
            ]);

            $category->update([
                'field_types' => $fields
            ]);

            $data = [];
            foreach($options as $key => $attr){
                $data[$key] = ['hy' => $arr[$key . '_hy'], 'en' => $arr[$key . '_en'], 'ru' => $arr[$key . '_ru']];
            }

            Product::create([
                'title' => ['hy' => $arr['title_hy'], 'ru' => $arr['title_ru'], 'en' => $arr['title_en']],
                'item_id' => !empty($arr['code']) ? $arr['code'] : '999999999',
                'price' => !empty($arr['price']) ? $arr['price'] : 55555,
                'parent_id' => null,
                'category_id' => $category->id,
                'size' => !empty($arr['size']) ? $arr['size'] : null,
                'data' => $data
            ]);
        }

        foreach ($rows as $row)
        {
            $arr = $row->toArray();

            if(!empty($arr['parent_code']) && $arr['parent_code'] != $arr['code']){
                $parent = Product::where('item_id', $arr['parent_code'])->first();

                if(!empty($parent)){
                    Product::where('item_id', $arr['code'])->update(['parent_id' => $parent->id]);
                }
            }
        }
    }
}
