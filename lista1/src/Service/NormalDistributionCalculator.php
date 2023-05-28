<?php

declare(strict_types=1);

namespace App\Service;

use Exception;

final class NormalDistributionCalculator
{
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
