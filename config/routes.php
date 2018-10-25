<?php
// config/routes.php
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;
use App\Controller\ApiController;
use App\Controller\IndexController;

$routes = new RouteCollection();

$routes->add('index', new Route('/', array(
    '_controller' => [IndexController::class, 'index']
)));

$routes->add('api_add_product', new Route('/api/add',
    array(
        '_controller' => [ApiController::class, 'add'],
    ),
    array(), array(), '', array(), array('POST')));

$routes->add('api_get_product', new Route('/api', array(
    '_controller' => [ApiController::class, 'index']
)));

return $routes;