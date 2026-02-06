<?php

use Kirby\Cms\App as Kirby;
use Kirby\Exception\NotFoundException;

Kirby::plugin('andimeier-ch/api', [
    'api' => [
        'data' => [
            'message' => function () {
                return 'world';
            },
            'song' => function ($uuid) {
                $song = page('page://' . $uuid);

                if (!$song) {
                    throw new NotFoundException([
                        'key' => 'song.notFound',
                        'data' => ['uuid' => $uuid]
                    ]);
                }

                return $song;
            },
            'songs' => function () {
                return kirby()->site()->find('songs')->children();
            },
        ],
        'routes' => [
            [
                'pattern' => 'songs',
                'method' => 'GET',
                'action' => function () {
                    return $this->songs();
                },
            ],
            [
                'pattern' => 'song/(:any)/chordpro',
                'method' => 'GET',
                'action' => function ($id) {
                    return $this->song($id)->chordProRaw();
                },
            ],
            [
                'pattern' => 'song/(:any)/json',
                'method' => 'GET',
                'action' => function ($id) {
                    return $this->song($id)->chordProJSON();
                },
            ],
            [
                'pattern' => 'song/(:any)/text',
                'method' => 'GET',
                'action' => function ($id) {
                    return $this->song($id)->chordProJSON(true);
                },
            ],
            [
                'pattern' => 'song/(:any)/html',
                'method' => 'GET',
                'action' => function ($id) {
                    return $this->song($id)->chordProHTML();
                },
            ],
        ],
    ],
]);
