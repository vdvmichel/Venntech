<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php if (isset($task) && isset($task_staffid) && isset($members)) { ?>
    <div class="row">
        <div class="col-md-12 ">
            <div class="panel_s">
                <div class="panel-body">

                    <div class="row">

                        <div class="col-md-6 ">
                            <?php echo render_select('task_staffid', $members, ['id', 'name'], _l('task_assigned'), $task_staffid, ['onchange' => 'change_task_staffid(this)', 'required' => 'true']); ?>
                        </div>

                        <div id="task_timer_btn"class="col-md-6 text-right mtop20">
                            <?php if ($task->billed == 0) {
                                $is_assigned = $task->current_user_is_assigned;
                                if (!$this->tasks_model->is_timer_started($task->id)) { ?>
                                    <p <?php if (!$is_assigned) { ?> data-toggle="tooltip" data-title="<?php echo _l('task_start_timer_only_assignee'); ?>"<?php } ?>>
                                        <a href="#"
                                           class="mbot10 btn<?php if (!$is_assigned || $task->status == Tasks_model::STATUS_COMPLETE) {
                                               echo ' disabled btn-default';
                                           } else {
                                               echo ' btn-success';
                                           } ?>"
                                           onclick="timer_action_venntech(this, <?php echo $task->id; ?>); return false;">
                                            <i class="fa fa-clock-o"></i> <?php echo _l('task_start_timer'); ?>
                                        </a>
                                    </p>
                                <?php } else { ?>
                                    <p>
                                        <a href="#" data-toggle="popover"
                                           data-placement="bottom"
                                           data-html="true" data-trigger="manual"
                                           data-title="<?php echo _l('note'); ?>"
                                           data-content='<?php echo render_textarea('timesheet_note'); ?><button type="button" onclick="timer_action_venntech(this, <?php echo $task->id; ?>, <?php echo $this->tasks_model->get_last_timer($task->id)->id; ?>);" class="btn btn-info btn-xs"><?php echo _l('save'); ?></button>'
                                           class="btn mbot10 btn-danger<?php if (!$is_assigned) {
                                               echo ' disabled';
                                           } ?>" onclick="return false;">
                                            <i class="fa fa-clock-o"></i> <?php echo _l('task_stop_timer'); ?>
                                        </a>
                                    </p>
                                <?php } ?>
                            <?php } ?>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>


    <script>

        let current_user_id = <?php echo get_staff_user_id(); ?>;
        let tooltip_assignee = '<?php echo _l('task_start_timer_only_assignee'); ?>';

        function change_task_staffid() {
            let task_staffid = document.getElementById("task_staffid").value;
            $.ajax({
                url: '/admin/venntech/taken/change_staffid/<?php echo $task->id; ?>',
                method: 'post',
                data: {'task_staffid': task_staffid},
                dataType: 'json',
                success: function (response) {
                    if (response.success == true) {
                        alert_float('success', response.message);

                        $('#task_timer_btn').empty();
                        $('#task_timer_btn').append(response.html);
                    } else {
                        alert_float('danger', response.message);
                        $('#task_timer_btn').empty();
                    }
                }
            });

        }


    </script>

    <script src="/modules/venntech/assets/js/venntech.js"></script>
<?php } ?>