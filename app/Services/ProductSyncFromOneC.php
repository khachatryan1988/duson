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
        if (empty($product->item_id)) {
            return ['stock' => (int)$product->quantity, 'price' => (int)$product->price];
        }

        $key = "onec:{$product->item_id}";
        $ttl = (int)config('onec.cache_ttl', 60);

        $fresh = Cache::remember($key, $ttl, function () use ($product) {
            $r = $this->oneC->getItemPriceAndQuantity($product->item_id);
            return $r['ok'] ? ['stock' => (int)$r['quantity'], 'price' => $r['price']] : null;
        });

        $result = $fresh ?: ['stock' => (int)$product->quantity, 'price' => (int)$product->price];

        if ($fresh && empty($product->avg_price) && empty($product->old_price)) {
            $product->quantity = $result['stock'];
            if (is_numeric($result['price'])) $product->price = (int)$result['price'];
            $product->save();
        }

        return $result;
    }
}
