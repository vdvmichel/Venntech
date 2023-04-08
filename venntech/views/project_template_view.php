<?php
defined('BASEPATH') or exit('No direct script access allowed');

init_head();

if (!isset($project_template)) {
    exit('No item is set');
}

?>

<div id="wrapper">
    <div class="content ">
        <div class="row ">
            <?php echo form_open_multipart('/admin/venntech/project_templates/edit'); ?>
            <div class="col-md-6">
                <div class="panel_s">
                    <div class="panel-body">

                        <!-- header with title -->
                        <h4 class="no-margin"><?php echo $title; ?></h4>
                        <hr class="hr-panel-heading"/>

                        <div class="row">
                            <div class="col-md-12">


                                <?php echo render_input('name', _l('name'), $project_template->name, "text", ['required' => true, 'maxLength' => 255]); ?>
                                <?php echo render_textarea('description', _l('description'), $project_template->description, ['maxLength' => 1023]); ?>

                                <?php

                                if ($edit_type == 'edit') {
                                    echo '<table class="table table-producten dataTable no-footer">';
                                    foreach ($template_tasks as $key => $template_task) {

                                        echo '<tr><td class="col-xs-8">';
                                        echo get_settings_taak_name($template_task['instellingen_taak_id']);
                                        echo '</td>';

                                        echo '<td class="col-xs-2">';
                                        echo '<div class="onoffswitch">
                                                <input type="checkbox" data-switch-url="' . admin_url("venntech/project_templates/change_task_status") . '" name="onoffswitch" class="onoffswitch-checkbox" id="' . $template_task['id'] . '" data-id="' . $template_task['id'] . '" ' . ($template_task['enabled'] == 1 ? 'checked' : '') . '>
                                                <label class="onoffswitch-label" for="' . $template_task['id'] . '"></label>
                                              </div>';
                                        echo '</td>';

                                        echo '<td class="col-xs-2">';
                                        if ($key != 0) {
                                            echo icon_btn('venntech/project_templates/task_order_up/' . $project_template->id . '/' . $template_task['id'], 'angle-up', 'btn-info');
                                        }
                                        if ($key != sizeof($template_tasks) - 1) {
                                            echo icon_btn('venntech/project_templates/task_order_down/' . $project_template->id . '/' . $template_task['id'], 'angle-down', 'btn-info');
                                        }
                                        echo '</td></tr>';
                                    }
                                    echo '</table>';
                                }
                                ?>

                                <?php echo form_hidden('id', $project_template->id); ?>

                            </div>
                        </div>


                        <!-- sticky footer with submit button -->
                        <div class="btn-bottom-toolbar text-right">
                            <a class="btn btn-info" href="/admin/venntech/project_templates"
                               role="button"><?php echo _l('cancel'); ?></a>
                            <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
                        </div>

                    </div>
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<?php init_tail(); ?>
<script type="text/javascript">
    $(function () {
        appValidateForm($('form'), {
            name: "required",
        });

        //$( "#all_tasks_elements" ).sortable();
    });

</script>
</body>
</html>
