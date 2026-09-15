<?php
/** @var FastRoute\RouteCollector  $router */
/** @var modX  $modx */

$router->get('api/team', Zoomx\Controllers\Api\Team\GetController::class);
$router->get('api/team/{dept_id}', Zoomx\Controllers\Api\Team\GetController::class);
$router->get('api/depts', Zoomx\Controllers\Api\Dept\GetController::class);
$router->get('api/pictures', Zoomx\Controllers\Api\Picture\GetController::class);

$router->post('api/feedback', Zoomx\Controllers\Api\Feedback\CreateController::class);

$routes = [
  'examples' => 'Example',
  'pricelist' => 'Price',
  'testimonials' => 'Testimonial',
];

$methods = [
  'get' => 'GetController',
  'post' => 'CreateController',
];

$ext_methods = [
  'patch' => 'UpdateController',
  'delete' => 'DeleteController'
];

foreach ($routes as $key => $value) {
  foreach ($methods as $method => $controller) {
    $router->{$method}("api/{$key}", "Zoomx\\Controllers\\Api\\$value\\$controller");
  }

  foreach ($ext_methods as $method => $controller) {
    $router->{$method}("api/{$key}/{id}", "Zoomx\\Controllers\\Api\\$value\\$controller");
  }
}
