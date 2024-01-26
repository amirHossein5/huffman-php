<?php

function echoln(string $mess = null): void
{
    echo $mess . PHP_EOL;
}

function prettyFileSize(float $fileSize): string
{
    return number_format($fileSize / 1000, 1) . " KB";
}
