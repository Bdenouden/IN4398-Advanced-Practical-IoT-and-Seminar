<div class="container-fluid pt-3">
    <div class="row">
        <div class="col-md-6">

            <h1 class="text-center">Add new module</h1>
            <p class="text-justify">
                To add a new module, ensure you have configured it already to be connected to your WiFi.
            </p>

        </div>
        <div class="col-md-6">

            <h1 class="text-center">Sensor Data</h1>

            <canvas id="sensorDataChart"></canvas>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"
        integrity="sha256-R4pqcOYV8lt7snxMQO/HSbVCFRPMdrhAFMH+vr9giYI=" crossorigin="anonymous"></script>


<script type="text/javascript">
    const chartConfig = {
        type: 'line',
        data: {
            datasets: [
                <?php for ($i = 0; $i < count($sensor_data); $i++) {
                ?>
                {
                label: "<?= $sensor_data[$i]["sensor_id"]; ?>",
                <?php
                    $data_array = explode(", ", $sensor_data[$i]["value"]);

                    $data_string = 'data: [';

                    foreach ($data_array as $data_val){
                        $data_string .= $data_val . ", ";
                    }

                    $data_string = rtrim($data_string, ", ") . "], fill: false,";

                    echo $data_string;

                ?>
                },
                <?php
                } ?>
            ]
        },
        options: {
            responsive: true,
            title: {
                display: true,
                text: 'click a sensor to hide its data'
            },
            hover: {
                mode: 'dataset'
            },
            scales: {
                xAxes: [{
                    display: true,
                    scaleLabel: {
                        show: true,
                    }
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