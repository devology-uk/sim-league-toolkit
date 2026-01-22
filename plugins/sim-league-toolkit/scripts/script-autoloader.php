<?php
  spl_autoload_register(function ($type) {
    if(!str_starts_with($type, 'SLTK')) {
      return;
    }

    $typeElements = explode('\\', $type);

    if(!is_array($typeElements)) {
      return;
    }

    $rootPath = dirname(__DIR__) . DIRECTORY_SEPARATOR;
    array_shift($typeElements);
    $typeName = array_pop($typeElements);
    $path = $rootPath . implode(DIRECTORY_SEPARATOR, $typeElements) . DIRECTORY_SEPARATOR . $typeName . '.php';

    echo($path);

    if(file_exists($path)) {
      require_once $path;
    }
  });
