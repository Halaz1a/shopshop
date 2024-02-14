<?php

declare(strict_types = 1);

namespace MyApp\Model;
use MyApp\Entity\User;
use PDO;

class UserModel{
    private PDO $db; #le type de $db est PDO

    public function __construct(PDO $db){
        $this->db = $db;
    }

    public function getAllUsers(){ 
        $sql = "SELECT * FROM User";
        $stmt = $this->db->query($sql);
        //On récupère dans stmt le contenu de la BDD grâce au PDO stocké dans db via query, stmt sera donc un tableau
        //Chaque ligne du stmt sera transformé en types
        //$this s'utilise avec les classes
        $users=[]; //On sait pas si on va avoir des résultats ou pas donc tableau vide

        //Tant qu'il y a des lignes, continuer 
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { 
            $users[] = new User($row['id_User'], $row['email'], $row['lastName'], $row['firstName'], $row['password'], json_decode($row['roles']), $row['address'], $row['postalCode'], $row['city'], $row['phone']); 
            //A chaque nouvelle valeur, j'ajoute un nouveau type ayant 1 id et 1 label
            //A la fin on récupère un tableau de types
            //Row = ligne
            //FETCH_ASSOC permet d'avoir les clés de la table
            //new permet de créer un objet d'une classe
        }
        return $users;
    }

    public function getOneUser(int $id_User):?User
    {
        $sql = "SELECT * FROM User WHERE id_User = :id_User";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":id_User", $id_User);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if(!$row){
            return null;
        }
        return new User($row['id_User'], $row['email'], $row['lastName'], $row['firstName'], $row['password'], json_decode($row['roles']), $row['address'], $row['postalCode'], $row['city'], $row['phone']);
    }

    public function updateUser(User $user): bool 
    {
        $sql = "UPDATE User SET email = :email, lastName = :lastName, firstName = :firstName, password = :password, address = :address, postalCode = :postalCode, city = :city, phone = :phone WHERE id_User = :id_User";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id_User', $user->getIdUser(), PDO::PARAM_INT);
        $stmt->bindValue(':email', $user->getEmail(), PDO::PARAM_STR);
        $stmt->bindValue(':lastName', $user->getLastName(), PDO::PARAM_STR);
        $stmt->bindValue(':firstName', $user->getFirstName(), PDO::PARAM_STR);
        $stmt->bindValue(':password', $user->getPassword(), PDO::PARAM_STR);
        $stmt->bindValue(':address', $user->getAddress(), PDO::PARAM_STR);
        $stmt->bindValue(':postalCode', $user->getPostalCode(), PDO::PARAM_STR);
        $stmt->bindValue(':city', $user->getCity(), PDO::PARAM_STR);
        $stmt->bindValue(':phone', $user->getPhone(), PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function createUser(User $user): bool {
        $sql = "INSERT INTO User (email, lastName, firstName, password, roles, address, postalCode, city, phone) VALUES (:email, :lastName, :firstName, :password, :roles, :address, :postalCode, :city, :phone)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':email', $user->getEmail(), PDO::PARAM_STR);
        $stmt->bindValue(':lastName', $user->getlastName(), PDO::PARAM_STR);
        $stmt->bindValue(':firstName', $user->getfirstName(), PDO::PARAM_STR);
        $stmt->bindValue(':password', $user->getPassword(), PDO::PARAM_STR);
        $stmt->bindValue(':roles', json_encode($user->getRole()));
        $stmt->bindValue(':address', $user->getAddress(), PDO::PARAM_STR);
        $stmt->bindValue(':postalCode', $user->getPostalCode(), PDO::PARAM_STR);
        $stmt->bindValue(':city', $user->getCity(), PDO::PARAM_STR);
        $stmt->bindValue(':phone', $user->getPhone(), PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function deleteUser(int $id): bool 
    {
        $sql = "DELETE FROM User WHERE id_User = :id_User";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id_User', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getUserByEmail(string $email): ?User
    {
        $sql = "SELECT * FROM User WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }
        return new User($row['id_User'], $row['email'], $row['lastName'], $row['firstName'], $row['password'], json_decode($row['roles']), $row['address'], $row['postalCode'], $row['city'], $row['phone']);
    }

    public function getUserById(int $id): ?User {
        $sql = "SELECT * FROM User WHERE id_User = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }
        return new User($row['id_User'], $row['email'], $row['lastName'], $row['firstName'], $row['password'], json_decode($row['roles']), $row['address'], $row['postalCode'], $row['city'], $row['phone']);
    }

}
