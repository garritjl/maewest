<?php

use Kirby\Cms\App;

App::plugin('maewest/textalign', [
    'hooks' => [
        'kirbytext:before' => function (string|null $text) {
            if ($text === null || str_contains($text, ':::') === false) {
                return $text;
            }

            $text = preg_replace('/^[ \t]*:::[ \t]*(left|center|right)[ \t]*$/mi', '%%align-$1%%', $text);
            $text = preg_replace('/^[ \t]*:::[ \t]*$/m', '%%align-end%%', $text);

            return $text;
        },
        'kirbytext:after' => function (string|null $text) {
            if ($text === null || str_contains($text, '%%align-') === false) {
                return $text;
            }

            $markers = [
                '%%align-(left|center|right)%%' => '<div class="align-$1">',
                '%%align-end%%'                 => '</div>',
            ];

            foreach ($markers as $marker => $html) {
                $text = preg_replace('#<p>\s*' . $marker . '\s*</p>#i', $html, $text);
                $text = preg_replace('#<p>\s*' . $marker . '\s*<br\s*/?>\s*#i', $html . '<p>', $text);
                $text = preg_replace('#\s*<br\s*/?>\s*' . $marker . '\s*</p>#i', '</p>' . $html, $text);
                $text = preg_replace('#\s*<br\s*/?>\s*' . $marker . '\s*<br\s*/?>\s*#i', '</p>' . $html . '<p>', $text);
            }

            return $text;
        }
    ]
]);
