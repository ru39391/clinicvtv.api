<?php

namespace Zoomx\Controllers\Common;

use Zoomx\Controllers\Common\Constants;

trait CommonTrait
{
  public function getInputData()
  {
    return json_decode(file_get_contents('php://input'), true);
  }

  public function setResponseHeaders()
  {
    $headers = [];
    $origin = $_SERVER['HTTP_ORIGIN'] ?? '';

    if (in_array($origin, Constants::ALLOWED_ORIGINS)) {
      $headers = [
        'Access-Control-Allow-Origin' => $origin,
        'Access-Control-Allow-Credentials' => true,
        'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS',
        'Access-Control-Allow-Headers' => 'Content-Type, Authorization, X-Requested-With',
        'Access-Control-Max-Age' => 8640
      ];
    }

    return ['headers' => $headers, 'code' => $_SERVER['REQUEST_METHOD'] === 'OPTIONS' ? 204 : 200];
  }

  public function setResponseData($output)
  {
    ['headers' => $headers, 'code' => $code] = $this->setResponseHeaders();

    return jsonx($output, $headers, $output['success'] === true ? $code : 401);
  }

  public function formatTimestamp($value = 0)
  {
    if($value === 0) {
      return $value;
    }

    $date = new \DateTime();
    $date->setTimestamp($value);
    $date->setTimezone(new \DateTimeZone('UTC'));

    return $date->format('d-m-Y H:i:s');
  }

  public function formatDate($date)
  {
    $value = $date === null ? new \DateTime() : new \DateTime($date);
    $value->setTimezone(new \DateTimeZone('UTC'));

    if($date === null) {
      $value->setTime((int)$value->format('H'), 0, 0);
    }

    return $value->format('Y-m-d\TH:i:00\Z');
  }

  public function formatData($data)
  {
    $updatedon = $data[Constants::UPDATEDON_KEY];

    if (isset($data[Constants::IS_ACTIVE_KEY])) {
      $data[Constants::IS_ACTIVE_KEY] = (bool)$data[Constants::IS_ACTIVE_KEY];
    }

    if (isset($data[Constants::CREATEDON_KEY])) {
      $data[Constants::CREATEDON_KEY] = $this->formatDate($data[Constants::CREATEDON_KEY]);
    }

    if (isset($data[Constants::UPDATEDON_KEY])) {
      $data[Constants::UPDATEDON_KEY] = $updatedon === null ? $updatedon : $this->formatDate($updatedon);
    }

    if (isset($data['credentials']) && is_string($data['credentials'])) {
        $decoded = json_decode($data['credentials'], true);

        if (json_last_error() === JSON_ERROR_NONE) {
            $data['credentials'] = $decoded;
        }
    }

    return $data;
  }
}
