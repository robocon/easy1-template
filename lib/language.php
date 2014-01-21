<?php
class AM_Text{
    static $lang;
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
    public function t($string,$force=false){
        $translation = self::$lang;
        if(!is_null($translation) && array_key_exists($string, $translation) && $force===false){
            return $translation[$string];
        }else{
            return $string;
        }
    }
}