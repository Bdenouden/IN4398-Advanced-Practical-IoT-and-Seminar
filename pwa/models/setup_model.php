<?php
class setup_model extends Model {

    public function requestAccountConfirmation($username, $password) {

        $result = Database::select("SELECT * FROM users WHERE user_name = :username", array(':username' => $username));

        if(isset($result[0])){

            throw new UserException("An account is already registered with this username!");

        }
        else{

            Database::beginTransaction();

            Database::query("INSERT INTO users (user_name, user_password, user_type) VALUES (:username, :password, :type)",
                array(
                    ':username' => $username,
                    ':password' => $password,
                    ':type' => "admin",
                )
            );

            try{
                fclose(fopen("setup.lock", "w"));

                Database::commit();

                Auth::redirect("/login?setup_success=true");

            }
            catch (Exception $e){
                var_dump($e);
                Database::rollBack();
            }

        }
    }

}




