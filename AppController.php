<?php

namespace Zoomx\Controllers;

class AppController extends BaseController
{
  public function index()
  {
    if ($this->modx->user->isMember('Administrator')) {
      $data = zoomx()->getResource(74);

      return viewx('app.tpl', $data->toArray());
    } else {
      return redirectx(zoomx()->getResource(5)->alias, 301);
    }
  }
}
