<?php

use Kirby\Cms\App;
use Kirby\Http\Response;

App::plugin('maewest/textepdf', [
    'routes' => [
        [
            'pattern' => 'works/(:any)/texte.zip',
            'action'  => function (string $slug) {
                $page = page('works/' . $slug);

                if ($page === null) {
                    return false;
                }

                $files = $page->textepdf()->toFiles();

                if ($files->count() < 2 || class_exists('ZipArchive') === false) {
                    return false;
                }

                $tmp = tempnam(sys_get_temp_dir(), 'texte');
                $zip = new ZipArchive();

                if ($zip->open($tmp, ZipArchive::OVERWRITE) !== true) {
                    unlink($tmp);
                    return false;
                }

                $used = [];

                foreach ($files as $file) {
                    $name = $file->filename();
                    $n    = 1;

                    while (isset($used[$name]) === true) {
                        $name = $file->name() . '-' . ++$n . '.' . $file->extension();
                    }

                    $used[$name] = true;
                    $zip->addFile($file->root(), $name);
                }

                $zip->close();

                $response = Response::download($tmp, $page->slug() . '-texte.zip');

                unlink($tmp);

                return $response;
            }
        ]
    ]
]);
