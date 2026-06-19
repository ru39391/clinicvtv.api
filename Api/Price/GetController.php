<?php

namespace Zoomx\Controllers\Api\Price;

use Zoomx\Controllers\Common\GetController as CommonGetController;
use Zoomx\Controllers\Common\RequestParamsTrait;

class GetController extends CommonGetController
{
  use RequestParamsTrait;

  public function index($id = null)
  {
    $class = \pricelistItem::class;
    $this->modx->loadClass($class, $this->modx->getOption('core_path') . 'components/pricelist/model/pricelist/');

    $where = [
      'dept_id' => (int)($this->getParam('dept_id', 0))
    ];

    return $this->getItems(
      $class,
      $id,
      array_merge(
        ['is_hidden' => (int)($this->getParam('is_hidden', 0))],
        array_filter($where, fn($item) => $item !== 0)
      )
    );
  }
}
