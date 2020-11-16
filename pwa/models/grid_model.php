<?php

class grid_model extends Model
{

    public function getSnlLocData()
    {
        return Database::select("
            SELECT  DISTINCT loc.id, loc.x, loc.y, snl.alias, data.value
            FROM snl_location_link as loc
            JOIN sensor_node_link as snl
            ON loc.snl_id=snl.id
            LEFT JOIN sensor_data as data 
            ON loc.snl_id=data.link_id

            LEFT OUTER JOIN sensor_data as d2 
            ON (loc.snl_id=d2.link_id AND 
            (data.measure_time < d2.measure_time OR 
            (data.measure_time = d2.measure_time AND data.id < d2.id))) 
            WHERE d2.id IS NULL;
        ");
    }

    public function setSnlLoc(int $id, int $x, int $y)
    {
        try {
            Database::beginTransaction();
            Database::query("
                UPDATE snl_location_link
                SET x=:x, y=:y
                WHERE id=:id
            ", array(
                ":id" => $id,
                ":x" => $x,
                ":y" => $y,
            ));
            Database::commit();
            return true;
        } catch (SystemException $e) {
            Database::rollBack();
            return $e->getMessage();
        }
    }

}