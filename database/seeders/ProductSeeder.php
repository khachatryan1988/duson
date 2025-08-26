<?php

namespace Database\Seeders;

use App\Imports\ProductsImport;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAttribute;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Excel;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
//        $list = ['Mattresses.xlsx', 'Bathrobes final.xlsx', 'Bed frames final.xlsx', 'Beds final.xlsx', 'Blankets & Throws final.xlsx',
//            'Duvets final.xlsx', 'Mattress protectors final.xlsx', 'Pillows final.xlsx', 'Pouf chairs final.xlsx', 'Towels final.xlsx'];
        $list = ['Bed sheets.xlsx'];

        foreach($list as $xlsx){
            $this->command->info($xlsx . ' -- start');
            Excel::import(new ProductsImport, public_path('json/' . $xlsx));
            $this->command->alert($xlsx . ' -- done');
        }
    }
}
