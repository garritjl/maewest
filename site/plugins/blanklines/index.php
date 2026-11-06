<?php

use Kirby\Cms\App;

App::plugin('maewest/blanklines', [
    'hooks' => [
        'kirbytext:before' => function (string|null $text) {
            if ($text === null) {
                return $text;
            }

            $text = str_replace("\r\n", "\n", $text);
            $text = preg_replace('/\n[ \t]+(?=\n)/', "\n", $text);

            return preg_replace_callback('/\n{3,}/', function ($match) {
                $extra = strlen($match[0]) - 2;
                return "\n\n<p class=\"blank-lines\">" . str_repeat('<br>', $extra) . "</p>\n\n";
            }, $text);
        }
    ]
]);
