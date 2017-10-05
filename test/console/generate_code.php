#!/usr/bin/env php
<?php
    require_once __DIR__.'/../../vendor/autoload.php';

    $strings = [];
    foreach ([false, true] as $b1) {
        foreach ([false, true] as $b2) {
            foreach ([false, true] as $b3) {
                foreach ([false, true] as $b4) {
                    $bit = ($b1 ? 1 : 0) | ($b2 ? 2 : 0) | ($b3 ? 4 : 0) | ($b4 ? 8 : 0);

                    $a = [];
                    if ($b1) {
                        $a = array_merge($a, range(0, 9));
                    }
                    if ($b2) {
                        $a = array_merge($a, range('a', 'z'));
                    }
                    if ($b3) {
                        $a = array_merge($a, range('A', 'Z'));
                    }
                    if ($b4) {
                        $a = array_merge($a, ['_', '-', ';', '!', '?', '.', '\'', '"', '@', '*',
                                              '/', '&', '#', '%', '`', '^', '+', '=', '~', '$',]);
                    }
                    $s = '';
                    foreach ($a as $char) {
                        $s .= sprintf('\'%s\', ', str_replace("'", "\\'", $char));
                    }
                    $strings[$bit] = "{$bit} => [{$s}],";
                }
            }
        }
    }
    ksort($strings);
    echo implode("\n", $strings)."\n";

?>