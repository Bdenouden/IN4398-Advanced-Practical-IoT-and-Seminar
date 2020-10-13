<?php

class api_model extends Model
{

    public function getKnownDevices(bool $active = true)
    {
        return Database::select("
                SELECT sensor_nodes.id AS node_id, sensor_nodes.added, sensor_nodes.is_active, snl.id AS link_id, snl.pins, snl.alias, st.*
                FROM sensor_nodes
                LEFT JOIN sensor_node_link snl on sensor_nodes.id = snl.node_id
                LEFT JOIN sensor_types st on snl.sensor_type_id = st.id
                WHERE is_active = :is_active
                ", array(
            ":is_active" => $active ? 1 : 0,
        ));
    }

    public function addNewDevice(string $node_id)
    {
        try {
            Database::beginTransaction();
            Database::query("INSERT INTO sensor_nodes (id) VALUES (:node_id)", array(
                ":node_id" => $node_id,
            ));
            Database::commit();
            return true;
        } catch (SystemException $e) {
            Database::rollBack();
            return false;
        }
    }

    public function storeSensorEntry(string $node_chipid, string $sensor_uid, string $data_type, ?float $data_value, string $data_unit, ?string $measure_time)
    {

        try {
            Database::beginTransaction();
            Database::query("INSERT INTO sensor_data (node_id, sensor_id, type, value, unit, measure_time) VALUES (:node_id, :sensor_id, :type, :value, :unit, IFNULL(:measure_time, DEFAULT(measure_time)))", array(
                ":node_id" => $node_chipid,
                ":sensor_id" => $sensor_uid,
                ":type" => $data_type,
                ":value" => $data_value,
                ":unit" => $data_unit,
                ":measure_time" => $measure_time
            ));
            Database::commit();
            return true;
        } catch (SystemException $e) {
            Database::rollBack();
            return $e->getMessage();
        }
    }

}




