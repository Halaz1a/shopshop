<?php
declare (strict_types = 1);
namespace MyApp\Controller;
use MyApp\Service\DependencyContainer;
use Twig\Environment;
use MyApp\Model\TypeModel;
use MyApp\Model\ProductModel;
use MyApp\Model\UserModel;
use MyApp\Entity\Type;
use MyApp\Entity\User;
use MyApp\Entity\Product;
use MyApp\Entity\Cart;
use MyApp\Entity\CartModel;
use MyApp\Entity\CartItem;
use MyApp\Entity\CartItemModel;
use MyApp\Entity\Rating;
use MyApp\Entity\RatingModel;


class DefaultController
{
    private $twig;
    private $typeModel;
    private $productModel;
    private $userModel;
    private $cartModel;
    private $cartItemModel;
    private $ratingModel;
  
    public function __construct(Environment $twig, DependencyContainer $dependencyContainer)
    {
        $this->twig = $twig;
        $this->typeModel = $dependencyContainer->get('TypeModel');
        $this->productModel = $dependencyContainer->get('ProductModel');
        $this->userModel = $dependencyContainer->get('UserModel');
        $this->cartModel = $dependencyContainer->get('CartModel');
        $this->cartItemModel = $dependencyContainer->get('CartItemModel');
        $this->ratingModel = $dependencyContainer->get('RatingModel');
    }

    public function home()
    {
        $types = $this->typeModel->getAllTypes();
        $products = $this->productModel->getAllProductsByStock();
        echo $this->twig->render('defaultController/home.html.twig', ['types'=>$types, 'products'=>$products]);
    }

    public function error404()
    {
        echo $this->twig->render('defaultController/error404.html.twig', []);
    }

    public function error500()
    {
        echo $this->twig->render('defaultController/error500.html.twig', []);
    }

    public function error403()
    {
        echo $this->twig->render('defaultController/error403.html.twig', []);
    }

    public function contact()
    {
        echo $this->twig->render('defaultController/contact.html.twig', []);
    }
    public function types()
    {
        $types = $this->typeModel->getAllTypes();
        echo $this->twig->render('defaultController/types.html.twig', ['types'=>$types]);
        //types est une clé, et il prend la valeur de la variable $types
    }

    public function products()
    {
        $products = $this->productModel->getAllProducts();
        echo $this->twig->render('defaultController/products.html.twig', ['products'=>$products]); 
    }

