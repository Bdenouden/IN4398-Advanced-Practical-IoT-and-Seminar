<?php

class admin_model extends Model
{

    public function getAdminPageSensorData()
    {

        return Database::select("SELECT sensor_id, GROUP_CONCAT(value) as value, GROUP_CONCAT(unit) as unit FROM sensor_data WHERE entry_time BETWEEN date_sub(now(), INTERVAL 3 HOUR) AND now() GROUP BY sensor_id");
    }

}

?>


