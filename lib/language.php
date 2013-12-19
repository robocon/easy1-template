<?php
class AM_Text{
    protected $T;
    function __construct($T) {
        $this->T = $T;
    }
    
    /**
     * Check string in an array from template/easy1/lang/
     * 
     * @param type $string  String
     * @param type $force   Boolean
     * @return type         String
     */
    public function t($string,$force=false){
        $translation = $this->T;
        if(!is_null($translation) && array_key_exists($string, $translation) && $force===false){
            return $translation[$string];
        }else{
            return $string;
        }
    }
}