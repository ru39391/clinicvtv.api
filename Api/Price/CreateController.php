<?php

namespace Zoomx\Controllers\Api\Price;

use Zoomx\Controllers\Common\CreateController as CommonCreateController;

class CreateController extends CommonCreateController
{
  public function index()
  {
    $class = \pricelistItem::class;
    $this->modx->loadClass($class, $this->modx->getOption('core_path') . 'components/pricelist/model/pricelist/');

    return $this->createData($class);
  }
}
