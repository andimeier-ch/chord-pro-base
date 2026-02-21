<?php

$songs = $page->children()->listed()
  ->map(fn ($song) => [
    'chordProURL' => $song->url() . '.chordpro',
    'chordProURI' => $song->uri() . '.chordpro',
    'position' => $song->num(),
    'title' => $song->title()->value(),
  ]);

echo json_encode(compact('songs'));
