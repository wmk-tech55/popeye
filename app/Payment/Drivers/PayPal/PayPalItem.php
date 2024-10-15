<?php

namespace MixCode\Payment\Drivers\PayPal;

use MixCode\Payment\Contracts\Item as Contract;
use PayPal\Api\Item;

class PayPalItem implements Contract
{
    /**
     * Payment Item
     *
     * @var \PayPal\Api\Item
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
        $this->item = new Item();
        $this->item
            ->setName($name)
            ->setCurrency('USD')
            ->setQuantity($quantity)
            ->setDescription($description)
            ->setPrice($price);
        
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
        return [
            'name'          => $this->item->getName(),
            'quantity'      => $this->item->getQuantity(),
            'description'   => $this->item->getDescription(),
            'price'         => $this->item->getPrice(),
        ];
    }
}