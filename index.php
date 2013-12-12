<?php
require_once 'templates/easy1/lang/tem_thai_utf8.php';
$db->connectdb(DB_NAME, DB_USERNAME, DB_PASSWORD);
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
        
        <link rel="stylesheet" href="templates/easy1/css/style.css">
        <script src="templates/easy1/js/libs/modernizr-2.0.6.min.js"></script>
        
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

                <?php if (CountBlock('pathway')) { ?>
                    <?php LoadBlock('pathway'); ?>
                <?php } ?>
                <?php $left = $right = false; ?>
                <?php if (CountBlock('left') && $name!='admin') { $left = true;?>
                    <div id="left-contain">
                    <?php LoadBlock('left'); ?>
                    </div>
                <?php } ?>
                
                <?php
                if (CountBlock('right') && ($name=='index' || $name=='') && $name!=='admin') {
                    $right = true;
                }
                
                if($left==true && $right==true){
                    $contain_width = 'width:563px';
                }else if($left==true && $right==false){
                    $contain_width = 'width:780px';
                }
                ?>
                
                <div id="content-main" style="<?php echo $contain_width?>">
                    <?php if($name=='index' || $name==''){ ?>
                        <?php if (CountBlock('user2')) { ?>
                            <?php LoadBlock('user2'); ?>
                        <?php } ?>

                        <?php if (CountBlock('center')) { ?>
                            <?php LoadBlock('center'); ?>
                        <?php } ?>

                        <?php if (CountBlock('user1')) { ?>
                            <?php LoadBlock('user1'); ?>
                        <?php } ?>
                    
                    <?php }else{ ?>
                        <?php require_once $MODPATHFILE; ?>
                    <?php } ?>
                </div>
                
                <?php if (CountBlock('right') && ($name=='index' || $name=='') && $name!=='admin') { ?>
                    <div id="right-contain">
                    <?php LoadBlock('right'); ?>
                    </div>
                <?php } ?>
                
            </div> <!-- #main -->
        </div> <!-- #main-container -->

        <div id="footer-container">
            <footer class="wrapper">
                <?php if (CountBlock('bottom')) { ?>
                    <?php LoadBlock('bottom'); ?>
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
                    <p>
                        <a rel="license" href="http://creativecommons.org/licenses/by-nc/4.0/"><img alt="Creative Commons License" style="border-width:0" src="http://i.creativecommons.org/l/by-nc/4.0/88x31.png" /></a><br />This work is licensed under a <a rel="license" href="http://creativecommons.org/licenses/by-nc/4.0/">Creative Commons Attribution-NonCommercial 4.0 International License</a>.
                    </p>
                </div>
            </footer>
        </div>
        <div id="btp">Back to top &uarr;</div>

        <script src="templates/easy1/js/jquery-1.10.2.min.js"></script>
        <script type="text/javascript">
            jQuery.noConflict();
            (function( $ ) {
              $(function() {
                // More code using $ as alias to jQuery
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