<?php

class RestfullResponse{
    protected Db $db;
    protected Request $request;
    protected string $table;
    protected $id;

    public function __construct() {
        $this->db = new Db();
    }

    public function setRequest(&$request){
        $this->request = $request;
    }

    public function setTable($table){
        $this->table = $table;
    }

    public function setId($id){
        $this->id = $id;
    }

    public function get() {
        if ($this->id != null) {
            $result = $this->db->getById($this->table, $this->id);
            if ($result === null)
                throw new SystemException(404);

            if ($result === false)
                throw new Exception("Doesn't exist!");
            return $result;
        }

        return $this->db->getAll($this->table);
    }

    public function post() {
        if (empty($this->request->post))
            throw new Exception("Empty Body!");

        $success = $this->db->insert($this->table, $this->request->post);
        if ($success === null)
            throw new SystemException(404);

        if ($success === false)
            throw new Exception("Error on inserting!");

        return [
            "success" => "Inserted successfully!"
        ];
    }

    public function put() {
        if (empty($this->request->put))
            throw new Exception("Empty Body!");

        if ($this->id != null)
            $result = $this->db->getById($this->table, $this->id);
        else
            throw new Exception("Missing parameters.");

        if ($result === null)
            throw new SystemException(404);

        if ($result === false)
            throw new Exception("Doesn't exist!");

        $success = $this->db->update($this->table, $this->request->put + [ "id" => $this->id]);
        if ($success === false)
            throw new Exception("Error on updating!");

        return [
            "success" => "Updated successfully!"
        ];
    }

    public function delete() {
        if ($this->id != null)
            $result = $this->db->getById($this->table, $this->id);
        else
            throw new Exception("Missing parameters.");

        if ($result === null)
            throw new SystemException(404);

        if ($result === false)
            throw new Exception("Doesn't exist!");

        $result = $this->db->delete($this->table, [
            "id" => $this->id
        ]);

        if ($result === false)
            throw new Exception("Couldn't delete!");

        return [
            "success" => "Deleted successfully!"
        ];
    }
}