<?php

namespace HuffmanPHP;

require './vendor/autoload.php';

$file_contents = file_get_contents('data.txt');

$letters = collect(str_split($file_contents))
    ->countBy()
    ->map(fn ($n, $c) => new Char($c, $n));

$keys = Tree::generate($letters)
    ->dot()
    ->filter(fn ($v, $k) => !str_ends_with($k, '.freq'))
    ->mapWithKeys(fn ($v, $k) => [
        $v->char => (string) str($k)
            ->replace('0.', '')
            ->replace(['left', 'right'], [1, 0])
            ->replace('.', '')
    ]);

$file_contents = str_replace(
    $keys->keys()->toArray(),
    $keys->values()->toArray(),
    $file_contents
);

$binaryFile = fopen('binary.huffman', 'wb');

foreach (str_split($file_contents, 8) as $bits) {
    $bits = str_pad($bits, 8, 0, STR_PAD_LEFT);
    $dec = str_pad(base_convert($bits, 2, 16), 2, 0, STR_PAD_LEFT);
    fwrite($binaryFile, pack('H*', $dec));
}

fclose($binaryFile);

Key::store($keys, 'keys.txt');

dd(filesize('binary.huffman') - filesize('data.txt'));
