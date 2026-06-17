<?php

namespace Zoomx\Controllers\Api\Testimonial;

use Zoomx\Controllers\Common\DeleteController as CommonDeleteController;

class DeleteController extends CommonDeleteController
{
  public function index($id)
  {
    $class = \testimonialItem::class;
    $this->modx->loadClass($class, $this->modx->getOption('core_path') . 'components/testimonial/model/testimonial/');

    return $this->deleteData($class, $id);
  }
}
