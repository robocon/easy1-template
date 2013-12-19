<?php defined('AM_EXEC') or die('Restricted Access');
$result = $db->select_query("SELECT * FROM " . TB_ALUMNUS . " ORDER BY id DESC LIMIT 10");
$NRow = $db->rows($result);

if ($NRow == 0) {
    ?>
    <p><b><?php echo $l->t('No data'); ?></b></p>
    <?php
} else {
    ?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="grids">
        <tr>
            <th><?php echo $l->t('Name'); ?></th>
            <th><?php echo $l->t('Sex'); ?></th>
            <th><?php echo $l->t('Graduation'); ?></th>
            <th><?php echo $l->t('Phone'); ?></th>
            <th><?php echo $l->t('Status'); ?></th>
        </tr>
        <?php
        while ($arr = $db->fetch($result)) {
            $sex = "-";
            if ($arr['sex'] == "1") {
                $sex = $l->t('Male');
            } elseif ($arr['sex'] == "2") {
                $sex = $l->t('Female');
            } elseif ($arr['sex'] == "3" || empty($arr['sex'])) {
                $sex = $l->t('Not sure');
            }

            $status = "-";
            if ($arr['school'] != '' && $arr['WORK'] == '') {
                $status = $l->t('Study');
            } else if ($arr['school'] == '' && $arr['WORK'] != '') {
                $status = $l->t('Work');
            } else {
                $status = $l->t('No data');
            }
            ?>
            <tr>
                <td>
                	<a href="index.php?name=alumnus&file=view&id=<?= $arr['id']; ?>" ><?php echo $arr['first_name'] . " " . $arr['last_name']; ?></a>
                </td>
                <td align="center"><?php echo $sex ?></td>
                <td align="center"><?php echo $arr['yearfin'] ?></td>
                <td align="center"><?php echo $arr['tel'] ?></td>
                <td align="center"><?php echo $status ?></td>
            </tr>
        <?php
    }
    ?>
    </table>
        <?php
    }
    ?>
<div style="text-align: right; font-weight: bold;">
    <a href=index.php?name=alumnus ><?php echo $l->t('See more'); ?></a>&nbsp;|&nbsp;<a href=index.php?name=alumnus&file=add><?php echo $l->t('Register'); ?></a>
</div>