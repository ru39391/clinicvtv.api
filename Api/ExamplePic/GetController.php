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
    $all = (int)($this->getParam('all', 0));
    $cache = (int)($this->getParam('cache', 3600));
    $dir = $this->modx->getOption('default_exmpl_pics');
    $path = $this->modx->getOption('assets_path') . $dir;
    $exts = $this->getParam('exts', 'jpg,jpeg,png');
    $sortby = $this->getParam('sortby', Constants::NAME_KEY);
    $sortdir = $this->getParam('sortdir', 'ASC');
    ['page' => $page, 'perPage' => $perPage] = $this->getValidPaginationParams();

    $output = [];
    $images = [];
    $cacheKey = 'images_' . md5($path . $exts . $sortby . $sortdir . $page . $perPage . $all);
    $cached = $this->modx->cacheManager->get($cacheKey);

    if ($cached !== null) {
      $output = $cached;
    } else {
      $result = [];
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
            ];

            $result[] = $image;
          }
        }
      }

      $sorters = [
        Constants::NAME_KEY => fn($a, $b) => $sortdir === 'ASC' ? strcmp($a[Constants::NAME_KEY], $b[Constants::NAME_KEY]) : strcmp($b[Constants::NAME_KEY], $a[Constants::NAME_KEY]),
        Constants::UPDATEDON_KEY => fn($a, $b) => $sortdir === 'ASC' ? $a[Constants::UPDATEDON_KEY] <=> $b[Constants::UPDATEDON_KEY] : $b[Constants::UPDATEDON_KEY] <=> $a[Constants::UPDATEDON_KEY],
        'size' => fn($a, $b) => $sortdir === 'ASC' ? $a['size'] <=> $b['size'] : $b['size'] <=> $a['size'],
        'random' => fn($a, $b) => rand(-1, 1),
      ];

      usort($images, $sorters[$sortby] ?? $sorters[Constants::NAME_KEY]);

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
