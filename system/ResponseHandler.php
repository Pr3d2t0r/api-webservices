<?php

class ResponseHandler{
    protected Db $db;
    protected ?array $parameters;
    protected Request $request;

    public function __construct(){
        $this->db = new Db();
    }

    public function setParameters(&$parameters){
        $this->parameters = $parameters;
    }

    public function setRequest(&$request){
        $this->request = $request;
    }
}