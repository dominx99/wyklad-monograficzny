<?php

declare(strict_types=1);

namespace App\Service;

use MathPHP\Probability\Distribution\Continuous\Normal;

final class SignTest
{
    public static function getS(array $data): int
    {
        $s = 0;

        for ($i = 1; $i < count($data); $i++) {
            if ($data[$i] > $data[$i - 1]) {
                $s++;
            }
        }

        return $s;
    }

    public static function calculate(array $data, float $alpha): array
    {
        $n = count($data);
        $ms = ($n - 1) / 2;
        $sigma = sqrt(($n + 1) / 12);
        $s = SignTest::getS($data);

        $result = abs($s - $ms) / $sigma;
        $alpha = $alpha;
        $normal = new Normal(0, 1);
        $criticalValue = $normal->inverse(1 - $alpha / 2);

        if ($result < $criticalValue) {
            $message = "Ponieważ {$result} < {$criticalValue} to nie ma podstaw do odrzucenia hipotezy zerowej. To oznacza, że szereg nie zawiera trendu.";
        } else {
            $message = "Ponieważ {$result} > {$criticalValue} to należy odrzucić hipotezę zerową. To oznacza, że pomiary zawierają trend.";
        }

        return [$n, $ms, $sigma, $s, $result, $criticalValue, $message];
    }
}
