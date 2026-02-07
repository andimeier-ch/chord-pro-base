<?php

$data = [
  'title' => $page->title()->value(),
  'chordProCode' => $page->chordProCode()->value(),
];

echo json_encode($data);
