<?php

class Application
{
    public Router $router;
    public Request $request;
    public function __construct() {
        $this->router = new Router();
        $this->request = new Request($_GET['path'] ?? '/', $_SERVER['REQUEST_METHOD']);
    }

    public function run() {
        try {
            $this->router->use($this->request);
        } catch (Exception $ex) {
            $className = strtoupper($this->request->parameters['type']) ."Adapter";
            $adapter = new $className();
            $adapter->set([
               "error" => $ex->getMessage()
            ]);

            echo $adapter->run();
        } finally {
            header('Content-Type:application/' . $this->request->parameters["type"]);
        }
    }
}