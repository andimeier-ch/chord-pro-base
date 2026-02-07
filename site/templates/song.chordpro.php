<?php

$kirby->response()->type('text/plain');

echo $page->chordProCode()->value();
