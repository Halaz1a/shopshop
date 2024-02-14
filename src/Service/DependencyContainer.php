<?php
namespace MyApp\Service;

use PDO;
use MyApp\Model\TypeModel;
use MyApp\Model\ProductModel;
use MyApp\Model\UserModel;
use MyApp\Model\CartModel;
use MyApp\Model\CartItemModel;
use MyApp\Model\RatingModel;

class DependencyContainer
{
    private $instances = [];

    public function __construct()
    {
    }

    public function get($key)
    {
        if (!isset($this->instances[$key])) {
            $this->instances[$key] = $this->createInstance($key);
        }

        return $this->instances[$key];
    }

    private function createInstance($key)
    {
        switch ($key) { //Sorte de suite de if 

            case 'PDO': return $this->createPDOInstance(); //si le cas 'PDO" se présente, on retourne
            case 'TypeModel' : 
                $pdo = $this->get('PDO'); //Récupération de la connexion à la base de données
                return new TypeModel($pdo);
            case 'ProductModel':
                $pdo = $this->get('PDO');
                return new ProductModel($pdo);
            case 'UserModel':
                $pdo = $this->get('PDO');
                return new UserModel($pdo);
            case 'CartModel':
                $pdo = $this->get('PDO');
                return new CartModel($pdo);
            case 'CartItemModel':
                $pdo = $this->get('PDO');
                return new CartItemModel($pdo);
            case 'RatingModel':
                $pdo = $this->get('PDO');
                return new RatingModel($pdo);
            default:
                throw new \Exception("No service found for key: " . $key); //si y'a pas de PDO, on renvoie ça
        }
    }

    private function createPDOInstance(){
        try{ //on essaye quelque chose
            $pdo = new PDO('mysql:host='.$_ENV['DB_HOST'].';dbname='.$_ENV['DB_NAME'].';charset=utf8',$_ENV['DB_USER'],$_ENV['DB_PASS']);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //permet d'obtenir des erreurs précises
            return $pdo;
        }
        catch(PDOException $e){ //s'il y a une erreur, on la récupère dans $e
            throw new \Exception("PDO erreur de connexion".$e->getMessages()); #on donne la nature de l'erreur et son numéro
        }
    }

}
?>
