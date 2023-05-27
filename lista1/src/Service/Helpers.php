<?php

declare(strict_types=1);

namespace App\Service;

final class Helpers
{
    public static function standardNormalCdf(float $zScore): float|int
    {
        // Współczynniki dla przybliżenia wielomianowego
        $a1 = 0.2316419;
        $a2 = 0.3193815;
        $a3 = -0.3565638;
        $a4 = 1.781478;
        $a5 = -1.821256;
        $a6 = 1.330274;

        // Odwrotność pierwiastka z 2*pi
        $inv_sqrt_2pi = 0.3989423;

        // Transformacja wartości wejściowej
        $t = 1 / (1 + $a1 * abs($zScore));

        // Wykładnicza część wzoru
        $exp_val = exp(-$zScore * $zScore / 2);

        // Obliczanie przybliżonego skumulowanego rozkładu normalnego
        $p = $inv_sqrt_2pi * $exp_val * $t * ($a2 + $t * ($a3 + $t * ($a4 + $t * ($a5 + $t * $a6))));

        // Korekta dla prawego ogona rozkładu
        if ($zScore > 0) {
            $p = 1 - $p;
        }

        // Zwracanie obliczonego prawdopodobieństwa
        return $p;
    }

    public static function reverseOperator(string $operator): string
    {
        return match ($operator) {
            'more' => 'less',
            'less' => 'more'
        };
    }
}
