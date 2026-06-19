<?php

namespace Zoomx\Controllers\Api\Testimonial;
use Zoomx\Controllers\Common\RequestParamsTrait;

use Zoomx\Controllers\Common\GetController as CommonGetController;

class GetController extends CommonGetController
{
  use RequestParamsTrait;

  public function index($id = null)
  {
    $class = \testimonialItem::class;
    $this->modx->loadClass($class, $this->modx->getOption('core_path') . 'components/testimonial/model/testimonial/');

    $where = [
      'spec_id' => (int)($this->getParam('spec_id', 0)),
      'rating' => (int)($this->getParam('rating', 0))
    ];

    return $this->getItems(
      $class,
      $id,
      array_merge(
        ['is_hidden' => (int)($this->getParam('is_hidden', 0))],
        array_filter($where, fn($item) => $item > 0)
      )
    );
  }
}
