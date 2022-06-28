<?php

class JSONAdapter extends Adapter {
    public function set($data) {
        $this->data = json_encode($data, JSON_PRETTY_PRINT);

        if (json_last_error() != 0) {
            switch (json_last_error()) {
                case JSON_ERROR_DEPTH:
                    throw new Exception("Profundidade máxima excedida.");
                case JSON_ERROR_STATE_MISMATCH:
                    throw new Exception("State mismatch.");
                case JSON_ERROR_CTRL_CHAR:
                    throw new Exception("Caracter de controlo encontrado.");
                CASE JSON_ERROR_SYNTAX:
                    throw new Exception("Erro de Síntaxe! String JSON mal-formada!");
                CASE JSON_ERROR_UTF8:
                    throw new Exception("Erro na codificação UTF-8!");
                default:
                    throw new Exception("Erro desconhecido! (" . json_last_error() . ")");
            }
        }
    }
}