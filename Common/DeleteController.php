<?php

namespace Zoomx\Controllers\Common;

use Zoomx\Controllers\Common\Constants;
use Zoomx\Controllers\Common\CommonController;
use Zoomx\Controllers\Common\CommonTrait;

class DeleteController extends CommonController
{
  use CommonTrait;

  private function deleteItem($item, $id)
  {
    $output = [];

    if($item->remove()) {
      $output = [
        'id' => (int)$id,
        'success' => true,
      ];
    } else {
      $output = [
        'id' => null,
        'error_code' => $this->modx->errorCode(),
        'success' => false,
      ];
    }

    $this->modx->cacheManager->clearCache();

    return $output;
  }

  public function handleItem($data, $class, $id)
  {
    $item = $id === null ? null : $this->modx->getObject($class, array('id' => $id));

    if(!$item) {
      return $this->setResponseData([
        'success' => false,
        'message' => Constants::COMMON_ERROR
      ]);
    }

    return $this->deleteItem($item, $id);
  }

  protected function deleteData($class, $id)
  {
    if(!$id) {
      return $this->setResponseData([
        'success' => false,
        'message' => Constants::COMMON_ERROR
      ]);
    }

    return $this->handleData($class, Constants::UPDATEDON_KEY, $id);
  }
}
