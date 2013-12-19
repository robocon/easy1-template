<?php defined('AM_EXEC') or die('Restricted Access'); ?>
<ul class="menu-lists">
    <?php
    $sql = "SELECT `name`, `menuname`, `links`, `target` FROM `".TB_PAGE."` WHERE `status`='1' AND `menugr`='mainmenu' ORDER BY `sort` ASC";
    $query = $db->select_query($sql);

    while ($item = $db->fetch($query)) {
        
        if (!is_null($item['links'])) {
            $uri = $item['links'];
        } else {
            $uri = "?name=page&file=page&op=" . $item['name'];
        }
        $target = $item['target'];
        ?>
        <li>
            <a href="<?php echo $uri ?>" target="<?php echo $target ?>">
                <?php echo $item['menuname'] ?>
            </a>
        </li>
        <?php
    }
    ?>
</ul>