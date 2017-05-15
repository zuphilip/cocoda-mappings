<?php

include '../vendor/autoload.php';

use Davaxi\Sparkline;

$data = [];

foreach (file('statlog.csv') as $line) {
    list($date,$count,$prop) = explode(',',trim($line));
    $data[$prop][$date] = $count;
}

foreach (file('total.csv') as $line) {
    list($date,$count) = explode(',',trim($line));
    $data['total'][$date] = log($count);
}

# make sure all properties have same dates
foreach ($data as $prop => $counts) {
    foreach ($counts as $date => $value) {
        $dates[$date] = $date;
        @$data['kos'][$date]++;
    }
}

foreach ($data as $prop => $counts) {
    foreach ($dates as $date) {
        if (!isset($counts[$date])) {
            $counts[$date] = 0;
        }
    }
    ksort($counts);
    $data[$prop] = $counts; 
} 

foreach ($data as $prop => $counts) {
    $sparkline = new Sparkline();
    $min = min($counts);
    $max = max($counts);
    if ($min == $max and $min > 0) {
        $min = 0;
    }
    $counts = array_map(
        function($count) use ($min) { return $count-$min; }, 
        $counts
    );
    $sparkline->setData($counts);

    $sparkline->save("$prop.png");
    $sparkline->destroy();
}
