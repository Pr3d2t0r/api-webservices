<?php
/**
 * Verifica se a chave existe no array e se existir retorna
 * @param array $array
 * @param string|int $key
 * @return mixed | null
 */
function chkArray($array,$key) {
    if (isset($array[$key]) && !empty($array[$key]))
        return $array[$key];
    return null;
}

/**
 * Filtra um array e retorna apenas os que passarem o filtro
 * @param array $array
 * @param closure $callback
 * @return array
 */
function filter($array, $callback){
    $newArray = [];

    foreach ($array as $item) {
        if($callback($item)) $newArray[] = $item;
    }

    return $newArray;
}

function chunkArray($array, $nItens=3){
    $count = 0;
    $x = 1;
    $new_arr = [];
    for ($i = 0; $i < count($array); $i++) {
        if ($count == $nItens) {
            $count = 0;
            $x++;
        }
        $new_arr[$x][] = $array[$i];
        $count++;
    }
    return $new_arr;
}

function inArray($array, $needles, $sensitiveCase=true, $ignore=[]) {
    foreach ($array as $key => $item) {
        if (is_array($array)) {
            if (in_array($key, $ignore, true))
                continue;
        }

        if (is_array($needles)) {
            foreach ($needles as $needle) {
                if (is_numeric($needle) || is_numeric($item))
                    continue;

                if ($sensitiveCase ? ($needle === strtoupper($item)) : $needle === $item) {
                    return $key;
                }
            }
        } else {
            if (($sensitiveCase ? $needles : strtoupper($needles)) == ($sensitiveCase ? $item : strtoupper($item))) return $key;
        }
    }
    return false;
}

function xml_encode(array $arr, string $name_for_numeric_keys = "key"): string {
    if (empty ( $arr )) {
        // avoid having a special case for <root/> and <root></root> i guess
        return '';
    }
    $is_iterable_compat = function ($v): bool {
        // php 7.0 compat for php7.1+'s is_itrable
        return is_array ( $v ) || ($v instanceof \Traversable);
    };
    $isAssoc = function (array $arr): bool {
        // thanks to Mark Amery for this
        if (array () === $arr)
            return false;
        return array_keys ( $arr ) !== range ( 0, count ( $arr ) - 1 );
    };
    $endsWith = function (string $haystack, string $needle): bool {
        // thanks to MrHus
        $length = strlen ( $needle );
        if ($length == 0) {
            return true;
        }
        return (substr ( $haystack, - $length ) === $needle);
    };
    $formatXML = function (string $xml) use ($endsWith): string {
        // there seems to be a bug with formatOutput on DOMDocuments that have used importNode with $deep=true
        // on PHP 7.0.15...
        $domd = new DOMDocument ( '1.0', 'UTF-8' );
        $domd->preserveWhiteSpace = false;
        $domd->formatOutput = true;
        $domd->loadXML ( '<root>' . $xml . '</root>' );
        $ret = trim ( $domd->saveXML ( $domd->getElementsByTagName ( "root" )->item ( 0 ) ) );
        assert ( 0 === strpos ( $ret, '<root>' ) );
        assert ( $endsWith ( $ret, '</root>' ) );
        $full = trim ( substr ( $ret, strlen ( '<root>' ), - strlen ( '</root>' ) ) );
        $ret = '';
        // ... seems each line except the first line starts with 2 ugly spaces,
        // presumably its the <root> element that starts with no spaces at all.
        foreach ( explode ( "\n", $full ) as $line ) {
            if (substr ( $line, 0, 2 ) === '  ') {
                $ret .= substr ( $line, 2 ) . "\n";
            } else {
                $ret .= $line . "\n";
            }
        }
        $ret = trim ( $ret );
        return $ret;
    };

    // $arr = new RecursiveArrayIterator ( $arr );
    // $iterator = new RecursiveIteratorIterator ( $arr, RecursiveIteratorIterator::SELF_FIRST );
    $iterator = $arr;
    $domd = new DOMDocument ();
    $root = $domd->createElement ( 'root' );
    foreach ( $iterator as $key => $val ) {
        // var_dump ( $key, $val );
        $ele = $domd->createElement ( is_int ( $key ) ? $name_for_numeric_keys : $key );
        if (! empty ( $val ) || $val === '0') {
            if ($is_iterable_compat ( $val )) {
                $asoc = $isAssoc ( $val );
                $tmp = hhb_xml_encode ( $val, is_int ( $key ) ? $name_for_numeric_keys : $key );
                // var_dump ( $tmp );
                // die ();
                $tmpDom = new DOMDocument();
                @$tmpDom->loadXML ( '<root>' . $tmp . '</root>' );
                foreach ( $tmpDom->getElementsByTagName ( "root" )->item ( 0 )->childNodes ?? [ ] as $tmp2 ) {
                    $tmp3 = $domd->importNode ( $tmp2, true );
                    if ($asoc) {
                        $ele->appendChild ( $tmp3 );
                    } else {
                        $root->appendChild ( $tmp3 );
                    }
                }
                unset ( $tmp, $tmp2, $tmp3, $tmpDom );
                if (! $asoc) {
                    // echo 'REMOVING';die();
                    // $ele->parentNode->removeChild($ele);
                    continue;
                }
            } else {
                $ele->textContent = $val;
            }
        }
        $root->appendChild ( $ele );
    }
    $domd->preserveWhiteSpace = false;
    $domd->formatOutput = true;
    $ret = trim ( $domd->saveXML ( $root ) );
    assert ( 0 === strpos ( $ret, '<root>' ) );
    assert ( $endsWith ( $ret, '</root>' ) );
    $ret = trim ( substr ( $ret, strlen ( '<root>' ), - strlen ( '</root>' ) ) );
    // seems to be a bug with formatOutput on DOMDocuments that have used importNode with $deep=true..
    $ret = $formatXML ( $ret );
    return $ret;
}