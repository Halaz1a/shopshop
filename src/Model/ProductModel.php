<?php

declare(strict_types = 1);

namespace MyApp\Model;
use MyApp\Entity\Product;
use PDO;
use MyApp\Entity\Type;

class ProductModel{
    private PDO $db; #le type de $db est PDO

    public function __construct(PDO $db){
        $this->db = $db;
    }

    public function getAllProducts(){ 
        $sql = "SELECT * FROM Product INNER JOIN Type ON Product.type = Type.id_Type";
        $stmt = $this->db->query($sql);
        //On récupère dans stmt le contenu de la BDD grâce au PDO stocké dans db via query, stmt sera donc un tableau
        //Chaque ligne du stmt sera transformé en types
        //$this s'utilise avec les classes
        $other=[];
        $products=[]; //On sait pas si on va avoir des résultats ou pas donc tableau vide

        //Tant qu'il y a des lignes, continuer 
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { 
            $other= new Type($row['id_Type'], $row['label']);
            $products[] = new Product($row['id_Product'], $row['name'], $row['description'], $row['price'], $other, $row['stock'], $row['image']); 
            //A chaque nouvelle valeur, j'ajoute un nouveau type ayant 1 id et 1 label
            //A la fin on récupère un tableau de types
            //Row = ligne
            //FETCH_ASSOC permet d'avoir les clés de la table
            //new permet de créer un objet d'une classe
        }
        return $products;
    }

    public function getAllProductsByStock(){ 
        $sql = "SELECT * FROM Product INNER JOIN Type ON Product.type = Type.id_Type WHERE stock > 0";
        $stmt = $this->db->query($sql);
        //On récupère dans stmt le contenu de la BDD grâce au PDO stocké dans db via query, stmt sera donc un tableau
        //Chaque ligne du stmt sera transformé en types
        //$this s'utilise avec les classes
        $other=[];
        $products=[]; //On sait pas si on va avoir des résultats ou pas donc tableau vide

        //Tant qu'il y a des lignes, continuer 
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { 
            $other= new Type($row['id_Type'], $row['label']);
            $products[] = new Product($row['id_Product'], $row['name'], $row['description'], $row['price'], $other, $row['stock'], $row['image']); 
            //A chaque nouvelle valeur, j'ajoute un nouveau type ayant 1 id et 1 label
            //A la fin on récupère un tableau de types
            //Row = ligne
            //FETCH_ASSOC permet d'avoir les clés de la table
            //new permet de créer un objet d'une classe
        }
        return $products;
    }

    public function getOneProduct(int $id_Product):?Product
    {
        $sql = "SELECT * FROM Product INNER JOIN Type ON Product.type = Type.id_Type WHERE id_Product = :id_Product ORDER BY name"; // :id pour faire des requêtes préparées pour la sécurité 
        //(éviter injections sql)
        $stmt = $this->db->prepare($sql); //Attention aux injections SQL donc
        $stmt->bindValue(":id_Product", $id_Product);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if(!$row){ //Si la ligne est vide, si elle n'a pas d'id, si la ligne n'est pas complète
            return null;
        }

        $other= new Type($row['id_Type'], $row['label']);
        $products = new Product($row['id_Product'], $row['name'], $row['description'], $row['price'], $other, $row['stock'], $row['image']); 
        return $products;
    }

    public function updateProduct(Product $products): bool 
    {
        $sql = "UPDATE Product SET name = :name, description = :description, price = :price, type = :type, stock = :stock WHERE id_Product = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $products->getId(), PDO::PARAM_INT);
        $stmt->bindValue(':name', $products->getName(), PDO::PARAM_STR);
        $stmt->bindValue(':description', $products->getDescription(), PDO::PARAM_STR);
        $stmt->bindValue(':price', $products->getPrice(), PDO::PARAM_STR);
        $stmt->bindValue(':type', $products->getType()->getId(), PDO::PARAM_INT);
        $stmt->bindValue(':stock', $products->getStock(), PDO::PARAM_INT);
        $stmt->bindValue(':image', $products->getImage(), PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function deleteProduct(int $id): bool 
    {
        $sql = "DELETE FROM Product WHERE id_Product = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function createProduct(Product $product): bool 
    {
        $sql = "INSERT INTO Product (name, description, price, type, stock, image) VALUES (:name, :description, :price, :type, :stock, :image)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':name', $product->getName(), PDO::PARAM_STR);
        $stmt->bindValue(':description', $product->getDescription(), PDO::PARAM_STR);
        $stmt->bindValue(':price', $product->getPrice(), PDO::PARAM_STR);
        $stmt->bindValue(':type', $product->getType()->getId(), PDO::PARAM_INT);
        $stmt->bindValue(':stock', $product->getStock(), PDO::PARAM_INT);
        $stmt->bindValue(':image', $products->getImage(), PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function getAllProductsByTypeUser(Type $type){ 
        $sql = "SELECT * FROM Product INNER JOIN Type ON Product.type = Type.id_Type WHERE Product.type = :id_Type AND stock > 0";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":id_Type", $type->getId(), PDO::PARAM_INT);
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { 
            $products[] = new Product($row['id_Product'], $row['name'], $row['description'], $row['price'], $type, $row['stock'], $row['image']); 
        }
        if (!empty($products)) {
            return $products;
        }
        else {
            return null;
        } 
    }

    public function getAllProductsByTypeAdmin(Type $type){ 
        $sql = "SELECT * FROM Product INNER JOIN Type ON Product.type = Type.id_Type WHERE Product.type = :id_Type";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":id_Type", $type->getId(), PDO::PARAM_INT);
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { 
            $products[] = new Product($row['id_Product'], $row['name'], $row['description'], $row['price'], $type, $row['stock'], $row['image']); 
        }
        if (!empty($products)) {
            return $products;
        }
        else {
            return null;
        } 
    }
}