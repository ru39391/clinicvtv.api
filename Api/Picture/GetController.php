<?php
namespace Zoomx\Controllers\Api\Picture;

use Zoomx\Controllers\Common\GetController as CommonGetController;
use Zoomx\Controllers\Common\CommonTrait;
use Zoomx\Controllers\Common\Constants;
use Zoomx\Controllers\Common\RequestParamsTrait;

class GetController extends CommonGetController
{
  use CommonTrait, RequestParamsTrait;

  public function index()
  {
    $all = (int)($this->getParam('all', 0));
    $thumbs = (int)($this->getParam('thumbs', 0));
    $cache = (int)($this->getParam('cache', 3600));
    $dir = $this->getParam('dir', $this->modx->getOption('default_exmpl_pics'));
    $path = $this->modx->getOption('assets_path') . $dir;
    $exts = $this->getParam('exts', 'jpg,jpeg,png');
    $sortby = $this->getParam('sortby', 'date');
    [
      'page' => $page,
      'perPage' => $perPage,
      'search' => $search,
      'sortdir' => $sortdir
    ] = $this->getValidPaginationParams();

    $output = [];
    $cacheKey = 'images_' . md5($path . $exts . $sortby . $sortdir . $page . $perPage . $all. $search);
    $cached = $this->modx->cacheManager->get($cacheKey);

    if ($cached !== null) {
      $output = $cached;
    } else {
      $result = [];
      $allowedExtensions = array_map('trim', explode(',', $exts));
      $allowedExtensions = array_map('strtolower', $allowedExtensions);
      $extPattern = '/\.(' . implode('|', $allowedExtensions) . ')$/i';
      $searchLower = strtolower(trim($search ?: ''));

      foreach (new \DirectoryIterator($path) as $file) {
        $name = $file->getFilename();

        if ($file->isFile() && preg_match($extPattern, $name)) {
          if (!empty($searchLower) && strpos(strtolower($name), $searchLower) === false) {
            continue;
          }

          $date = $file->getMTime();
          $timestamp = $this->formatTimestamp($date);

          $result[] = [
            Constants::NAME_KEY => $name,
            'url' => '/assets/' . $dir . '/' . $name,
            'size' => $file->getSize(),
            'size_formatted' => $file->getSize() > 1048576
              ? round($file->getSize() / 1048576, 2) . ' MB'
              : round($file->getSize() / 1024, 2) . ' KB',
            'date' => $date,
            Constants::UPDATEDON_KEY => $this->formatDate($timestamp),
            'ext' => strtolower($file->getExtension())
          ];
        }
      }

      $sorters = [
        Constants::NAME_KEY => fn($a, $b) => $sortdir === 'ASC' ? strcmp($a[Constants::NAME_KEY], $b[Constants::NAME_KEY]) : strcmp($b[Constants::NAME_KEY], $a[Constants::NAME_KEY]),
        'date' => fn($a, $b) => $sortdir === 'ASC' ? $a['date'] <=> $b['date'] : $b['date'] <=> $a['date'],
        'size' => fn($a, $b) => $sortdir === 'ASC' ? $a['size'] <=> $b['size'] : $b['size'] <=> $a['size'],
        'random' => fn($a, $b) => rand(-1, 1),
      ];

      usort($result, $sorters[$sortby] ?? $sorters['date']);

      $totalCount = count($result);

      if ($all === 1) {
        $perPage = $totalCount;
        $page = 1;
      }

      $offset = ($page - 1) * $perPage;
      $images = array_slice($result, $offset, $perPage);
      $totalPages = ceil($totalCount / ($perPage ?: 1));

      foreach ($images as &$img) {
        $data = getimagesize($path . '/' . $img[Constants::NAME_KEY]);
        $isImgExist = $data !== false;

        $img['width'] = $isImgExist ? $data[0] : 0;
        $img['height'] = $isImgExist ? $data[1] : 0;

        if($thumbs) {
          $img['pics'] = [
            'webp' => $this->modx->runSnippet('pthumb', [
              'input' => $img['url'],
              'options' => 'zc=1&q=100&w=514&h=440&f=webp'
            ]),
            'thumb' => $this->modx->runSnippet('pthumb', [
              'input' => $img['url'],
              'options' => 'zc=1&q=100&w=514&h=440'
            ]),
          ];
        }
      }

      $output = [
        'data' => [
          'page' => $page,
          'perPage' => $perPage,
          'totalCount' => $totalCount,
          'totalPages' => $totalPages,
          'data' => $images,
          'sortby' => $sortby,
          'sortdir' => $sortdir,
          'cache' => $cache > 0 ? 'cached' : 'no-cache',
        ]
      ];

      if ($cache > 0) {
        $this->modx->cacheManager->set($cacheKey, $output, $cache);
      }
    }

    ['headers' => $headers, 'code' => $code] = $this->setResponseHeaders();

    return jsonx($output, $headers, $code);
  }
}
