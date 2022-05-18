<?php

class ResponseHandler{
    protected Db $db;

    public function __construct(){
        $this->db = new Db();
    }
}