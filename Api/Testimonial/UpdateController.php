<?php

namespace Zoomx\Controllers\Api\Testimonial;

use Zoomx\Controllers\Common\UpdateController as CommonUpdateController;

class UpdateController extends CommonUpdateController
{
  public function index($id)
  {
    $class = \testimonialItem::class;
    $this->modx->loadClass($class, $this->modx->getOption('core_path') . 'components/testimonial/model/testimonial/');

    return $this->updateData($class, $id);
  }
}
