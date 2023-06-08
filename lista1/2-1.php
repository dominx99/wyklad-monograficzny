<?php

// Próbka danych
$data = [11.1, 5.4, 18.0, 7.8, 15.2, 13.5, 15.2, 20.0, 7.8];

// Wykonanie testu Kołmogorowa-Lillieforsa
$result = kstest($data, 'norm');

// Wyświetlenie wyników
echo "Wartość statystyki testowej: " . $result[0] . "\n";
echo "Wartość p-value: " . $result[1] . "\n";

// Funkcja wykonująca test Kołmogorowa-Lillieforsa
function kstest($data, $distribution) {
    $n = count($data);
    
    // Sortowanie danych
    sort($data);
    
    // Obliczanie dystrybuanty empirycznej
    $empirical_cdf = array();
    for ($i = 0; $i < $n; $i++) {
        $empirical_cdf[$data[$i]] = ($i + 1) / $n;
    }
    
    // Obliczanie wartości testowej statystyki K-L
    $ks_statistic = 0.0;
    foreach ($data as $x) {
        $cdf_value = $empirical_cdf[$x];
        $ks_statistic = max($ks_statistic, abs($cdf_value - cdf_normal($x)));
    }
    
    // Obliczanie p-value
    $ks_critical = 1.35810 / sqrt($n);
    $p_value = 1.0 - exp(-2.0 * pow(($ks_statistic * sqrt($n)), 2));
    
    // Zwracanie wyników
    return [$ks_statistic, $p_value];
}

// Funkcja obliczająca dystrybuantę dla rozkładu normalnego
function cdf_normal($x) {
    return 0.5 * (1 + erf(($x - 0) / (sqrt(2) * 1)));
}

// Funkcja obliczająca funkcję błędu
function erf($x) {
    $pi = 3.141592653589793238;
    $a1 =  0.254829592;
    $a2 = -0.284496736;
    $a3 =  1.421413741;
    $a4 = -1.453152027;
    $a5 =  1.061405429;
    $p  =  0.3275911;

    $sign = ($x >= 0) ? 1 : -1;
    $x = abs($x);

    $t = 1.0 / (1.0 + $p * $x);
    $y = ((((($a5 * $t + $a4) * $t) + $a3) * $t + $a2) * $t + $a1) * $t;
    $result = 1.0 - ($y * exp(-$x * $x));
    
    return $sign * $result;
}

?>
