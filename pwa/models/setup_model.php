<?php
class setup_model extends Model {

    public function initialAccountSetup(string $username, string $password, string $api_username, string $api_password) {

        $result = Database::select(
            "SELECT user_name
            FROM users
            WHERE user_name = :username
            OR user_name = :api_username",
            array(
                ':username' => $username,
                ':api_username' => $api_username
            )
        );

        var_dump($result);

        if(isset($result[0])){

            throw new UserException("An account is already registered with username " . $result[0]['user_name']);

        }
        else{

            Database::beginTransaction();

            try {
                Database::query("INSERT INTO users (user_name, user_password, user_type) VALUES (:username, :password, :type)",
                    array(
                        ':username' => $username,
                        ':password' => $password,
                        ':type' => "admin",
                    )
                );
                Database::query("INSERT INTO users (user_name, user_password, user_type) VALUES (:username, :password, :type)",
                    array(
                        ':username' => $api_username,
                        ':password' => $api_password,
                        ':type' => "api",
                    )
                );
            } catch (SystemException $e) {
                Database::rollBack();
                var_dump($e);
                Auth::redirect("/login?setup_success=false");
            }

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




