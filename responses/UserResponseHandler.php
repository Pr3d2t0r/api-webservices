<?php

class UserResponseHandler extends ResponseHandler {
    public function index(){
        $parametros = ( func_num_args() >= 1 ) ? func_get_arg(0) : [];
        $className = strtoupper($parametros['type']) ."Adapter";
        $adapter = new $className();

        if (isset($parametros[0]))
            $result = $this->db->getById("users", $parametros[0], PDO::FETCH_OBJ);
        else
            throw new Exception("Missing parameters.");

        if ($result === false)
            throw new Exception("User doesn't exist!");

        $adapter->set($result);
        echo $adapter->run();

        /*$success = $this->db->insert("users", [
            "nome" => "César",
            "username" => "cesar",
            "password" => "23a6a3cf06cfd8b1a6cda468e5756a6a6a1d21e7",
            "nivel" => 1,
            "email" => "",
            "ativo" => 1
        ]);

        if ($success === false)
            throw new Exception("sasasa");

        $adapter->set([
            "success" => "User inserted successfully!"
        ]);
        echo $adapter->run();*/
    }
}