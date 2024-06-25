<?php

// Function to format bytes
function formatBytes($size, $decimals = 0)
{
    $unit = array(
        '0' => 'Byte',
        '1' => 'KiB',
        '2' => 'MiB',
        '3' => 'GiB',
        '4' => 'TiB',
        '5' => 'PiB',
        '6' => 'EiB',
        '7' => 'ZiB',
        '8' => 'YiB'
    );

    for ($i = 0; $size >= 1024 && $i <= count($unit); $i++) {
        $size = $size / 1024;
    }

    return round($size, $decimals) . ' ' . $unit[$i];
}

// Function to format bytes2
function formatBytes2($size, $decimals = 0)
{
    $unit = array(
        '0' => 'Byte',
        '1' => 'KB',
        '2' => 'MB',
        '3' => 'GB',
        '4' => 'TB',
        '5' => 'PB',
        '6' => 'EB',
        '7' => 'ZB',
        '8' => 'YB'
    );

    for ($i = 0; $size >= 1000 && $i <= count($unit); $i++) {
        $size = $size / 1000;
    }

    return round($size, $decimals) . '' . $unit[$i];
}

// Function to format bites
function formatBites($size, $decimals = 0)
{
    $unit = array(
        '0' => 'bps',
        '1' => 'kbps',
        '2' => 'Mbps',
        '3' => 'Gbps',
        '4' => 'Tbps',
        '5' => 'Pbps',
        '6' => 'Ebps',
        '7' => 'Zbps',
        '8' => 'Ybps'
    );

    for ($i = 0; $size >= 1000 && $i <= count($unit); $i++) {
        $size = $size / 1000;
    }

    return round($size, $decimals) . ' ' . $unit[$i];
}

function formatUptime($uptime)
{
    preg_match_all('/(\d+)([wdhms])/', $uptime, $matches);

    $formattedUptime = '';

    if (isset($matches[0]) && count($matches[0]) > 0) {
        $times = [
            'w' => '%dw',
            'd' => '%dd',
            'h' => '%02d',
            'm' => '%02d',
            's' => '%02d',
        ];

        $formattedUptimeParts = [];

        foreach ($matches[1] as $index => $value) {
            $formattedUptimeParts[] = sprintf($times[$matches[2][$index]], $value);
        }

        // Conditionally join parts based on presence of weeks and days
        if (in_array('w', $matches[2])) {
            $formattedUptime = implode(' ', $formattedUptimeParts);
        } elseif (in_array('d', $matches[2])) {
            $formattedUptime = implode(' ', array_slice($formattedUptimeParts, 1));
        } else {
            $formattedUptime = implode(' ', array_slice($formattedUptimeParts, 2));
        }
    }

    return $formattedUptime;
}

function randomColor()
{
    $colors = [
        'bg-purple-100', 'bg-purple-100', 'bg-pink-100',
        'bg-red-100', 'bg-yellow-100', 'bg-green-100', 'bg-orange-100',
        'bg-cyan-100', 'bg-emerald-100', 'bg-sky-100'
    ];

    return $colors;
}
