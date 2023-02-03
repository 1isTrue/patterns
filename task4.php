<?php

/**
    TRUE
*/
isPatternAndStringCompatible('', '');
isPatternAndStringCompatible('1', '1');
isPatternAndStringCompatible('101', '101');
isPatternAndStringCompatible('10?', '101');
isPatternAndStringCompatible('010?010?1', '010101001');

/**
    FALSE
*/
isPatternAndStringCompatible('', '1');
isPatternAndStringCompatible('1', '111');
isPatternAndStringCompatible('101', '1');
isPatternAndStringCompatible('101', '1010');
isPatternAndStringCompatible('10?', '10');
isPatternAndStringCompatible('010?010?0', '010101001');


function isPatternAndStringCompatible($pattern, $string): bool
{
    if (gettype($pattern) != 'string' || gettype($string) != 'string') {
        return false;
    }

    if (mb_detect_encoding($pattern) != 'ASCII' || mb_detect_encoding($string) != 'ASCII') {
        return false;
    }

    if ($pattern == '') {
        return ($string == '');
    }

    if ($string == '') {
        return false;
    }

    $pattern_length = strlen($pattern);
    
    if ($pattern_length != strlen($string)) {
        return false;
    }

    if (preg_match('/[^01]/', $string) == 1) {
        return false;
    }

    if (preg_match('/[^01\?]/', $pattern) == 1) {
        return false;
    }

    if (strcmp($pattern, $string) == 0) {
        return true;
    }

    for ($current_position = 0; $current_position < $pattern_length; $current_position++) {
        if ($pattern[$current_position] != $string[$current_position] && $pattern[$current_position] != '?') {
            return false;
        }
    }

    return true;
}

function allVariantsFormPattern(string $pattern): array
{
    if (mb_detect_encoding($pattern) != 'ASCII') {
        return [];
    }

    if (preg_match('/[^01\?]/', $pattern) == 1) {
        return [];
    }

    $variants = [''];

    foreach (str_split($pattern) as $char) {
        
        if ($char == '?') {
            foreach ($variants as $key => $value) {
                $variants[] = $value . '0';
                $variants[$key] .= 1;
            }
        } else {
            foreach ($variants as $key => $value) {
                $variants[$key] .= $char;
            }
        }
    }

    if ($variants[0] == '') {
        return [];
    }

    return $variants;
}
