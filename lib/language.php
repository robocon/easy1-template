<?php defined('AM_EXEC') or die('Restricted Access');
class AM_Text{
    private static $lang;
    function __construct() {
    }

    public static function add_text($T){
        if(is_null($T)) $T = array();

        if(is_null(self::$lang)){
            self::$lang = $T;
        }else{
            self::$lang = array_merge(self::$lang, $T);
        }
    }
    
    /**
     * Check string in an array from template/easy1/lang/
     * 
     * @param type $string  String
     * @param type $force   Boolean
     * @return type         String
     */
    public static function t($string,$force=false){
        $translation = self::$lang;
        if(!is_null($translation) && array_key_exists($string, $translation) && $force===false){
            return $translation[$string];
        }else{
            return $string;
        }
    }

    public static function sprintf($string){
	    $items = func_get_args();
	    if(count($items)>0){
		    $items[0] = self::t($items[0]);
		    return call_user_func_array('sprintf', $items);
	    }
    }
}
