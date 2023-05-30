<?php

declare(strict_types=1);

namespace App\Service;

final class EmpiricalDistributionChartProvider
{
    public function __construct(
        private readonly UniformDistributionChartProvider $uniformDistributionChartProvider,
    ) {
    }

    public function provide(array $values)
    {
        sort($values);
        $valuesMap = array_count_values($values);
        $data = [];
        $valuesCount = count($values);
        $a = min($values);
        $b = max($values);
        $allSteps = range($a, $b, 1);

        $previousValue = 0;
        $previousStep = null;
        $distances = [];

        foreach ($allSteps as $stepValue) {
            if (!isset($valuesMap[$stepValue])) {
                $data[] = [
                    'x' => $stepValue,
                    'y' => $previousValue,
                    'stepped' => false,
                ];

                continue;
            }


            if ($previousStep !== null) {
                $distances[] = $stepValue - $previousStep;
            }
            $count = $valuesMap[$stepValue];

            $previousValue = $previousValue + ($count / $valuesCount);
            $previousStep = $stepValue;

            $data[] = [
                'x' => $stepValue,
                'y' => $previousValue,
                'stepped' => true,
            ];
        }

        return [$a, $b, min($distances), $data];
    }

    public function getExtendedChart(array $values): array
    {
        [$a, $b, $minDistance, $data] = $this->provide($values);

        foreach (range($a - 1, $a - $minDistance) as $step) {
            array_unshift($data, [
                'x' => $step,
                'y' => 0,
            ]);
        }

        foreach (range($b + 1, $b + $minDistance) as $step) {
            $data[] = [
                'x' => $step,
                'y' => 1,
            ];
        }

        return [$data, $minDistance];
    }

    public function calculateKolmogorovDistance(array $values)
    {
        [$a, $b, $minDistance, $empiricalCdf] = $this->provide($values);
        $uniformCdf = $this->uniformDistributionChartProvider->provide($a, $b);

        $maxDistance = 0;
        foreach ($empiricalCdf as $key => $empiricalCdfValue) {
            $uniformCdfValue = $uniformCdf[$key];

            $distance = abs($empiricalCdfValue['y'] - $uniformCdfValue['y']);
            if ($distance > $maxDistance) {
                $maxDistance = $distance;
            }
        }

        return $maxDistance;
    }
}
