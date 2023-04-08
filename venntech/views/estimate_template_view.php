<?php
defined('BASEPATH') or exit('No direct script access allowed');

init_head();

if (!isset($estimate_template)) {
    exit('No item is set');
}

?>

<div id="wrapper">
    <div class="content ">
        <div class="row ">
            <?php echo form_open_multipart('/admin/venntech/estimate_templates/edit'); ?>
            <div class="col-md-6">
                <div class="panel_s">
                    <div class="panel-body">

                        <!-- header with title -->
                        <h4 class="no-margin"><?php echo $title; ?></h4>
                        <hr class="hr-panel-heading"/>

                        <div class="row">
                            <div class="col-md-12">


                                <?php echo render_input('name', _l('name'), $estimate_template->name, "text", ['required' => true, 'maxLength' => 255]); ?>

                                <?php echo render_select('project_template_id', $project_templates, ['id', 'name'], _l('project_template'), $estimate_template->project_template_id, ['required' => true]); ?>



                                <?php
                                // render datatable initializes the structure of the table without data..
                                if($estimate_template->id != "") {
                                ?>
                                    <div class="form-group">
                                        <a href="/admin/venntech/estimate_template_elements/edit/<?php echo $estimate_template->id ?>">
                                            <button type="button" class="btn btn-info import btn-import-submit"><?php echo _l('add_new', _l('estimate_template_element')); ?></button>
                                        </a>
                                    </div>
                                <?php
                                    render_datatable(array(
                                        '#',
                                        _l('name'),
                                        _l('actions'),
                                    ), 'estimate-template-elements');

                                }

                                ?>


                                <?php echo form_hidden('id', $estimate_template->id); ?>

                            </div>
                        </div>

                        <!-- sticky footer with submit button -->
                        <div class="btn-bottom-toolbar text-right">
                            <a class="btn btn-info" href="/admin/venntech/estimate_templates"
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
            project_template_id: "required"
        });

        let template_id = <?php echo $estimate_template->id ?>;
        if(template_id != ""){
            let url = "<?php echo admin_url('venntech/estimate_templates/table_elements/' . $estimate_template->id ) ?>";
            initDataTable('.table-estimate-template-elements', url);
        }

    });

</script>
</body>
</html>
