<?php

namespace HuffmanPHP;

require './vendor/autoload.php';

$inputFileName = $argv[1] ?? null;
$outputFileName = $argv[2] ?? null;
$keysFileName = 'build/keys.txt';
$startTime = microtime(true);

if (!$inputFileName or !$outputFileName) {
    echoln('php src/compress.php [input-file-name] [output-file-name]');
    exit(1);
}

$file_contents = file_get_contents($inputFileName);

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

Key::store($keys, $keysFileName);

$binary = str_replace(
    $keys->keys()->toArray(),
    $keys->values()->toArray(),
    $file_contents
);

writeBinaryInto($outputFileName, $binary);

$elapsedTime = round(microtime(true) - $startTime, 2);
echoln("took {$elapsedTime}s");

echoln();
echoln("{$inputFileName}: " . prettyFileSize(filesize($inputFileName)));
echoln("{$outputFileName}: " . prettyFileSize(filesize($outputFileName)));
echoln('diff: ' . prettyFileSize(filesize($outputFileName) - filesize($inputFileName)));









function writeBinaryInto(string $fileName, string $binary): void
{
    $binaryFile = fopen($fileName, 'wb');

    foreach (str_split($binary, 8) as $bits) {
        $bits = str_pad($bits, 8, 0, STR_PAD_LEFT);
        $dec = str_pad(base_convert($bits, 2, 16), 2, 0, STR_PAD_LEFT);
        fwrite($binaryFile, pack('H*', $dec));
    }

    fclose($binaryFile);
}
