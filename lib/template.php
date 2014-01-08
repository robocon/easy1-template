<?php defined('AM_EXEC') or die('Restricted Access');

class AM_Template{
    
    static $default_lang = 'th';
    protected $db;
    public function __construct($db) {
        if(count((array)$db)>8){
            $this->db = $db;
        }else{
            $connect = $db->connectdb(DB_NAME,DB_USERNAME,DB_PASSWORD);
            $this->db = $connect;
        }
    }

    public function loadBlock($position = '') {
        $db = $this->db;
        $query = $db->select_query("SELECT * FROM `".TB_BLOCK."` WHERE `pblock`='$position' AND `status` = 1 ORDER BY `sort` ASC");
        while ($item = $db->fetch($query)) {
            if ($position == 'left' 
                    || $position == 'right'
                    || $position == 'center'
                    || $position == 'user1') {
                ?>
                <div class="column-<?php echo $position?>">
                    <h3><?php echo $item['blockname']?></h3>
                    <div class="modules-sub-title"></div>
                    <div class="modules-data">
                        <?php
                        $this->loadCode($item);
                        ?>
                    </div>
                </div>
                <?php
            } else {
                $this->loadCode($item);
            }
        }
    }

    protected function loadCode($item){
        $db = $this->db;
        if (!$item['code']) {

            $file = 'block/'. $item['filename'] . '.' . $item['sfile'];
            if(is_file(AM_TEMP_DIR.'/modules/'.$file)){

                // Load language before load block
                $lang = AM_TEMP_DIR.'/lang/'.self::$default_lang.'/'.$file;
                if(is_file($lang)){
                    require_once $lang;
                }
                
                // Set language before using in block
                $l = new AM_Text($T);
                require_once AM_TEMP_DIR.'/modules/'.$file;
            }else{
                require_once 'modules/'.$file;
            }

        } else {
            echo $item['code'];
        }
    }

    public function block_count($positions){
        $db = $this->db;
        
        $locations = array();
        foreach ($positions as $value) {
            $locations[] = "'{$value}'";
        }
        
        $after_positions = implode(',', $locations);
        $sql = "SELECT `pblock`,COUNT(pblock) AS `prow`
FROM `".TB_BLOCK."` 
WHERE pblock in({$after_positions}) AND `status` = 1
GROUP BY `pblock` 
ORDER BY `pblock` ASC;";
        $query = $db->select_query($sql);
        
        $items = array();
        while($item = $db->fetch($query)){
            $pblock = $item['pblock'];
            $count = $item['prow'];
            $items[$pblock] = $count;
        }
        
        foreach($positions AS $position){
            if(array_key_exists($position, $items)===false){
                $items[$position] = 0;
            }
        }
        
        return $items;
    }
}
