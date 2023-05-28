<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\HistogramCalculator;
use App\Service\NormalDistributionCalculator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class Lista1Controller extends AbstractController
{
    public function __construct(
        private readonly NormalDistributionCalculator $normalDistributionCalculator,
        private readonly HistogramCalculator $histogramCalculator,
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

        return new JsonResponse([
            'probability' => $probability,
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
}
