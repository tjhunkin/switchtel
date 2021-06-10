<?php

namespace SwitchTel\Models\Products\Interfaces;

interface ProductInterface
{
    public function getServiceFee();
    public function setServiceFee(float $serviceFee);
}