<?php

return [
    'always_in_stock_categories' => explode(',', env('ALWAYS_IN_STOCK_CATEGORIES', '111,114,115,116,117')),

    'always_in_stock_stock' => (int) env('ALWAYS_IN_STOCK_STOCK', 999999),
];
