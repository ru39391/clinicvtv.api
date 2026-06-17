<?php

namespace Zoomx\Controllers\Common;

use Zoomx\Controllers\BaseController;
use Zoomx\Controllers\Common\CommonTrait;
use Zoomx\Controllers\Common\Constants;

class GetController extends BaseController
{
  use CommonTrait;

  protected function getItems($class, $id = null, $page = 1, $perPage = 6, $sortby = 'id', $sortdir = 'DESC', $search = null)
  {
    $output = [];

    if ($id) {
      $data = $this->modx->getObject($class, array('id' => $id));

      if(!$data) {
        return $this->setResponseData([
          'success' => false,
          'message' => Constants::COMMON_ERROR
        ]);
      }

      $output = $this->formatData($data->toArray());
    } else {
      $page = max(1, (int)$page);
      $perPage = max(1, min(100, (int)$perPage));
      $totalQuery = $this->modx->newQuery($class);

      if (!empty($search)) {
        $search = trim($search);
        $totalQuery->where([
          'name:LIKE' => '%' . $search . '%'
        ]);
      }

      $totalCount = $this->modx->getCount($class, $totalQuery);
      $totalQuery->sortby($sortby, $sortdir);
      $totalQuery->limit($perPage, ($page - 1) * $perPage);
      $data = $this->modx->getCollection($class, $totalQuery);

      $items = [];
      foreach($data as $item) {
        $items[] = $this->formatData($item->toArray());
      }

      $totalPages = ceil($totalCount / $perPage);

      $output = [
        'data' => [
          'page' => $page,
          'perPage' => $perPage,
          'totalCount' => $totalCount,
          'totalPages' => $totalPages,
          'data' => $items,
          'sortby' => $sortby,
          'sortdir' => $sortdir,
          'search' => $search ?: null
        ]
      ];
    }

    ['headers' => $headers, 'code' => $code] = $this->setResponseHeaders();

    return jsonx($output, $headers, $code);
  }
}
