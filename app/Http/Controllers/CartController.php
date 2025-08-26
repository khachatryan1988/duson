<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Traits\CartTrait;
use Illuminate\Http\Request;

class CartController extends Controller
{
    use CartTrait;

    public function show(){
        return view('cart')->with($this->getCartDetails());
    }
}
