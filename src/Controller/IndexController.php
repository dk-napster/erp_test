<?php
// src/Controller/IndexController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\ProductService;


class IndexController extends AbstractController
{

    public function index(ProductService $productService)
    {
        return $this->render('index/index.html.twig', array('data' => $productService->getData()));
    }


}