<?php defined('AM_EXEC') or die('Restricted Access');

include("modules/admin/workboard/includes/functions.php");
?>
<style type="text/css">
.progress-cover{display: block; width: 100%; height: 5px; background: #FFF; border: 1px solid #BEBEBE; padding: 1px; }
.progress-bar{height: 5px; display: block; background: #FF8F00; }
</style>
<table class="grids" >
    <tr>
        <th><?php echo $l->t('Project name'); ?></th>
        <th width="30"><?php echo $l->t('Status'); ?></th>
        <th width="180"><?php echo $l->t('Progress'); ?></th>
        <th width="20">%</th>
    </tr>
    <?php
	DBi::connect();
    $sql = "SELECT a.*, b.status_name FROM"
            . " ".TB_WORKBOARD_PROJECTS." AS a LEFT JOIN ".TB_WORKBOARD_STATUS." AS b ON a.status_id = b.status_id"
            . " ORDER BY a.project_name LIMIT 10";
	$query = DBi::select($sql);
    $count = 0;
	while($project = $query->fetch_assoc()){
        // Check status
        $status = !empty($project['status_name']) ? $project['status_name'] : "<i>" . $l->t('None') . "</i>";

        $set_width = '0%';
        if (!empty($project['status_percent'])) {
            $set_width = $project['status_percent'] . "%";
        }
        ?>
        <tr>
            <td><a href="index.php?name=workboard&file=project&project_id=<?php echo $project['project_id'] ?>"><?php echo $project['project_name'] ?></a></td>
            <td align="center"><?php echo $status ?></td>
            <td>
                <div class="progress-cover" title="<?php echo $project['status_percent'] ?>%"><span class="progress-bar" style="width:<?php echo $set_width ?>;"></span></div>
            </td>
            <td align="center"><?php echo $project['status_percent'] ?></td>
        </tr>
        <?php
    }
    ?>
</table>
<a href="index.php?name=workboard" class="readmore"><?php echo $l->t('See more project'); ?></a>
