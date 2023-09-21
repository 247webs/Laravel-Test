<?php

namespace App\Helpers;

use Laravel\Cashier\Cashier;

class Stripe
{
    /**
     * Init the helper class
     */
    public static function init(): self 
    {
        return new self();
    }

    /**
     * Returning the products of stripe
     * @param string|null $id
     */
    public function products(?string $id = null): mixed
    {
        if (!empty($id)) {
            $product = Cashier::stripe()->products->retrieve($id);
            $price = $this->prices($product->default_price);
            $product->unit_amount = $price->unit_amount;

            return $product;
        }

        $products = Cashier::stripe()->products->all(['active' => true]);
        foreach ($products as $product) {
            $price = $this->prices($product->default_price);
            $product->unit_amount = $price->unit_amount;
        }

        return $products;
    }

    /**
     * Returning the prices based on id or all
     * @param string|null $id
     */
    public function prices(?string $id = null): mixed
    {
        if (!empty($id)) {
            return Cashier::stripe()->prices->retrieve($id);
        }

        return Cashier::stripe()->prices->all();
    }
}