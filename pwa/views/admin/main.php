<div class="container-fluid pt-3">
    <div class="row">
        <div class="col-md-6">

            <h1 class="text-center">Link modules to your node</h1>
            <p class="text-justify">
                To add modules to a new node, ensure you have configured the node already to be connected to your WiFi.
                It will then automatically show up here to allow you to assign which modules are connected to it.
            </p>

            <?php
            foreach ($nodes as $node_id => $node_data) {
                ?>
                <h4>Node: <?= $node_id ?></h4>
                <table class="table table-responsive-md" id="table_<?= $node_id ?>">
                    <tr>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Raw Minimum Value</th>
                        <th>Raw Maximum Value</th>
                        <th>Real Minimum Value</th>
                        <th>Real Maximum Value</th>
                        <th></th>
                    </tr>
                    <?php
                    if (is_array($node_data["sensors"]) && count($node_data["sensors"]) > 1) {
                        foreach ($node_data["sensors"] as $sensor) {
                            ?>
                            <tr id="row_<?= $node_id . '_' . $sensor["link_id"] ?>">
                                <form>
                                    <td><?= $sensor["name"] ?></td>
                                    <td><?= $sensor["type"] ?></td>
                                    <td><?= $sensor["rawMinVal"] ?></td>
                                    <td><?= $sensor["rawMaxVal"] ?></td>
                                    <td><?= $sensor["minVal"] ?></td>
                                    <td><?= $sensor["maxVal"] ?></td>
                                    <td><button class="btn btn-dark" onclick="return removeSensorRow(this)">Remove</button></td>
                                </form>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                   
                </table>
                <button class="btn btn-dark" onclick="addNewRowTo(<?= $node_id ?>)">Add new sensor</button>
                <button class="btn btn-dark" onclick="saveNodeData(<?= $node_id ?>)">Save newly added sensors</button>
                <?php
            }
            ?>




        </div>
        <div class="col-md-5 offset-md-1">

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

    const removeButton = "<button class='btn btn-dark' onclick='removeElementById()'>Remove</button>";

    let sensorNameDropdown = "<select class='form-control' onChange='initializeRowForId(this)'>";
    <?php
        foreach ($sensor_types as $sensor_type){
            ?>
            sensorNameDropdown += "<option value='<?= $sensor_type["id"] ?>'><?= $sensor_type["name"] ?></option>";
    <?php
        }
    ?>

    sensorNameDropdown += "</select>";

    let count = 0;

    function removeSensorRow(element){
        const toDelete = confirm("Are you sure you want to delete this sensor?");

        if (toDelete === true) {
            element = element.parentNode.parentNode;

            const sensorId = element.id.split("_")[2];

            $.ajax({
                method: "POST",
                url: "/admin",
                data: {
                    AJAX: 1,
                    ACTION: 'removeSensorFromNode',
                    sensorId: parseInt(sensorId),
                    'csrf-token': '<?php echo $_SESSION['csrf-token']?>'
                }
            })
            .done(function(result) {
                const data = $.parseJSON(result);
                console.log(data);
            });

            element.parentNode.removeChild(element);
        }
        return false;
    }

    function addNewRowTo(tableName) {
        const table = document.getElementById("table_" + tableName);
        const row = table.insertRow();

        row.id = "row_" + count;

        const nameCell = row.insertCell();
        const typeCell = row.insertCell();
        const rawMinCell = row.insertCell();
        const rawMaxCell = row.insertCell();
        const minCell = row.insertCell();
        const maxCell = row.insertCell();
        const removeCell = row.insertCell();

        nameCell.innerHTML = sensorNameDropdown;

        removeCell.innerHTML = "<button class='btn btn-dark' onclick='return removeSensorRow(this)'>Remove</button>";

        initializeRowForId("row_" + count);

        count++;
    }

    function initializeRowForId(rowId) {

        let row;

        if (typeof rowId !== "string"){
            row = document.getElementById(rowId.parentNode.parentNode.id);
        }
        else {
            row = document.getElementById(rowId);
        }

        const selectedSensorId = row.firstChild.firstChild.value;

        $.ajax({
            method: "POST",
            url: "/admin",
            data: {
                AJAX: 1,
                ACTION: 'getSensorDataForId',
                sensorId: parseInt(selectedSensorId),
                'csrf-token': '<?php echo $_SESSION['csrf-token']?>'
            }
        })
            .done(function (result) {

                const data = $.parseJSON(result)[0];

                row.firstChild.nextSibling.innerHTML = data.type;
                row.firstChild.nextSibling.nextSibling.innerHTML = data.rawMinVal;
                row.firstChild.nextSibling.nextSibling.nextSibling.innerHTML = data.rawMaxVal;
                row.firstChild.nextSibling.nextSibling.nextSibling.nextSibling.innerHTML = data.minVal;
                row.firstChild.nextSibling.nextSibling.nextSibling.nextSibling.nextSibling.innerHTML = data.maxVal;
            });
    }

    function saveNodeData(tableId) {
        const table = $("#table_" + tableId).find("tr");
        for (let i = 0; i < table.length; i++) {
            if ((table[i].id.match(new RegExp("_", "g")) || []).length === 1) {
                const sensorIdToAdd = table[i].firstChild.firstChild.value;
                const nodeIdToAdd = table[i].parentNode.parentNode.id.split("_")[1];

                $.ajax({
                    method: "POST",
                    url: "/admin",
                    data: {
                        AJAX: 1,
                        ACTION: 'addSensorToNode',
                        sensorId: parseInt(sensorIdToAdd),
                        nodeId: nodeIdToAdd,
                        'csrf-token': '<?php echo $_SESSION['csrf-token']?>'
                    }
                })
                    .done(function (result) {

                        const data = $.parseJSON(result);

                        if (result === "true") {
                            window.location.reload();
                        } else {
                            alert("Something went wrong with saving!");
                        }
                    });
            }
        }
    }


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