<?php

namespace Zoomx\Controllers\Api\Price;

use Zoomx\Controllers\Common\GetController as CommonGetController;

class GetController extends CommonGetController
{
  public function index($id = null)
  {
    $class = \pricelistItem::class;
    $this->modx->loadClass($class, $this->modx->getOption('core_path') . 'components/pricelist/model/pricelist/');

    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $perPage = isset($_GET['perPage']) ? (int)$_GET['perPage'] : 6;
    $sortby = isset($_GET['sortby']) ? $_GET['sortby'] : 'id';
    $sortdir = isset($_GET['sortdir']) ? $_GET['sortdir'] : 'DESC';
    $search = isset($_GET['search']) ? $_GET['search'] : null;

    return $this->getItems($class, $id, $page, $perPage, $sortby, $sortdir, $search);
  }
}
