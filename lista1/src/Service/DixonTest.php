<?php

declare(strict_types=1);

namespace App\Service;

use App\Service\TableProvider\DixonTable;
use MathPHP\Statistics\Average;

final class DixonTest
{
    public static function getX1AndX2(array $data): array
    {
        $mean = Average::mean($data);
        $max = 0;
        $x1 = 0;
        foreach ($data as $value) {
            $tmp = max($max, abs($value - $mean));

            if ($tmp > $max) {
                $max = $tmp;
                $x1 = $value;
            }
        }

        sort($data);
        $indexOfX1 = array_search($x1, $data);

        $prevIndex = $indexOfX1 - 1;
        $afterIndex = $indexOfX1 + 1;

        if (!isset($data[$prevIndex])) {
            $x2 = $data[$afterIndex];
        } elseif (!isset($data[$afterIndex])) {
            $x2 = $data[$prevIndex];
        } else {
            $x2 = min(abs($data[$prevIndex] - $x1), abs($data[$afterIndex] - $x1));
        }

        return [$x1, $x2];
    }

    public static function getQCritical(int $n, float $alpha): float
    {
        return DixonTable::getScore($n, (string) $alpha);
    }
}
