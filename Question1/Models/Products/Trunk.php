<?php

namespace SwitchTel\Models\Products;

use SwitchTel\Models\Products\Base\Product;
use SwitchTel\Models\Products\Interfaces\ProductInterface;

class Trunk extends Product implements ProductInterface
{
    protected float $serviceFee;

    public function getServiceFee() : float
    {
        return $this->serviceFee;
    }

    public function setServiceFee(float $serviceFee)
    {
        $this->serviceFee = $serviceFee;
    }
}