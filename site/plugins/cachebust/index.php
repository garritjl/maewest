<?php

use Kirby\Cms\App;

App::plugin('maewest/cachebust', []);

if (function_exists('assetv') === false) {
    function assetv(string $path): string
    {
        $file = App::instance()->root('index') . '/' . ltrim($path, '/');

        if (is_file($file) === true) {
            return $path . '?v=' . filemtime($file);
        }

        return $path;
    }
}
