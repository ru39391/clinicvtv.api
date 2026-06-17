<?php
/** @var FastRoute\RouteCollector  $router */
/** @var modX  $modx */

$router->get('api/team', Zoomx\Controllers\Api\Team\GetController::class);

$routes = [
  'exaples' => 'Example',
  'pricelist' => 'Price',
  'testimonials' => 'Testimonial',
];

$methods = [
  'get' => 'GetController',
  'post' => 'CreateController',
  'patch' => 'UpdateController',
  'delete' => 'DeleteController'
];

foreach ($routes as $key => $value) {
  foreach ($methods as $method => $controller) {
    $router->{$method}("api/{$key}", "Zoomx\\Controllers\\Api\\$value\\$controller");
  }
}
