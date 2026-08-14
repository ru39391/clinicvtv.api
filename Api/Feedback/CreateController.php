<?php

namespace Zoomx\Controllers\Api\Feedback;

use Zoomx\Controllers\Common\Constants;
use Zoomx\Controllers\Common\CommonController;
use Zoomx\Controllers\Common\CommonTrait;

class CreateController extends CommonController
{
  use CommonTrait;

  private function getIpAddress()
  {
    if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
      return $_SERVER['HTTP_CF_CONNECTING_IP'];
    }

    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
      $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);

      return trim($ips[0]);
    }

    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
      return $_SERVER['HTTP_CLIENT_IP'];
    }

    if (!empty($_SERVER['REMOTE_ADDR'])) {
      return $_SERVER['REMOTE_ADDR'];
    }

    return '0.0.0.0';
  }

  private function createItem($data, $class)
  {
    $output = [];

    $response = $this->modx->newObject(
      $class,
      [
        'form' => 'form-' . $data['pageId'],
        'context_key' => 'web',
        'values' => json_encode($data),
        'ip' => $this->getIpAddress(),
        'date' => time(),
        'encrypted' => 0,
        'encryption_type' => 1,
        'hash' => bin2hex(random_bytes(16))
      ]
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

    return $output;
  }

  public function handleItem($data, $class)
  {
    return $this->createItem($data, $class);
  }

  protected function loadFormItForm()
  {
    $modelPath = $this->modx->getOption('core_path') . 'components/formit/src/FormIt/Model/';

    $this->modx->addPackage('FormIt', $modelPath);

    if (class_exists('FormItForm')) {
      return 'FormItForm';
    }

    if (class_exists('Sterc\\FormIt\\Model\\FormItForm')) {
      return 'Sterc\\FormIt\\Model\\FormItForm';
    }

    if ($this->modx->loadClass('FormItForm', $modelPath, false, true)) {
      return 'FormItForm';
    }

    return null;
  }

  public function index()
  {
    $class = $this->loadFormItForm();

    if($class === null) {
      return $this->setResponseData(['success' => false, 'class' => $class]);
    }

    return $this->handleData($class, Constants::CREATEDON_KEY);
  }
}
