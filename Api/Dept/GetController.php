<?php

namespace Zoomx\Controllers\Api\Dept;

use Zoomx\Controllers\Common\GetController as CommonGetController;

class GetController extends CommonGetController
{
  public function index()
  {
    $output = [];
    $class = \modResource::class;
    $params = array(
      'parent' => 7,
      'deleted' => 0,
      'published' => 1,
    );
    $query = $this->modx->newQuery($class, $params);
    $query->sortby('menuindex', 'DESC');
    $resources = $this->modx->getCollection($class, $query);

    foreach($resources as $item) {
      $data = $item->toArray();
      $output[] = [
        'id' => $data['id'],
        'pagetitle' => $data['pagetitle'],
        'desc' => $data['description'],
        'menuindex' => $data['menuindex'],
        'url' => $data['uri']
      ];
    }

    ['headers' => $headers, 'code' => $code] = $this->setResponseHeaders();

    return jsonx($output, $headers, $code);
  }
}
