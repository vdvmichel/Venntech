<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
    <?php echo form_open_multipart('/admin/venntech/taak/edit'); ?>

    <?php if (isset($item->task_comments->id)) {
        echo form_hidden('task_comments[id]', $item->task_comments->id);
    } ?>
    <?php if (isset($task)) {
        echo form_hidden('task_comments[taskid]', $task->id);
    } ?>
    <div class="content ">

        <?php include 'top_task_view.php' ?>

        <div class="row">
            <div class="col-md-6">
                <div class="panel_s">
                    <div class="panel-body">
                        <!-- header with title -->
                        <h4 class="no-margin"><?php echo $title; ?></h4>
                        <hr class="hr-panel-heading"/>
                        <?php echo render_textarea('task_comments[content]', _l('module_description'), $item->task_comments->content, ['maxLength' => 1023]); ?>

                        <!-- sticky footer with submit button -->
                        <div class="btn-bottom-toolbar text-right">
                            <a class="btn btn-info" href="/admin/venntech/taken"
                               role="button"><?php echo _l('cancel'); ?></a>

                            <?php if (($edit_type == "edit" && staff_can('edit', FEATURE_TAKEN))
                                || ($edit_type == "create" && staff_can('create', FEATURE_TAKEN))) { ?>

                                <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>

                                <?php if (isset($task->id) && $task->status != 5) { ?>
                                    <button type="submit" class="btn btn-success" name="complete"
                                            value="Complete"><?php echo _l('task_single_mark_as_complete'); ?></button>
                                <?php } ?>

                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
<?php init_tail(); ?>
<script type="text/javascript">
    $(function () {
        appValidateForm($('form'), {
            'task_comments[content]': "required",
        });
    });
</script>
</body>
</html>
