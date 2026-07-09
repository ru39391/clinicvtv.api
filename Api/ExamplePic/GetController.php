<?php
namespace Zoomx\Controllers\Api\ExamplePic;

use Zoomx\Controllers\Common\GetController as CommonGetController;
use Zoomx\Controllers\Common\Constants;
use Zoomx\Controllers\Common\RequestParamsTrait;

class GetController extends CommonGetController
{
  use RequestParamsTrait;

  public function index()
  {
    $cache = (int)($this->getParam('cache', 3600));
    $dir = $this->modx->getOption('default_exmpl_pics');
    $path = $this->modx->getOption('assets_path') . $dir;
    $exts = $this->getParam('exts', 'jpg,jpeg,png');
    $limit = (int)($this->getParam('limit', 0));
    $params = $this->getValidPaginationParams();
    $sortby = $this->getParam('sortby', Constants::NAME_KEY);
    $sortdir = $this->getParam('sortdir', 'ASC');

    $output = [];
    $images = [];
    $cacheKey = 'images_' . md5($path . $exts . $sortby . $sortdir . $limit);
    $cached = $this->modx->cacheManager->get($cacheKey);

    if ($cached !== null) {
      $output = $cached;
    } else {
      $allowedExtensions = array_map('trim', explode(',', $exts));
      $allowedExtensions = array_map('strtolower', $allowedExtensions);

      foreach (new \DirectoryIterator($path) as $file) {
        if ($file->isFile()) {
          $ext = strtolower($file->getExtension());

          if (in_array($ext, $allowedExtensions)) {
            $name = $file->getFilename();
            $date = $file->getMTime();
            $image = [
              Constants::NAME_KEY => $name,
              'url' => 'assets/' . $dir . '/' . $name,
              'size' => $file->getSize(),
              'size_formatted' => $file->getSize() > 1048576
                ? round($file->getSize() / 1048576, 2) . ' MB'
                : round($file->getSize() / 1024, 2) . ' KB',
              Constants::UPDATEDON_KEY => date('Y-m-d H:i:s', $file->getMTime()),
              'ext' => $ext,
              'width' => 0,
              'height' => 0,
            ];

            $imageInfo = getimagesize($file->getRealPath());
            if ($imageInfo !== false) {
              $image['width']  = $imageInfo[0];
              $image['height'] = $imageInfo[1];
            }

            $images[] = $image;
          }
        }
      }

      $sorters = [
          'size' => fn($a, $b) => $sortdir === 'ASC' ? $a['size'] <=> $b['size'] : $b['size'] <=> $a['size'],
          'modified' => fn($a, $b) => $sortdir === 'ASC' ? $a['modified'] <=> $b['modified'] : $b['modified'] <=> $a['modified'],
          'random' => fn($a, $b) => rand(-1, 1),
          'name' => fn($a, $b) => $sortdir === 'ASC' ? strcmp($a['name'], $b['name']) : strcmp($b['name'], $a['name'])
      ];

      usort($images, $sorters[$sortby] ?? $sorters['name']);

      $totalCount = count($images);

      if ($limit > 0 && $totalCount > $limit) {
        $images = array_slice($images, 0, $limit);
      }

      $output = [
        'data' => [
          //'page' => $page,
          //'perPage' => $perPage,
          'totalCount' => $totalCount,
          //'totalPages' => $totalPages,
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
