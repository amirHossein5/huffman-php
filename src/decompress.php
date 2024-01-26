<?php

use HuffmanPHP\Key;
use Illuminate\Support\Collection;

require './vendor/autoload.php';

$compressedFileName = $argv[1] ?? null;
$outputFileName = $argv[2] ?? null;
$keysFileName = 'build/keys.txt';
$startTime = microtime(true);

if (!$compressedFileName or !$outputFileName) {
    echoln('php src/decompress.php [compressed-file-name] [output-file-name]');
    exit(1);
}

$binary = parseHuffmanEncoded($compressedFileName);

file_put_contents(
    $outputFileName,
    replaceBinaryWithKeys(Key::read($keysFileName), $binary)
);

$elapsedTime = round(microtime(true) - $startTime, 2);
echoln("took {$elapsedTime}s");










function parseHuffmanEncoded(string $huffmanFileName): string
{
    $huffmanEncoded = file_get_contents($huffmanFileName);
    $unpack = unpack('H*', $huffmanEncoded)[1];
    $binary = '';

    foreach ($arr = str_split($unpack, 2) as $key => $dec) {
        $v = base_convert($dec, 16, 2);
        if (array_key_last($arr) !== $key) {
            $v = str_pad($v, 8, 0, STR_PAD_LEFT);
        }
        $binary .= $v;
    }

    return $binary;
}

function replaceBinaryWithKeys(Collection $keys, string $binary): string
{
    $replaced = '';
    $notMatched = '';

    foreach (str_split($binary) as $bit) {
        $char = $keys
            ->filter(fn ($code) => $code === $notMatched . $bit)
            ->keys()
            ->first();

        if ($char) {
            $notMatched = '';
            $replaced .= $char === '/\n' ? "\n" : $char;
        } else {
            $notMatched .= $bit;
        }
    }

    return $replaced;
}
