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

    public function getSnl()
    {
        return Database::select("
            SELECT snl.id, snl.alias 
            FROM sensor_node_link snl
            LEFT JOIN snl_location_link loc
                ON snl.id = loc.snl_id
            WHERE loc.snl_id IS NULL;
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

    public function delete($id)
    {
        try {
            Database::beginTransaction();
            Database::query("
                DELETE
                FROM snl_location_link
                WHERE id = :id
            ", array(
                ":id" => $id
            ));
            Database::commit();
            return true;
        } catch (SystemException $e) {
            Database::rollBack();
            return $e->getMessage();
        }
    }

    public function add($snl_id)
    {
        try {
            Database::beginTransaction();
            Database::query("
                INSERT INTO snl_location_link (snl_id, x,y)
                VALUES (:snl_id, 10, 10)
            ", array(
                ":snl_id" => $snl_id,
            ));
            Database::commit();
            return true;
        } catch (SystemException $e) {
            Database::rollBack();
            return $e->getMessage();
        }
    }

    public function getUserSettings()
    {

        $defaultSettings = [
            'radius' => '100',
            'snapToGrid' => '0',
            'mapName' => 'Map name'
        ];

        $user_id = User::g('user_id');
        if ($user_id) {
            // return settings
            $settings = Database::select(
                "
            SELECT *
            FROM grid_settings
            WHERE user_id = :id
            ",
                array(
                    ':id' => $user_id
                )
            )[0];

            if (empty($settings)) { // first-time user 
                $settings = $defaultSettings;
            }
        } else { // unknown user
            $settings = $defaultSettings;
        }


        return $settings;
    }

    public function setUserSettings($radius, $snap, $mapName)
    {

        $user_id = User::g('user_id');

        $current = Database::select("
        SELECT id FROM grid_settings WHERE user_id = :user_id
        ", array(
            ":user_id" => $user_id
        ));

        
        $exists = !empty($current);

        if ($exists == 1) {
            $query = "
                UPDATE grid_settings 
                SET radius=:radius, snapToGrid=:snap, mapName=:mapName
                WHERE user_id = :user_id       
            ";
        } else {
            $query = "
                INSERT INTO grid_settings ( user_id, radius, snapToGrid, mapName)
                VALUES (:user_id, :radius, :snap, :mapName)
            ";
        }


        try {
            Database::beginTransaction();
            Database::query(
                $query,
                array(
                    ":user_id" => $user_id,
                    ":radius" => $radius,
                    ":snap" => ($snap == "true" ? 1 : 0),
                    ":mapName" => $mapName
                )
            );
            Database::commit();
            return true;
        } catch (SystemException $e) {
            Database::rollBack();
            return $e->getMessage();
        }
    }
}
