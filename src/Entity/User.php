<?php

declare(strict_types = 1);

namespace MyApp\Entity;

//Créer une classe du même nom que la table
class User{
    private ?int $id_User = null; //le ? signifie que la valeur peut être nulle car c'est la BDD qui faut un auto increment
    private string $email;
    private string $lastName;
    private string $firstName;
    private string $password;
    private array $roles;
    private string $address;
    private string $postalCode;
    private string $city;
    private string $phone;

    //Ne pas oublier les deux _ pour les fonctions

    public function __construct(?int $id_User, string $email, string $lastName, string $firstName, string $password, array $roles, string $address, string $postalCode, string $city, string $phone){
        $this->id_User = $id_User; //On indique que $this-> id est la même chose que $id
        $this->email = $email;
        $this->lastName = $lastName;
        $this->firstName = $firstName;
        $this->password = $password;
        $this->roles = $roles;
        $this->address = $address;
        $this->postalCode = $postalCode;
        $this->city = $city;
        $this->phone = $phone;
    }

    //En PHP, une fonction associée à un type qui renvoie une valeur

    public function getIdUser():?int{
        return $this->id_User;
    }

    public function getEmail():string{
        return $this->email;
    }

    public function getLastName():string{
        return $this->lastName;
    }

    public function getFirstName():string{
        return $this->firstName;
    }

    public function getPassword():string{
        return $this->password;
    }

    public function getRole():array{
        return $this->roles;
    }

    public function getAddress():string{
        return $this->address;
    }

    public function getPostalCode():string{
        return $this->postalCode;
    }

    public function getCity():string{
        return $this->city;
    }

    public function getPhone():string{
        return $this->phone;
    }

    public function setIdUser(?int $id_User):void{ //void permet de signaler qu'on ne renvoie rien
        $this->id_User = $id_User;
    }

    public function setEmail(string $email):void{
        $this->email = $email;
    }

    public function setFirstName(string $firstName):void{
        $this->firstName = $firstName;
    }

    public function setLastName(string $lastName):void{
        $this->lastName = $lastName;
    }

    public function setPassword(string $password):void{
        $this->password = $password;
    }

    public function setRoles(array $roles): void{
        $this->roles = $roles;
    }

    public function setAddress(string $address): void{
        $this->address = $address;
    }

    public function setPostalCode(string $postalCode): void{
        $this->postalCode = $postalCode;
    }

    public function setCity(string $city): void{
        $this->city = $city;
    }

    public function setPhone(string $phone): void{
        $this->phone = $phone;
    }

    public function verifyPassword(string $password): bool{
        return password_verify($password, $this->password);
    }
}