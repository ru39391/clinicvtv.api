<?php

namespace Zoomx\Controllers\Common;

use Zoomx\Controllers\Common\Constants;
use Zoomx\Controllers\Common\CommonController;
use Zoomx\Controllers\Common\CommonTrait;

class CreateController extends CommonController
{
  use CommonTrait;

  private function createItem($data, $class)
  {
    $output = [];

    $response = $this->modx->newObject(
      $class,
      array_merge($data, [Constants::CREATEDON_KEY => date('Y-m-d H:i:s'), Constants::UPDATEDON_KEY => date('Y-m-d H:i:s')])
    );

    if ($response->save()) {
      $output = $response->toArray();
    } else {
      $output = [
        'id' => null,
        'error_code' => $this->modx->errorCode(),
        'success' => false
      ];
    }
    $this->modx->cacheManager->clearCache();
    if ($class === \testimonialItem::class && !$this->modx->user->isMember('Administrator')) {
      $this->modx->runSnippet('sendTestimonialData', $output);
    }

    return $output;
  }

  public function handleItem($data, $class)
  {
    return $this->createItem($data, $class);
  }

  protected function createData($class)
  {
    return $this->handleData($class, Constants::CREATEDON_KEY);
  }
}
