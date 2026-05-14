<?php

header('Access-Control-Allow-Origin: *');

$songs = $page->songs()->toStructure()
  ->map(function ($entry) {
    $song = $entry->song()->toPage();
    if (!$song) {
      return null;
    }
    $key   = $entry->key()->value();
    $query = $key !== '' ? '?key=' . rawurlencode($key) : '';
    return [
      'chordProURL' => $song->url() . '.chordpro' . $query,
      'chordProURI' => $song->uri() . '.chordpro' . $query,
      'slug'        => $song->slug(),
      'title'       => $song->title()->value(),
      'uuid'        => $song->uuid()->id(),
      'key'         => $key !== '' ? $key : null,
    ];
  })
  ->filter(fn ($s) => $s !== null)
  ->values();

echo json_encode([
  'title' => $page->title()->value(),
  'date'  => $page->date()->value(),
  'songs' => $songs,
]);
