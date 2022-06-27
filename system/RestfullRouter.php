<?php


/**
 * Class Router
 * @author Rafael Velosa
 */
class RestfullRouter implements IRouter {
     /**
     * Retorna o respetivo controlador pa pagina
     * @param Request $request
     * @return null
     */
    public function use(Request &$request) {
        $response = new RestfullResponse();

        if (!is_numeric($request->action))
            throw new Exception("Invalid parameters.");

        $response->setId($request->action);
        $response->setTable(str_replace("/", "", $request->page));
        $response->setRequest($request);

        if (method_exists($response, strtolower($request->method)))
            return $response->{strtolower($request->method)}();

        throw new SystemException(404);
    }
}