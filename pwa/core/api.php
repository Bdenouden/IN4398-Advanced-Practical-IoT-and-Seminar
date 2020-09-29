<?php

class Api {

    public static function aggregateSensorsPerNode(array $sensors){
        $output = [];
        foreach($sensors as $sensor){
            $sensor_data = [
                "id" => $sensor["id"],
                "name" => $sensor["name"],
                "type" => $sensor["type"],
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
                    "id" => $sensor["id"],
                    "added" => $sensor["added"],
                    "is_active" => $sensor["is_active"],
                    "sensors" => [$sensor_data]
                ];
            }
        }
        return $output;
    }

}