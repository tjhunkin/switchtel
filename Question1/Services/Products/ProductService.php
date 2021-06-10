<?php

namespace SwitchTel\Services\Products;

use SwitchTel\Services\Products\Interfaces\ProductServiceInterface;
use SwitchTel\Models\Products\Interfaces\ProductInterface;

class ProductService implements ProductServiceInterface
{
    /**
     * Increase Product Service Fee
     *
     * @param ProductInterface $product
     * @param float $percentageIncrease
     */
    public function increaseProductFee(ProductInterface $product,float $percentageIncrease) : void
    {
        // get the current service fee amount
        $currentServiceFee = $product->getServiceFee(); // E.g. R 500.33

        // determine how much the increased value would be
        $increase = $currentServiceFee * $percentageIncrease; // E.g. R 500.33 * 0.05% = R 25.0165

        // add the increased value to the
        $product->setServiceFee($currentServiceFee + $increase); // E.g. R 525.3465
    }
}