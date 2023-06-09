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

        $mean = $this->pythonMathAdapter->mean($data);

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
            ...$mean,
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
    }
}
