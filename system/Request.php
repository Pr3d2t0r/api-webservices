<?php
/**
 * Class Request
 * @author Rafael Velosa
 */
class Request{

    /**
     * Guarda o nome do controlador
     * @var string
     */
    public string $page;

    /**
     * Guarda a ação
     * @var string
     */
    public string $action;

    /**
     * Guarda os parametros do url
     * @var array
     */
    public array $parameters;

    public array $get = array();
    public array $post = array();
    public array $put = array();
    public array $delete = array();

    /**
     * Retorna o metodo do pedido
     * @var string
     */
    public string $method;

    /**
     * Guarda o tipo da resposta se é em json ou xml
     * @var string
     */
    public string $type = DEFAULT_TYPE;

    public ?string $apiKey;

    public string $client_ip;

    /**
     * Request constructor.
     * Envia o url para a função sanitizeUrl e devide o url e coloca cada parte no seu respetivo attr
     * @param string $url
     * @param string $method
     */
    public function __construct(string $url, string $method){
        $this->method = $method;
        $this->sanitizeUrl($url);
    }

    /**
     * Recbe o url, devide e coloca cada parte no seu respetivo attr
     * @param string $url
     */
    private function sanitizeUrl(string $url){
        $path = explode("/", $url);
        $in_arr = inArray($path, ["json", "xml"], false, ["get"]);
        if ($in_arr !== false){
            $this->type = $path[$in_arr];
            filter($path, function($value){
                return strtoupper($value) != "JSON" && strtoupper($value) != "XML";
            });
        }

        $this->page = (chkArray($path, 0) == null) ? '/' : $path[0].'/';

        $this->action = (chkArray($path,1) == null) ? 'index' : $path[1];

        // remove a pos que fica no fim do array caso o user ponha uma '/' no fim do link
        filter($path, function ($value) {
            return $value != "";
        });

        if (count($path)>2)
            $path = array_splice($path, 2);
        else
            $path = [];

        if (isset($_GET)) {
            foreach ($_GET as $key => $value)
                $this->get[$key] = $value;
        }

        if (isset($_POST)) {
            foreach ($_POST as $key => $value)
                $this->post[$key] = $value;
        }

        if (strtoupper($this->method) != "POST" && strtoupper($this->method) != "GET"){

            $lines = file('php://input');
            $keyLinePrefix = 'Content-Disposition: form-data; name="';

            $names = [];
            $counter = 0;
            $found = false;

            foreach ($lines as $num => $line) {
                if (strpos($line, $keyLinePrefix) !== false) {
                    $names[$counter] = substr($line, 38, -3);
                    $found = true;
                } else if($found) {
                    $this->{strtolower($this->method)}[$names[$counter]] = mb_substr($line, 0, -2, 'UTF-8');
                    if (strlen(trim($this->{strtolower($this->method)}[$names[$counter]])) > 0){
                        $found = false;
                        $counter++;
                    }
                }
            }
        }

        $this->parameters = $path;
        $this->apiKey = $this->get["apikey"] ?? null;

        unset($this->get["apikey"]);
        $this->client_ip = $_SERVER['REMOTE_ADDR'];
    }

    public function __toString(){
        return "[$this->method] -> Page: $this->page, Action: $this->action";
    }
}