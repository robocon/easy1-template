<?php defined('AM_EXEC') or die('Restricted Access');
class AM_Template{
    
    public static $default_lang = 'th';
    protected $db;
    public function __construct($db) {
        require_once AM_TEMP_DIR.'/lang/'.self::$default_lang.'/'.self::$default_lang.'.php';
        $l = new AM_Text();
        AM_Text::add_text($T);

        $ajax = intval($_POST['ajax']);
        if($ajax > 0){
            $this->load_ajax();
        }
    }

    public function loadBlock($position = '') {
	DBi::connect();
	$query = DBi::select("SELECT * FROM `".TB_BLOCK."` WHERE `pblock`=? AND `status` = 1 ORDER BY `sort` ASC", array($position));
        while ($item = $query->fetch_assoc()) {
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
                AM_Text::add_text($T);
                $l = new AM_Text();
                require_once AM_TEMP_DIR.'/modules/'.$file;
            }else{
                require_once 'modules/'.$file;
            }

        } else {
            echo $item['code'];
        }
    }

    public function block_count($positions){
	$dbi = DBi::connect();
        $locations = array();
        foreach ($positions as $value) {
            $locations[] = "'{$value}'";
        }
        
        $after_positions = implode(',', $locations);
        $sql = "SELECT `pblock`,COUNT(pblock) AS `prow`
FROM `".TB_BLOCK."` 
WHERE `pblock` IN( $after_positions ) AND `status` = 1
GROUP BY `pblock` 
ORDER BY `pblock` ASC;";
	$res = DBi::select($sql);
        $items = array();
        while($item = $res->fetch_assoc()){
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

    protected function load_ajax(){
        $admin_user = empty($_SESSION['admin_user'])? "" : $_SESSION['admin_user'];
        $admin_pwd = empty($_SESSION['admin_pwd'])? "" : $_SESSION['admin_pwd'];
        $login_true = empty($_SESSION['login_true'])? "" : $_SESSION['login_true'];
        $pwd_login = empty($_SESSION['pwd_login'])? "" : $_SESSION['pwd_login'];

        require_once("../../includes/config.in.php");
        require_once("../../includes/function.in.php");
        require_once("../../includes/class.mysql.php");

        $db = New DB();
        $db->connectdb(DB_NAME,DB_USERNAME,DB_PASSWORD);

        $this->load_modules($db);
    }

    public function load_modules($db){

        // Reserved method $_GET
        $mod_name = isset($_GET['name']) ? strval($_GET['name']) : 'index' ;
        $mod_file = isset($_GET['file']) ? strval($_GET['file']) : 'index' ;
        $mod_path = '/modules/'.str_replace('../','',$mod_name).'/'.str_replace('../','',$mod_file).'.php';

        if(is_file(AM_TEMP_DIR.$mod_path)){

            // Load language before load modules
            $lang = AM_TEMP_DIR.'/lang/'.self::$default_lang.'/'.$mod_name.'/'.$mod_file.'.php';
            if(is_file($lang)){
                require_once $lang;
            }

            // Set language before using in block
            AM_Text::add_text($T);
            $l = new AM_Text();
            require_once AM_TEMP_DIR.$mod_path;
        }else{
            $original_path = dirname(dirname(AM_TEMP_DIR));
            require_once $original_path.$mod_path;
        }
    }
}
