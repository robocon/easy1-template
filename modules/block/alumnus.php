<?php defined('AM_EXEC') or die('Restricted Access');
$result = $db->select_query("select * from " . TB_ALUMNUS . " order by id DESC LIMIT 10");
$NRow = $db->rows($result);

if ($NRow == 0) {
    ?>
    <p><b><?php echo _ALUM_NULL ?></b></p>
    <?php
} else {
    ?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="grids">
        <tr class="odd" style="background-color: #CCE9FD; text-align: center; font-weight: bold;">
            <td width="170"><?php echo _ALUM_TABLE_COL2 ?></td>
            <td width="40"><?php echo _ALUM_TABLE_COL3 ?></td>
            <td width="40">ปีที่จบ</td>
            <td width="70">เบอร์ติดต่อ</td>
            <td width="60"><?php echo _ALUM_TABLE_COL6 ?></td>
        </tr>
        <?php
        while ($arr = $db->sql_fetchrow($result)) {

            $sex = "-";
            if ($arr[7] == "1") {
                $sex = _ALUM_SEX1;
            } elseif ($arr[7] == "2") {
                $sex = _ALUM_SEX2;
            } elseif ($arr[7] == "3" || empty($arr[7])) {
                $sex = _ALUM_SEX3;
            }

            $status = "-";
            if ($arr[17] != '' && $arr[18] == '') {
                $status = _ALUM_STATUS_EDU1;
            } else if ($arr[17] == '' && $arr[18] != '') {
                $status = _ALUM_STATUS_EDU2;
            } else {
                $status = _ALUM_STATUS_EDU3;
            }
            ?>
            <tr>
                <td>
                	<a href="index.php?name=alumnus&file=view&id=<?= $arr[0]; ?>" ><?php echo $arr[2] . " " . $arr[3]; ?></a>
                </td>
                <td align="center"><?php echo $sex ?></td>
                <td align="center"><?php echo $arr['11'] ?></td>
                <td align="center"><?php echo $arr['30'] ?></td>
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
    <a href=index.php?name=alumnus ><?php echo _ALUM_LINK_ALL ?></a>&nbsp;|&nbsp;<a href=index.php?name=alumnus&file=add><?php echo _ALUM_LINK_ADD ?></a>
</div>