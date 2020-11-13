<?php

class grid_model extends Model
{

    public function getSnlLocData()
    {
        return Database::select("
            SELECT loc.id, loc.x, loc.y, snl.alias
            FROM snl_location_link as loc
            INNER JOIN sensor_node_link as snl
            ON loc.snl_id=snl.id
        ");
    }

}