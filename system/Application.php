<?php

class Application
{
    public IRouter $router;
    public Request $request;
    public Security $security;

    public function __construct() {
        $this->router = new Router();
        $this->request = new Request($_GET['path'] ?? '/', $_SERVER['REQUEST_METHOD']);
    }

    public function run() {
        $this->security = new Security($this->request);

        $className = strtoupper($this->request->type) . "Adapter";
        $adapter = new $className();

        try {
            $this->security->isValid();

            $adapter->set($this->router->use($this->request));
        } catch (SystemException $e){
            http_response_code(intval($e->getCode()));
            exit;
        } catch (Exception $ex) {
            $adapter->set([
               "error" => $ex->getMessage()
            ]);
        } finally {
            echo $adapter->run();
            header('Content-Type:application/' . $this->request->type);
        }
    }
}