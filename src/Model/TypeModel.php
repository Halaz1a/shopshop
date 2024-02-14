<?php

declare(strict_types = 1);

namespace MyApp\Model;
use MyApp\Entity\Type;
use PDO;

class TypeModel{
    private PDO $db; #le type de $db est PDO

    public function __construct(PDO $db){
        $this->db = $db;
    }

    public function getAllTypes(){ 
        $sql = "SELECT * FROM Type ORDER BY label";
        $stmt = $this->db->query($sql);
        //On récupère dans stmt le contenu de la BDD grâce au PDO stocké dans db via query, stmt sera donc un tableau
        //Chaque ligne du stmt sera transformé en types
        $types=[];

        //Tant qu'il y a des lignes, continuer 
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { 
            $types[] = new Type($row['id_Type'], $row['label']); 
            //A chaque nouvelle valeur, j'ajoute un nouveau type ayant 1 id et 1 label
            //A la fin on récupère un tableau de types
            //Row = ligne
            //FETCH_ASSOC permet d'avoir les clés de la table
            //On crée une collection, un tableau d'objets
        }
        

        return $types;
    }

    public function getOneType(int $id):?Type{
        $sql = "SELECT * FROM Type WHERE id_Type = :id"; // :id pour faire des requêtes préparées pour la sécurité 
        //(éviter injections sql)
        $stmt = $this->db->prepare($sql); //Attention aux injections SQL donc
        $stmt->bindValue(":id", $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if(!$row){ //Si la ligne est vide, si elle n'a pas d'id, si la ligne n'est pas complète
            return null;
        }
        return new Type($row['id_Type'], $row['label']);
    }

    public function updateType(Type $types): bool 
        {
        $sql = "UPDATE Type SET label = :label WHERE id_Type = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':label', $types->getLabel(), PDO::PARAM_STR);
        $stmt->bindValue(':id', $types->getId(), PDO::PARAM_INT);
        return $stmt->execute();
        }

    public function createType(Type $types): bool 
    {
        $sql = "INSERT INTO Type (label) VALUES (:label)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':label', $types->getLabel(), PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function deleteType(int $id): bool 
    {
        $sql = "DELETE FROM Type WHERE id_Type = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

}