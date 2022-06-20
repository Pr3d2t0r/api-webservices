<?php

class Security
{
    private Db $db;
    private string $apiKey;

    public function __construct($apiKey) {
        $this->db = new Db();
        $this->apiKey = $apiKey;
    }

    public function isValid() {
        if (!$this->apiKey)
            throw new Exception("Invalid API Key.");

        $result = $this->db->getByField("api_keys", "token", $this->apiKey, "NOW() < valid_til OR valid_til IS NULL");

        if (!$result)
            throw new Exception("API Key Not Found!");
    }
}