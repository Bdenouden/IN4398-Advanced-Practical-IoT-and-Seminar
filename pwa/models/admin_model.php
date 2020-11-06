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

    public function getTriggers()
    {
        return Database::select("
            SELECT *, triggers.id AS trigger_id
            FROM triggers
            LEFT JOIN sensor_node_link snl on snl.id = triggers.link_id
            LEFT JOIN sensor_types st on snl.sensor_type_id = st.id
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

    public function addSensorToNode(int $sensor_id, string $node_id, array $pins_to_add, ?string $alias)
    {
        try {
            Database::beginTransaction();
            Database::query("
                INSERT INTO sensor_node_link (node_id, sensor_type_id, pins, alias)
                VALUES (:node_id, :sensor_id, :pins, :alias)
            ", array(
                ":node_id" => $node_id,
                ":sensor_id" => $sensor_id,
                ":pins" => "[" . implode(",", $pins_to_add) . "]",
                ":alias" => $alias
            ));
            Database::commit();
            return true;
        } catch (SystemException $e) {
            Database::rollBack();
            return $e->getMessage();
        }
    }

    public function addTriggerToSensor(?int $trigger_id, int $link_id, int $ltGt, int $val, int $notification_type, string $recipient)
    {
        try {
            Database::beginTransaction();
            Database::query("
                INSERT INTO triggers (id, link_id, lessThan_greaterThan, val, notification_type, recipient)
                VALUES (:id, :link_id, :ltGt, :val, :notification_type, :recipient)
                ON DUPLICATE KEY UPDATE link_id=:link_id, lessThan_greaterThan=:ltGt, val=:val, notification_type=:notification_type, recipient=:recipient
            ", array(
                ":id" => $trigger_id,
                ":link_id" => $link_id,
                ":ltGt" => $ltGt,
                ":val" => $val,
                ":notification_type" => $notification_type,
                ":recipient" => $recipient
            ));
            Database::commit();
            return true;
        } catch (SystemException $e) {
            Database::rollBack();
            return $e->getMessage();
        }
    }

    public function removeTrigger(int $trigger_id)
    {
        try{
            Database::beginTransaction();
            Database::query("
                DELETE
                FROM triggers
                WHERE id = :trigger_id
            ", array(
                ":trigger_id" => $trigger_id
            ));
            Database::commit();
            return true;
        } catch (SystemException $e) {
            Database::rollBack();
            return $e->getMessage();
        }
    }

}
