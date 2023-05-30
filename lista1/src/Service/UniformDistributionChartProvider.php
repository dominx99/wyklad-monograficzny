<?php

declare(strict_types=1);

namespace App\Service;

final class UniformDistributionChartProvider
{
    public function provide(float $a, float $b): array
    {
        $steps = range($a, $b, 1);
        $uniformCdf = [];

        foreach ($steps as $step) {
            $uniformCdf[] = [
                'x' => $step,
                'y' => ($step - $a) / ($b - $a),
            ];
        }

        return $uniformCdf;
    }

    public function getExtendedChart(float $a, float $b, float $minDistance): array
    {
        $uniformCdf = $this->provide($a, $b);

        foreach (range($a - 1, $a - $minDistance) as $step) {
            array_unshift($uniformCdf, [
                'x' => $step,
                'y' => 0,
            ]);
        }

        foreach (range($b + 1, $b + $minDistance) as $step) {
            $uniformCdf[] = [
                'x' => $step,
                'y' => 1,
            ];
        }

        return $uniformCdf;
    }
}
