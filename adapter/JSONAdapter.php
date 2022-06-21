<?php

class JSONAdapter extends Adapter {
    public function set($data) {
        $this->data = json_encode($data);

        if (json_last_error() != 0) {
            switch (json_last_error()) {
                case JSON_ERROR_DEPTH:
                    throw new AppException("Profundidade máxima excedida.");
                case JSON_ERROR_STATE_MISMATCH:
                    throw new AppException("State mismatch.");
                case JSON_ERROR_CTRL_CHAR:
                    throw new AppException("Caracter de controlo encontrado.");
                CASE JSON_ERROR_SYNTAX:
                    throw new AppException("Erro de Síntaxe! String JSON mal-formada!");
                CASE JSON_ERROR_UTF8:
                    throw new AppException("Erro na codificação UTF-8!");
                default:
                    throw new AppException("Erro desconhecido! (" . json_last_error() . ")");
            }
        }
    }
}