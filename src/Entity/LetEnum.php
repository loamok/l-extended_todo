<?php

namespace App\Entity;

use ReflectionClass;

/**
 * Description of LetEnum
 *
 * @author symio
 */
abstract class LetEnum {
    
    private static $constCacheArray = NULL;

    private static function getConstants() : array {
        if (self::$constCacheArray == NULL) {
            self::$constCacheArray = [];
        }
        
        $calledClass = get_called_class();
        if (!array_key_exists($calledClass, self::$constCacheArray)) {
            $reflect = new ReflectionClass($calledClass);
            self::$constCacheArray[$calledClass] = $reflect->getConstants();
        }
        
        return self::$constCacheArray[$calledClass];
    }

    public static function isValidName(string $name, ?bool $strict = false) : bool {
        $res = false;
        
        $constants = self::getConstants();

        if ($strict) {
            $res = array_key_exists($name, $constants);
        } else {
            $keys = array_map('strtolower', array_keys($constants));
            $res = in_array(strtolower($name), $keys);
        }
        
        return $res;
    }

    public static function isValidValue($value, ?bool $strict = true) : bool {
        $values = array_values(self::getConstants());
        
        return in_array($value, $values, $strict);
    }
    
}