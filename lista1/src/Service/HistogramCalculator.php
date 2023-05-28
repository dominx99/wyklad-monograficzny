<?php

declare(strict_types=1);

namespace App\Service;

final class HistogramCalculator
{
    /**
     * @param array<float> $data
     * @return array<array{rangeStart: float, rangeEnd: float, binLabel: string, binCount: int}>
     */
    public function calculate(array $data): array
    {
        // Sortowanie danych
        sort($data);

        // Obliczenie zakresu przedziałów
        $minValue = min($data);
        $maxValue = max($data);

        // Liczba klas histogramu
        $numBins = (int) ceil(sqrt(count($data)) * 0.75);

        if ($numBins < 5) {
            $numBins = 5;
        }

        $binWidth = ($maxValue - $minValue) / $numBins;

        // Inicjalizacja liczników dla przedziałów
        $histogram = array_fill(0, $numBins, 0);

        // Podział danych na przedziały i zliczanie
        foreach ($data as $value) {
            $binIndex = floor(($value - $minValue) / $binWidth);

            if ($value === $maxValue) {
                $binIndex -= 1;
            }

            $histogram[$binIndex]++;
        }

        return array_map(fn (float $value, int $i) => [
            'rangeStart' => $minValue + ($i * $binWidth),
            'rangeEnd' => $minValue + (($i + 1) * $binWidth),
            'binLabel' => sprintf("%.2f - %.2f", $minValue + ($i * $binWidth), $minValue + (($i + 1) * $binWidth)),
            'binCount' => $value,
        ], $histogram, array_keys($histogram));
    }
}
