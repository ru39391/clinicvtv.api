<?php

namespace Zoomx\Controllers\Api\Testimonial;
use Zoomx\Controllers\Common\Constants;
use Zoomx\Controllers\Common\RequestParamsTrait;

use Zoomx\Controllers\Common\GetController as CommonGetController;

class GetController extends CommonGetController
{
  use RequestParamsTrait;

  public function index($id = null)
  {
    $class = \testimonialItem::class;
    $this->modx->loadClass($class, $this->modx->getOption('core_path') . 'components/testimonial/model/testimonial/');

    $is_hidden = $this->getParam(Constants::IS_HIDDEN_KEY);
    $spec_ids = $this->getParam('spec_ids', null);
    $where = [
      Constants::SPEC_ID_KEY => $spec_ids === null ? (int)($this->getParam(Constants::SPEC_ID_KEY, 0)) : 0,
      Constants::RATING_KEY => (int)($this->getParam(Constants::RATING_KEY, 0))
    ];
    $arr = $spec_ids !== null ? ['spec_id:IN' => explode(',', $spec_ids)] : [];

    return $this->getItems(
      $class,
      $id,
      array_merge(
        $is_hidden === 'all'
          ? []
          : [Constants::IS_HIDDEN_KEY => (int)($this->getParam(Constants::IS_HIDDEN_KEY, 0))],
        array_filter($where, fn($item) => $item > 0),
        $arr
      )
    );
  }
}
