<?php

namespace Zoomx\Controllers\Api\Example;

use Zoomx\Controllers\Common\GetController as CommonGetController;
use Zoomx\Controllers\Common\RequestParamsTrait;

class GetController extends CommonGetController
{
  use RequestParamsTrait;

  public function index($id = null)
  {
    $class = \exampleItem::class;
    $this->modx->loadClass($class, $this->modx->getOption('core_path') . 'components/example/model/example/');

    $where = [
      'spec_id' => (int)($this->getParam('spec_id', 0)),
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
