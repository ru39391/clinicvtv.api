<?php

namespace Zoomx\Controllers\Api\Team;

use Zoomx\Controllers\Common\GetController as CommonGetController;

class GetController extends CommonGetController
{
  protected function setDeptNames($arr): string
  {
    $res = [];

    foreach($arr as $id) {
      $data = $this->modx->getObject(\modResource::class, $id);
      $props = $data->get('properties')[1]['tvs'];

      $res[] = $this->modx->runSnippet('handleCategoryData', [
        'input' => $props['tv_team_category'],
        'index' => 1,
      ]);
    }

    return implode(', ', array_map(fn($item) => mb_strtolower($item), $res));
  }

  protected function handleProps($props): array
  {
    $output = [
      'depts_id' => []
    ];

    if (!is_array($props) || empty($props)) {
      return $output;
    }

    if (isset($props[1]['tvs']) && is_array($props[1]['tvs'])) {
      $tvs = $props[1]['tvs'];
      $pics = isset($tvs['tv_img']) ? $tvs['tv_img'] : $this->modx->getOption('default_team_nophoto');

      $output['pics'] = [
        'webp' => $this->modx->runSnippet('pthumb', [
          'input' => $pics,
          'options' => 'q=100&h=470&f=webp'
        ]),
        'thumb' => $this->modx->runSnippet('pthumb', [
          'input' => $pics,
          'options' => 'q=100&h=470'
        ]),
      ];

      if (isset($tvs['tv_dept'])) {
        $depts = $this->modx->runSnippet('handleCategoryData', [
          'input' => $tvs['tv_dept'],
          'arr' => 1,
          'delimiter' => ','
        ]);

        foreach($depts as $depts_id) {
          $output['depts_id'][] = (int)$depts_id;
        }
      }
    }

    return array_merge(
      $output,
      [
        'depts' => $this->setDeptNames($output['depts_id'])
      ]
    );
  }

  public function index($dept_id = null)
  {
    $output = [];
    $class = \modResource::class;
    $params = array(
      'parent' => 8,
      'deleted' => 0,
      'published' => 1,
    );
    $query = $this->modx->newQuery($class, $params);
    $query->sortby('menuindex', 'DESC');
    $resources = $this->modx->getCollection($class, $query);

    foreach($resources as $item) {
      $data = $item->toArray();
      $output[] = array_merge(
        $this->handleProps($data['properties']),
        [
          'id' => $data['id'],
          'pagetitle' => $data['pagetitle'],
          'introtext' => $data['introtext'],
          'menuindex' => $data['menuindex'],
          'url' => $data['uri']
        ]
      );
    }

    if ($dept_id !== null) {
      $output = array_filter($output, fn($item) => isset($item['depts_id']) && in_array($dept_id, $item['depts_id']));
      $output = array_values($output);
    }

    ['headers' => $headers, 'code' => $code] = $this->setResponseHeaders();

    return jsonx($output, $headers, $code);
  }
}
