<?php

class DataConverter {
    public static function arrayToQueryXml($data, $xml){
        foreach ($data as $k => $v) {
            is_array($v)
                ? self::arrayToQueryXml($v, $xml->addChild($k))
                : $xml->addChild($k, $v);
        }
        return $xml;
    }
}
