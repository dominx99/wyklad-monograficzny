<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\EmpiricalDistributionChartProvider;
use App\Service\EmpiricalStandardDeviationCalculator;
use App\Service\HistogramCalculator;
use App\Service\NormalDistributionCalculator;
use App\Service\NormalDistributionChartProvider;
use App\Service\UniformDistributionChartProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class Lista1Controller extends AbstractController
{
    public function __construct(
        private readonly NormalDistributionCalculator $normalDistributionCalculator,
        private readonly NormalDistributionChartProvider $normalDistributionChartProvider,
        private readonly HistogramCalculator $histogramCalculator,
        private readonly EmpiricalDistributionChartProvider $empiricalDistributionChartProvider,
        private readonly UniformDistributionChartProvider $uniformDistributionChartProvider,
        private readonly EmpiricalStandardDeviationCalculator $empiricalStandardDeviationCalculator,
    ) {
    }

    #[Route('/lista1/zadanie1')]
    public function zadanie1(): Response
    {
        return $this->render('lista1/zadanie1.html.twig');
    }

    #[Route('/lista1/zadanie2')]
    public function zadanie2(): Response
    {
        return $this->render('lista1/zadanie2.html.twig');
    }

    #[Route('/lista1/zadanie3')]
    public function zadanie3(): Response
    {
        return $this->render('lista1/zadanie3.html.twig');
    }

    #[Route('/lista1/zadanie4')]
    public function zadanie4(): Response
    {
        return $this->render('lista1/zadanie4.html.twig');
    }

    #[Route('/lista1/zadanie1/oblicz')]
    public function zadanie1Oblicz(Request $request): JsonResponse
    {
        $body = json_decode($request->getContent(), true);

        $values = array_map(fn ($value) => (float) $value, $body['values']);

        $probability = $this->normalDistributionCalculator->calculateProbability(
            (float) $body['mean'],
            (float) $body['standardDeviation'],
            $body['operator'],
            $values,
        );

        $metadata = $this->normalDistributionChartProvider->provide(
            (float) $body['mean'],
            (float) $body['standardDeviation'],
            $body['operator'],
            0.1,
            $values,
        );

        return new JsonResponse([
            'probability' => $probability,
            'meta' => $metadata,
        ]);
    }

    #[Route('/lista1/zadanie2/oblicz')]
    public function zadanie2Oblicz(Request $request): JsonResponse
    {
        $body = json_decode($request->getContent(), true);

        $values = array_map(fn ($value) => (float) $value, $body['values']);

        $histogram = $this->histogramCalculator->calculate($values);

        return new JsonResponse([
            'histogram' => $histogram,
        ]);
    }

    #[Route('/lista1/zadanie3/oblicz')]
    public function zadanie3Oblicz(Request $request): JsonResponse
    {
        $values = array_map(fn ($value) => (int) $value, json_decode($request->getContent(), true)['values']);

        [$empiricalDistribution, $minDistance] = $this->empiricalDistributionChartProvider->getExtendedChart($values);
        $uniformDistribution = $this->uniformDistributionChartProvider->getExtendedChart(min($values), max($values), $minDistance);
        $odlegloscKolmogorowa = $this->empiricalDistributionChartProvider->calculateKolmogorovDistance($values);

        return new JsonResponse([
            'empirical_distribution' => $empiricalDistribution,
            'uniform_distribution' => $uniformDistribution,
            'komlogorov_distance' => $odlegloscKolmogorowa,
        ]);
    }

    #[Route('/lista1/zadanie4/oblicz')]
    public function zadanie4Oblicz(Request $request): JsonResponse
    {
        $content = json_decode($request->getContent(), true);
        $values = array_map(fn ($value) => (float) $value, $content['values']);
        $data = $this->empiricalStandardDeviationCalculator->calculate($values);

        return new JsonResponse($data);
    }
}
