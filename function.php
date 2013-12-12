<?php

function LoadBlock($pblock = "") {
    global $db;
    $db->connectdb(DB_NAME, DB_USERNAME, DB_PASSWORD);
    $query = $db->select_query("SELECT * FROM " . TB_BLOCK . " WHERE status='1' and pblock='{$pblock}' order by sort");

    while ($item = $db->fetch($query)) {

        if ($pblock == 'left' || $pblock == 'right') {
            ?>
            <div class="column-<?php echo $pblock?>">
                <h3><?php echo $item['blockname']?></h3>
                <div class="modules-sub-title"></div>
                <div class="modules-data">
                    <?php
                    loadCode($item);
                    ?>
                </div>
            </div>
            <?php
        } else if ($pblock == 'center' || $pblock == 'user1') {
            ?>
            <div class="column-center">
                <h3><?php echo $item['blockname']?></h3>
                <div class="modules-sub-title"></div>
                <?php
                loadCode($item);
                ?>
            </div>
            <?php
        } else {
            loadCode($item);
        }
    }
}

function loadCode($item){
    global $db;
    if (!$item['code']) {
        
        $file = __DIR__.'/modules/block/'. $item['filename'] . '.' . $item['sfile'];
        if(is_file($file)){
            require_once $file;
        }else{
            require_once 'modules/block/'. $item['filename'] . '.' . $item['sfile'];
        }
        
    } else {
        echo $item['code'];
    }
}