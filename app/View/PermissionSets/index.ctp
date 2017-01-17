<?php
echo $this->Html->css('permission');
echo $this->Html->script(array('jquery-lates', 'fixed_table_rc'));

$this->Html->addCrumb('My Permissions', 'javascript:void(0);', array('class' => 'breadcrumblast'));
?>

<script>
    $(document).ready(function(e) {
        setTimeout(function() {
            $('#fixed_hdr1').fxdHdrCol({
                fixedCols: 1,
                width: "100%",
                height: 500,
                colModal: [
                    {width: 244, align: 'center'},
                    {width: 70, align: 'center'},
                    {width: 70, align: 'center'},
                    {width: 70, align: 'center'},
                    {width: 70, align: 'center'},
                    {width: 70, align: 'center'},
                    {width: 70, align: 'center'},
                    {width: 70, align: 'center'},
                    {width: 70, align: 'center'},
                    {width: 70, align: 'center'},
                    {width: 70, align: 'center'},
                    {width: 70, align: 'center'},
                    {width: 70, align: 'center'},
                    {width: 70, align: 'center'},
                    {width: 70, align: 'center'},
                    {width: 70, align: 'center'},
                    {width: 70, align: 'center'},
                    {width: 70, align: 'center'},
                    {width: 70, align: 'center'},
                    {width: 70, align: 'center'},
                    {width: 70, align: 'center'},
                    {width: 70, align: 'center'},
                    {width: 70, align: 'center'},
                    {width: 70, align: 'center'},
                    {width: 70, align: 'center'},
                    {width: 70, align: 'center'},
                    {width: 70, align: 'center'},
                    {width: 70, align: 'center'},
                    {width: 70, align: 'center'},
                    {width: 70, align: 'center'},
                    {width: 70, align: 'center'},
                    {width: 70, align: 'center'},
                    {width: 70, align: 'center'},
                    {width: 70, align: 'center'},
                    {width: 70, align: 'center'},
                    {width: 70, align: 'center'},
                    {width: 70, align: 'center'},
                    {width: 70, align: 'center'},
                    {width: 70, align: 'center'},
                    {width: 70, align: 'center'},
                    {width: 70, align: 'center'},
                    {width: 70, align: 'center'},
                    {width: 70, align: 'center'},
                    {width: 70, align: 'center'},
                    {width: 70, align: 'center'},
                    {width: 70, align: 'center'},
                    {width: 70, align: 'center'},
                    {width: 70, align: 'center'},
		    {width: 70, align: 'center'},
                    {width: 70, align: 'center'},
                    {width: 70, align: 'center'},
                    {width: 70, align: 'center'},
                    {width: 70, align: 'center'},
                    {width: 70, align: 'center'},
                    {width: 70, align: 'center'},
                    {width: 70, align: 'center'},
                    {width: 70, align: 'center'},
                    {width: 70, align: 'center'},
                    {width: 70, align: 'center'},
                    {width: 70, align: 'center'},
                    {width: 70, align: 'center'},
                    {width: 70, align: 'center'},
                    {width: 70, align: 'center'},
                    {width: 70, align: 'center'},
                    {width: 70, align: 'center'},
                    {width: 70, align: 'center'},
                ]
            });
        }, 500);
    });

</script>

<div class="dwrapper">
    <div class="tblWrapper">
        <table id="fixed_hdr1" class="table">
            <thead>
                <?php
                $column_count = 1;
                $i = 1;

                $headers = array(__d('acl', 'ACTION', true));

                foreach ($roles as $role) {
                    $headers[] = $role['Role']['role_name'];
                    $column_count++;
                }
                // $thOptions = array('width' => '60%');

                echo $this->Html->tableHeaders($headers);
                ?>
            </thead>
            <tbody>
                <?php
                $permission_id = array();

                foreach ($actions as $action) {
                    echo '<tr onclick = "row_color(' . $i . ')" id=row_' . $i . '>';
                    echo '<td>';

                    // echo '<div style="float:left; width:20px; padding-right:3px;">' . $this->Form->radio('action', array('1' => ' '), array(false)) . '</div>';
                    echo $action['ControllerSet']['controller_name'] . '->' . $action['ControllerSet']['action_name'];
                    // echo '<div style="clear:both;"></div>';



                    echo '</td>';

                    foreach ($roles as $role) {

                        echo '<td align="center">';
                        echo '<div id="' . $action['ControllerSet']['id'] . '_' . $role['Role']['id'] . '" style="display:none; float:left;">';
                        echo $this->Html->image('design/waiting16.gif', array('class' => 'loader', 'id' => $action['ControllerSet']['id'] . '_' . $role['Role']['id']));
                        echo '</div>';


                        if ($role['Role']['role_name'] == 'ADMIN')
                            echo $this->Html->image('design/tick_disabled.png');
                        else {
                            foreach ($permissions as $permission) {
                                if ($role['Role']['id'] == $permission['PermissionSet']['role_id'] && $action['ControllerSet']['id'] == $permission['PermissionSet']['controller_id']) {

                                    echo '<div id= "' . 'ajax' . $action['ControllerSet']['id'] . '_' . $role['Role']['id'] . '" >';
                                    echo $this->Html->image('design/tick.png', array('class' => 'del_perm', 'id' => $action['ControllerSet']['id'] . '_' . $role['Role']['id'], 'name' => $permission['PermissionSet']['id']));
                                    echo '</div>';
                                    $permission_id[] = $permission['PermissionSet']['role_id'] . '_' . $permission['PermissionSet']['controller_id'];
                                }
                            }
                            $value = $role['Role']['id'] . '_' . $action['ControllerSet']['id'];
                            if (!in_array($value, $permission_id)) {

                                echo '<div id= "' . 'ajax' . $action['ControllerSet']['id'] . '_' . $role['Role']['id'] . '" >';
                                echo $this->Html->image('design/cross.png', array('class' => 'pointer', 'id' => $action['ControllerSet']['id'] . '_' . $role['Role']['id']));
                                echo '</div>';
                            }
                        }

                        echo '</td>';
                    }

                    echo '</tr>';
                    $i++;
                }
                ?>
            </tbody>
        </table>
    </div>
</div>


