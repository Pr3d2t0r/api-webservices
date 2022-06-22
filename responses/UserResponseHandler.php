<?php

class UserResponseHandler extends ResponseHandler {
    public function index(){
        if (isset($this->parameters[0]))
            $result = $this->db->getById("users", $this->parameters[0]);
        else
            throw new Exception("Missing parameters.");

        if ($result === false)
            throw new Exception("User doesn't exist!");

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
        return $this->db->getAll("users");
    }

    public function update() {
        if (isset($this->parameters[0]))
            $result = $this->db->getById("users", $this->parameters[0]);
        else
            throw new Exception("Missing parameters.");

        if ($result === false)
            throw new Exception("User doesn't exist!");

        $result = $this->db->update("users", [
            "username" => "abc",
            "id" => $this->parameters[0],
        ]);

        if ($result === false)
            throw new Exception("Couldn't update this user.");

        return [
            "success" => "User updated successfully!"
        ];
    }

    public function delete() {
        if (isset($this->parameters[0]))
            $result = $this->db->getById("users", $this->parameters[0]);
        else
            throw new Exception("Missing parameters.");

        if ($result === false)
            throw new Exception("User doesn't exist!");

        $result = $this->db->delete("users", [
            "id" => $this->parameters[0]
        ]);

        if ($result === false)
            throw new Exception("Couldn't delete this user.");

        return [
            "success" => "User deleted successfully!"
        ];
    }
}