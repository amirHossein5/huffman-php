<?php

use HuffmanPHP\Key;

require './vendor/autoload.php';

$binContent = file_get_contents('binary.huffman');

$unpack = unpack('H*', $binContent)[1];
$binary = '';

foreach ($arr = str_split($unpack, 2) as $key => $dec) {
    $v = base_convert($dec, 16, 2);
    if (array_key_last($arr) !== $key) {
        $v = str_pad($v, 8, 0, STR_PAD_LEFT);
    }
    $binary .= $v;
}

$keys = Key::read('keys.txt');
$binaryArray = str_split($binary);
$replaced = '';
$notMatched = '';

foreach ($binaryArray as $key => $bit) {
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

file_put_contents('output.txt', $replaced);
