<?php
/**
 * Carrega automaticamente as classes
 * @param string $name
 * @return null
 */
function autoloader($name) {
    $file = APPLICATIONPATH . '/' . $name . '.php';
    $file2 = APPLICATIONPATH . '/../responses/' . $name . '.php';
    $file3 = APPLICATIONPATH . '/../adapter/' . $name . '.php';
    $file4 = APPLICATIONPATH . '/exceptions/' . $name . '.php';
    $file5 = APPLICATIONPATH . '/interface/' . $name . '.php';

    if (file_exists($file)) {
        require_once $file;
        return;
    }

    if (file_exists($file2)) {
        require_once $file2;
        return;
    }

    if (file_exists($file3)) {
        require_once $file3;
        return;
    }

    if (file_exists($file4)) {
        require_once $file4;
        return;
    }

    if (file_exists($file5)) {
        require_once $file5;
        return;
    }

    echo '404';

    return null;
}

spl_autoload_register('autoloader');