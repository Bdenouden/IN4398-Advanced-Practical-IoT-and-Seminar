<div class="container-fluid pt-3">
    <div class="row">
        <div class="col-md-6 offset-md-3">

            <h1 class="text-center">Sensor Data</h1>

            <canvas id="sensorDataChart"></canvas>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"
        integrity="sha256-R4pqcOYV8lt7snxMQO/HSbVCFRPMdrhAFMH+vr9giYI=" crossorigin="anonymous"></script>

<?php

$unique_sensors = [];

for ($i = 0; $i < count($sensor_data); $i++) {
    if (array_key_exists($sensor_data[$i]["node_id"], $unique_sensors)) {
        if (!in_array($sensor_data[$i]["type"], $unique_sensors[$sensor_data[$i]["node_id"]])) {
            $unique_sensors[$sensor_data[$i]["node_id"]][] = $sensor_data[$i]["type"];
        }
    } else {
        $unique_sensors[$sensor_data[$i]["node_id"]] = [$sensor_data[$i]["type"]];
    }
}

$unique_sensor_count = 0;

foreach ($unique_sensors as $sensor) {
    $unique_sensor_count += count($sensor);
}

$date = new DateTime('now');
$date->modify('-2 hours')->modify('-10 minutes');
$timestamps = [];

for ($i = 0; $i < 13; $i++) {
    $date->modify('+10 minute');
    $timestamps[] = clone $date;
}

$js_timestamps = "[";
foreach ($timestamps as $timestamp) {
    $js_timestamps .= "'" . $timestamp->format("H:i") . "',";
}
$js_timestamps = substr($js_timestamps, 0, -1) . "]";

$date_parse = function ($value) {
    return date_parse($value);
};

function inTimeBracket($timestamp_array, $index, $minute_index)
{
    $in_bracket = false;
    if (floor($timestamp_array[$index]->format("i") / 6) == $minute_index) {
        $in_bracket = true;
    }
    return $in_bracket;
}

?>

<script type="text/javascript">

    const chartConfig = {
        type: 'line',
        data: {
            labels: <?= $js_timestamps ?>,
            datasets: [
                <?php
                $units = [];
                for ($i = 0; $i < count($sensor_data); $i++) {
                $unit = $sensor_data[$i]["unit"];
                if ($unit !== "") {
                    $label = "[" . $sensor_data[$i]["node_id"] . "] " . $sensor_data[$i]["type"] . " (" . $unit . ")";
                } else {
                    $label = "[" . $sensor_data[$i]["node_id"] . "] " . $sensor_data[$i]["type"];
                }
                if (!in_array($unit, $units)) {
                    $units[] = $unit;
                }
                ?>
                {
                    label: "<?= $label ?>",
                    <?php
                    $data_string = 'data: [';

                    for ($j = 0; $j < 13; $j++) {
                        $added = false;
                        foreach ($sensor_data as $data) {
                            if ($timestamps[$j]->format("H") == $data["hour"] && inTimeBracket($timestamps, $j, $data["minute_window_id"]) && $data["type"] == $sensor_data[$i]["type"] && $data["node_id"] == $sensor_data[$i]["node_id"]) {
                                $data_string .= $data['value'] . ",";
                                $added = true;
                            }
                        }
                        if (!$added) {
                            $data_string .= ",";
                        }
                    }

                    $data_string = rtrim($data_string, ", ") . "], fill: false,";

                    echo $data_string;

                    if ($i == $unique_sensor_count - 1) {
                        echo "}";
                        break;
                    }
                    ?>
                },
                <?php
                } ?>
            ],
        },
        options: {
            responsive: true,
            title: {
                display: true,
                text: 'click a sensor to hide its data'
            },
            hover: {
                mode: 'point'
            },
            scales: {
                xAxes: [{
                    display: true,
                    scaleLabel: {
                        show: true,
                    },
                }],
                yAxes: [{
                    display: true,
                    scaleLabel: {
                        show: true,
                        labelString: 'Value'
                    },
                }]
            }
        }
    };

    $.each(chartConfig.data.datasets, function (i, dataset) {
        const color = hexToRgb(intToRGB(hashCode(dataset.label)));
        dataset.borderColor = 'rgba(' + color.r + ',' + color.g + ',' + color.b + ', 0.4)';
        dataset.backgroundColor = 'rgba(' + color.r + ',' + color.g + ',' + color.b + ', 0.5)';
        dataset.pointBorderColor = 'rgba(' + color.r + ',' + color.g + ',' + color.b + ', 0.7)';
        dataset.pointBackgroundColor = 'rgba(' + color.r + ',' + color.g + ',' + color.b + ', 0.5)';
        dataset.pointBorderWidth = 1;
    });

    function hashCode(str) { // java String#hashCode
        let hash = 0;
        for (let i = 0; i < str.length; i++) {
            hash = str.charCodeAt(i) + ((hash << 5) - hash);
        }
        return hash;
    }

    function intToRGB(i) {
        const c = (i & 0x00FFFFFF)
            .toString(16)
            .toUpperCase();

        return "00000".substring(0, 6 - c.length) + c;
    }

    function hexToRgb(hex) {
        const result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
        return result ? {
            r: parseInt(result[1], 16),
            g: parseInt(result[2], 16),
            b: parseInt(result[3], 16)
        } : null;
    }

    window.onload = function () {
        const ctx = document.getElementById("sensorDataChart").getContext("2d");
        window.myLine = new Chart(ctx, chartConfig);
    };
</script>
