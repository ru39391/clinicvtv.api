<?php

namespace Zoomx\Controllers\Api\Price;

use Zoomx\Controllers\Common\GetController as CommonGetController;

class GetController extends CommonGetController
{
  public function index($id = null)
  {
    $class = \pricelistItem::class;
    $this->modx->loadClass($class, $this->modx->getOption('core_path') . 'components/pricelist/model/pricelist/');

    return $this->getItems($class, $id);
  }
}
