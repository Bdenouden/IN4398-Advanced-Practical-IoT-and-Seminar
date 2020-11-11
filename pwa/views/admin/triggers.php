<div class="container-fluid pt-3 pb-5">
    <div class="row">
        <div class="col-md-12">

            <h1 class="text-center">Create Triggers</h1>
            <p class="text-center">
                Here you can determine when you want to receive a notification!
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
                    <p class="form-inline" id="trigger_<?= $node_id ?>_<?= $count ?>">
                        <span class="IIT">IF</span>
                        <select class="form-control" style="width:200px" id="linkid_<?= $node_id ?>_<?= $count ?>">
                            <?php
                            foreach ($node_data["sensors"] as $sensor) {
                                if ($sensor["link_id"] == null) {
                                    break;
                                }
                                ?>
                                <option value="<?= $sensor["link_id"] ?>"><?= (isset($sensor["alias"])) ? $sensor["alias"] : $sensor["name"] ?></option>
                                <?php
                            }
                            ?>
                        </select>
                        <span class="IIT">IS</span>
                        <select class="form-control" id="ltgt_<?= $node_id ?>_<?= $count ?>">
                            <option value="0">less than</option>
                            <option value="1">greater than</option>
                        </select>
                        <input class="form-control" id="number_<?= $node_id ?>_<?= $count ?>" type="number" placeholder="20" size="5"/>
                        <span class="IIT">THEN</span>
                        <select class="form-control" id="action_<?= $node_id ?>_<?= $count ?>">
                            <!--<option value="0">send a push notification</option>-->
                            <option value="1">send an email</option>
                        </select>
                        <span class="IIT">TO</span>
                        <input class="form-control" id="email__<?= $node_id ?>_<?= $count ?>" type="email" placeholder="your@email.com" />
                        <button class="IIT btn btn-outline-danger ml-1" style="width:40px; height:40px" onclick="return removeTrigger('trigger_<?= $node_id ?>_<?= $count ?>')"><i class="far fa-trash-alt"></i></button>
                    </p>
                    <?php
                    $count++;
                } else if (isset($triggers[$node_id]) && count($triggers[$node_id]) > 0) {
                    foreach ($triggers[$node_id] as $trigger){
                        ?>

                        <p class="form-inline" id="trigger_<?= $node_id ?>_<?= $count ?>" trigger_id="<?= $trigger["trigger_id"] ?>">
                            <span class="IIT">IF</span>
                            <select class="form-control" style="width:200px" id="linkid_<?= $node_id ?>_<?= $count ?>" autocomplete="off">
                                <?php
                                foreach ($node_data["sensors"] as $sensor) {
                                    if ($sensor["link_id"] == null) {
                                        break;
                                    }
                                    ?>
                                    <option value="<?= $sensor["link_id"] ?>" <?php echo($sensor["link_id"] == $trigger["link_id"] ? 'selected' : '') ?>><?= (isset($sensor["alias"])) ? $sensor["alias"] : $sensor["name"] ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                            <span class="IIT">IS</span>
                            <select class="form-control" id="ltgt_<?= $node_id ?>_<?= $count ?>" autocomplete="off">
                                <option value="0" <?php echo(0 == $trigger["lessThan_greaterThan"] ? 'selected' : '') ?>>less than</option>
                                <option value="1" <?php echo(1 == $trigger["lessThan_greaterThan"] ? 'selected' : '') ?>>greater than</option>
                            </select>
                            <input class="form-control" id="number_<?= $node_id ?>_<?= $count ?>" type="number" placeholder="20" size="5" value="<?= $trigger["val"] ?>"  autocomplete="off" />
                            <span class="IIT">THEN</span>
                            <select class="form-control" id="action_<?= $node_id ?>_<?= $count ?>" autocomplete="off">
                                <!--                                <option value="0" <?php //echo(0 == $trigger["notification_type"] ? 'selected' : '') ?>>send a push notification</option>-->
                                <option value="1" <?php echo(1 == $trigger["notification_type"] ? 'selected' : '') ?>>send an email</option>
                            </select>
                            <span class="IIT">TO</span>
                            <input class="form-control" id="email__<?= $node_id ?>_<?= $count ?>" type="email" placeholder="your@email.com" value="<?= (isset($trigger["recipient"])) ? $trigger["recipient"] : "" ?>" />
                            <button class="IIT btn btn-outline-danger ml-1" style="width:40px; height:40px" onclick="return removeTrigger('trigger_<?= $node_id ?>_<?= $count ?>')"><i class="far fa-trash-alt"></i></button>
                        </p>

                        <?php
                        $count++;
                    }
                }
                ?>
                <button class="IIT btn btn-outline-success" style="width:40px; height:40px" onclick="addNewTriggerRow('<?= $node_id ?>')"><i class="fas fa-plus"></i></button>
                <button class="IIT btn btn-outline-success" style="width:40px; height:40px" onclick="saveTriggerData('<?= $node_id ?>')"><i class="far fa-save"></i></button>

                <?php
            }
            ?>

        </div>
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

    function addNewTriggerRow(nodeId) {
        const possibleElements = document.querySelectorAll('[id^=trigger_' + nodeId + ']');

        const paragraph = possibleElements[possibleElements.length - 1];

        let count = parseInt(paragraph.id.split("_")[paragraph.id.split("_").length - 1]);

        const newParagraph = document.createElement("p");
        newParagraph.classList.add("form-inline");
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
            const recipient = dataArray[4];

            $.ajax({
                method: "POST",
                url: "/link",
                data: {
                    AJAX: 1,
                    ACTION: 'addTriggerToSensor',
                    linkId: parseInt(linkId),
                    ltGt: parseInt(ltGt),
                    triggerVal: parseInt(triggerVal),
                    notificationChoice: parseInt(notificationChoice),
                    recipient: recipient,
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
                url: "/link",
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

</script>