<?php defined('AM_EXEC') or die('Restricted Access'); ?>
<style type="text/css">
#mainmenu-mobile{position: absolute; top: 0; right: 0; }
#mainmenu-icon{background-color: #3C8DC5; padding: 5px 3px 2px 3px; display: inline-block; }
@media (max-width: 480px) {
    #mainmenu-mobile{display: block;}
    #mainmenu-lists{display: none;}
}
@media(min-width: 480px){
    #mainmenu-mobile{display: none;}
    #mainmenu-lists{display: block!important;}
}
</style>
<div id="mainmenu-mobile">
    <i href="#" id="mainmenu-icon">
        <span class="icon-top"></span>
        <span class="icon-top"></span>
        <span class="icon-top"></span>
    </i>
</div>
<ul class="menu-lists" id="mainmenu-lists">
    <?php
	DBi::connect();
	$sql = "SELECT `name`, `menuname`, `links`, `target` FROM `".TB_PAGE."` WHERE `status`='1' AND `menugr`='mainmenu' ORDER BY `sort` ASC";
	$select = DBi::select($sql);
	while($item = $select->fetch_assoc()){
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
<script type="text/javascript">
$(function(){
    $('#mainmenu-icon').click(function(){
        $('#mainmenu-lists').slideToggle();
    });
});
</script>
