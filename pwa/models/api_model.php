<?php
class api_model extends Model {

    public function getValidApiKeys()
    {
        return Database::select("SELECT * FROM sensor_data");
    }

    public function storeSensorEntry(string $node_chipid, string $sensor_uid, string $data_type, float $data_value, string $data_unit)
    {

        try {
            Database::beginTransaction();
            Database::query("INSERT INTO sensor_data (node_id, sensor_id, type, value, unit) VALUES (:node_id, :sensor_id, :type, :value, :unit)", array(
                ":node_id" => $node_chipid,
                ":sensor_id" => $sensor_uid,
                ":type" => $data_type,
                ":value" => $data_value,
                ":unit" => $data_unit
            ));
            Database::commit();
            return true;
        } catch (SystemException $e) {
            Database::rollBack();
            return false;
        }
    }

}




