<?php

class Security
{
    private Db $db;
    private Request $request;
    private array $config;

    public function __construct(Request $request, $config = []) {
        $this->db = new Db();
        $this->request = $request;
        $this->config = array_merge(SECURITY_CONFIG, $config);
    }

    public function isValid() {
        //TODO: Verificar se tem algum método não permitido em $config["not_allowed_methods"]
        if (isset($this->config["not_allowed_methods"])){
            if (inArray($this->config["not_allowed_methods"], $this->request->method, false) !== false) {
                echo "blocked";
            }
        }

        if (!$this->request->apiKey)
            throw new Exception("Invalid API Key.");

        $result = $this->db->getByField("api_keys", "token", $this->request->apiKey, "NOW() < valid_til OR valid_til IS NULL");

        if (!$result)
            throw new Exception("API Key Not Found!");
    }
}