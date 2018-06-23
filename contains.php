<?php

// searches text for given filters and takes according actions
function checkForContains($text, $mode) {
    $contains = false;

    // performance check for empty text
    if ($text !== null && $text !== '') {
        if ($mode == '2.3') {
            if (strpos($text, '2.3') || $text == '2.3' ||
                strpos($text, '2,3') || $text == '2,3' ||
                strpos($text, '2:3') || $text == '2:3' ||
                strpos($text, '2;3') || $text == '2;3') {
                    $contains = true;
            }
        } elseif ($mode == 'dhbw') {
            $text = strtolower($text);

            if (strpos($text, 'dh')          || $text == 'dh'          ||
                strpos($text, 'dhbw')        || $text == 'dhbw'        ||
                strpos($text, 'universität') || $text == 'universität' ||
                strpos($text, 'schule')      || $text == 'schule'      ||
                strpos($text, 'uni')         || $text == 'uni') {
                    $contains = true;
            }
        } elseif ($mode == 'brüchi') {
            $text = strtolower($text);

            if (strpos($text, 'zu spät')      || $text == 'zu spät'    ||
                strpos($text, 'den brüchi')   || $text == 'der brüchi' ||
                strpos($text, 'der brüchi')   || $text == 'den brüchi' ||
                strpos($text, 'kommt später') || $text == 'kommt später') {
                    $contains = true;
                }
        }
    }

    return $contains;
}