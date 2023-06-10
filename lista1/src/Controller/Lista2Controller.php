<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\EmpiricalDistributionChartProvider;
use App\Service\Helpers;
use App\Service\HistogramCalculator;
use App\Service\NormalDistributionCalculator;
use App\Service\Python\PythonMathAdapter;
use App\Service\TableProvider\KSTestCriticalValuesTable;
use MathPHP\Probability\Distribution\Table\ChiSquared;
use MathPHP\Statistics\Average;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class Lista2Controller extends AbstractController
{
    public function __construct(
        private readonly PythonMathAdapter $pythonMathAdapter,
        private readonly EmpiricalDistributionChartProvider $empiricalDistributionChartProvider,
        private readonly KSTestCriticalValuesTable $ksTestCriticalValuesTable,
        private readonly HistogramCalculator $histogramCalculator,
        private readonly NormalDistributionCalculator $normalDistributionCalculator,
    ) {
    }

    #[Route('/lista2/zadanie1')]
    public function zadanie1(): Response
    {
        return $this->render('lista2/zadanie1.html.twig');
    }

    #[Route('/lista2/zadanie2')]
    public function zadanie2(): Response
    {
        return $this->render('lista2/zadanie2.html.twig');
    }

    #[Route('/lista2/zadanie3')]
    public function zadanie3(): Response
    {
        return $this->render('lista2/zadanie3.html.twig');
    }

    #[Route('/lista2/zadanie4')]
    public function zadanie4(): Response
    {
        return $this->render('lista2/zadanie4.html.twig');
    }

    #[Route('/lista2/zadanie5')]
    public function zadanie5(): Response
    {
        return $this->render('lista2/zadanie5.html.twig');
    }

    #[Route('/lista2/zadanie1/oblicz')]
    public function zadanie1Oblicz(Request $request): JsonResponse
    {
        $body = json_decode($request->getContent(), true);

        $data = array_map(fn ($value) => (float) $value, $body['values']);

        if ($body['excludeDuplicates']) {
            $data = array_unique($data);
        }

        sort($data);
        $alpha = (float) $body['alpha'];

        $result = $this->pythonMathAdapter->kstest($data);
        $criticalValue = $this->ksTestCriticalValuesTable->getCriticalValue(count($data), $alpha);

        $table = $this->render('lista2/zadanie1_table.html.twig', [
            'table' => $result['result'],
            'criticalValue' => $criticalValue,
            'maximum' => $result['maximum'],
        ])->getContent();

        return new JsonResponse([
            'n' => count($result['result']),
            'criticalValue' => $criticalValue,
            'maximum' => $result['maximum'],
            's' => $result['s'],
            's1' => $result['s1'],
            'table' => $table,
            'mean' => $result['mean'],
        ]);
    }

    #[Route('/lista2/zadanie2/oblicz')]
    public function zadanie2Oblicz(Request $request): JsonResponse
    {
        $body = json_decode($request->getContent(), true);

        $data = array_map(fn ($value) => (float) $value, $body['values']);

        $shapiro = $this->pythonMathAdapter->shapiro($data);
        $mean = $this->pythonMathAdapter->mean($data);
        $variance = $this->pythonMathAdapter->variance($data);
        $variance['variance'] *= (count($data) - 1);

        return new JsonResponse([...$shapiro, ...$mean, ...$variance]);
    }

    #[Route('/lista2/zadanie3/oblicz')]
    public function zadanie3Oblicz(Request $request): JsonResponse
    {
        $body = json_decode($request->getContent(), true);

        $data = array_map(fn ($value) => (float) $value, $body['values']);

        $skewTest = $this->pythonMathAdapter->skewtest($data);
        $kurtosisTest = $this->pythonMathAdapter->kurtosistest($data);

        $kurtosis = $this->pythonMathAdapter->kurtosis($data);
        $kurtosis['kurtosis'] += 3;
        $skew = $this->pythonMathAdapter->skew($data);
        $skew['skew'] = abs($skew['skew']);

        return new JsonResponse([
            'skew_test' => $skewTest,
            'kurtosis_test' => $kurtosisTest,
            ...$skew,
            ...$kurtosis,
        ]);
    }

    #[Route('/lista2/zadanie4/oblicz')]
    public function zadanie4Oblicz(Request $request): JsonResponse
    {
        $body = json_decode($request->getContent(), true);

        $mergeBoundaries = $body['mergeBoundaries'];
        $alpha = (float) $body['alpha'];
        $data = array_map(fn ($i) => (float) $i, $body['values']);

        $histogram = $this->histogramCalculator->calculate($data);
        $histogramClone = [...$histogram];

        $pValues = [];

        $n = count($data);
        $mean = $this->pythonMathAdapter->mean($data)['mean'];
        $stdev = $this->pythonMathAdapter->stdev($data)['stdev'];

        if ($mergeBoundaries) {
            $value = $this->normalDistributionCalculator->calculateProbability(
                $mean,
                $stdev,
                'less',
                [
                    $histogramClone[0]['rangeEnd']
                ]
            ) + $this->normalDistributionCalculator->calculateProbability(
                $mean,
                $stdev,
                'more',
                [
                    $histogramClone[array_key_last($histogramClone)]['rangeStart']
                ]
            );
            $pValues[] = [
                'value' => $value,
                'count' => $histogramClone[0]['binCount'] + $histogramClone[array_key_last($histogramClone)]['binCount'],
            ];

            array_pop($histogramClone);
            array_shift($histogramClone);
        }

        foreach ($histogramClone as $key => $value) {
            $value = $this->normalDistributionCalculator->calculateProbability(
                $this->pythonMathAdapter->mean($data)['mean'],
                $this->pythonMathAdapter->stdev($data)['stdev'],
                'between',
                [
                    $value['rangeStart'],
                    $value['rangeEnd'],
                ]
            );
            $pValues[] = [
                'value' => $value,
                'count' => $histogramClone[$key]['binCount'],
            ];
        }

        $stats = array_map(
            function ($pValue) use ($n) {
                return pow(($pValue['count'] - ($pValue['value'] * $n)), 2) / ($pValue['value'] * $n);
            },
            $pValues,
        );

        $sum = array_sum($stats);

        $criticalValue = ChiSquared::getChiSquareValue(count($pValues) - 3, $alpha);

        if ($criticalValue > $sum) {
            $result = 'Wartość statystyki testowej jest <strong>mniejsza</strong> od wartości krytycznej, więc <strong>nie ma podstaw</strong> do odrzucenia hipotezy';
        } else {
            $result = 'Wartość statystyki testowej jest <strong>większa</strong> od wartości krytycznej, więc <strong>należy odrzucić</strong> hipotezę';
        }

        return new JsonResponse([
            'histogram' => $histogram,
            'result_list' => $this->render('partials/result_list.html.twig', [
                'results' => [
                    'n = ' => $n,
                    'średnia = ' => $mean,
                    'S = ' => $stdev,
                    'wartość statystyki testowej = ' . implode(' + ', $stats) . ' = ' => $sum,
                    'wartość krytyczna = ' => $criticalValue,
                    'Wniosek: ' => $result,
                ],
            ])->getContent(),
            'p_value_table' => $this->render('partials/table.html.twig', [
                'headers' => ['P', 'Prawdopodobieństwo'],
                'table' => array_map(fn ($pValue, $key) => [$key + 1, $pValue['value']], $pValues, array_keys($pValues)),
            ])->getContent(),
        ]);
    }

    #[Route('/lista2/zadanie5/oblicz')]
    public function zadanie5Oblicz(Request $request): JsonResponse
    {
        $body = json_decode($request->getContent(), true);
        $data = array_map(fn ($i) => (float) $i, $body['values']);
        $m = (int) $body['m'];

        $averages = Helpers::exponentialMovingAverage($data, $m);
        $medians = Helpers::exponentialMovingMedian($data, $m);

        $averageResiduals = Helpers::calculateAverageResiduals($data, $averages);
        $medianResiduals = Helpers::calculateAverageResiduals($data, $medians);

        return new JsonResponse([
            'data' => $data,
            'm' => $m,
            'averages' => $averages,
            'medians' => $medians,
            'average_residuals' => $averageResiduals,
            'median_residuals' => $medianResiduals,
            'average_table' => $this->render('partials/table.html.twig', [
                'headers' => [
                    'i',
                    ...range(0, count($averages) - 1)
                ],
                'table' => [
                    ['Xi', ...$data],
                    ['Średnia', ...$averages],
                    ['Reszta', ...$averageResiduals],
                ],
            ])->getContent(),
            'median_table' => $this->render('partials/table.html.twig', [
                'headers' => [
                    'i',
                    ...range(0, count($medians) - 1)
                ],
                'table' => [
                    ['Xi', ...$data],
                    ['Me(X)', ...$medians],
                    ['Xi-Me(X)', ...$medianResiduals],
                ],
            ])->getContent(),
        ]);
    }
}
