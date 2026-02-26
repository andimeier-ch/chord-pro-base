<?php

header('Access-Control-Allow-Origin: *');

$songs = $page->songs()->toPages()
  ->map(fn ($song) => [
    'chordProURL' => $song->url() . '.chordpro',
    'chordProURI' => $song->uri() . '.chordpro',
    'slug'        => $song->slug(),
    'title'       => $song->title()->value(),
    'uuid'        => $song->uuid()->id(),
  ]);

echo json_encode([
  'title' => $page->title()->value(),
  'date'  => $page->date()->value(),
  'songs' => $songs,
]);
