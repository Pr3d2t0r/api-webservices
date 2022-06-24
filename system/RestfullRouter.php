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
    public function use(Request &$request){

        throw new SystemException(404);
    }
}