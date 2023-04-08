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
            <?php echo form_open_multipart('/admin/venntech/estimate_template_elements/edit/' . $estimate_template->id); ?>
            <div class="col-md-6">
                <div class="panel_s">
                    <div class="panel-body">

                        <!-- header with title -->
                        <h4 class="no-margin"><?php echo $title; ?></h4>
                        <hr class="hr-panel-heading"/>

                        <div class="row">
                            <div class="col-md-12">


                                <?php echo render_input('estimate_template[name]', _l('estimate_template'), $estimate_template->name, "text", ['disabled' => true]); ?>

                                <?php echo render_select('project_template_id', $project_templates, ['id', 'name'], _l('project_template'), $estimate_template->project_template_id, ['disabled' => true]); ?>

                                <?php echo render_input('name', _l('name'), $estimate_template_element->name, "text", ['required' => true, 'maxLength' => 255]); ?>

                                <div id="all_groups_elements">
                                    <?php
                                    foreach ($all_groups as $key => $item_arr) {
                                        echo '<div id="select-all_groups-index-' . $key . '" class="row">';
                                        echo '<div class="col-xs-7">';
                                        echo render_select('all_groups[' . $key . '][id]', $groups_options, ['id', 'name'], _l('item_group_name'), $item_arr['id'], ['required' => true]);
                                        echo '</div>';
                                        echo '<div class="col-xs-3">';
                                        echo render_yes_no_option_venntech('all_groups[' . $key . '][multiply]', _l('multiply'), $item_arr['multiply'], 'multiply_with_number_of_panels');
                                        echo '</div>';
                                        echo '<div class="col-xs-2" style="padding-top: 30px"><button type="button" class="btn btn-danger btn-icon" onclick="removeRecordRow(\'all_groups\', ' . $key . ')"><i class="fa fa-minus"></i></button></div>';
                                        echo '</div>';
                                    }
                                    ?>
                                </div>

                                <div id="all_samengestelde_producten_elements">
                                    <?php
                                    foreach ($all_samengestelde as $key => $item_arr) {
                                        echo '<div id="select-all_samengestelde-index-' . $key . '" class="row">';
                                        echo '<div class="col-xs-7">';
                                        echo render_select('all_samengestelde[' . $key . '][id]', $samengestelde_options, ['id', 'name'], _l('samengestelde_product'), $item_arr['id'], ['required' => true]);
                                        echo '</div>';
                                        echo '<div class="col-xs-3">';
                                        echo render_yes_no_option_venntech('all_samengestelde[' . $key . '][multiply]', _l('multiply'), $item_arr['multiply'], 'multiply_with_number_of_panels');
                                        echo '</div>';
                                        echo '<div class="col-xs-2" style="padding-top: 30px"><button type="button" class="btn btn-danger btn-icon" onclick="removeRecordRow(\'all_samengestelde\', ' . $key . ')"><i class="fa fa-minus"></i></button></div>';
                                        echo '</div>';
                                    }
                                    ?>
                                </div>

                                <div id="all_items_elements">
                                    <?php
                                    foreach ($all_items as $key => $item_arr) {
                                        echo '<div id="select-all_items-index-' . $key . '" class="row">';
                                        echo '<div class="col-xs-7">';
                                        echo render_select('all_items[' . $key . '][id]', $items_options, ['id', 'name'], _l('product'), $item_arr['id'], ['required' => true]);
                                        echo '</div>';
                                        echo '<div class="col-xs-3">';
                                        echo render_yes_no_option_venntech('all_items[' . $key . '][multiply]', _l('multiply'), $item_arr['multiply'], 'multiply_with_number_of_panels');
                                        echo '</div>';
                                        echo '<div class="col-xs-2" style="padding-top: 30px"><button type="button" class="btn btn-danger btn-icon" onclick="removeRecordRow(\'all_items\', ' . $key . ')"><i class="fa fa-minus"></i></button></div>';
                                        echo '</div>';
                                    }
                                    ?>
                                </div>

                                <div class="row mbot5">
                                    <div class="col-md-6">
                                        <button type="button" class="btn btn-success" onclick="addGroupsRow()"><i class="fa fa-plus "></i> <?php echo _l('item_group_name') ?> </button>
                                    </div>
                                </div>
                                <div class="row mbot5">
                                    <div class="col-md-6">
                                        <button type="button" class="btn btn-success" onclick="addSamengesteldeRow()"><i class="fa fa-plus "></i> <?php echo _l('samengestelde_product') ?> </button>
                                    </div>
                                </div>
                                <div class="row mbot5">
                                    <div class="col-md-6">
                                        <button type="button" class="btn btn-success" onclick="addItemsRow()"><i class="fa fa-plus "></i> <?php echo _l('product') ?> </button>
                                    </div>
                                </div>


                                <?php echo form_hidden('id', $estimate_template_element->id); ?>
                                <?php echo form_hidden('estimate_template_id', $estimate_template_element->estimate_template_id); ?>

                            </div>
                        </div>

                        <!-- sticky footer with submit button -->
                        <div class="btn-bottom-toolbar text-right">
                            <a class="btn btn-info" href="/admin/venntech/estimate_templates/edit/<?php echo $estimate_template_element->estimate_template_id; ?>"
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
    });

    function get_select_yes_no_html(array_name, key, select_options_html, yes_no_html) {
        let id_tobe = array_name + '[' + key + '][id]';
        let multiply_tobe = array_name + '[' + key + '][multiply]';

        // we have to change names and ids of the render and yes no in javascript!! because our key is calculated in javascript!!
        let select_options = select_options_html.replaceAll('CHANGE_SELECT_NAME_AND_ID', id_tobe);
        let yes_no = yes_no_html.replaceAll('CHANGE_YES_NO_NAME_AND_ID', multiply_tobe);

        let selectHTML = '<div id="select-' + array_name + '-index-' + key + '" class="row">';
        selectHTML += '<div class="col-xs-7">';
        selectHTML += select_options;
        selectHTML += '</div>';
        selectHTML += '<div class="col-xs-3">';
        selectHTML += yes_no;
        selectHTML += '</div>';
        selectHTML += '<div class="col-xs-2" style="padding-top: 30px"><button type="button" class="btn btn-danger btn-icon" onclick="removeRecordRow(\'' + array_name + '\',' + key + ')"><i class="fa fa-minus"></i></button></div>';
        selectHTML += '</div>';

        return selectHTML;
    }

    function addGroupsRow() {

        let allProductsElements = $('#all_groups_elements');
        let key = allProductsElements.find('select').length;

        let select_options = '<?php echo render_select('CHANGE_SELECT_NAME_AND_ID', $groups_options, ['id', 'name'], _l('item_group_name'), '', ['required' => true]) ?>';
        let yes_no = '<?php render_yes_no_option_venntech('CHANGE_YES_NO_NAME_AND_ID', _l('multiply'), 0, 'multiply_with_number_of_panels') ?>';
        // we have to change names and ids of the render and yes no in javascript!! because our key is calculated in javascript!!
        let selectHTML = get_select_yes_no_html('all_groups', key, select_options, yes_no);
        allProductsElements.append(selectHTML);

        allProductsElements.find('select').selectpicker('refresh');
    }

    function addSamengesteldeRow() {

        let allProductsElements = $('#all_samengestelde_producten_elements');
        let key = allProductsElements.find('select').length;

        let select_options = '<?php echo render_select('CHANGE_SELECT_NAME_AND_ID', $samengestelde_options, ['id', 'name'], _l('samengestelde_product'), '', ['required' => true]) ?>';
        let yes_no = '<?php render_yes_no_option_venntech('CHANGE_YES_NO_NAME_AND_ID', _l('multiply'), 0, 'multiply_with_number_of_panels') ?>';

        let selectHTML = get_select_yes_no_html('all_samengestelde', key, select_options, yes_no);

        allProductsElements.append(selectHTML);

        allProductsElements.find('select').selectpicker('refresh');
    }

    function addItemsRow() {

        let allProductsElements = $('#all_items_elements');
        let key = allProductsElements.find('select').length;

        let select_options = '<?php echo render_select('CHANGE_SELECT_NAME_AND_ID', $items_options, ['id', 'name'], _l('product'), '', ['required' => true]) ?>';
        let yes_no = '<?php render_yes_no_option_venntech('CHANGE_YES_NO_NAME_AND_ID', _l('multiply'), 0, 'multiply_with_number_of_panels') ?>';
        // we have to change names and ids of the render and yes no in javascript!! because our key is calculated in javascript!!
        let selectHTML = get_select_yes_no_html('all_items', key, select_options, yes_no);
        allProductsElements.append(selectHTML);

        allProductsElements.find('select').selectpicker('refresh');
    }

    function removeRecordRow(array_name, key) {
        let selector = '#select-' + array_name + '-index-' + key;
        $(selector).remove();

    }

</script>
</body>
</html>
