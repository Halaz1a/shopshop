<?php

declare(strict_types = 1);

namespace MyApp\Entity;

class Cart{
    private ?int $id_Cart = null; 
    private string $creationDate;
    private string $status;
    private User $id; //User

    public function __construct(?int $id_Cart, string $creationDate, string $status, User $id){
        $this->id_Cart = $id_Cart; 
        $this->creationDate = $creationDate;
        $this->status = $status;
        $this->id = $id;
    }


    public function getId_Cart():?int{
        return $this->id_Cart;
    }

    public function getCreationDate():string{
        return $this->creationDate;
    }

    public function getStatus():string{
        return $this->status;
    }

    public function getId():User{
        return $this->id;
    }

    public function setId_Cart(?int $idCart):void{ //void permet de signaler qu'on ne renvoie rien
        $this->id_Cart = $id_Cart;
    }

    public function setCreationDate(string $creationDate):void{
        $this->creationDate = $creationdate;
    }

    public function setStatus(string $status):void{
        $this->status = $status;
    }

    public function setId(User $id):void{
        $this->id = $id;
    }
}