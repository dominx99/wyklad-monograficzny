<?php

declare(strict_types=1);

namespace App\Service;

final class EmpiricalStandardDeviationCalculator
{
    /** @param float[] $values */
    public function calculate(array $values): array
    {
        $n = count($values);
        // Oblicz wartość średnią
        $mean = array_sum($values) / $n;

        // Oblicz empiryczne odchylenie standardowe
        $variance = array_map(function ($x) use ($mean) {
            return pow($x - $mean, 2);
        }, $values);
        $empiricalStandardDeviation = sqrt(array_sum($variance) / ($n - 1));

        // Oblicz kwartyle
        sort($values);
        $q1 = $values[round(
            0.25 * (count($values) + 1)
        ) - 1];
        $q2 = $values[round(0.5 * (count($values) + 1)) - 1];
        $q3 = $values[round(0.75 * (count($values) + 1)) - 1];

        $median = $q2;

        // Oblicz współczynnik skosności
        $skewness = 0;
        if ($empiricalStandardDeviation != 0) {
            $mean_diff_cubed = array_map(function ($x) use ($mean) {
                return pow($x - $mean, 3);
            }, $values);
            $skewness = array_sum($mean_diff_cubed) / ($n * pow($empiricalStandardDeviation, 3));
        }

        // Oblicz współczynnik skupienia
        $kurtosis_coeff = 0;
        if ($empiricalStandardDeviation != 0) {
            $mean_diff_fourth = array_map(function ($x) use ($mean) {
                return pow($x - $mean, 4);
            }, $values);
            $kurtosis_coeff = (array_sum($mean_diff_fourth) / ($n * pow($empiricalStandardDeviation, 4))) - 3;
        }

        return [
            'mean' => $mean,
            'min' => min($values),
            'max' => max($values),
            'median' => $median,
            'q1' => $q1,
            'q3' => $q3,
            'empirical_standard_deviation' => $empiricalStandardDeviation,
            'skewness' => $skewness,
            'kurtosis_coeff' => $kurtosis_coeff,
        ];
    }
}
