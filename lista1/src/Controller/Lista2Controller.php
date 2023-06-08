<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\EmpiricalDistributionChartProvider;
use App\Service\Python\PythonMathAdapter;
use App\Service\TableProvider\KSTestCriticalValuesTable;
use MathPHP\Probability\Distribution\Continuous\ChiSquared;
use MathPHP\Probability\Distribution\Continuous\Normal;
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

    #[Route('/lista2/zadanie1/oblicz')]
    public function zadanie1Oblicz(Request $request): JsonResponse
    {
        $body = json_decode($request->getContent(), true);

        $data = array_map(fn ($value) => (float) $value, $body['values']);
        $alpha = (float) $body['alpha'];

        $result = $this->pythonMathAdapter->kstest($data);
        $criticalValue = $this->ksTestCriticalValuesTable->getCriticalValue(count($data), $alpha);

        $table = $this->render('lista2/zadanie1_table.html.twig', [
            'table' => $result['result'],
            'criticalValue' => $criticalValue,
            'maximum' => $result['maximum'],
        ])->getContent();

        return new JsonResponse([
            'n' => count($data),
            'criticalValue' => $criticalValue,
            'maximum' => $result['maximum'],
            'table' => $table,
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
    }

    #[Route('/lista2/zadanie4/oblicz')]
    public function zadanie4Oblicz(Request $request): JsonResponse
    {
    }
}
