<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Cache;

class ProductSyncFromOneC
{
    public function __construct(private OneCClient $oneC) {}

    /**
     */
    public function sync(Product $product): array
    {
        if ($product->isAlwaysInStock()) {

            $fresh = $this->pullOnce($product);

            $price = is_numeric($fresh['price'] ?? null) ? (int)$fresh['price'] : (int)$product->price;

            if ($fresh && empty($product->avg_price) && empty($product->old_price) && is_numeric($fresh['price'] ?? null)) {
                $product->price = (int)$fresh['price'];
                $product->save();
            }

            return [
                'stock' => (int) config('inventory.always_in_stock_stock', 999999),
                'price' => $price,
            ];
        }

        $key = "onec:{$product->item_id}";
        $ttl = (int)config('onec.cache_ttl', 60);

        $fresh = Cache::remember($key, $ttl, function () use ($product) {
            $r = $this->oneC->getItemPriceAndQuantity($product->item_id);
            return $r['ok'] ? ['stock' => (int)$r['quantity'], 'price' => $r['price']] : null;
        });

        $result = $fresh ?: ['stock' => (int)$product->quantity, 'price' => (int)$product->price];

        if ($fresh && empty($product->avg_price) && empty($product->old_price)) {
            $product->quantity = max(0, (int)$result['stock']);
            if (is_numeric($result['price'])) $product->price = (int)$result['price'];
            $product->save();
        }

        return $result;
    }


    private function pullOnce(Product $product): ?array
    {
        $r = $this->oneC->getItemPriceAndQuantity($product->item_id);
        return $r['ok'] ? ['price' => $r['price']] : null;
    }

}
