<?php

header('Access-Control-Allow-Origin: *');

$sets = $page->children()->listed()
  ->map(fn ($set) => [
    'slug'       => $set->slug(),
    'title'      => $set->title()->value(),
    'date'       => $set->date()->value(),
    'songsCount' => $set->songs()->toPages()->count(),
  ]);

echo json_encode(compact('sets'));
