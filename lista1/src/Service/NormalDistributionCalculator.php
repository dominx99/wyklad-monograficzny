<?php

declare(strict_types=1);

namespace App\Service;

use Exception;

final class NormalDistributionCalculator
{
    // $mean = 1;
    // $sigma = 2;
    //
    // echo sprintf('P(X > 0) dla N(1, 2) = %s', calculate_probability($mean, $sigma, '>', [0])) . PHP_EOL;
    // echo sprintf('P(-1 < X < 2) dla N(1, 2) = %s', calculate_probability($mean, $sigma, 'between', [-1, 2])) . PHP_EOL;
    // echo sprintf('P(X < 3) dla N(1, 2) = %s', calculate_probability($mean, $sigma, '<', [3])) . PHP_EOL;
    //
    // echo "-------" . PHP_EOL;
    //
    // $a = 1;
    // echo sprintf('P(|X - 1| > 2) dla N(1, 2), gdzie a = 1 = %s', $sigma * ($mean - standard_normal_cdf($a))) . PHP_EOL;
    //
    // $a = 2;
    // echo sprintf('P(|X - 1| > 2) dla N(1, 2), gdzie a = 2 = %s', $sigma * ($mean - standard_normal_cdf($a))) . PHP_EOL;
    //
    // $a = 3;
    // echo sprintf('P(|X - 1| > 2) dla N(1, 2), gdzie a = 3 = %s', $sigma * ($mean - standard_normal_cdf($a))) . PHP_EOL;

    /** @param array<float> $values */
    public function calculateProbability(float $mean, float $sigma, string $operator, array $values): float
    {
        $zScores = [];

        foreach ($values as $value) {
            $zScores[] = ($value - $mean) / $sigma;
        }

        return match ($operator) {
            'more' => 1 - Helpers::standardNormalCdf($zScores[0]),
            'less' => Helpers::standardNormalCdf($zScores[0]),
            'between' => Helpers::standardNormalCdf($zScores[1]) - Helpers::standardNormalCdf($zScores[0]),
            'outside' => 1 - (Helpers::standardNormalCdf($zScores[1]) - Helpers::standardNormalCdf($zScores[0])),
            'sigma3' => $sigma * ($mean - Helpers::standardNormalCdf($values[0])),
            default => throw new Exception('Invalid operator')
        };
    }
}
