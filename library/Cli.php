<?php
class Cli
{
    public static function getParams(array $options, $argc, $argv)
    {
        if ($argc > 1) {
            foreach ($argv as $com) {
                if (0 === strpos($com, '-')) {
                    $varName = substr($com, 1, strpos($com, '=') - 1);
                    $varValue = substr($com, strpos($com, '=') + 1);

                    if (array_key_exists($varName, $options)) $options[$varName] = $varValue;
                }
            }
        }

        return $options;
    }
}