<?php
// app/Providers/AppServiceProvider.php

use App\Models\Product;
use App\Observers\ProductObserver;

public function boot(): void
{
    Product::observe(ProductObserver::class);
}