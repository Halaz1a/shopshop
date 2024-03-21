<?php

declare(strict_types = 1);

namespace MyApp\Model;
use PDO;
use MyApp\Entity\Product;
use MyApp\Entity\Cart;
use MyApp\Entity\User;
use MyApp\Entity\Type;
use MyApp\Entity\CartItem;

class CartItemModel{
    private PDO $db;

    public function __construct(PDO $db){
        $this->db = $db;
    }

    public function getAllItemsByCart(int $id_Cart){ 
        $sql = "SELECT * FROM CartItem 
        INNER JOIN Cart ON CartItem.id_Cart = Cart.id_Cart
        INNER JOIN Product ON CartItem.id_Product = Product.id_Product
        INNER JOIN User ON Cart.id = User.id_User
        INNER JOIN Type ON Product.type = Type.id_Type
        WHERE CartItem.id_Cart = :id_Cart";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":id_Cart", $id_Cart);
        $stmt->execute();

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $type = new Type($row['id_Type'], $row['label']);
            $user = new User($row['id_User'], $row['email'],$row['lastName'], $row['firstName'], $row['password'], json_decode($row['roles']), $row['address'], $row['postalCode'], $row['city'], $row['phone']);
            $product = new Product($row['id_Product'], $row['name'], $row['description'], $row['price'], $type, $row['stock'], $row['image']);
            $cart = new Cart($row['id_Cart'], $row['creationDate'], $row['status'], $user); 
            $items[] = new CartItem($row['quantity'], $row['unitPrice'], $product, $cart);
        }
        if (!empty ($items)) {
            return $items;
        }
        else {
            return null;
        }
    }

    public function createCartItem(CartItem $cartItem): bool 
    {
        $sql = "INSERT INTO CartItem (quantity, unitPrice, id_Product, id_Cart) VALUES (:quantity, :unitPrice, :id_Product, :id_Cart)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':quantity', $cartItem->getQuantity(), PDO::PARAM_INT);
        $stmt->bindValue(':unitPrice', $cartItem->getUnitPrice(), PDO::PARAM_STR);
        $stmt->bindValue(':id_Product', $cartItem->getId_Product()->getId(), PDO::PARAM_INT);
        $stmt->bindValue(':id_Cart', $cartItem->getId_Cart()->getId_Cart(), PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function deleteCartItem(int $id_Product, int $id_Cart): bool 
    {
        $sql = "DELETE FROM CartItem WHERE id_Product = :id_Product AND id_Cart = :id_Cart";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id_Product', $id_Product, PDO::PARAM_INT);
        $stmt->bindValue(':id_Cart', $id_Cart, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function updateCartItem(CartItem $cartItem): bool 
    {
        $sql = "UPDATE CartItem SET quantity = :quantity WHERE id_Product = :id_Product AND id_Cart = :id_Cart";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':quantity', $cartItem->getQuantity(), PDO::PARAM_INT);
        $stmt->bindValue(':unitPrice', $cartItem->getUnitPrice(), PDO::PARAM_STR);
        $stmt->bindValue(':id_Product', $cartItem->getId_Product()->getId(), PDO::PARAM_INT);
        $stmt->bindValue(':id_Cart', $cartItem->getId_Cart()->getId_Cart(), PDO::PARAM_INT);
        return $stmt->execute();
    }


}