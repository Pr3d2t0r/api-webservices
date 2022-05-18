<?php

class XMLAdapter extends Adapter {
    public function set($data) {
        if (is_object($data))
            throw new Exception("Couldn't convert object to xml.");

        $this->data .= "<" . XML_ROOT_ELEMENT . ">";
        $this->data .= xml_encode($data);
        $this->data .= "</" . XML_ROOT_ELEMENT . ">";
    }
}