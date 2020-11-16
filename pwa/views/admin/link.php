<div class="container-fluid pt-3 pb-5">
    <div class="row">
        <div class="col-md-12">

            <h1 class="text-center">Link modules to your node</h1>
            <p class="text-center">
                To add modules to a new node, ensure you have configured the node already to be connected to your WiFi.
                It will then automatically show up here to allow you to assign which modules are connected to it.
            </p>
            <?php
            foreach ($nodes as $node_id => $node_data) {
            ?>
                <h4 class="pt-5">Node: <?= $node_id ?>
                    <span>
                        <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-chevron-bar-expand" onclick="toggleTable(this, true)" node_id="<?= $node_id ?>" style="display: none;" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M3.646 10.146a.5.5 0 0 1 .708 0L8 13.793l3.646-3.647a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 0-.708zm0-4.292a.5.5 0 0 0 .708 0L8 2.207l3.646 3.647a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 0 0 0 .708zM1 8a.5.5 0 0 1 .5-.5h13a.5.5 0 0 1 0 1h-13A.5.5 0 0 1 1 8z" />
                        </svg>
                        <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-chevron-bar-contract" onclick="toggleTable(this, false)" node_id="<?= $node_id ?>" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M3.646 14.854a.5.5 0 0 0 .708 0L8 11.207l3.646 3.647a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 0 0 0 .708zm0-13.708a.5.5 0 0 1 .708 0L8 4.793l3.646-3.647a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 0-.708zM1 8a.5.5 0 0 1 .5-.5h13a.5.5 0 0 1 0 1h-13A.5.5 0 0 1 1 8z" />
                        </svg>
                    </span>
                </h4>
                <div>
                    <table class="table table-responsive-md" id="table_<?= $node_id ?>">
                        <tr>
                            <th>Name</th>
                            <th>Alias</th>
                            <th>Type</th>
                            <th>Raw Minimum Value</th>
                            <th>Raw Maximum Value</th>
                            <th>Real Minimum Value</th>
                            <th>Real Maximum Value</th>
                            <th class="text-center">Pin</th>
                            <th class="text-center">SDA</th>
                            <th class="text-center">SCL</th>
                            <th class="text-center">I2C Address</th>
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
                                    <td><?= (isset($sensor["alias"])) ? $sensor["alias"] : "" ?></td>
                                    <td><?= $sensor["type"] ?></td>
                                    <td><?= $sensor["rawMinVal"] ?></td>
                                    <td><?= $sensor["rawMaxVal"] ?></td>
                                    <td><?= $sensor["minVal"] ?></td>
                                    <td><?= $sensor["maxVal"] ?></td>
                                    <td class="text-center"><?= (count($sensor["pins"]) === 1) ? $sensor["pins"][0] : "" ?></td>
                                    <td class="text-center"><?= (count($sensor["pins"]) === 3) ? $sensor["pins"][0] : "" ?></td>
                                    <td class="text-center"><?= (count($sensor["pins"]) === 3) ? $sensor["pins"][1] : "" ?></td>
                                    <td class="text-center"><?= (count($sensor["pins"]) === 3) ? $sensor["pins"][2] : "" ?></td>
                                    <td>
                                        <button class="IIT btn btn-outline-danger" style="width:40px; height:40px" onclick="return removeSensorRow(this)"><i class="far fa-trash-alt"></i></button>
                                    </td>
                                </tr>
                        <?php
                            }
                        }
                        ?>

                    </table>

                    <button class="IIT btn btn-outline-success" style="width:40px; height:40px" onclick="addNewSensorRowTo('<?= $node_id ?>')"><i class="fas fa-plus"></i></button>
                    <button class="IIT btn btn-outline-success" style="width:40px; height:40px" onclick="saveNodeData('<?= $node_id ?>')"><i class="far fa-save"></i></button>
                </div>
            <?php
            }
            ?>

        </div>
    </div>
</div>

