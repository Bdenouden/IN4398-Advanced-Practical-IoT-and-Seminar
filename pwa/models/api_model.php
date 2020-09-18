<?php
class api_model extends Model {

    public function getValidApiKeys()
    {
        return Database::select("SELECT * FROM api_keys");
    }

}




