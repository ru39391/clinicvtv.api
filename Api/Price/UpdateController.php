<?php

namespace Zoomx\Controllers\Api\Price;

use Zoomx\Controllers\Common\UpdateController as CommonUpdateController;

class UpdateController extends CommonUpdateController
{
  public function index($id)
  {
    $class = \pricelistItem::class;
    $this->modx->loadClass($class, $this->modx->getOption('core_path') . 'components/pricelist/model/pricelist/');

    return $this->updateData($class, $id);
  }
}
