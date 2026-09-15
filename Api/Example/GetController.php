<?php

namespace Zoomx\Controllers\Api\Example;

use Zoomx\Controllers\Common\GetController as CommonGetController;
use Zoomx\Controllers\Common\Constants;
use Zoomx\Controllers\Common\RequestParamsTrait;

class GetController extends CommonGetController
{
  use RequestParamsTrait;

  public function index($id = null)
  {
    $class = \exampleItem::class;
    $this->modx->loadClass($class, $this->modx->getOption('core_path') . 'components/example/model/example/');

    $is_hidden = $this->getParam(Constants::IS_HIDDEN_KEY);
    $where = [
      Constants::SPEC_ID_KEY => (int)($this->getParam(Constants::SPEC_ID_KEY, 0)),
      Constants::DEPT_ID_KEY => (int)($this->getParam(Constants::DEPT_ID_KEY, 0))
    ];

    return $this->getItems(
      $class,
      $id,
      array_merge(
        $is_hidden === 'all'
          ? []
          : [Constants::IS_HIDDEN_KEY => (int)($this->getParam(Constants::IS_HIDDEN_KEY, 0))],
        array_filter($where, fn($item) => $item !== 0)
      )
    );
  }
}
