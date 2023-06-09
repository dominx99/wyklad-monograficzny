<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\DixonTest;
use App\Service\Helpers;
use App\Service\Python\PythonMathAdapter;
use App\Service\Sigm3Outliers;
use App\Service\SignTest;
use App\Service\TurningPointsTest;
use MathPHP\Probability\Distribution\Continuous\Normal;
use MathPHP\Probability\Distribution\Table\ChiSquared;
use MathPHP\Statistics\Average;
use MathPHP\Statistics\Outlier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Helper\Helper;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class Lista3Controller extends AbstractController
{
    public function __construct(
        private readonly PythonMathAdapter $pythonMathAdapter,
        private readonly Sigm3Outliers $sigma3Outliers
    ) {
    }

    #[Route(path: '/lista3/zadanie1', methods: ['GET'])]
    public function zadanie1(): Response
    {
        return $this->render('lista3/zadanie1.html.twig');
    }

    #[Route(path: '/lista3/zadanie2', methods: ['GET'])]
    public function zadanie2(): Response
    {
        return $this->render('lista3/zadanie2.html.twig');
    }
    #
    #[Route(path: '/lista3/zadanie3', methods: ['GET'])]
    public function zadanie3(): Response
    {
        return $this->render('lista3/zadanie3.html.twig');
    }

    #[Route(path: '/lista3/zadanie1/oblicz', name: 'lista3_zadanie1_oblicz')]
    public function zadanie1Oblicz(Request $request): JsonResponse
    {
        $body = json_decode($request->getContent(), true);
        $data = array_map(fn ($item) => (float) $item, $body['values']);
        $alpha = ((float) $body['alpha']);
        $alphaB = 1 - ((float) $body['alpha']);
        $n = count($data);

        [$x1, $x2] = DixonTest::getX1AndX2($data);
        $q = abs($x1 - $x2) / (max($data) - min($data));
        $qCritical = DixonTest::getQCritical($n, $alphaB);

        $message = $q < $qCritical
            ? 'Nie ma podstaw do odrzucenia hipotezy zerowej'
            : 'Hipoteza zerowa została odrzucona';

        $g = Outlier::grubbsCriticalValue($alpha / 2, $n, 'one');
        $gCriticalValue = Outlier::grubbsStatistic($data, 'two');
        $gMessage = $gCriticalValue < $g
            ? 'Nie ma podstaw do odrzucenia hipotezy zerowej'
            : 'Hipoteza zerowa została odrzucona';

        return new JsonResponse([
            'dixon_result' => $this->render('partials/result_list.html.twig', [
                'results' => [
                    'x1 = ' => $x1,
                    'x2 = ' => $x2,
                    'Q = ' => $q,
                    "Q({$alpha}, {$n}) = " => $qCritical,
                    'Wniosek: ' => $message,
                ]
            ])->getContent(),
            'grubbs_result' => $this->render('partials/result_list.html.twig', [
                'results' => [
                    'G = ' => $gCriticalValue,
                    "wartość krytyczna = " => $g,
                    'Wniosek: ' => $gMessage,
                ]
            ])->getContent(),
        ]);
    }

    #[Route(path: '/lista3/zadanie2/oblicz')]
    public function zadanie2Oblicz(Request $request): JsonResponse
    {
        $body = json_decode($request->getContent(), true);
        $data = array_map(fn ($item) => (float) $item, $body['values']);

        sort($data);

        $results = [];

        $isLowerOutlier = false;
        $isUpperOutlier = false;

        do {
            $lowerOutlier = $this->sigma3Outliers->checkLowerOutlier($data);
            $upperOutlier = $this->sigma3Outliers->checkUpperOutlier($data);
            $results[] = $lowerOutlier;
            $results[] = $upperOutlier;
            $isLowerOutlier = $lowerOutlier['isOutlier'];
            $isUpperOutlier = $upperOutlier['isOutlier'];

            if ($lowerOutlier['isOutlier']) {
                $data = $this->sigma3Outliers->filterOutlier($data, $lowerOutlier['outlier']);
            }

            if ($upperOutlier['isOutlier']) {
                $data = $this->sigma3Outliers->filterOutlier($data, $upperOutlier['outlier']);
            }
        } while ($isLowerOutlier || $isUpperOutlier);

        return new JsonResponse([
            'result' => $this->render('partials/sigma3_result.html.twig', [
                'data' => $data,
                'results' => $results,
            ])->getContent(),
        ]);
    }

    #[Route(path: '/lista3/zadanie3/oblicz')]
    public function zadanie3Oblicz(Request $request): JsonResponse
    {
        $body = json_decode($request->getContent(), true);
        $data = array_map(fn ($item) => (float) $item, $body['values']);
        $alpha = ((float) $body['alpha']);

        [$n, $ms, $sigma, $s, $result, $criticalValue, $message] = SignTest::calculate($data, $alpha);
        $signTestResult = $this->render('partials/result_list.html.twig', [
            'results' => [
                'n = ' => $n,
                'S = ' => $s,
                'm<sub>s</sub> = ' => $ms,
                'σ<sub>S</sub> = ' => $sigma,
                '|S - m<sub>s</sub>| / σ<sub>s</sub> = ' => $result,
                'Wartość krytyczna = ' => $criticalValue,
                'Wniosek: ' => $message,
            ]
        ])->getContent();

        [$n, $mt, $sigma, $t, $result, $criticalValue, $message] = TurningPointsTest::calculate($data, $alpha);

        return new JsonResponse([
            'sign_result' => $signTestResult,
            'turning_points_result' => $this->render('partials/result_list.html.twig', [
                'results' => [
                    'n = ' => $n,
                    'T = ' => $t,
                    'm<sub>T</sub> = ' => $mt,
                    'σ<sub>T</sub> = ' => $sigma,
                    '|T - m<sub>s</sub>| / σ<sub>s</sub> = ' => $result,
                    'Wartość krytyczna = ' => $criticalValue,
                    'Wniosek: ' => $message,
                ]
            ])->getContent(),
        ]);
    }
}
