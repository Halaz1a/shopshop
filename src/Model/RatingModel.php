<?php

declare(strict_types = 1);

namespace MyApp\Model;
use MyApp\Entity\Rating;
use MyApp\Entity\Product;
use MyApp\Entity\User;
use MyApp\Entity\Type;
use PDO;

class RatingModel{
    private PDO $db; #le type de $db est PDO

    public function __construct(PDO $db){
        $this->db = $db;
    }

    public function getAllRatings(){ 
        $sql = "SELECT * FROM Rating
        INNER JOIN Product ON Rating.id_Product = Product.id_Product
        INNER JOIN User ON Rating.id_User = User.id_User";
        $stmt = $this->db->query($sql);
        $user=[];
        $product=[];
        $ratings=[]; 

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { 
            $user = new User($row['id_User'], $row['email'], $row['lastName'], $row['firstName'], $row['password'], json_decode($row['roles']), $row['address'], $row['postalCode'], $row['city'], $row['phone']);
            $product = new Product($row['id'], $row['name'], $row['description'], $row['price'], $other, $row['stock'], $row['image']);
            $ratings[] = new Rating($row['id_Rating'], $row['stars'], $row['comment'], $user, $product); 
        }
        return $ratings;
    }

    public function getRatingsByProduct(int $id_Product)
    {
        $sql = "SELECT * FROM Rating 
        INNER JOIN Product ON Rating.id_Product = Product.id_Product
        INNER JOIN User ON Rating.id_User = User.id_User
        INNER JOIN Type ON Product.type = Type.id_Type
        WHERE Rating.id_Product = :id_Product";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":id_Product", $id_Product);
        $stmt->execute();
        $type = [];
        $user = [];
        $product = [];
        $ratings = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { 
            $type = new Type($row['id_Type'], $row['label']);
            $user = new User($row['id_User'], $row['email'], $row['lastName'], $row['firstName'], $row['password'], json_decode($row['roles']), $row['address'], $row['postalCode'], $row['city'], $row['phone']);
            $product = new Product($row['id_Product'], $row['name'], $row['description'], $row['price'], $type, $row['stock'], $row['image']);
            $ratings[] = new Rating($row['id_Rating'], $row['stars'], $row['comment'], $user, $product); 
        }
        return $ratings; 
    }

    public function updateRating(Rating $rating): bool 
    {
        $sql = "UPDATE Rating SET stars = :stars, comment = :comment WHERE id_Rating = :id_Rating";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id_Rating', $rating->getIdRating(), PDO::PARAM_INT);
        $stmt->bindValue(':stars', $rating->getStars(), PDO::PARAM_INT);
        $stmt->bindValue(':comment', $rating->getComment(), PDO::PARAM_STR);
        $stmt->bindValue(':id_User', $rating->getIdUser()->getIdUser(), PDO::PARAM_INT);
        $stmt->bindValue(':id', $rating->getIdProduct()->getId(), PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function createRating(Rating $rating): bool {
        $sql = "INSERT INTO Rating (stars, comment, id_User, id_Product) VALUES (:stars, :comment, :id_User, :id_Product)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':stars', $rating->getStars(), PDO::PARAM_INT);
        $stmt->bindValue(':comment', $rating->getComment(), PDO::PARAM_STR);
        $stmt->bindValue(':id_User', $rating->getIdUser()->getIdUser(), PDO::PARAM_INT);
        $stmt->bindValue(':id_Product', $rating->getIdProduct()->getId(), PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function deleteRating(int $id_Rating): bool 
    {
        $sql = "DELETE FROM Rating WHERE id_Rating = :id_Rating";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id_Rating', $id_Rating, PDO::PARAM_INT);
        return $stmt->execute();
    }

}