<?php

namespace Zoomx\Controllers\Api\Position;

use Zoomx\Controllers\Common\UpdateController as CommonUpdateController;

class UpdateController extends CommonUpdateController
{
  public function index($id)
  {
    $class = \exampleItem::class;
    $this->modx->loadClass($class, $this->modx->getOption('core_path') . 'components/example/model/example/');

    return $this->updateData($class, $id);
  }
}
