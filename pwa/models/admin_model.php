<?php

class admin_model extends Model
{

    public function getAdminPageSensorData()
    {

        return Database::select("
            SELECT node_id, type, unit, ROUND(AVG(value), 2) as value, DATE(measure_time) AS date, HOUR(measure_time) AS hour, FLOOR(MINUTE(measure_time) / 6) AS minute_window_id
            FROM sensor_data
            WHERE measure_time BETWEEN DATE_SUB(NOW(), INTERVAL 2 HOUR) AND NOW()
            GROUP BY node_id, type, date, hour, minute_window_id
            ORDER BY MIN(measure_time)
        ");
    }

}
