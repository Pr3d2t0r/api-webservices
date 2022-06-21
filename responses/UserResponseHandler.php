<?php

class UserResponseHandler extends ResponseHandler {
    public function index(){
        $parametros = ( func_num_args() >= 1 ) ? func_get_arg(0) : [];

        if (isset($parametros[0]))
            $result = $this->db->getById("users", $parametros[0]);
        else
            throw new AppException("Missing parameters.");

        if ($result === false)
            throw new AppException("User doesn't exist!");

        return $result;

        /*$success = $this->db->insert("users", [
            "nome" => "César",
            "username" => "cesar",
            "password" => "23a6a3cf06cfd8b1a6cda468e5756a6a6a1d21e7",
            "nivel" => 1,
            "email" => "",
            "ativo" => 1
        ]);

        if ($success === false)
            throw new Exception("Error on inserting!");

        return [
            "success" => "User inserted successfully!"
        ];
        */
    }

    public function all(){
        $parametros = ( func_num_args() >= 1 ) ? func_get_arg(0) : [];

        return $this->db->getAll("users");
    }

    public function update() {
        $parametros = (func_num_args() >= 1) ? func_get_arg(0) : [];

        if (isset($parametros[0]))
            $result = $this->db->getById("users", $parametros[0]);
        else
            throw new AppException("Missing parameters.");

        if ($result === false)
            throw new AppException("User doesn't exist!");

        $result = $this->db->update("users", [
            "username" => "abc",
            "id" => $parametros[0],
        ]);

        if ($result === false)
            throw new AppException("Couldn't update this user.");

        return [
            "success" => "User updated successfully!"
        ];
    }

    public function delete() {
        $parametros = (func_num_args() >= 1) ? func_get_arg(0) : [];

        if (isset($parametros[0]))
            $result = $this->db->getById("users", $parametros[0]);
        else
            throw new AppException("Missing parameters.");

        if ($result === false)
            throw new AppException("User doesn't exist!");

        $result = $this->db->delete("users", [
            "id" => $parametros[0]
        ]);

        if ($result === false)
            throw new AppException("Couldn't delete this user.");

        return [
            "success" => "User deleted successfully!"
        ];
    }
}