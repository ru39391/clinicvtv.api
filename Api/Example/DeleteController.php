<?php

namespace Zoomx\Controllers\Api\Example;

use Zoomx\Controllers\Common\DeleteController as CommonDeleteController;

class DeleteController extends CommonDeleteController
{
  public function index($id)
  {
    $class = \exampleItem::class;
    $this->modx->loadClass($class, $this->modx->getOption('core_path') . 'components/example/model/example/');

    return $this->deleteData($class, $id);
  }
}
