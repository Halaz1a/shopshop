<?php

declare(strict_types = 1);

namespace MyApp\Entity;

class CartItem{
    private int $quantity;
    private float $unitPrice;
    private Product $id_Product; 
    private Cart $id_Cart; 

    public function __construct(int $quantity, float $unitPrice, Product $id_Product, Cart $id_Cart){
        $this->quantity = $quantity; 
        $this->unitPrice = $unitPrice;
        $this->id_Product = $id_Product;
        $this->id_Cart = $id_Cart;
    }


    public function getQuantity():int{
        return $this->quantity;
    }

    public function getUnitPrice():float{
        return $this->unitPrice;
    }

    public function getId_Product():Product{
        return $this->id_Product;
    }

    public function getId_Cart():Cart{
        return $this->id_Cart;
    }

    public function setQuantity(int $quantity):void{ 
        $this->quantity = $quantity;
    }

    public function setCreationDate(float $unitPrice):void{
        $this->unitPrice = $unitPrice;
    }

    public function setId_Product(Product $id_Product):void{
        $this->id_Product = $id_Product;
    }

    public function setId_Cart(Cart $id_Cart):void{
        $this->id_Cart = $id_Cart;
    }
}