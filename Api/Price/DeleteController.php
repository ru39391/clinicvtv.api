<?php

namespace Zoomx\Controllers\Api\Price;

use Zoomx\Controllers\Common\DeleteController as CommonDeleteController;

class DeleteController extends CommonDeleteController
{
  public function index($id)
  {
    $class = \pricelistItem::class;
    $this->modx->loadClass($class, $this->modx->getOption('core_path') . 'components/pricelist/model/pricelist/');

    return $this->deleteData($class, $id);
  }
}
