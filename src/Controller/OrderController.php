<?php
declare (strict_types = 1);
namespace MyApp\Controller;
use MyApp\Service\DependencyContainer;
use Twig\Environment;


class OrderController
{
    private $twig;
    private $typeModel;
    private $productModel;
    private $userModel;
  
    public function __construct(Environment $twig, DependencyContainer $dependencyContainer)
    {
        $this->twig = $twig;
    }

    public function orders()
    {
        echo $this->twig->render('orderController/orders.html.twig', []);
    }
}

