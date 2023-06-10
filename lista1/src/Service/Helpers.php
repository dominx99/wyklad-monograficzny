<?php

declare(strict_types=1);

namespace App\Service;

use Exception;
use MathPHP\Statistics\Average;

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

    public static function kstest(array $data)
    {
    }

    public static function empiricalCdf(array $data): array
    {
        $n = count($data);

        // Sortowanie danych
        sort($data);

        // Obliczanie dystrybuanty empirycznej
        $empirical_cdf = array();
        for ($i = 0; $i < $n; $i++) {
            $empirical_cdf[$data[$i]] = ($i + 1) / $n;
        }

        return $empirical_cdf;
    }

    public static function movingMedian(array $sequence, $window_size)
    {
        $medians = [];
        $length = count($sequence);
        for ($i = 0; $i <= $length - $window_size; $i++) {
            $window = array_slice($sequence, $i, $window_size);
            sort($window);
            $median_index = floor($window_size / 2);
            $median = $window[$median_index];
            $medians[] = $median;
        }
        return $medians;
    }

    public static function calculateAverageResiduals(array $sequence, array $moving_averages)
    {
        $residuals = [];
        $length = count($sequence);
        for ($i = 0; $i < $length; $i++) {
            $residual = $sequence[$i] - $moving_averages[$i];
            $residuals[] = $residual;
        }
        return $residuals;
    }

    public static function exponentialMovingMedian($sequence, $windowSize)
    {
        $windowSizeAtStart = 3;

        $medians = [];

        $length = $windowSize;
        $tmp = floor($length / 2);

        foreach ($sequence as $key => $value) {
            $length = $windowSize;
            $cutFrom = 0;
            $startExists = isset($sequence[$key - $tmp]);
            $endExists = isset($sequence[$key + $tmp]);

            if ($startExists && $endExists) {
                $cutFrom = $key - $tmp;
            } elseif ($startExists && !$endExists) {
                $cutFrom = $key - $tmp;
                $length = $windowSizeAtStart + (array_key_last($sequence) - $key);
            } elseif (!$startExists && $endExists) {
                $cutFrom = 0;
                $length = $windowSizeAtStart + $key;
            } else {
                throw new Exception('Something went wrong');
            }

            $cutFrom = (int) $cutFrom;
            $medianFrom = array_slice($sequence, $cutFrom, $length);
            $medians[] = Average::median(($medianFrom));
        }

        return $medians;
    }

    public static function exponentialMovingAverage($sequence, $windowSize)
    {
        $windowSizeAtStart = 3;

        $medians = [];

        $length = $windowSize;
        $tmp = floor($length / 2);

        foreach ($sequence as $key => $value) {
            $length = $windowSize;
            $cutFrom = 0;
            $startExists = isset($sequence[$key - $tmp]);
            $endExists = isset($sequence[$key + $tmp]);

            if ($startExists && $endExists) {
                $cutFrom = $key - $tmp;
            } elseif ($startExists && !$endExists) {
                $cutFrom = $key - $tmp;
                $length = $windowSizeAtStart + (array_key_last($sequence) - $key);
            } elseif (!$startExists && $endExists) {
                $cutFrom = 0;
                $length = $windowSizeAtStart + $key;
            } else {
                throw new Exception('Something went wrong');
            }

            $cutFrom = (int) $cutFrom;
            $medianFrom = array_slice($sequence, $cutFrom, $length);
            $medians[] = Average::mean(($medianFrom));
        }

        return $medians;
    }
}
