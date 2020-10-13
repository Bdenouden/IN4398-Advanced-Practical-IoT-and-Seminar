<?php

class Api {

    public static function aggregateSensorsPerNode(array $sensors){
        $output = [];
        foreach($sensors as $sensor){
            $sensor_data = [
                "link_id" => $sensor["link_id"],
                "name" => $sensor["name"],
                "alias" => $sensor["alias"],
                "type" => $sensor["type"],
                "pins" => json_decode($sensor['pins']),
                "rawMinVal" => $sensor["rawMinVal"],
                "rawMaxVal" => $sensor["rawMaxVal"],
                "minVal" => $sensor["minVal"],
                "maxVal" => $sensor["maxVal"]
            ];

            if (array_key_exists($sensor["node_id"], $output)){
                $output[$sensor["node_id"]]["sensors"][] = $sensor_data;
            }
            else {
                $output[$sensor["node_id"]] = [
                    "added" => $sensor["added"],
                    "is_active" => $sensor["is_active"],
                    "sensors" => [$sensor_data]
                ];
            }
        }
        return $output;
    }

    public static function aggregateTriggersPerNode(array $triggers){
        $output = [];
        foreach($triggers as $trigger){
            $trigger_data = [
                "trigger_id" => $trigger["trigger_id"],
                "link_id" => $trigger["link_id"],
                "lessThan_greaterThan" => $trigger["lessThan_greaterThan"],
                "val" => $trigger["val"],
                "notification_type" => $trigger["notification_type"],
                "name" => $trigger["name"],
            ];

            if (array_key_exists($trigger["node_id"], $output)){
                $output[$trigger["node_id"]][] = $trigger_data;
            }
            else {
                $output[$trigger["node_id"]] = [$trigger_data];
            }
        }
        return $output;
    }

}