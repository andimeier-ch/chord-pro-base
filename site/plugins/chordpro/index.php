<?php

use Kirby\Cms\App as Kirby;
use AndiMeier\ChordPro\Services\ChordProFileImporter;
use AndiMeier\ChordPro\Models\ChordProSongPage;

@include_once __DIR__ . '/vendor/autoload.php';

Kirby::plugin('andimeier-ch/chordpro', [
    'pageModels' => [
        'song' => ChordProSongPage::class,
    ],
    'fields' => [
        'chordproeditor' => [],
    ],
    'fileTypes' => [
        'chordpro' => [
            'mime' => 'text/plain',
            'type' => 'document',
        ],
        'chopro' => [
            'mime' => 'text/plain',
            'type' => 'document',
        ],
    ],
    'hooks' => [
        'file.create:after' => fn (\Kirby\Cms\File $file) => ChordProFileImporter::importFromUpload($file),
    ],
]);
