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
     * Guarda os parametros
     * @var array
     */
    public array $parameters;

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

        if (count($path)>2) $path = array_splice($path, 2);
        else $path = [];

        if (isset($_GET)) {
            $path = $path ?? [];
            foreach ($_GET as $key => $value)
                $path['get'][$key] = $value;
        }
        $this->parameters = $path;
    }

    public function __toString(){
        return "[$this->method] -> Page: $this->page, Action: $this->action";
    }
}