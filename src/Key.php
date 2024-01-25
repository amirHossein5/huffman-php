<?php

namespace HuffmanPHP;

use Illuminate\Support\Collection;

class Key
{
    public static function store(Collection $keys, string $fileName): void
    {
        $file = fopen($fileName, 'w');
        foreach ($keys as $char => $code) {
            if ($char === PHP_EOL) {
                $char = '/\n';
            }
            fwrite($file, "{$char}={$code}".PHP_EOL);
        }
        fclose($file);
    }

    public static function read(string $fileName): Collection
    {
        $file_contents = trim(file_get_contents($fileName));
        $codes = [];

        foreach (explode(PHP_EOL, $file_contents) as $line) {
            [$char, $code] = explode('=', $line);
            $codes[$char] = $code;
        }

        return collect($codes);
    }
}
