<?php

declare(strict_types=1);

namespace App\Service\Python;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

final class PythonMathAdapter
{
    private const SHAPIRO = 'src/resources/python/math/shapiro.py';
    private const MEAN = 'src/resources/python/math/mean.py';
    private const VARIANCE = 'src/resources/python/math/variance.py';
    private const KSTEST = 'src/resources/python/math/kstest.py';

    public function __construct(private readonly string $projectDir)
    {
    }

    public function shapiro(array $data): array
    {
        return $this->execute(self::SHAPIRO, json_encode($data));
    }

    public function mean(array $data): array
    {
        return $this->execute(self::MEAN, json_encode($data));
    }

    public function variance(array $data): array
    {
        return $this->execute(self::VARIANCE, json_encode($data));
    }

    public function kstest(array $data): array
    {
        return $this->execute(self::KSTEST, json_encode($data));
    }

    private function execute(string $path, string $json): array
    {
        $process = new Process(['python3', $this->file($path), $json]);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $output = $process->getOutput();
        $result = json_decode($output, true);

        return $result;
    }

    private function file(string $path): string
    {
        return sprintf('%s/%s', $this->projectDir, $path);
    }
}
