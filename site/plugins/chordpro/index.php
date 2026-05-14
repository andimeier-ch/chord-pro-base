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
        'songkeyselect'  => [
            'extends' => 'select',
            'mixins'  => ['options'],
        ],
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
    'api' => [
        'routes' => [
            [
                'pattern' => 'chordpro/songs/(:any)/original-key',
                'method'  => 'GET',
                'action'  => function ($uuid) {
                    $song = kirby()->page('page://' . $uuid);
                    if (!$song || !($song instanceof ChordProSongPage)) {
                        return ['key' => null];
                    }

                    $key = $song->chordProKey();
                    if ($key === null) {
                        return ['key' => null];
                    }

                    // Normalise flats to sharps so the value matches the
                    // dropdown's option keys (labels show both notations).
                    static $flatToSharp = [
                        'Db'  => 'C#',  'Eb'  => 'D#',  'Gb'  => 'F#',
                        'Ab'  => 'G#',  'Bb'  => 'A#',
                        'Dbm' => 'C#m', 'Ebm' => 'D#m', 'Gbm' => 'F#m',
                        'Abm' => 'G#m', 'Bbm' => 'A#m',
                    ];

                    return ['key' => $flatToSharp[$key] ?? $key];
                },
            ],
        ],
    ],
    'hooks' => [
        'file.create:after' => fn (\Kirby\Cms\File $file) => ChordProFileImporter::importFromUpload($file),
    ],
]);
