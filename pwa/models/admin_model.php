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

    public function getSensorTypes()
    {
        return Database::select("
            SELECT *
            FROM sensor_types
        ");
    }

    public function getSensorDataForId(int $sensor_id)
    {
        return Database::select("
            SELECT *
            FROM sensor_types
            WHERE id = :sensor_id
        ", array(
            ":sensor_id" => $sensor_id
        ));
    }

    public function removeSensorFromNode(int $sensor_id)
    {
        try {
            Database::beginTransaction();
            Database::query("
                DELETE
                FROM sensor_node_link
                WHERE id = :sensor_id
            ", array(
                ":sensor_id" => $sensor_id
            ));
            Database::commit();
            return true;
        } catch (SystemException $e) {
            Database::rollBack();
            return $e->getMessage();
        }
    }

    public function addSensorToNode(int $sensor_id, string $node_id)
    {
        try {
            Database::beginTransaction();
            Database::query("
                INSERT INTO sensor_node_link (node_id, sensor_type_id)
                VALUES (:node_id, :sensor_id)
            ", array(
                ":node_id" => $node_id,
                ":sensor_id" => $sensor_id
            ));
            Database::commit();
            return true;
        } catch (SystemException $e) {
            Database::rollBack();
            return $e->getMessage();
        }
    }

    public function addTriggertoSensor(int $link_id, int $ltGt, int $val, int $notification_type)
    {
        try {
            Database::beginTransaction();
            Database::query("
                INSERT INTO triggers (link_id, lessThan_greaterThan, val, notification_type)
                VALUES (:link_id, :ltGt, :val, :notification_type)
            ", array(
               ":link_id" => $link_id,
               ":ltGt" => $ltGt,
               ":val" => $val,
               ":notification_type" => $notification_type
            ));
            Database::commit();
            return true;
        } catch (SystemException $e) {
            Database::rollBack();
            return $e->getMessage();
        }
    }

}
