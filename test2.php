<?php


function one($k, $n)
{
    $bottom = $k * pow(($n + 1), (2 * $k));
    return 1 / $bottom;
}

function row($row, $n)
{
    $sum = 0;
    for ($i = 1; $i <= $n; $i++) {
        $sum = one($row, $i);
    }
    return $sum;
}

function all($k, $n)
{
    $sum = 0;
    for ($i = 1; $i <= $k; $i++) {
        $sum += row($i, $n);
    }
    return $sum;
}

// echo one(11,10);

// echo row(2, 5);

echo all(1, 3);
