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
        $className = strtoupper($this->request->type) . "Adapter";
        $adapter = new $className();

        try {
            $this->request->security->isValid();

            $adapter->set($this->router->use($this->request));
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