<script type="text/javascript">
    function toggleTable(el, mustExpand) {
        console.log(mustExpand)
        if (mustExpand) {
            // show collapse icon
            el.parentNode.getElementsByClassName('bi-chevron-bar-expand')[0].style.display = "none"
            el.parentNode.getElementsByClassName('bi-chevron-bar-contract')[0].style.display = "unset"
        } else {
            // show expand icon
            el.parentNode.getElementsByClassName('bi-chevron-bar-expand')[0].style.display = "unset"
            el.parentNode.getElementsByClassName('bi-chevron-bar-contract')[0].style.display = "none"
        }
        tableName = "#table_" + el.getAttribute('node_id');
        $(tableName).parent().slideToggle()
    }



    // General

    function removeElement(element) {
        const toRemove = document.getElementById(element);
        toRemove.parentNode.removeChild(toRemove);
    }

    function insertAfter(newNode, referenceNode) {
        referenceNode.parentNode.insertBefore(newNode, referenceNode.nextSibling);
    }

    let sensorNameDropdown = "<select class='form-control' onChange='initializeRowForId(this)'>";
    <?php
    foreach ($sensor_types as $sensor_type) {
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
                    url: "/link",
                    data: {
                        AJAX: 1,
                        ACTION: 'removeSensorFromNode',
                        sensorId: parseInt(sensorId),
                        'csrf-token': '<?php echo $_SESSION['csrf-token'] ?>'
                    }
                })
                .done(function(result) {

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
        const aliasCell = row.insertCell();
        const typeCell = row.insertCell();

        const rawMinCell = row.insertCell();
        const rawMaxCell = row.insertCell();
        const minCell = row.insertCell();
        const maxCell = row.insertCell();

        const pinCell = row.insertCell();
        const SDACell = row.insertCell();
        const SCLCell = row.insertCell();
        const I2CCell = row.insertCell();

        const removeCell = row.insertCell();

        nameCell.innerHTML = sensorNameDropdown;

        aliasCell.innerHTML = "<input type='text' name='alias' class='form-control' autocomplete='off' />";

        removeCell.innerHTML = "<button class='IIT btn btn-outline-danger' style='width:40px; height:40px' onclick='return removeSensorRow(this)'><i class='far fa-trash-alt'></i></button>";

        initializeRowForId("row_" + count, pinCell, SDACell, SCLCell, I2CCell);

        count++;
    }

    function initializeRowForId(rowId, pinCell = null, SDACell = null, SCLCell = null, I2CCell = null) {

        let row;

        if (typeof rowId !== "string") {
            row = document.getElementById(rowId.parentNode.parentNode.id);
        } else {
            row = document.getElementById(rowId);
        }

        const selectedSensorId = row.firstChild.firstChild.value;

        $.ajax({
                method: "POST",
                url: "/link",
                data: {
                    AJAX: 1,
                    ACTION: 'getSensorDataForId',
                    sensorId: parseInt(selectedSensorId),
                    'csrf-token': '<?php echo $_SESSION['csrf-token'] ?>'
                }
            })
            .done(function(result) {

                const data = $.parseJSON(result)[0];

                row.firstChild.nextSibling.nextSibling.innerHTML = data.type;
                row.firstChild.nextSibling.nextSibling.nextSibling.innerHTML = data.rawMinVal;
                row.firstChild.nextSibling.nextSibling.nextSibling.nextSibling.innerHTML = data.rawMaxVal;
                row.firstChild.nextSibling.nextSibling.nextSibling.nextSibling.nextSibling.innerHTML = data.minVal;
                row.firstChild.nextSibling.nextSibling.nextSibling.nextSibling.nextSibling.nextSibling.innerHTML = data.maxVal;

                if (data.type === "analog" || data.type === "dhtxx") {
                    row.firstChild.nextSibling.nextSibling.nextSibling.nextSibling.nextSibling.nextSibling.nextSibling.innerHTML = "<input type='number' name='pin' class='form-control' value=0>"
                    row.firstChild.nextSibling.nextSibling.nextSibling.nextSibling.nextSibling.nextSibling.nextSibling.nextSibling.innerHTML = ""
                    row.firstChild.nextSibling.nextSibling.nextSibling.nextSibling.nextSibling.nextSibling.nextSibling.nextSibling.nextSibling.innerHTML = ""
                    row.firstChild.nextSibling.nextSibling.nextSibling.nextSibling.nextSibling.nextSibling.nextSibling.nextSibling.nextSibling.nextSibling.innerHTML = ""
                } else {
                    row.firstChild.nextSibling.nextSibling.nextSibling.nextSibling.nextSibling.nextSibling.nextSibling.innerHTML = ""
                    row.firstChild.nextSibling.nextSibling.nextSibling.nextSibling.nextSibling.nextSibling.nextSibling.nextSibling.innerHTML = "<input type='number' name='pin' class='form-control' value=0>"
                    row.firstChild.nextSibling.nextSibling.nextSibling.nextSibling.nextSibling.nextSibling.nextSibling.nextSibling.nextSibling.innerHTML = "<input type='number' name='pin' class='form-control' value=0>"
                    row.firstChild.nextSibling.nextSibling.nextSibling.nextSibling.nextSibling.nextSibling.nextSibling.nextSibling.nextSibling.nextSibling.innerHTML = "<input type='number' name='pin' class='form-control' value=0>"
                }
            });
    }

    function saveNodeData(tableId) {
        const table = $("#table_" + tableId).find("tr");
        for (let i = 0; i < table.length; i++) {
            if ((table[i].id.match(new RegExp("_", "g")) || []).length === 1) {
                const sensorIdToAdd = table[i].firstChild.firstChild.value;
                const nodeIdToAdd = table[i].parentNode.parentNode.id.split("_")[1];
                const linkAlias = table[i].firstChild.nextSibling.firstChild.value;

                let pinsToAdd = [];

                for (let j = 0; j < table[i].children.length; j++) {
                    if (table[i].children[j].firstChild !== null && table[i].children[j].firstChild.name === "pin") {
                        pinsToAdd.push(parseInt(table[i].children[j].firstChild.value));
                    }
                }


                $.ajax({
                        method: "POST",
                        url: "/link",
                        data: {
                            AJAX: 1,
                            ACTION: 'addSensorToNode',
                            sensorId: parseInt(sensorIdToAdd),
                            nodeId: nodeIdToAdd,
                            linkAlias: linkAlias,
                            pinsToAdd: pinsToAdd,
                            'csrf-token': '<?php echo $_SESSION['csrf-token'] ?>'
                        }
                    })
                    .done(function(result) {

                        if (result === "true") {
                            window.location.reload();
                        } else {
                            alert("Something went wrong with saving!");
                        }
                    });
            }
        }

    }
</script>