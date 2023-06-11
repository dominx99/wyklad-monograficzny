<?php

function dixon_critical_values($alpha) {
    // Tablica rozmiarów prób
    $n_values = range(3, 20);

    // Tablica wartości krytycznych dla poszczególnych rozmiarów prób
    $critical_values = [];

    foreach ($n_values as $n) {
        $critical_value = t_distribution_quantile(1 - $alpha / (2 * $n), $n - 2);
        $critical_values[$n] = [
            "0.90" => $critical_value,
            "0.95" => $critical_value,
            "0.99" => $critical_value,
        ];
    }

    return $critical_values;
}

// Funkcja obliczająca wartość kwantyla dla rozkładu t-Studenta
function t_distribution_quantile($p, $df) {
    $quantile = 0;
    $step = 0.0001;
    $x = -5;

    while ($quantile < $p) {
        $quantile = t_distribution_cdf($x, $df);
        $x += $step;
    }

    return $x;
}

// Funkcja obliczająca dystrybuantę dla rozkładu t-Studenta
function t_distribution_cdf($x, $df) {
    $x = $x / sqrt(($df * pow($x, 2)) + $df);
    $a = $df / 2;
    $b = 1 / 2;

    return (1 / beta_function($a, $b)) * integral(0, $x, $a, $b);
}

// Funkcja obliczająca wartość funkcji beta
function beta_function($a, $b) {
    return (gamma_function($a) * gamma_function($b)) / gamma_function($a + $b);
}

// Funkcja obliczająca wartość funkcji gamma
function gamma_function($x) {
    if ($x == 1 || $x == 2) {
        return 1;
    }

    return ($x - 1) * gamma_function($x - 1);
}

// Funkcja obliczająca wartość całki numerycznej metodą Simpsona
function integral($a, $b, $alpha, $beta) {
    $n = 1000;
    $h = ($b - $a) / $n;
    $sum = 0;

    for ($i = 0; $i <= $n; $i++) {
        $x = $a + $i * $h;
        $sum += function_to_integrate($x, $alpha, $beta) * weight($i, $n);
    }

    return ($h / 3) * $sum;
}

// Funkcja do całkowania
function function_to_integrate($x, $alpha, $beta) {
    return pow($x, $alpha - 1) * pow(1 - $x, $beta - 1);
}

// Funkcja obliczająca wagę dla metody Simpsona
function weight($i, $n) {
    if ($i == 0 || $i == $n) {
        return 1;
    } elseif ($i % 2 == 0) {
        return 2;
    } else {
        return 4;
    }
}

// Poziom istotności
$alpha = 0.05;

// Generowanie tablicy wartości krytycznych dla testu Dixona
$dixon_values = dixon_critical_values($alpha);

// Wyświetlanie tablicy wartości krytycznych
echo "[\n";
foreach ($dixon_values as $n => $values) {
    echo "    $n => [\n";
    foreach ($values as $level => $value) {
        echo "        \"$level\" => $value,\n";
    }
    echo "    ],\n";
}
echo "]\n";

?>
