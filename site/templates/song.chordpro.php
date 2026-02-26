<?php

header('Access-Control-Allow-Origin: *');

$kirby->response()->type('text/plain');

echo $page->chordProCode()->value();
