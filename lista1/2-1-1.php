<?php
// Przykładowa próba
$data = array(50.4, 52.5, 54.6, 55.1, 55.3);

// Wyliczenie kwantyli dla próby
$quantiles = array();
for ($i = 1; $i <= 99; $i++) {
    $quantiles[] = $i / 100;
}
$sample_quantiles = array_map(function ($quantile) use ($data) {
    return quantile($data, $quantile);
}, $quantiles);

// Wyliczenie kwantyli dla rozkładu normalnego
$normal_quantiles = array_map(function ($quantile) {
    return stats_dens_normal_quantile($quantile);
}, $quantiles);

// Funkcja obliczająca kwantyl
function quantile($data, $quantile) {
    sort($data);
    $index = ($quantile * (count($data) - 1)) + 1;
    $floor = floor($index);
    $ceil = ceil($index);
    if ($floor == $ceil) {
        return $data[$floor - 1];
    }
    $lower = $data[$floor - 1] * ($ceil - $index);
    $upper = $data[$ceil - 1] * ($index - $floor);
    return $lower + $upper;
}

// Funkcja obliczająca kwantyl dla rozkładu normalnego
function stats_dens_normal_quantile($p) {
    return stats_dens_normal_pct($p);
}

// Wyświetlenie kwantyli dla próby
echo "Kwantyle próby:\n";
foreach ($sample_quantiles as $quantile) {
    echo $quantile . "\n";
}

// Wyświetlenie kwantyli dla rozkładu normalnego
echo "Kwantyle rozkładu normalnego:\n";
foreach ($normal_quantiles as $quantile) {
    echo $quantile . "\n";
}
?>
