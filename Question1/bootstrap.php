<?php

require __DIR__ . '/vendor/autoload.php';

// create the dependency injection container

$builder = new \DI\ContainerBuilder();
$container = $builder->build();

// bind the ProductServiceInterface to the ProductService concrete class
$container->set('\SwitchTel\Services\Products\Interfaces\ProductServiceInterface', \DI\create('\SwitchTel\Services\Products\ProductService'));