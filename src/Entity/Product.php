<?php

declare(strict_types = 1);

namespace MyApp\Entity;
use MyApp\Entity\Type;

//Créer une classe du même nom que la table
class Product{
    private ?int $id = null; //le ? signifie que la valeur peut être nulle car c'est la BDD qui faut un auto increment
    private string $name;
    private string $description;
    private float $price;
    private Type $type;
    private int $stock;
    private string $image;

    //Ne pas oublier les deux _ pour les fonctions

    public function __construct(?int $id, string $name, string $description, float $price, Type $type, int $stock, string $image)
    {
        $this->id = $id; //On indique que $this-> id est la même chose que $id
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
        $this->type = $type;
        $this->stock = $stock;
        $this->image = $image;
    }

    //En PHP, une fonction associée à un type qui renvoie une valeur

    public function getId():?int{
        return $this->id;
    }

    public function getName():string{
        return $this->name;
    }

    public function getDescription():string{
        return $this->description;
    }

    public function getPrice():float{
        return $this->price;
    }

    public function getType():Type{
        return $this->type;
    }

    public function getStock():int{
        return $this->stock;
    }

    public function getImage():string{
        return $this->image;
    }

    public function setId(?int $id):void{ //void permet de signaler qu'on ne renvoie rien
        $this->id = $id;
    }

    public function setName(string $name):void{
        $this->name = $name;
    }

    public function setDescription(string $description):void{
        $this->description = $description;
    }

    public function setPrice(string $price):void{
        $this->price = $price;
    }

    public function setType(Type $type):void{
        $this->type = $type;
    }

    public function setStock(int $stock):void{
        $this->stock = $stock;
    }

    public function setImage(int $image):void{
        $this->image = $image;
    }

}