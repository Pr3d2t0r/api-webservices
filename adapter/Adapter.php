<?php

abstract class Adapter {
    protected $data;

    public abstract function set($data);

    public function run(){
        return $this->data;
    }
}