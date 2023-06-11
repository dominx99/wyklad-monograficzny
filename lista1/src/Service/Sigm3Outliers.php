<?php

declare(strict_types=1);

namespace App\Service;

use App\Service\Python\PythonMathAdapter;

final class Sigm3Outliers
{
    public function __construct(private readonly PythonMathAdapter $pythonMathAdapter)
    {
    }

    public function checkUpperOutlier(array $data)
    {
        $max = max($data);

        return $this->checkOutlier($this->filterOutlier($data, $max), $max, $data);
    }

    public function checkLowerOutlier(array $data)
    {
        $min = min($data);

        return $this->checkOutlier($this->filterOutlier($data, $min), $min, $data);
    }

    public function checkOutlier(array $data, float $guessedOutlier, array $prevData): array
    {
        $mean = $this->pythonMathAdapter->mean($data)['mean'];
        $s = $this->pythonMathAdapter->stdev($data)['stdev'];

        $lowerBound = $mean - 3 * $s;
        $upperBound = $mean + 3 * $s;

        return [
            'mean' => $mean,
            's' => $s,
            'outlier' => $guessedOutlier,
            'lowerBound' => $lowerBound,
            'upperBound' => $upperBound,
            'isOutlier' => $guessedOutlier < $lowerBound || $guessedOutlier > $upperBound,
            'data' => $data,
            'prevData' => $prevData,
        ];
    }

    public function filterOutlier(array $data, float $outlier): array
    {
        return array_values(array_filter($data, fn ($item) => $item !== $outlier));
    }
}
