<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\DixonTest;
use App\Service\Helpers;
use App\Service\Python\PythonMathAdapter;
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
    public function __construct(private readonly PythonMathAdapter $pythonMathAdapter)
    {
    }

    #[Route(path: '/lista3/zadanie1', name: 'lista3_zadanie1', methods: ['GET'])]
    public function zadanie1(): Response
    {
        return $this->render('lista3/zadanie1.html.twig');
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

        $g = Outlier::grubbsCriticalValue($alpha, $n, 'one');
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
}
