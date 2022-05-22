<?php


/**
 * Class Router
 * @author Rafael Velosa
 */
class Router{

    /**
     * Guarda as rotas
     * @var array
     */
    private array $routes = [];

    /**
     * Define o controlador para quando essa pagina for requesitada
     * @param string $url
     * @param ResponseHandler $handler
     * @return null
     */
    public function get(string $url, ResponseHandler $handler){
        $this->routes[$url]['GET'] = $handler;
    }

    /**
     * Define o manipulador para quando houver um pedido post para essa pagina
     * @param $url string
     * @param ResponseHandler $handler
     * @return null
     */
    public function post(string $url, ResponseHandler $handler){
        $this->routes[$url]['POST'] = $handler;
    }

    /**
     * Retorna o respetivo controlador pa pagina
     * @param Request $request
     * @return null
     */
    public function use(Request &$request){
        if(isset($this->routes[$request->page][$request->method])) {
            if (method_exists($this->routes[$request->page][strtoupper($request->method)], $request->action)) {
                return $this->routes[$request->page][strtoupper($request->method)]->{$request->action}($request->parameters);
            }
            if (is_numeric($request->action)){
                array_unshift($request->parameters, $request->action);
                return $this->routes[$request->page][strtoupper($request->method)]->index($request->parameters);
            }
        }

        if(isset($this->routes['404']['GET'])){
            return $this->routes['404']['GET']->index(['errorCode' => '404']);
        }
        throw new Exception("404 Page Not Found");
    }
}