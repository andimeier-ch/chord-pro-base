<?php

use Kirby\Cms\App as Kirby;
use AndiMeier\ChordPro\Models\ChordProSongPage;

@include_once __DIR__ . '/vendor/autoload.php';

Kirby::plugin('andimeier-ch/chordpro', [
    'pageModels' => [
        'song' => ChordProSongPage::class,
    ],
]);
