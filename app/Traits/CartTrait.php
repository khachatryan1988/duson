<?php
namespace App\Traits;

use App\Models\City;
use App\Models\Product;
use App\Models\State;
use App\Services\ProductSyncFromOneC;
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
        $sync = app(ProductSyncFromOneC::class);


        foreach ($cart->getItems() as $item) {
            $product = Product::find($item->getId());
            if (!$product) continue;

            $live = $sync->sync($product);
            $stock = max(0, (int)$live['stock']);
            $unitPrice = is_numeric($live['price'] ?? null) ? (int)$live['price'] : (int)$product->price;


            $cart->updateItem($item->getHash(), [
                'price' => $unitPrice,
            ]);


            $desiredQty = (int)$item->getQuantity();
            $newQty = min($desiredQty, max($stock, 1));
            if ($stock === 0) {
                $cart->removeItem($item->getHash());
                continue;
            } else if ($newQty !== $desiredQty) {
                $cart->updateItem($item->getHash(), ['quantity' => $newQty]);
            }
        }

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
        if (!$product) return redirect()->back();

        $sync = app(ProductSyncFromOneC::class);
        $live = $sync->sync($product);
        $stock = max(0, (int)$live['stock']);
        $unitPrice = is_numeric($live['price'] ?? null) ? (int)$live['price'] : (int)$product->price;

        if ($stock <= 0) {
            return redirect()->back()->with(['status' => 'out_of_stock', 'id' => $product->id]);
        }

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
        $newQty = max(1, (int)$request->input("quantity", 1));
//        $this->cart()->updateItem($hash, [
//            'quantity' => $request->input("quantity"),
//        ]);
//        return redirect()->back()->with(['status' => 'added']);
        $cart = $this->cart();
        $item = $cart->findItem($hash);
        if (!$item) return redirect()->back();

        $product = Product::find($item->getId());
        if (!$product) return redirect()->back();

        $sync = app(ProductSyncFromOneC::class);
        $live = $sync->sync($product);
        $stock = max(0, (int)$live['stock']);
        if ($stock <= 0) {
            $cart->removeItem($hash);
            return redirect()->back()->with(['status' => 'out_of_stock']);
        }

        $safeQty = min($newQty, $stock);
        $cart->updateItem($hash, [
            'quantity' => $safeQty,
            'price'    => is_numeric($live['price'] ?? null) ? (int)$live['price'] : (int)$product->price,
        ]);

        return redirect()->back()->with(['status' => 'updated']);
    }


    public function removeCartItem(Request $request){
        $hash = $request->id;
        $this->cart()->removeItem($hash);
        return redirect()->back()->with(['status' => 'removed']);
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
