<?php

declare(strict_types = 1);

namespace MyApp\Entity;

//Créer une classe du même nom que la table
class Rating{
    private ?int $id_Rating = null; 
    private int $stars;
    private string $comment;
    private User $id_User;
    private Product $id_Product;

    //Ne pas oublier les deux _ pour les fonctions

    public function __construct(?int $id_Rating, int $stars, string $comment, User $id_User, Product $id_Product){
        $this->id_Rating = $id_Rating; //On indique que $this-> id est la même chose que $id
        $this->stars = $stars;
        $this->comment = $comment;
        $this->id_User = $id_User;
        $this->id_Product = $id_Product;
    }

    //En PHP, une fonction associée à un type qui renvoie une valeur

    public function getIdRating():?int{
        return $this->id_Rating;
    }

    public function getStars():int{
        return $this->stars;
    }

    public function getComment():string{
        return $this->comment;
    }

    public function getIdUser():User{
        return $this->id_User;
    }

    public function getIdProduct():Product{
        return $this->id_Product;
    }

    public function setIdRating(?int $id_Rating):void{ //void permet de signaler qu'on ne renvoie rien
        $this->id_Rating = $id_Rating;
    }

    public function setStars(int $stars):void{
        $this->stars = $stars;
    }

    public function setComment(string $comment):void{
        $this->comment = $comment;
    }

    public function setIdUser(User $id_User):void{
        $this->id_User = $id_User;
    }

    public function setIdProduct(Product $od_Product):void{
        $this->id_Product = $id_Product;
    }

}