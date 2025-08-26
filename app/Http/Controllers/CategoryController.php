<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function show(Request $request, $slug) {
        $category = Category::where('slug', $slug)->firstOrFail();

        $products = $category->products();

        $hasFilters = false;
        if(!empty($request->filters)){
            foreach($request->filters as $key => $value){
                if(!empty($value)){
                    $hasFilters = true;
                    $products->where($key, $value);
                }
            }
        }

        if($hasFilters == false){
            $products->mainProducts();
        }

        $products->where('price', '>', 0);

        if(!empty($request->sortBy)){
            $sortBy = $request->sortBy;
            $exp = explode('_', $sortBy);
            $products->orderBy($exp[0], $exp[1]);
        }else {
//            $products->orderBy('updated_at', 'desc');
            $products->orderBy('quantity', 'desc');
        }

        return view('category')->with([
            'cat' => $category,
            'products' => $products->paginate(12),
            'filters' => $request->has('filters') ? $request->filters : []
        ]);
    }
}
