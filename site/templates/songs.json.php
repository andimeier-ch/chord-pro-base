<?php

header('Access-Control-Allow-Origin: *');

$songs = $page->children()->listed()
  ->map(fn ($song) => [
    'chordProURL' => $song->url() . '.chordpro',
    'chordProURI' => $song->uri() . '.chordpro',
    'slug' => $song->slug(),
    'num' => $song->num(),
    'title' => $song->title()->value(),
    'uuid' => $song->uuid()->id(),
  ]);

echo json_encode(compact('songs'));
