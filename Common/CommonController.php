<?php

namespace Zoomx\Controllers\Common;

use Zoomx\Controllers\BaseController;
use Zoomx\Controllers\Common\Constants;
use Zoomx\Controllers\Common\CommonTrait;

class CommonController extends BaseController
{
  use CommonTrait;

  protected function handleData($class, $dateKey, $id = null)
  {
    $output = $this->handleItem($this->getInputData(), $class, $id);

    if($output['id'] !== null) {
      $output['success'] = true;
    } else {
      $output['message'] = Constants::COMMON_ERROR;
    }

    return $this->setResponseData(
      $this->formatData($output)
    );
  }
}
