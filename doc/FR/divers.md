/**
 * 
 * Tested with : 

namespace App\Entity;
use ReflectionClass;
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

abstract class ForType extends LetEnum {
    
    const TODO = "todo";
    const EVENT = "event";
    const AGENDA = "agenda";
    const AGTYPE = "agtype";
    const FREEBUSY = "freebusy";
    
}

class Category {

    private $forTypes = [];
    
    public function __construct() {
        $this->forTypes = [];
    }

    public function getForTypes(): array {
        
        return $this->forTypes;
    }

    public function setForTypes(array $forTypes): self {
        $this->forTypes = $forTypes;

        return $this;
    }
    
    public function addForType(string $forType) : self {
        if(ForType::isValidValue($forType)) {
            $this->forTypes[strtoupper($forType)] = $forType;
        }
        
        return $this;
    }
    
    public function hasForType(string $forType) : bool {
        
        return in_array($forType, $this->forTypes);
    }
    
}

$cat = new Category();

$cat->addForType(ForType::TODO);
$cat->addForType(ForType::EVENT);
$cat->addForType("plop");

echo "Todo: ", var_export($cat->hasForType(ForType::TODO), true), "\n";
echo "todo: ", var_export($cat->hasForType("todo"), true), "\n";
echo "Event: ", var_export($cat->hasForType(ForType::EVENT), true), "\n";
echo "event: ", var_export($cat->hasForType("event"), true), "\n";
echo "FREEBUSY: ", var_export($cat->hasForType(ForType::FREEBUSY), true), "\n";
echo "plop: ", var_export($cat->hasForType("plop"), true), "\n";

