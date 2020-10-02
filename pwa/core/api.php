<?php

class Api {

    public static function aggregateSensorsPerNode(array $sensors){
        $output = [];
        foreach($sensors as $sensor){
            $sensor_data = [
                "link_id" => $sensor["link_id"],
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
                    "link_id" => $sensor["link_id"],
                    "added" => $sensor["added"],
                    "is_active" => $sensor["is_active"],
                    "sensors" => [$sensor_data]
                ];
            }
        }
        return $output;
    }

}