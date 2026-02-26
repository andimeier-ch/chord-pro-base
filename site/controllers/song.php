<?php

use Kirby\Exception\ErrorPageException;

return function ($kirby) {
    $ressource = $kirby->request()->path()->last();
    $ressourceElements = explode('.', $ressource);
    $ressourceType = array_key_exists(1, $ressourceElements) ? $ressourceElements[1] : 'html';

    if (!in_array($ressourceType, ['json', 'chordpro'])) {
        throw new ErrorPageException(
            httpCode: 403,
            fallback: 'Song can not be fetched in this Format.',
        );
    }
};
