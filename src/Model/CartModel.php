<?php

declare(strict_types = 1);

namespace MyApp\Model;
use PDO;
use MyApp\Entity\User;
use MyApp\Entity\Cart;

class CartModel{
    private PDO $db;

    public function __construct(PDO $db){
        $this->db = $db;
    }

    public function getAllCarts(){ 
        $sql = "SELECT * FROM Cart INNER JOIN User ON Cart.id = User.id_User";
        $stmt = $this->db->query($sql);
        $users=[];
        $carts=[]; 

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { 
            $users= new User(intVal($row['id_User']), $row['email'],$row['lastName'], $row['firstName'], $row['password'], json_decode($row['roles']), $row['address'], $row['postalCode'], $row['city'], $row['phone']);
            $carts[] = new Cart(intVal($row['id_Cart']), $row['creationDate'], $row['status'], $users);
        }
        return $carts;
    }

    public function getOneCart(int $id_Cart):?Cart
    {
        $sql = "SELECT * FROM Cart INNER JOIN User ON Cart.id = User.id_User WHERE id_Cart = :id_Cart"; 
        $stmt = $this->db->prepare($sql); //Attention aux injections SQL donc
        $stmt->bindValue(":id_Cart", $id_Cart);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if(!$row){ //Si la ligne est vide, si elle n'a pas d'id, si la ligne n'est pas complète
            return null;
        }

        $user = new User($row['id_User'], $row['email'],$row['lastName'], $row['firstName'], $row['password'], json_decode($row['roles']), $row['address'], $row['postalCode'], $row['city'], $row['phone']);
        $cart = new Cart($row['id_Cart'], $row['creationDate'], $row['status'], $user); 
        return $cart;
    }

    public function updateCart(Cart $cart): bool 
    {
        $sql = "UPDATE Cart SET creationDate = :creationDate, status = :status, id = :id WHERE id_Cart = :id_Cart";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id_Cart', $cart->getId_Cart(), PDO::PARAM_INT);
        $stmt->bindValue(':creationDate', $cart->getCreationDate(), PDO::PARAM_STR);
        $stmt->bindValue(':status', $cart->getStatus(), PDO::PARAM_STR);
        $stmt->bindValue(':id', $cart->getId()->getIdUser(), PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function deleteCart(int $id_Cart): bool 
    {
        $sql = "DELETE FROM Cart WHERE id_Cart = :id_Cart";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id_Cart', $id_Cart, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function createCart(Cart $cart): bool 
    {
        $sql = "INSERT INTO Cart (creationDate, status, id) VALUES (:creationDate, :status, :id)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':creationDate', $cart->getCreationDate(), PDO::PARAM_STR);
        $stmt->bindValue(':status', $cart->getStatus(), PDO::PARAM_STR);
        $stmt->bindValue(':id', $cart->getId()->getIdUser(), PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function getCartByUser(int $id_User):?Cart
    {
        $sql = "SELECT * FROM Cart INNER JOIN User ON Cart.id = User.id_User WHERE User.id_User = :id_User"; 
        $stmt = $this->db->prepare($sql); 
        $stmt->bindValue(":id_User", $id_User);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if(!$row){ 
            return null;
        }

        $user = new User($row['id_User'], $row['email'],$row['lastName'], $row['firstName'], $row['password'], json_decode($row['roles']), $row['address'], $row['postalCode'], $row['city'], $row['phone']);
        $cart = new Cart($row['id_Cart'], $row['creationDate'], $row['status'], $users); 
        return $cart;
    }
}