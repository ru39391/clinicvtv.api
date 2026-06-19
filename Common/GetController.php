<?php

namespace Zoomx\Controllers\Common;

use Zoomx\Controllers\BaseController;
use Zoomx\Controllers\Common\CommonTrait;
use Zoomx\Controllers\Common\RequestParamsTrait;
use Zoomx\Controllers\Common\Constants;

class GetController extends BaseController
{
  use CommonTrait, RequestParamsTrait;

  protected function getItems($class, $id = null, $where = [])
  {
    $output = [];
    $params = $this->getValidPaginationParams();

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
      $all = $params['all'];
      $page = $params['page'];
      $perPage = $params['perPage'];
      $totalQuery = $this->modx->newQuery($class);

      if(count($where) > 0) {
        $totalQuery->where($where);
      }

      if (!empty($params['search'])) {
        $search = trim($params['search']);
        $totalQuery->where([
          'name:LIKE' => '%' . $search . '%'
        ]);
      }

      $totalCount = $this->modx->getCount($class, $totalQuery);

      if($all === 1) $perPage = $totalCount;

      $totalQuery->sortby($params['sortby'], $params['sortdir']);
      $totalQuery->limit($perPage, ($page - 1) * $perPage);
      $data = $this->modx->getCollection($class, $totalQuery);
      $items = [];

      foreach($data as $item) {
        $items[] = $this->formatData($item->toArray());
      }

      $totalPages = ceil($totalCount / ($perPage ?: 1));

      $output = [
        'data' => [
          'page' => $page,
          'perPage' => $perPage,
          'totalCount' => $totalCount,
          'totalPages' => $totalPages,
          'data' => $items,
          'sortby' => $params['sortby'],
          'sortdir' => $params['sortdir'],
          'search' => $search ?: null
        ]
      ];
    }

    ['headers' => $headers, 'code' => $code] = $this->setResponseHeaders();

    return jsonx($output, $headers, $code);
  }
}
