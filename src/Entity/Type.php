<?php

// Pour nous obliger à mettre des types de variable
declare(strict_types = 1);

namespace MyApp\Entity;

//Créer une classe du même nom que la table
class Type{
    private ?int $id = null; //le ? signifie que la valeur peut être nulle car c'est la BDD qui faut un auto increment
    private string $label;

    //Ne pas oublier les deux _ pour les fonctions

    public function __construct(?int $id, string $label){
        $this->id = $id; //On indique que $this-> id est la même chose que $id
        $this->label = $label;
    }

    //En PHP, une fonction associée à un type qui renvoie une valeur
    //get pour retourner des valeurs (intérieur vers extérieur)
    //set pour enregistrer/modifier des valeurs (extérieur vers intérieur)

    public function getId():?int{
        return $this->id;
    }

    public function getLabel():?string{
        return $this->label;
    }

    public function setId(?int $id):void{ //void permet de signaler qu'on ne renvoie rien
        $this->id = $id;
    }

    public function setLabel(?string $label):void{
        $this->label = $label;
    }

}