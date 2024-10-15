<?php

namespace MixCode\Payment\Drivers\Tap;

use MixCode\Payment\Contracts\Item as Contract;

class TapItem implements Contract
{
    /**
     * Payment Item
     *
     * @var array
     */
    protected $item;

    /**
     * Create new Payment Item
     *
     * @param string $name item name
     * @param float $price item price 
     * @param string $description item description
     * @param integer $quantity item quantity
     */
    public function __construct(string $name, float $price, string $description, int $quantity = 1) 
    {
        $this->item = [
            'name'              => $name,
            'amount_per_unit'   => $price,
            'total_amount'      => ($price * $quantity),
            'description'       => $description,
            'quantity'          => $quantity,
        ];
    }

    /**
     * Return underlining item
     *
     * @return mixed
     */
    public function toBase()
    {
        return $this->item;
    }
    
    /**
     * Return underlining item array
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->item;
    }
}