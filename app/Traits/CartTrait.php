<?php
namespace App\Traits;

use App\Models\City;
use App\Models\Product;
use App\Models\State;
use Cart;
use Illuminate\Http\Request;

trait CartTrait{

    public function cart(){
        return Cart::name('shopping');
    }

    public function getItems(){
        return $this->cart()->getItems();
    }

    public function getCartDetails(){

        $cart = $this->cart();

        $actions = $cart->getActions();
        $actionsTotal = $this->getActionsTotal($actions);

        return [
            'items' => $cart->getItems(),
            'count' => $cart->countItems(),
            'subtotal' => number_format($cart->getItemsSubtotal(), 0, 2) . ' ' . trans('դր․'),
            'total' => number_format($cart->getTotal(), 0, 2) . ' ' . trans('դր․'),
            'actions' => $actions,
            'totals' => [
                'subtotal' => $cart->getItemsSubtotal(),
                'total' => $cart->getTotal(),
                'shipping' => $actionsTotal
            ]
        ];
    }

    public function getActionsTotal($actions) {
        $total = 0;
        if(!empty($actions)){
            foreach($actions as $action){
                $total += $action->getDetails()['amount'];
            }
        }

        return $total;
    }

    public function clearCart(){
        $cart = $this->cart();

        $cart->clearActions();
        $cart->destroy();
    }

    public function addToCart(Request $request){
        $id = $request->id;
        $product = Product::find($id);

        $this->cart()->addItem([
            'id'    => $product->id,
            'title' => $product->title,
            'price' => $product->price,
            'quantity' => 1,
            'options' => [
                'amount' => $product->amount,
                'image' => $product->image,
                'attributes' => $product->attributes()->pluck('value', 'key')
            ]
        ]);

        return redirect()->back()->with(['status' => 'added', 'id' => $product->id]);
    }

    public function updateCartItem(Request $request){
        $hash = $request->id;
        $this->cart()->updateItem($hash, [
            'quantity' => $request->input("quantity"),
        ]);
        return redirect()->back()->with(['status' => 'added']);
    }

    public function removeCartItem(Request $request){
        $hash = $request->id;
        $this->cart()->removeItem($hash);
        return redirect()->back()->with(['status' => 'added']);
    }

    public function calculateShippingCost($cityID = null, $stateID = null){

        $shipingCost = 0;

        if(!empty($cityID)){
            $city = City::find($cityID);

            $shipingCost = $city->km * 150;

            $state = $city->state;

            if($state->free){
                if(!empty($state->free_limit)){
                    $cartTotal = $this->cart()->getItemsSubtotal();
                    if($cartTotal >= $state->free_limit){
                        $shipingCost = 0;
                    }elseif ($cartTotal <= $state->free_limit && $city->km = 5){
                        $shipingCost = 1000;
                    }
                }
                else{
                    $shipingCost = 0;
                }
            }
        }
        $this->addShippingCost($shipingCost);

        $form = session('checkout');
        if(!empty($form)){
            $form['city'] = $cityID;
            $form['state'] = $stateID;
            session()->put('checkout', $form);
        }else{
            $form = [];
            $form['city'] = $cityID;
            $form['state'] = $stateID;
            session()->put('checkout', $form);
        }

        return $shipingCost;
    }

    public function addShippingCost($shipingCost){
        $this->cart()->applyAction([
            'id' => 1,
            'group' => 'Additional costs',
            'title' => 'Առաքման արժեք',
            'value' => $shipingCost
        ]);
    }
}
