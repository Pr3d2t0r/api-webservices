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

    public function isValid()
    {
        if (isset($this->config["allowed_methods"])) {
            if (inArray($this->config["allowed_methods"], $this->request->method, false) === false)
                throw new SystemException(405);
        } else if (isset($this->config["not_allowed_methods"])) {
            if (inArray($this->config["not_allowed_methods"], $this->request->method, false) !== false)
                throw new SystemException(405);
        }

        if (RESTFULL) {
            if (isset($this->config["restfull"])) {
                if (isset($this->config["restfull"]["allowed_tables"])) {
                    if (inArray($this->config["restfull"]["allowed_tables"], str_replace("/", "", $this->request->page)) === false)
                        throw new SystemException(403);
                } else if (isset($this->config["restfull"]["blocked_tables"])) {
                    if (inArray($this->config["restfull"]["blocked_tables"], str_replace("/", "", $this->request->page)) !== false)
                        throw new SystemException(403);
                }
            }
        }

        if ((ENVIRONMENT ?? "development") == "production") {
            if (isset($this->config["allowed_clients_ip"]) && !empty($this->config["allowed_clients_ip"])) {
                if (is_array($this->config["allowed_clients_ip"]))
                    if (inArray($this->config["allowed_clients_ip"], $this->request->client_ip, false) === false)
                        throw new SystemException(403);
            }
        }

        if (!$this->request->apiKey)
            throw new SystemException(403);

        $result = $this->db->getByField("api_keys", "token", $this->request->apiKey, " AND (NOW() < valid_til OR valid_til IS NULL)");

        if (!$result)
            throw new SystemException(403);
    }
}