<?php

declare(strict_types=1);

namespace App\Service;

final class NormalDistributionChartProvider
{
    public function __construct(
        private readonly NormalDistributionCalculator $normalDistributionCalculator,
    ) {
    }

    public function provide(
        float $mean,
        float $standardDeviation,
        string $operator,
        float $step,
        array $values,
    ): array {
        return [
            'mean' => $mean,
            'standardDeviation' => $standardDeviation,
            'probabilityDensityFunction' => $this->provideProbabilityDensityFunction(
                $mean,
                $standardDeviation,
                $step,
            ),
            'probability' => $this->provideProbability(
                $mean,
                $standardDeviation,
                $operator,
                $step,
                $values,
            ),
        ];
    }

    private function provideProbability(
        float $mean,
        float $standardDeviation,
        string $operator,
        float $step,
        array $values,
    ): array {
        $density = $this->provideProbabilityDensityFunction($mean, $standardDeviation, $step);

        $probabilities = [];
        foreach ($density as $key => $value) {
            if (!$this->isValueInScope($value['x'], $operator, $mean, $values)) {
                $probabilities[] = [
                    'x' => $value['x'],
                    'y' => 0,
                ];

                continue;
            }

            $probabilities[] = [
                'x' => $value['x'],
                'y' => $this->calculateProbabilityDensityFunction(
                    $value['x'],
                    $mean,
                    $standardDeviation,
                ),
            ];
        }

        return $probabilities;
    }

    private function isValueInScope(float $value, string $operator, float $mean, array $values): bool
    {
        return match ($operator) {
            'more' => $value > $values[0],
            'less' => $value < $values[0],
            'between' => $value > $values[0] && $value < $values[1],
            'outside' => $value < $values[0] || $value > $values[1],
            'sigma3' => $value < -($values[0] * $mean) || $value > ($values[0] * $mean),
        };
    }

    private function provideProbabilityDensityFunction(float $mean, float $standardDeviation, float $step): array
    {
        $probabilityDensityFunction = [];

        for ($x = $mean - 3 * $standardDeviation; $x <= $mean + 3 * $standardDeviation; $x += $step) {
            $probabilityDensityFunction[] = [
                'x' => round($x, 2),
                'y' => $this->calculateProbabilityDensityFunction($x, $mean, $standardDeviation),
            ];
        }

        return $probabilityDensityFunction;
    }

    private function calculateProbabilityDensityFunction(float $x, float $mean, float $standardDeviation): float
    {
        return (1 / ($standardDeviation * sqrt(2 * pi()))) * exp(-0.5 * (($x - $mean) / $standardDeviation) ** 2);
    }
}
