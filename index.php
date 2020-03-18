<?php
header('Content-Type:text/plain');

define('PATTERN', '/(\(.*\)|\<.*\>|\[.*\])/');
define('PARENTHESES_PATTERN', '/[\(\)\[\]\<\>]/');

/**
 * @param array $classes
 * @return string
 */
function classlist($classes) {
    $classArray = [];
    $allows = array_filter($classes, function ($c) {
        return $c !== false;
    });

    foreach ($allows as $key => $value) {
        if (is_int($key)) {
            $classArray[] = $value;
        } else {
            $classArray[] = $key;
        }
    }

    return implode(' ', $classArray);
}


/**
 * @param string $str
 * @return bool
 */
function validate($str) {

    if(!preg_match(PARENTHESES_PATTERN, $str)) {
        return true;
    }
    preg_match_all(PATTERN, $str, $matches);
    if (empty($matches[0])) {
        return false;
    } else {
        foreach ($matches[0] as $match) {
            $substr = preg_replace('/^[\(\[\<]|[\)\]\>]$/', '', $match);
            if(!validate($substr)) {
                return false;
            }
        }
        return true;
    }
}

$isAuthorized = true;
$isAdmin = false;
$classes = [
    "b-user",
    "b-user--authorized" => $isAuthorized,
    "b-user--admin" => $isAdmin
];

echo 'Classes: ' . classlist($classes) . "\n";

$lines = [
    '5 + 3 - (4 + 12)',
    '<test> [ foo ] ( bar )',
    '[ ( foo) ] <bar>',
    '[ ( test ] )',
    '( hello'
];


foreach ($lines as $line) {
    echo '"' .$line . '"' . ' ' . (validate($line) ? 'valid' : 'not valid') . "\n";
}