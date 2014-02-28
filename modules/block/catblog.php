<?php defined('AM_EXEC') or die('Restricted Access');
$mysqli = DBi::connect();
$sql = "SELECT a.`id`, a.`category_name`,b.`blog_row` FROM
`".TB_BLOG_CAT."` AS a
LEFT JOIN(
	SELECT COUNT(`id`) AS `blog_row`, `category` FROM `".TB_BLOG."` GROUP BY `category` ORDER BY `category` ASC
) AS b ON b.`category` = a.`id`
ORDER BY a.`sort` ASC";
$select = DBi::select($sql);
?>
<ul class="menu-lists">
    <?php
    while ($groupstext = $select->fetch_assoc()) {
        $row = is_null($groupstext['blog_row']) ? 0 : $groupstext['blog_row'] ;
    ?>
    <li>
        <a href="index.php?name=blog&category=<?php echo $groupstext['id']?>"><?php echo $groupstext['category_name'] . " (" . $row . ")"?></a>
    </li>
    <?php
    }
    ?>
</ul>
