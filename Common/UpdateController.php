<?php

namespace Zoomx\Controllers\Common;

use Zoomx\Controllers\Common\Constants;
use Zoomx\Controllers\Common\CommonController;
use Zoomx\Controllers\Common\CommonTrait;

class UpdateController extends CommonController
{
  use CommonTrait;

  private function updateItem($data, $item)
  {
    $output = [];

    foreach (array_filter(array_keys($data), fn($key) => $key !== Constants::ID_KEY) as $key) {
      $item->set($key, $data[$key]);
    }
    $item->set(Constants::UPDATEDON_KEY, date('Y-m-d H:i:s'));

    if ($item->save()) {
      $output = $item->toArray();
    } else {
      $output = [
        'id' => null,
        'error_code' => $this->modx->errorCode(),
        'success' => false
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

    return $this->updateItem($data, $item);
  }

  protected function updateData($class, $id)
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
