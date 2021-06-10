<?php

namespace SwitchTel\Commands\Products;

use SwitchTel\Models\Products\Interfaces\ProductInterface;
use SwitchTel\Services\Products\Interfaces\ProductServiceInterface;

class IncreaseProductServiceFee
{
    private ProductServiceInterface $productService;

    public function __construct(ProductServiceInterface $productService)
    {
        $this->productService = $productService;
    }

    public function increaseProductFee(ProductInterface $product,float $fee) : void
    {
        $this->productService->increaseProductFee($product,$fee);
    }
}