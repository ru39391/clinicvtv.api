<?php

namespace Zoomx\Controllers\Api\Example;

use Zoomx\Controllers\Common\GetController as CommonGetController;

class GetController extends CommonGetController
{
  public function index($id = null)
  {
    $class = \exampleItem::class;
    $this->modx->loadClass($class, $this->modx->getOption('core_path') . 'components/example/model/example/');

    return $this->getItems($class, $id);
  }
}
