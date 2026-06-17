<?php
namespace Zoomx\Controllers;

use modX;

abstract class BaseController
{
  protected $modx;

  private function disableAutoloadRes()
  {
    zoomx()->autoloadResource(false);
  }

  public function __construct(modX $modx)
  {
    $this->modx = $modx;
    $this->disableAutoloadRes();
  }
}
