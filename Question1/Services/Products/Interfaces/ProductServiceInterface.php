<?php

namespace SwitchTel\Services\Products\Interfaces;

use SwitchTel\Models\Products\Interfaces\ProductInterface;

interface ProductServiceInterface
{
    public function increaseProductFee(ProductInterface $product,float $percentageIncrease) : void;
}