    public function updateType(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
            $label = filter_input(INPUT_POST, 'label', FILTER_SANITIZE_STRING);
            if (!empty($_POST['label'])) {
                $type = new Type(intVal($id), $label);
                $success = $this->typeModel->updateType($type);
                if ($success) {
                    header('Location: index.php?page=types');
                }
            }
        }
        else{
            $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        }
        $type = $this->typeModel->getOneType(intVal($id));
        echo $this->twig->render('defaultController/updateType.html.twig', ['type'=>$type]);
    }

    public function users()
    {
        $users = $this->userModel->getAllUsers();
        echo $this->twig->render('defaultController/users.html.twig', ['users'=>$users]); 
    }

    public function updateUser() 
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
            $lastName = filter_input(INPUT_POST, 'lastName', FILTER_SANITIZE_STRING);
            $firstName = filter_input(INPUT_POST, 'firstName', FILTER_SANITIZE_STRING);
            $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);
            $postalCode = filter_input(INPUT_POST, 'postalCode', FILTER_SANITIZE_STRING);
            $city = filter_input(INPUT_POST, 'city', FILTER_SANITIZE_STRING);
            $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);

            $password = $_POST['password'];
            $passwordLength = strlen($password);
            $containsDigit = preg_match('/\d/', $password);
            $containsUpper = preg_match('/[A-Z]/', $password);
            $containsLower = preg_match('/[a-z]/', $password);
            $containsSpecial = preg_match('/[^a-zA-Z\d]/', $password);


            if (!$lastName || !$firstName || !$email || !$password || !$address || !$postalCode || !$city || !$phone) {
                $_SESSION['message'] = 'Erreur : données invalides';
            } elseif ($passwordLength < 12 || !$containsDigit || !$containsUpper || !$containsLower || !$containsSpecial) {
                $_SESSION['message'] = 'Erreur : mot de passe non conforme';
            } else {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $user = new User(intVal($id), $email, $lastName, $firstName, $hashedPassword, ['user'], $address, $postalCode, $city, $phone);
                $success = $this->userModel->updateUser($user);
                if ($success) {
                    header('Location: index.php?page=users');
                }
                else {
                    $_SESSION['message'] = 'Erreur lors de la modification';
                }
            }
        }
        else{
            $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        }
            $user = $this->userModel->getOneUser(intVal($id));
            echo $this->twig->render('defaultController/updateUser.html.twig', ['user'=>$user]);
    }

    public function addType()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $label = filter_input(INPUT_POST, 'label', FILTER_SANITIZE_STRING);
            if (!empty($_POST['label'])) {
                $types = new Type(null, $label);
                $success = $this->typeModel->createType($types);
                if ($success) {
                    header('Location: index.php?page=types');
                }
            }
        }
        echo $this->twig->render('defaultController/addType.html.twig', []);
    }

    public function addUser()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
            $lastName = filter_input(INPUT_POST, 'lastName', FILTER_SANITIZE_STRING);
            $firstName = filter_input(INPUT_POST, 'firstName', FILTER_SANITIZE_STRING);
            $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);
            $postalCode = filter_input(INPUT_POST, 'postalCode', FILTER_SANITIZE_STRING);
            $city = filter_input(INPUT_POST, 'city', FILTER_SANITIZE_STRING);
            $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);

            $password = $_POST['password'];
            $passwordLength = strlen($password);
            $containsDigit = preg_match('/\d/', $password);
            $containsUpper = preg_match('/[A-Z]/', $password);
            $containsLower = preg_match('/[a-z]/', $password);
            $containsSpecial = preg_match('/[^a-zA-Z\d]/', $password);


            if (!$lastName || !$firstName || !$email || !$password || !$address || !$postalCode || !$city || !$phone) {
                $_SESSION['message'] = 'Erreur : données invalides';
            } elseif ($passwordLength < 12 || !$containsDigit || !$containsUpper || !$containsLower || !$containsSpecial) {
                $_SESSION['message'] = 'Erreur : mot de passe non conforme';
            } else {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $user = new User(null, $email, $lastName, $firstName, $hashedPassword, ['user'], $address, $postalCode, $city, $phone);
                $success = $this->userModel->createUser($user);
                if ($success) {
                    header('Location: index.php?page=users');
                }
                else {
                    $_SESSION['message'] = 'Erreur lors de l ajout';
                }
            }
        }
        echo $this->twig->render('defaultController/addUser.html.twig', []);
    }

    public function deleteType()
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $this->typeModel->deleteType(intVal($id));
        header('Location: index.php?page=types');
    }

    public function deleteUser()
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $this->userModel->deleteUser(intVal($id));
        header('Location: index.php?page=users');
    }

    public function deleteProduct()
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $this->productModel->deleteProduct(intVal($id));
        header('Location: index.php?page=products');
    }

    public function updateProduct() 
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
            $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
            $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
            $price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_NUMBER_FLOAT);
            $type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_NUMBER_INT);
            $stock = filter_input(INPUT_POST, 'stock', FILTER_SANITIZE_NUMBER_INT);
            $image = filter_input(INPUT_POST, 'image', FILTER_SANITIZE_STRING);
            if (!empty($_POST['name'])) {
                $other = new Type(intVal($type), "");
                $product = new Product(intVal($id), $name, $description, floatVal($price), $other, intVal($stock), $image);
                $success = $this->productModel->updateProduct($product);
                if ($success) {
                    header('Location: index.php?page=products');
                }
            }
        }
        else{
            $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        }
            $product = $this->productModel->getOneProduct(intVal($id));
            $types = $this->typeModel->getAllTypes();
            echo $this->twig->render('defaultController/updateProduct.html.twig', ['product'=>$product, 'types'=>$types]);
    }

    public function registration() 
    {
       if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
            $firstName = filter_input(INPUT_POST, 'firstName', FILTER_SANITIZE_STRING);
            $lastName = filter_input(INPUT_POST, 'lastName', FILTER_SANITIZE_STRING);
            $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);
            $postalCode = filter_input(INPUT_POST, 'postalCode', FILTER_SANITIZE_STRING);
            $city = filter_input(INPUT_POST, 'city', FILTER_SANITIZE_STRING);
            $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);
            
            $password = $_POST['password'];
            $passwordLength = strlen($password);
            $containsDigit = preg_match('/\d/', $password);
            $containsUpper = preg_match('/[A-Z]/', $password);
            $containsLower = preg_match('/[a-z]/', $password);
            $containsSpecial = preg_match('/[^a-zA-Z\d]/', $password);
        

            if (!$lastName || !$firstName || !$email || !$password || !$address || !$postalCode || !$city || !$phone) {
                $_SESSION['message'] = 'Erreur : données invalides';
            } elseif ($passwordLength < 12 || !$containsDigit || !$containsUpper || !$containsLower || !$containsSpecial) {
                $_SESSION['message'] = 'Erreur : mot de passe non conforme';
            } else {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $user = new User(null, $firstName, $lastName, $email, $hashedPassword, ['user'], $address, $postalCode, $city, $phone);
                $result = $this->userModel->createUser($user);
                if ($result) {
                    $_SESSION['message'] = 'Votre inscription est terminée';
                    header('Location: index.php?page=login');
                    exit;
                }
                else {
                    $_SESSION['message'] = 'Erreur lors de l inscription';
                }
            }
        header('Location: index.php?page=registration');
        exit;
        } 
    echo $this->twig->render('defaultController/registration.html.twig', []);
    }

    public function addProduct()
    {
        $types = $this->typeModel->getAllTypes();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
            $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
            $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
            $price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_NUMBER_FLOAT);
            $type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_NUMBER_INT);
            $stock = filter_input(INPUT_POST, 'stock', FILTER_SANITIZE_NUMBER_INT);
            $image = filter_input(INPUT_POST, 'image', FILTER_SANITIZE_STRING);

            if (!empty($name) && !empty($description) && !empty($price) && !empty($stock) && !empty($type) && !empty($image)) {
                $types = $this->typeModel->getOneType(intVal($type));
                if ($types == null) {
                    $_SESSION['message'] = 'Erreur sur le type.';
                } else {
                    $product = new Product(null, $name, $description, floatVal($price), $types, intVal($stock), $image);
                    $success = $this->productModel->createProduct($product);
                    if ($success) {
                        header('Location: index.php?page=products');
                    }
                }
            } else {
                $_SESSION['message'] = 'Veuillez saisir toutes les données.';
            
            }
        }
        echo $this->twig->render('defaultController/addProduct.html.twig', ['types'=>$types]);
    }

    public function carts()
    {
        $carts = $this->cartModel->getAllCarts();
        echo $this->twig->render('defaultController/carts.html.twig', ['carts'=>$carts]); 
    }

    public function addCart()
    {
        $users = $this->userModel->getAllUsers();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_Cart = filter_input(INPUT_POST, 'id_Cart', FILTER_SANITIZE_NUMBER_INT);
            $creationDate = date('Y-m-d');
            $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING);
            $id_User = filter_input(INPUT_POST, 'id_User', FILTER_SANITIZE_NUMBER_INT);

            if (!empty($creationDate) && !empty($status) && !empty($id_User)) {
                $users = $this->userModel->getUserById(intVal($id_User));
                if ($users == null) {
                    $_SESSION['message'] = 'Erreur.';
                } else {
                    $cart = new Cart(null, $creationDate, $status, $users);
                    $success = $this->cartModel->createCart($cart);
                    if ($success) {
                        header('Location: index.php?page=carts');
                    }
                }
            } else {
                $_SESSION['message'] = 'Veuillez saisir toutes les données.';
            
            }
        }
        echo $this->twig->render('defaultController/addCart.html.twig', ['users'=>$users]);
    }

    public function deleteCart()
    {
        $id_Cart = filter_input(INPUT_GET, 'id_Cart', FILTER_SANITIZE_NUMBER_INT);
        $this->cartModel->deleteCart(intVal($id_Cart));
        header('Location: index.php?page=carts');
    }

    public function updateCart() 
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_Cart = filter_input(INPUT_POST, 'id_Cart', FILTER_SANITIZE_NUMBER_INT);
            $creationDate = filter_input(INPUT_POST, 'creationDate', FILTER_SANITIZE_NUMBER_INT);
            $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING);
            $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

            if (!empty($_POST['status']) && !empty($_POST['id'])) {
                $user = $this->userModel->getUserById(intVal($id));
                $cart = new Cart(intVal($id_Cart), $creationDate, $status, $user);
                $success = $this->cartModel->updateCart($cart);
                if ($success) {
                    header('Location: index.php?page=carts');
                }
            }
        }
        else{
            $id_Cart = filter_input(INPUT_GET, 'id_Cart', FILTER_SANITIZE_NUMBER_INT);
        }
            $users = $this->userModel->getAllUsers();
            $cart = $this->cartModel->getOneCart(intVal($id_Cart));
            echo $this->twig->render('defaultController/updateCart.html.twig', ['users'=>$users, 'cart'=>$cart]);
    }

    public function productsList()
    {
        $id_Type = filter_input(INPUT_GET, 'id_Type', FILTER_SANITIZE_NUMBER_INT);
        $type = $this->typeModel->getOneType(intVal($id_Type));
        $products = $this->productModel->getAllProductsByTypeUser($type);
        if ($products == null){
            $_SESSION['message'] = 'Aucun produit de ce type.';
        }
        echo $this->twig->render('defaultController/productsList.html.twig', ['products'=>$products, 'type'=>$type]);
    }

    public function login(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
            $password = $_POST['password'];
            $user = $this->userModel->getUserByEmail($email);
            if(!$user){
                $_SESSION['message'] = 'Utilisateur ou mot de passe erroné';
                header('Location: index.php?page=login');
            }
            else{
                if ($user->verifyPassword($password)){
                    $_SESSION['id_User'] = $user->getIdUser();
                    $_SESSION['login'] = $user->getEmail();
                    $_SESSION['roles'] = $user->getRole();
                    header('Location: index.php');
                    exit;
                }
                else{
                    $_SESSION['message'] = 'Utilisateur ou mot de passe erroné';
                    header('Location: index.php?page=login');
                    exit;
                }
            }
        }
        echo $this->twig->render('defaultController/login.html.twig', []);
    }

    public function logout(){
        $_SESSION = array();
        session_destroy();
        header('Location: index.php?page=home');
        exit;
    }

    public function getAllItemsByCart() {
        $users = $this->userModel->getAllUsers();
        $products = $this->productModel->getAllProducts();
        $carts = $this->cartModel->getAllCarts();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $quantity = filter_input(INPUT_POST, 'quantity', FILTER_SANITIZE_NUMBER_INT);
            $unitPrice = filter_input(INPUT_POST, 'unitPrice', FILTER_SANITIZE_NUMBER_FLOAT);
            $id_Product = filter_input(INPUT_POST, 'id_Product', FILTER_SANITIZE_NUMBER_INT);
            $id_Cart = filter_input(INPUT_POST, 'id_Cart', FILTER_SANITIZE_NUMBER_INT);

            if (!empty($quantity) && !empty($id_Product) && !empty($id_Cart)) {
                $products = $this->productModel->getOneProduct(intVal($id_Product));
                $carts = $this->cartModel->getOneCart(intVal($id_Cart)); 
                if ($users == null) {
                    $_SESSION['message'] = 'Erreur.';
                } else {
                    $cart = new Cart(null, $creationDate, $status, $users);
                    $success = $this->cartModel->createCart($cart);
                    if ($success) {
                        header('Location: index.php?page=carts');
                    }
                }
            } else {
                $_SESSION['message'] = 'Veuillez saisir toutes les données.';
            
            }
        }
        echo $this->twig->render('defaultController/addItem.html.twig', ['users'=>$users]);
    }

    public function addRating()
    {
        if (!empty($_SESSION['roles'])){
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $stars = filter_input(INPUT_POST, 'stars', FILTER_SANITIZE_NUMBER_INT);
                if ($stars >= 1 && $stars <= 5) {
                    $comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_STRING);
                    $id_User =  $_SESSION['id_User'];
                    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
                    
                    if (!empty($_POST['stars']) && !empty($_POST['comment'])) {
                        $product = $this->productModel->getOneProduct(intVal($id));
                        $user = $this->userModel->getOneUser(intVal($id_User));
                        $rating = new Rating(null, intVal($stars), $comment, $user, $product);
                        $success = $this->ratingModel->createRating($rating);
                        if ($success) {
                            header('Location: index.php?page=home');
                        }
                    }
                }
                else{
                    $_SESSION['message'] = 'Le produit doit être noté entre 1 et 5.';
                }
            }
            else {
                $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
            }
                $product = $this->productModel->getOneProduct(intVal($id));
                echo $this->twig->render('defaultController/addRating.html.twig', ['product'=>$product]);
        }
        
        else {
            $_SESSION['message'] = 'Vous devez vous connecter avant de laisser un avis.';
            echo $this->twig->render('defaultController/login.html.twig', []);
        }
    }

    public function ratingsList()
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $ratings = $this->ratingModel->getRatingsByProduct(intVal($id));
        if ($ratings == null){
            $_SESSION['message'] = 'Aucun avis sur ce produit.';
        }
        echo $this->twig->render('defaultController/ratingsList.html.twig', ['ratings'=>$ratings]);
        

    }

    public function addItem()
    {
        $products = $this->productModel->getAllProductsByStock();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $quantity = filter_input(INPUT_POST, 'quantity', FILTER_SANITIZE_NUMBER_INT);
            $id_Product = filter_input(INPUT_POST, 'id_Product', FILTER_SANITIZE_NUMBER_INT);
            $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
            

            if (!empty($quantity) && !empty($id_Product) && !empty($id)) {
                $product = $this->productModel->getOneProduct(intVal($id_Product));
                $unitPrice = $product->getPrice();
                $cart = $this->cartModel->getOneCart(intVal($id));
                $cartItem = new CartItem(intVal($quantity), $unitPrice, $product, $cart);
                $success = $this->cartItemModel->createCartItem($cartItem);
                if ($success) {
                    header('Location: index.php?page=carts');
                }
            } else {
                $_SESSION['message'] = 'Veuillez saisir toutes les données.';
            
            }
        }
        echo $this->twig->render('defaultController/addItem.html.twig', ['products'=>$products]);
    }

    public function itemsList()
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $items = $this->cartItemModel->getAllItemsByCart(intVal($id));
        echo $this->twig->render('defaultController/itemsList.html.twig', ['items'=>$items]);
    }

    public function addCartItem()
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $product = $this->productModel->getOneProduct(intVal($id));
        $id_User =  $_SESSION['id_User'];
        $cart = $this->cartModel->getCartByUser($id_User);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $quantity = filter_input(INPUT_POST, 'quantity', FILTER_SANITIZE_NUMBER_INT);
            

            if (!empty($quantity)) {
                $unitPrice = $product->getPrice();
                $cartItem = new CartItem(intVal($quantity), $unitPrice, $product, $cart);
                $success = $this->cartItemModel->createCartItem($cartItem);
                if ($success) {
                    header('Location: index.php?page=home');
                }
            } else {
                $_SESSION['message'] = 'Veuillez saisir toutes les données.';
            
            }
        }
        echo $this->twig->render('defaultController/addCartItem.html.twig', ['product'=>$product]);
    }

    public function myCart()
    {
        $id_User =  $_SESSION['id_User'];
        $cart = $this->cartModel->getCartByUser($id_User);

        if (empty($cart)){
            $creationDate = date('Y-m-d');
            $status="en cours";
            $user = $this->userModel->getOneUser(intVal($id_User));
            $cart = new Cart(null, $creationDate, $status, $user);
        }

        $id_Cart = $cart->getId_Cart();
        $items = $this->cartItemModel->getAllItemsByCart(intVal($id_Cart));

        if (empty($items)){
            $_SESSION['message'] = 'Aucun article dans le panier.';
        }
        echo $this->twig->render('defaultController/myCart.html.twig', ['items'=>$items]);
    }

    public function deleteItem()
    {
        $id_Product = filter_input(INPUT_GET, 'id_Product', FILTER_SANITIZE_NUMBER_INT);
        $id_Cart = filter_input(INPUT_GET, 'id_Cart', FILTER_SANITIZE_NUMBER_INT);
        $this->cartItemModel->deleteCartItem(intVal($id_Product), intVal($id_Cart));
        header('Location: index.php?page=carts');
    }

    public function deleteCartItem()
    {
        $id_Product = filter_input(INPUT_GET, 'id_Product', FILTER_SANITIZE_NUMBER_INT);
        $id_Cart = filter_input(INPUT_GET, 'id_Cart', FILTER_SANITIZE_NUMBER_INT);
        $this->cartItemModel->deleteCartItem(intVal($id_Product), intVal($id_Cart));
        header('Location: index.php?page=myCart');
    }

    public function updateItem() 
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_Product = filter_input(INPUT_GET, 'id_Product', FILTER_SANITIZE_NUMBER_INT);
            $id_Cart = filter_input(INPUT_GET, 'id_Cart', FILTER_SANITIZE_NUMBER_INT);
            $quantity = filter_input(INPUT_POST, 'quantity', FILTER_SANITIZE_NUMBER_INT);

            if (!empty($_POST['quantity'])) {
                $cart = $this->cartModel->getOneCart(intVal($id_Cart));
                $product = $this->productModel->getOneProduct(intVal($id_Product));
                $unitPrice = $product->getPrice();
                $cartItem = new CartItem(intVal($quantity), $unitPrice, $product, $cart);
                $success = $this->cartItemModel->updateCartItem($cartItem);
                if ($success) {
                    header('Location: index.php?page=carts');
                }
            }
        }
        else{
            $id_Product = filter_input(INPUT_GET, 'id_Product', FILTER_SANITIZE_NUMBER_INT);
            $id_Cart = filter_input(INPUT_GET, 'id_Cart', FILTER_SANITIZE_NUMBER_INT);
        }
        $product = $this->productModel->getOneProduct(intVal($id_Product));
            echo $this->twig->render('defaultController/updateItem.html.twig', ['product'=>$product]);
    }

}

/*Client : modifier item | Admin : modifier cart --> problème | Commandes */