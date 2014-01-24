<?php
/**
 * @copyright   (c) 3013 easy1 Atomy Maxsite
 * @license     MIT-LICENSE.txt
 */
define('AM_EXEC', 1);
define('AM_TEMPLATE_VERSION', '1.0');
define('AM_TEMP_DIR', str_replace("\\", "/", dirname(__FILE__)));
define('ROOT_TEMP', str_replace($_SERVER['DOCUMENT_ROOT']."/", "", AM_TEMP_DIR));
define('AM_APPLICATION', ROOT_TEMP."/modules/");

require_once 'lang/tem_thai_utf8.php';
require_once 'lib/database.php';
require_once 'lib/language.php';
require_once 'lib/template.php';
require_once 'lib/date.php';
require_once 'lib/utilities.php';

$temp = new AM_Template($db);

// Set position in template
$positions = array('pathway', 'left', 'right', 'user2', 'center', 'user1', 'bottom');
$locations = $temp->block_count($positions);

$query_config = $db->select_query("SELECT * FROM " . TB_CONFIG." AS a WHERE a.posit IN('footer1','footer2')");

$query_template = $db->select_query("SELECT * FROM " . TB_TEMPLATES." AS a WHERE a.sort=1 AND a.temname='easy1'");
$template = $db->fetch($query_template);
?>
<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

        <title><?php echo WEB_TITILE; ?></title>
        <meta name="description" content="<?php echo WEB_TITILE; ?>">
        <meta name="author" content="<?php echo WEB_TITILE; ?>">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        
    	<link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.3.0/pure-min.css">
    	<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
        
        <link rel="stylesheet" href="templates/easy1/css/style.css">
        <script src="templates/easy1/js/libs/modernizr-2.0.6.min.js"></script>
        <script src="templates/easy1/js/jquery-1.10.2.min.js"></script>
    </head>

    <body>
        <?php if($template!==false){ ?>
        <div id="logo-container">
            <div class="wrapper clearfix">
                <img src="templates/easy1/images/config/<?php echo $template['picname']?>">
            </div>
        </div>
        <?php } ?>
        <div id="header-cover">
            <div id="header-container" class="navbar-fixed">
                <header class="wrapper clearfix">
                    <div id="web-title">
                        <?php echo WEB_TITILE; ?>
                        <i href="#" id="menu-icon">
                            <span class="icon-top"></span>
                            <span class="icon-top"></span>
                            <span class="icon-top"></span>
                        </i>
                    </div>
                    <nav>
                        <ul>
                            <li><a href="index.php">หน้าแรก</a></li>
                            <li><a href="index.php?name=news">ข่าวสาร</a></li>
                            <li><a href="index.php?name=webboard">เว็บบอร์ด</a></li>
                            <li><a href="index.php?name=gallery">รูปภาพ</a></li>
                            <li><a href="index.php?name=gbook">สมุดเยี่ยม</a></li>
                            <li><a href="index.php?name=admin">Admin</a></li>
                        </ul>
                    </nav>
                    
                </header>
            </div>
        </div>
        <div id="main-container">
            <div id="main" class="wrapper clearfix">

                <?php if ($locations['pathway'] > 0 ) { ?>
                    <?php $temp->loadBlock('pathway'); ?>
                <?php } ?>
                <?php $left = $right = false; ?>
                <?php if ($locations['left'] > 0 && $name!='admin') { $left = true;?>
                    <div id="left-contain">
                    <?php $temp->loadBlock('left'); ?>
                    </div>
                <?php } ?>
                
                <?php
                if ($locations['right'] > 0 && ($name=='index' || $name=='') && $name!=='admin') {
                    $right = true;
                }
                
                if($left==true && $right==true){
                    $contain_width = 'width:563px';
                }else if($left==true && $right==false){
                    $contain_width = 'width:780px';
                }
                ?>
                
                <div id="content-main" style="<?php echo $contain_width?>">

		<?php
		$x_message = addslashes($_SESSION['x_message']);
		if($x_message){
			?>
			<div class="x-message"> <?php echo $x_message; ?> </div>
			<?php
			$_SESSION['x_message'] = null;
		}
		?>

                    <?php if($name=='index' || $name==''){ ?>
                        <?php if ($locations['user2'] > 0) { ?>
                            <?php $temp->loadBlock('user2'); ?>
                        <?php } ?>

                        <?php if ($locations['center'] > 0) { ?>
                            <?php $temp->loadBlock('center'); ?>
                        <?php } ?>

                        <?php if ($locations['user1'] > 0) { ?>
                            <?php $temp->loadBlock('user1'); ?>
                        <?php } ?>
                    
                    <?php }else{ ?>
                        <?php

                        // Reserved method $_GET
                        $mod_name = isset($_GET['name']) ? strval($_GET['name']) : 'index' ;
                        $mod_file = isset($_GET['file']) ? strval($_GET['file']) : 'index' ;
                        $mod_path = '/modules/'.str_replace('../','',$mod_name).'/'.str_replace('../','',$mod_file).'.php';

                        if(is_file(AM_TEMP_DIR.$mod_path)){

                            // Load language before load modules
                            $lang = AM_TEMP_DIR.'/lang/'.AM_Template::$default_lang.'/'.$mod_name.'/'.$mod_file.'.php';
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
                        ?>
                    <?php } ?>
                </div>
                
                <?php if (CountBlock('right') && ($name=='index' || $name=='') && $name!=='admin') { ?>
                    <div id="right-contain">
                    <?php $temp->loadBlock('right'); ?>
                    </div>
                <?php } ?>
                
            </div> <!-- #main -->
        </div> <!-- #main-container -->

        <div id="footer-container">
            <footer class="wrapper">
                <?php if ($locations['bottom'] > 0) { ?>
                    <?php $temp->loadBlock('bottom'); ?>
                <?php } ?>
                <nav>
                    <ul>
                        <li><a href="index.php">หน้าแรก</a></li>
                        <li><a href="index.php?name=news">ข่าวสาร</a></li>
                        <li><a href="index.php?name=webboard">เว็บบอร์ด</a></li>
                        <li><a href="index.php?name=gallery">รูปภาพ</a></li>
                        <li><a href="index.php?name=gbook">สมุดเยี่ยม</a></li>
                        <li><a href="index.php?name=admin">Admin</a></li>
                    </ul>
                </nav>
                <div id="footer-detials">
                    <?php while($config = $db->fetch($query_config)){ ?>
                    <p><?php echo $config['name']?></p>
                    <?php } ?>
                    <p>HTML5 Template from <a href="http://www.initializr.com/">Initializr</a>, redesign by <a href="mailto:kinglizardsg@gmail.com">KinglizardSG</a></p>
                </div>
            </footer>
        </div>
        <div id="btp">Back to top &uarr;</div>

        <script type="text/javascript">
            jQuery.noConflict();
            (function( $ ) {
              $(function() {
                var $w = $(window);
                var nav = $('#header-container');
                var navTopY = nav.offset().top;
                $w.scroll(function() {
                    var scrollWindow = $w.scrollTop();
                    if (scrollWindow > navTopY) {
                        nav.removeClass('navbar-fixed').addClass('navbar-fixed-top');
                        $('#btp').fadeIn(500);
                    } else {
                        nav.removeClass('navbar-fixed-top').addClass('navbar-fixed');
                        $('#btp').fadeOut(500);
                    }
                });
                
                $('#btp').click(function(event) {
                    event.preventDefault();
                    $('html, body').animate({scrollTop: 0}, 300);
                });
                
                $('#menu-icon').click(function(){
                    $('#header-container header nav').slideToggle();
                });
                
              });
            })(jQuery);
        </script>
        
    </body>
</html>
