<?php

header('Access-Control-Allow-Origin: *');

$key = $kirby->request()->query()->get('key');

$data = [
  'title'        => $page->title()->value(),
  'chordProCode' => $page->chordProCode()->value(),
  'key'          => $key !== null && $key !== '' ? $key : null,
];

echo json_encode($data);
