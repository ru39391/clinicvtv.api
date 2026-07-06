<?php
namespace Zoomx\Controllers\Common;
use Zoomx\Controllers\Common\Constants;

trait RequestParamsTrait
{
  protected function getParam(string $key, $default = null)
  {
    return isset($_GET[$key]) ? $_GET[$key] : $default;
  }

  protected function getPaginationParams(): array
  {
    return [
      'all' => (int)($this->getParam('all', 0)),
      'page' => (int)($this->getParam('page', 1)),
      'perPage' => (int)($this->getParam('perPage', 10)),
      'sortby' => $this->getParam('sortby', Constants::CREATEDON_KEY),
      'sortdir' => $this->getParam('sortdir', 'DESC'),
      'search' => $this->getParam('search', null),
    ];
  }

  protected function getValidPaginationParams(): array
  {
    $params = $this->getPaginationParams();
    $params['page'] = $params['all'] === 1 ? 1 : max(1, $params['page']);
    $params['perPage'] = max(1, min(100, $params['perPage']));

    return $params;
  }
}
