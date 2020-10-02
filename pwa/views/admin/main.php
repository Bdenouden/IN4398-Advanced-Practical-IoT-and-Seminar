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
                <button class="btn btn-dark" onclick="addNewSensorRowTo(<?= $node_id ?>)">Add new sensor</button>
                <button class="btn btn-dark" onclick="saveNodeData(<?= $node_id ?>)">Save newly added sensors</button>
                <?php
            }
            ?>

        </div>
        <div class="col-md-6">

            <h1 class="text-center">Create Triggers</h1>
            <p class="text-justify">
                Here you can determine when you want to receive a push notification on your device(s)!
                <br>&nbsp;
            </p>

            <?php
            foreach ($nodes as $node_id => $node_data) {
                ?>
                <h4>Node: <?= $node_id ?></h4>

                IF "DROPDOWN SENSORS" IS "lessthan" "value" THEN "push"
                <br>

                <p>
                    IF
                    <select>
                        <option>humidity</option>
                        <option>Temperature</option>
                    </select>
                    IS
                    <select>
                        <option>less than</option>
                        <option>greater than</option>
                    </select>
                    <input type="number" placeholder="20" />
                    THEN
                    <select>
                        <option>push</option>
                        <option>mail</option>
                    </select>
                </p>

                <?php
            }
            ?>

        </div>
        <!--
        <div class="col-md-6">

            <h1 class="text-center">Manage Sensor Types</h1>
            <p class="text-justify">
                Here you can manage your sensor types and their respective settings.
            </p>

            <table class="table table-responsive-md" id="table_sensortypes">

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
                foreach ($sensor_types as $sensor_type){
                    ?>
                    <tr>
                        <td>
                            <input class="form-control" type="text" id="name_<?= $node_id ?>" name="name_<?= $node_id ?>" value="<?= $sensor_type["name"] ?>">
                        </td>
                        <td>
                            <select class="form-control" id="type_<?= $node_id ?>" name="type_<?= $node_id ?>">
                                <option value="analog" <?php echo ("analog" == $sensor_type["type"] ? 'selected' : '') ?>>Analog</option>
                                <option value="dht11" <?php echo ("dht11" == $sensor_type["type"] ? 'selected' : '') ?>>DHT11</option>
                            </select>
                        </td>
                        <td><input type="number" value="<?= $sensor_type["rawMinVal"] ?>" /></td>
                        <td><input type="number" value="<?= $sensor_type["rawMaxVal"] ?>" /></td>
                        <td><input type="number" value="<?= $sensor_type["minVal"] ?>" /></td>
                        <td><input type="number" value="<?= $sensor_type["maxVal"] ?>" /></td>
                        <td><button class="btn btn-dark" onclick="removeTypeRow(this)">Remove</button></td>
                    </tr>
                <?php
                }
                ?>

            </table>

            <button class="btn btn-dark" onclick="addNewTypeRowTo('sensortypes')">Add new type</button>
            <button class="btn btn-dark" onclick="saveSensorTypeData(<?= $node_id ?>)">Save changes</button>

        </div>
        -->
    </div>
</div>

<script type="text/javascript">

    // Left side

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

    function addNewSensorRowTo(tableName) {
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

    // Right side

    function addNewTypeRowTo(tableName) {
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

/*
        <td>
            <input class="form-control" type="text" id="name_<?= $node_id ?>" name="name_<?= $node_id ?>" value="<?= $sensor_type["name"] ?>">
        </td>
        <td>
            <select class="form-control" id="type_<?= $node_id ?>" name="type_<?= $node_id ?>">
                <option value="analog" <?php echo ("analog" == $sensor_type["type"] ? 'selected' : '') ?>>Analog</option>
                <option value="dht11" <?php echo ("dht11" == $sensor_type["type"] ? 'selected' : '') ?>>DHT11</option>
            </select>
        </td>
        <td><input type="number" value="<?= $sensor_type["rawMinVal"] ?>" /></td>
        <td><input type="number" value="<?= $sensor_type["rawMaxVal"] ?>" /></td>
        <td><input type="number" value="<?= $sensor_type["minVal"] ?>" /></td>
        <td><input type="number" value="<?= $sensor_type["maxVal"] ?>" /></td>
*/

        nameCell.innerHTML = "<input class='form-control' type='text' id='name_" + count + "' name='name_" + count + "'>";
        typeCell.innerHTML = "<"


        removeCell.innerHTML = "<button class='btn btn-dark' onclick='return removeTypeRow(this)'>Remove</button>";

        initializeRowForId("row_" + count);

        count++;
    }

    function removeTypeRow(element) {
        const toDelete = confirm("Are you sure you want to delete this sensor type?" +
            "\n!! This will also remove it from ALL nodes using this type !!");

        if (toDelete === true) {
            element = element.parentNode.parentNode;

            const sensorId = element.id.split("_")[2];

            $.ajax({
                method: "POST",
                url: "/admin",
                data: {
                    AJAX: 1,
                    ACTION: 'removeSensorType',
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


</script>