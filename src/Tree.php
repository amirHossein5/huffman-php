<?php

namespace HuffmanPHP;

use Illuminate\Support\Collection;

class Tree
{
    public static function generate(Collection $nodes): Collection
    {
        if ($nodes->count() === 1) {
            return $nodes;
        }

        $nodes = $nodes->sortBy('freq');

        $right = $nodes->shift();
        $left = $nodes->shift();

        $rightFreq = is_array($right) ? $right['freq'] : $right->freq;
        $leftFreq = is_array($left) ? $left['freq'] : $left->freq;

        return static::generate(
            $nodes->prepend([
                'right' => $right,
                'left' => $left,
                'freq' => $rightFreq + $leftFreq
            ])
        );
    }
}
