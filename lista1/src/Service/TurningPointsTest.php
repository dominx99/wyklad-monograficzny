<?php

declare(strict_types=1);

namespace App\Service;

use App\Service\Python\PythonMathAdapter;
use MathPHP\Probability\Distribution\Continuous\Normal;

final class TurningPointsTest
{
    public function __construct(private readonly PythonMathAdapter $pythonMathAdapter)
    {
    }

    public static function getT(array $data): int
    {
        $t = 0;

        for ($i = 1; $i < count($data) - 1; $i++) {
            if (($data[$i - 1] < $data[$i] && $data[$i] > $data[$i + 1]) || ($data[$i - 1] > $data[$i] && $data[$i] < $data[$i + 1])) {
                $t++;
            }
        }

        return $t;
    }

    public static function calculate(array $data, float $alpha): array
    {
        $n = count($data);
        $mt = 2 * ($n - 2) / 3;
        $t = TurningPointsTest::getT($data);
        $sigma = sqrt((16 * $n - 29) / 90);

        $result = abs($t - $mt) / $sigma;
        $alpha = $alpha;
        $normal = new Normal(0, 1);
        $criticalValue = $normal->inverse(1 - $alpha / 2);

        if ($result < $criticalValue) {
            $message = "Ponieważ {$result} < {$criticalValue} to nie ma podstaw do odrzucenia hipotezy zerowej. Stąd wynika, że sąsiednie pomiary z dużym prawdopodobieństwem są niezależne.";
        } else {
            $message = "Ponieważ {$result} > {$criticalValue} to należy odrzucić hipotezę zerową. Stąd wynika, że sąsiednie pomiary z dużym prawdopodobieństwem są skorelowane.";
        }

        return [$n, $mt, $sigma, $t, $result, $criticalValue, $message];
    }
}
