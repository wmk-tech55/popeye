<?php

namespace MixCode\Payment\Contracts;

interface Item
{
    /**
     * Create new Payment Item
     *
     * @param string $name item name
     * @param float $price item price 
     * @param string $description item description
     * @param integer $quantity item quantity
     */
    public function __construct(string $name, float $price, string $description, int $quantity = 1);

    /**
     * Return underlining item
     *
     * @return mixed
     */
    public function toBase();
    
    /**
     * Return underlining item array
     *
     * @return array
     */
    public function toArray(): array;

}