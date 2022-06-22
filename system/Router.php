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
    public function response(string $url, ResponseHandler $handler){
        $this->routes[$url] = $handler;
    }

    /**
     * Retorna o respetivo controlador pa pagina
     * @param Request $request
     * @return null
     */
    public function use(Request &$request){
        if (isset($this->routes[$request->page])) {
            $this->routes[$request->page]->setRequest($request);

            if (method_exists($this->routes[$request->page], $request->action)) {
                $this->routes[$request->page]->setParameters($request->parameters);
                return $this->routes[$request->page]->{$request->action}();
            }

            if (is_numeric($request->action)) {
                array_unshift($request->parameters, $request->action);
                $this->routes[$request->page]->setParameters($request->parameters);
                return $this->routes[$request->page]->index();
            }
        }

        if (isset($this->routes['404']['GET'])){
            return $this->routes['404']['GET']->index(['errorCode' => '404']);
        }
        throw new SystemException(404);
    }
}