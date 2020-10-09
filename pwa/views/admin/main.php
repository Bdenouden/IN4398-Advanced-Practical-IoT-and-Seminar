<div class="container-fluid pt-3 pb-5">
    <div class="row">
        <div class="col-md-6">

            <h1 class="text-center">Link modules to your node</h1>
            <p class="text-center">
                To add modules to a new node, ensure you have configured the node already to be connected to your WiFi.
                It will then automatically show up here to allow you to assign which modules are connected to it.
            </p>
            <?php
            foreach ($nodes as $node_id => $node_data) {
                ?>
                <h4 class="pt-5">Node: <?= $node_id ?></h4>
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
                    if (is_array($node_data["sensors"]) && count($node_data["sensors"]) > 0) {
                        foreach ($node_data["sensors"] as $sensor) {
                            if ($sensor['link_id'] == null) {
                                break;
                            }
                            ?>
                            <tr id="row_<?= $node_id . '_' . $sensor["link_id"] ?>">
                                <td><?= $sensor["name"] ?></td>
                                <td><?= $sensor["type"] ?></td>
                                <td><?= $sensor["rawMinVal"] ?></td>
                                <td><?= $sensor["rawMaxVal"] ?></td>
                                <td><?= $sensor["minVal"] ?></td>
                                <td><?= $sensor["maxVal"] ?></td>
                                <td>
                                    <button class="IIT btn btn-outline-danger" style="width:40px; height:40px" onclick="return removeSensorRow(this)"><i class="far fa-trash-alt"></i></button>
                                </td>
                            </tr>
                            <?php
                        }
                    }
                    ?>

                </table>
                <button class="IIT btn btn-outline-success" style="width:40px; height:40px" onclick="addNewSensorRowTo(<?= $node_id ?>)"><i class="fas fa-plus"></i></button>
                <button class="IIT btn btn-outline-success" style="width:40px; height:40px" onclick="saveNodeData(<?= $node_id ?>)"><i class="far fa-save"></i></button>
                <?php
            }
            ?>

        </div>
        <div class="col-md-6">

            <h1 class="text-center">Create Triggers</h1>
            <p class="text-center">
                Here you can determine when you want to receive a push notification on your device(s)!
                <br>&nbsp;
            </p>

            <?php
            $count = 0;
            foreach ($nodes as $node_id => $node_data) {
                ?>
                <h4 class="pt-5">Node: <?= $node_id ?></h4>

                <?php
                if (is_array($node_data["sensors"]) && count($node_data["sensors"]) > 0 && $node_data["sensors"][0]["link_id"] !== null && !isset($triggers[$node_id])) {
                    ?>
                    <p id="trigger_<?= $node_id ?>_<?= $count ?>">
                        <span class="IIT">IF</span>
                        <select id="linkid_<?= $node_id ?>_<?= $count ?>">
                            <?php
                            foreach ($node_data["sensors"] as $sensor) {
                                if ($sensor["link_id"] == null) {
                                    break;
                                }
                                ?>
                                <option value="<?= $sensor["link_id"] ?>"><?= $sensor["name"] ?></option>
                                <?php
                            }
                            ?>
                        </select>
                        <span class="IIT">IS</span>
                        <select id="ltgt_<?= $node_id ?>_<?= $count ?>">
                            <option value="0">less than</option>
                            <option value="1">greater than</option>
                        </select>
                        <input id="number_<?= $node_id ?>_<?= $count ?>" type="number" placeholder="20" size="5"/>
                        <span class="IIT">THEN</span>
                        <select id="action_<?= $node_id ?>_<?= $count ?>">
<!--                            <option value="0">send a push notification</option>-->
                            <option value="1">send an email</option>
                        </select>
                        <button class="IIT btn btn-outline-danger ml-1" style="width:40px; height:40px" onclick="return removeTrigger('trigger_<?= $node_id ?>_<?= $count ?>')"><i class="far fa-trash-alt"></i></button>
                    </p>
                    <?php
                    $count++;
                } else if (isset($triggers[$node_id]) && count($triggers[$node_id]) > 0) {
                    foreach ($triggers[$node_id] as $trigger){
                    ?>

                        <p id="trigger_<?= $node_id ?>_<?= $count ?>" trigger_id="<?= $trigger["trigger_id"] ?>">
                            <span class="IIT">IF</span>
                            <select id="linkid_<?= $node_id ?>_<?= $count ?>" autocomplete="off">
                                <?php
                                foreach ($node_data["sensors"] as $sensor) {
                                    if ($sensor["link_id"] == null) {
                                        break;
                                    }
                                    ?>
                                    <option value="<?= $sensor["link_id"] ?>" <?php echo($sensor["name"] == $trigger["name"] ? 'selected' : '') ?>><?= $sensor["name"] ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                            <span class="IIT">IS</span>
                            <select id="ltgt_<?= $node_id ?>_<?= $count ?>" autocomplete="off">
                                <option value="0" <?php echo(0 == $trigger["lessThan_greaterThan"] ? 'selected' : '') ?>>less than</option>
                                <option value="1" <?php echo(1 == $trigger["lessThan_greaterThan"] ? 'selected' : '') ?>>greater than</option>
                            </select>
                            <input id="number_<?= $node_id ?>_<?= $count ?>" type="number" placeholder="20" size="5" value="<?= $trigger["val"] ?>"  autocomplete="off" />
                            <span class="IIT">THEN</span>
                            <select id="action_<?= $node_id ?>_<?= $count ?>" autocomplete="off">
<!--                                <option value="0" <?php //echo(0 == $trigger["notification_type"] ? 'selected' : '') ?>>send a push notification</option>-->
                                <option value="1" <?php echo(1 == $trigger["notification_type"] ? 'selected' : '') ?>>send an email</option>
                            </select>
                            <button class="IIT btn btn-outline-danger ml-1" style="width:40px; height:40px" onclick="return removeTrigger('trigger_<?= $node_id ?>_<?= $count ?>')"><i class="far fa-trash-alt"></i></button>
                        </p>

                    <?php
                        $count++;
                    }
                }
                ?>
                <button class="IIT btn btn-outline-success" style="width:40px; height:40px" onclick="addNewTriggerRow(<?= $node_id ?>)"><i class="fas fa-plus"></i></button>
                <button class="IIT btn btn-outline-success" style="width:40px; height:40px" onclick="saveTriggerData(<?= $node_id ?>)"><i class="far fa-save"></i></button>

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
        foreach ($sensor_types as $sensor_type) {
            ?>
                    <tr>
                        <td>
                            <input class="form-control" type="text" id="name_<?= $node_id ?>" name="name_<?= $node_id ?>" value="<?= $sensor_type["name"] ?>">
                        </td>
                        <td>
                            <select class="form-control" id="type_<?= $node_id ?>" name="type_<?= $node_id ?>">
                                <option value="analog" <?php echo("analog" == $sensor_type["type"] ? 'selected' : '') ?>>Analog</option>
                                <option value="dht11" <?php echo("dht11" == $sensor_type["type"] ? 'selected' : '') ?>>DHT11</option>
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

    // General

    function removeElement(element){
        const toRemove = document.getElementById(element);
        toRemove.parentNode.removeChild(toRemove);
    }

    function insertAfter(newNode, referenceNode) {
        referenceNode.parentNode.insertBefore(newNode, referenceNode.nextSibling);
    }

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

    function removeSensorRow(element) {
        const toDelete = confirm("Are you sure you want to delete this sensor?" +
            "\n!! This will also remove ALL the triggers you have defined !!");

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
                .done(function (result) {

                    if (result === "true") {
                        element.parentNode.removeChild(element);
                        window.location.reload();
                    }
                });

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

        removeCell.innerHTML = "<button class='IIT btn btn-outline-danger' style='width:40px; height:40px' onclick='return removeSensorRow(this)'><i class='far fa-trash-alt'></i></button>";

        initializeRowForId("row_" + count);

        count++;
    }

    function initializeRowForId(rowId) {

        let row;

        if (typeof rowId !== "string") {
            row = document.getElementById(rowId.parentNode.parentNode.id);
        } else {
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

    function addNewTriggerRow(nodeId) {
        const possibleElements = document.querySelectorAll('[id^=trigger_' + nodeId + ']');

        const paragraph = possibleElements[possibleElements.length - 1];

        let count = parseInt(paragraph.id.split("_")[paragraph.id.split("_").length - 1]);

        const newParagraph = document.createElement("p");
        newParagraph.id = "trigger_".concat(nodeId, "_", count + 1);

        newParagraph.innerHTML = paragraph.innerHTML.replace(paragraph.id, newParagraph.id);

        const renameElements = newParagraph.querySelectorAll('[id*=_' + nodeId + "_" + count + ']');

        for (let i = 0; i < renameElements.length; i++){
            renameElements[i].id = renameElements[i].id.replace('_' + nodeId + "_" + count, '_'.concat(nodeId, "_", count + 1));
        }

        insertAfter(newParagraph, paragraph);
    }

    function saveTriggerData(nodeId) {
        const possibleElements = document.querySelectorAll('[id^=trigger_' + nodeId + ']');

        for (let i = 0; i < possibleElements.length; i++) {
            const children = possibleElements[i].children;

            let dataArray = [];

            for (let j = 0; j < children.length; j++) {
                if (children[j].id.indexOf(nodeId) !== -1) {
                    dataArray.push(children[j].value);
                }
            }

            const linkId = dataArray[0];
            const ltGt = dataArray[1];
            const triggerVal = dataArray[2];
            const notificationChoice = dataArray[3];

            $.ajax({
                method: "POST",
                url: "/admin",
                data: {
                    AJAX: 1,
                    ACTION: 'addTriggerToSensor',
                    linkId: parseInt(linkId),
                    ltGt: parseInt(ltGt),
                    triggerVal: parseInt(triggerVal),
                    notificationChoice: parseInt(notificationChoice),
                    triggerId: parseInt(possibleElements[i].getAttribute("trigger_id")),
                    'csrf-token': '<?php echo $_SESSION['csrf-token']?>'
                }
            })
                .done(function (result) {

                    if (result !== "") {
                        const data = $.parseJSON(result);
                        window.location.reload();
                    }
                });
        }
    }

    function removeTrigger(element){
        const toDelete = confirm("Are you sure you want to delete this trigger?");

        if (toDelete === true) {
            $.ajax({
                method: "POST",
                url: "/admin",
                data: {
                    AJAX: 1,
                    ACTION: 'removeTrigger',
                    triggerId: parseInt(document.getElementById(element).getAttribute("trigger_id")),
                    'csrf-token': '<?php echo $_SESSION['csrf-token']?>'
                }
            })
                .done(function (result) {

                    if (result === "true") {
                        removeElement(element);
                    }
                });
        }
        return false;

    }

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
                <option value="analog" <?php echo("analog" == $sensor_type["type"] ? 'selected' : '') ?>>Analog</option>
                <option value="dht11" <?php echo("dht11" == $sensor_type["type"] ? 'selected' : '') ?>>DHT11</option>
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
                .done(function (result) {
                    const data = $.parseJSON(result);
                    console.log(data);
                });

            removeElement(element);
        }
        return false;
    }


</script>