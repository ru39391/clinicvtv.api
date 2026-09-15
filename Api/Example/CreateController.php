<?php

namespace Zoomx\Controllers\Api\Example;

use Zoomx\Controllers\Common\CreateController as CommonCreateController;

class CreateController extends CommonCreateController
{
  public function index()
  {
    $class = \exampleItem::class;
    $this->modx->loadClass($class, $this->modx->getOption('core_path') . 'components/example/model/example/');

    return $this->createData($class);
  }
}
