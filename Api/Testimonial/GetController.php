<?php

namespace Zoomx\Controllers\Api\Testimonial;

use Zoomx\Controllers\Common\GetController as CommonGetController;

class GetController extends CommonGetController
{
  public function index($id = null)
  {
    $class = \testimonialItem::class;
    $this->modx->loadClass($class, $this->modx->getOption('core_path') . 'components/testimonial/model/testimonial/');

    return $this->getItems($class, $id);
  }
}
