<?php


/**
 * Class Router
 * @author Rafael Velosa
 */
class Router implements IRouter {
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
        } else {
            if (RESTFULL ?? false) {
                if (!is_numeric($request->action))
                    throw new Exception("Invalid parameter.");

                $response = new RestfullResponse();

                $response->setId($request->action);
                $response->setTable(str_replace("/", "", $request->page));
                $response->setRequest($request);

                if (method_exists($response, strtolower($request->method)))
                    return $response->{strtolower($request->method)}();
            }
        }

        if (isset($this->routes['404']))
            return $this->routes['404']->index([ 'errorCode' => '404' ]);

        throw new SystemException(404);
    }
}