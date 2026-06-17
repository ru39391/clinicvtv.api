<?php

namespace Zoomx\Controllers\Api\Testimonial;

use Zoomx\Controllers\Common\CreateController as CommonCreateController;

class CreateController extends CommonCreateController
{
  public function index()
  {
    $class = \testimonialItem::class;
    $this->modx->loadClass($class, $this->modx->getOption('core_path') . 'components/testimonial/model/testimonial/');

    return $this->createData($class);
  }
}
