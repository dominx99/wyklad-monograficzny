<?php

declare(strict_types=1);

namespace App\Service\TableProvider;

use InvalidArgumentException;

final class KSTestCriticalValuesTable
{
    public const VALUES = [
        4 => [
            '0.01' => 0.417,
            '0.05' => 0.381,
        ],
        5 => [
            '0.01' => 0.405,
            '0.05' => 0.337,
        ],
        6 => [
            '0.01' => 0.364,
            '0.05' => 0.319,
        ],
        7 => [
            '0.01' => 0.348,
            '0.05' => 0.300,
        ],
        8 => [
            '0.01' => 0.331,
            '0.05' => 0.285,
        ],
        9 => [
            '0.01' => 0.311,
            '0.05' => 0.271,
        ],
        10 => [
            '0.01' => 0.294,
            '0.05' => 0.258,
        ],
        11 => [
            '0.01' => 0.284,
            '0.05' => 0.249,
        ],
        12 => [
            '0.01' => 0.275,
            '0.05' => 0.242,
        ],
        13 => [
            '0.01' => 0.268,
            '0.05' => 0.234,
        ],
        14 => [
            '0.01' => 0.261,
            '0.05' => 0.227,
        ],
        15 => [
            '0.01' => 0.257,
            '0.05' => 0.220,
        ],
        16 => [
            '0.01' => 0.250,
            '0.05' => 0.213,
        ],
        17 => [
            '0.01' => 0.245,
            '0.05' => 0.206,
        ],
        18 => [
            '0.01' => 0.239,
            '0.05' => 0.200,
        ],
        19 => [
            '0.01' => 0.235,
            '0.05' => 0.195,
        ],
        20 => [
            '0.01' => 0.231,
            '0.05' => 0.190,
        ],
        25 => [
            '0.01' => 0.203,
            '0.05' => 0.180,
        ],
        30 => [
            '0.01' => 0.187,
            '0.05' => 0.161,
        ],
    ];

    public function getCriticalValue(int $n, float $alpha): float
    {
        return match (true) {
            $n >= 4 && $n < 20 => $this->getFromTable($n, $alpha),
            $n >= 20 && $n <= 25 => $this->getFromTable(20, $alpha),
            $n > 25 && $n <= 30 => $this->getFromTable(25, $alpha),
            $n > 30 => $this->calculateCriticalValue($n, $alpha),
            default => throw new InvalidArgumentException('Zbyt mała próba'),
        };
    }

    private function getFromTable(int $n, float $alpha)
    {
        return self::VALUES[$n][(string)$alpha];
    }

    private function calculateCriticalValue(int $n, float $alpha): float
    {
        return match ($alpha) {
            '0.01' => 1.031 / sqrt($n),
            '0.05' => 0.886 / sqrt($n),
        };
    }
}
