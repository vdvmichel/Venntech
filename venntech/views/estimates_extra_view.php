<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<?php

if (!isset($item)) {
    exit('No item is set');
}
?>

<div id="wrapper">
    <div class="content ">
        <div class="row ">

            <?php echo form_open('/admin/venntech/offertes/edit'); ?>
            <?php echo form_hidden('id', $item->id); ?>

            <div class="col-lg-6">
                <div class="panel_s">
                    <div class="panel-body">

                        <!-- header with title -->
                        <h4 class="no-margin"><?php echo $title; ?></h4>
                        <hr class="hr-panel-heading"/>
                        <div class="row">
                            <div class="col-lg-12">


                                <div class="f_client_id">
                                    <div class="form-group select-placeholder">
                                        <label for="clientid" class="control-label"><?php echo _l('estimate_select_customer'); ?></label>
                                        <select id="clientid" name="clientid" data-live-search="true" data-width="100%" class="ajax-search<?php if (empty($clientid)) {
                                            echo ' customer-removed';
                                        } ?>" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" required>
                                            <?php
                                            $selected = $clientid;
                                            if ($selected != '') {
                                                $rel_data = get_relation_data('customer', $selected);
                                                $rel_val = get_relation_values($rel_data, 'customer');
                                                echo '<option value="' . $rel_val['id'] . '" selected>' . $rel_val['name'] . '</option>';
                                            } ?>
                                        </select>
                                    </div>
                                </div>

                                <?php echo render_select('staffid', $members, ['id', 'name'], _l('naam_sales_verkoper'), $item->staffid, ['required' => 'true']); ?>
                                <?php echo render_input('commission_percentage', _l('commission_percentage'), '', "number", ['disabled' => true]); ?>
                                <?php echo render_select('estimate_template_id', $estimate_templates_options, ['id', 'name'], _l('estimate_template'), $item->estimate_template_id, ['required' => true]); ?>
                                <?php echo render_select('tax_id', $taxes, ['id', 'name', 'taxrate'], _l('tax'), $item->tax_id, ['required' => true]); ?>
                                <?php echo render_input('number_of_panels', _l('number_of_panels'), $item->number_of_panels, "number", ['required' => true]); ?>
                                <?php echo render_input('hespul_waarde', _l('hespul_waarde'), $item->hespul_waarde, "number", ['required' => true]); ?>
                                <?php echo render_select('korting_type_id', $korting_types, ['id', 'name'], _l('korting'), $item->korting_type_id, ['required' => true]); ?>


                                <!-- sticky footer with submit button -->
                                <div class="btn-bottom-toolbar text-right">
                                    <a class="btn btn-info" href="/admin/venntech/offertes" role="button"><?php echo _l('cancel'); ?></a>

                                    <?php if (staff_can('create', 'estimates')) { ?>
                                        <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
                                        <button id="save_and_send" name="save_and_send" type="submit" class="btn btn-info"><?php echo _l('save_and_send'); ?></button>
                                    <?php } ?>

                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="panel_s">
                    <div class="panel-body">

                        <div class="horizontal-tabs">
                            <ul class="nav nav-tabs profile-tabs row customer-profile-tabs nav-tabs-horizontal"
                                role="tablist">
                                <li role="presentation" class="active">
                                    <a href="#estimate_elements" aria-controls="estimate_elements" role="tab" data-toggle="tab"
                                       aria-expanded="true"><?php echo _l('estimate_elements'); ?></a>
                                </li>
                                <li role="presentation">
                                    <a href="#verbruiksmaterialen" aria-controls="verbruiksmaterialen" role="tab" data-toggle="tab" aria-expanded="false">
                                        <?php echo _l('verbruiksmaterialen'); ?> </a>
                                </li>
                            </ul>
                        </div>

                        <div class="tab-content mtop15">
                            <div role="tabpanel" class="tab-pane active" id="estimate_elements">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div id="estimate_template_items">
                                            &nbsp;<!-- this will be replaced with rest call result -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane active" id="verbruiksmaterialen">
                                <div class="row">
                                    <div class="col-md-12">

                                        <div id="all_verbruiksmaterialen">
                                            <?php
                                            if (isset($all_verbruiksmaterialen) && is_array($all_verbruiksmaterialen)) {
                                                foreach ($all_verbruiksmaterialen as $key => $item_arr) {
                                                    echo '<div id="select-all_verbruiksmaterialen-index-' . $key . '" class="row">';
                                                    echo '<div class="col-xs-7">';
                                                    echo render_select('all_verbruiksmaterialen[' . $key . '][items_id]', $verbruiksmaterialen, ['id', 'name'], _l('product'), $item_arr['items_id'], ['required' => true]);
                                                    echo '</div>';
                                                    echo '<div class="col-xs-3">';
                                                    echo render_input('all_verbruiksmaterialen[' . $key . '][qty]', _l('aantal'), $item_arr['quantity'], 'number', ['required' => true]);
                                                    echo '</div>';
                                                    echo '<div class="col-xs-2" style="padding-top: 30px"><button type="button" class="btn btn-danger btn-icon" onclick="removeRecordRow(\'all_verbruiksmaterialen\', ' . $key . ')"><i class="fa fa-minus"></i></button></div>';
                                                    echo '</div>';
                                                }
                                            }
                                            ?>
                                        </div>

                                        <div class="row mbot5">
                                            <div class="col-md-6">
                                                <button type="button" class="btn btn-success" onclick="addItemsRow()"><i class="fa fa-plus "></i> <?php echo _l('product') ?> </button>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
            <?php echo form_close(); ?>

        </div>
    </div>
</div>
<?php
init_tail();
?>
<script type="text/javascript">
    $(function () {
        appValidateForm($('form'), {
            'clientid': 'required',
            'estimate_template_id': 'required',
            'tax_id': 'required',
            'number_of_panels': 'required',
            'group_ids': 'required',
            // 'naam_sales_verkoper': 'required', // staffid is used instead
            'staffid': 'required',
        });

        let estimate_template_id = "<?php echo $item->estimate_template_id ?>";
        if (estimate_template_id != '') {
            $.ajax({
                url: admin_url + 'venntech/offertes/estimate_template_items_html',
                method: 'post',
                data: {template_id: estimate_template_id, estimates_extra_id: "<?php echo $item->id ?>"},
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        let groupsHTML = response.groupsHTML;
                        $('#estimate_template_items').empty();
                        $('#estimate_template_items').append(groupsHTML);
                        $('#estimate_template_items').find('select').selectpicker('refresh');
                    }
                }
            });
        }

        // Fetch initial commission percentage if staffid is already selected
        let initialStaffId = $('select[name="staffid"]').val();
        if (initialStaffId) {
            fetchCommissionPercentage(initialStaffId);
        }
    });

    $('#estimate_template_id').change(function () {
        $('#groups_element_id').empty();
        var template_id = $(this).val();
        $.ajax({
            url: admin_url + 'venntech/offertes/estimate_template_items_html',
            method: 'post',
            data: {template_id: template_id, estimates_extra_id: ''},
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    let groupsHTML = response.groupsHTML;
                    $('#estimate_template_items').empty();
                    $('#estimate_template_items').append(groupsHTML);
                    $('#estimate_template_items').find('select').selectpicker('refresh');
                }
            }
        });
    });

    $('select[name="staffid"]').change(function() {
        let staff_id = $(this).val();
        fetchCommissionPercentage(staff_id);
    });

    function fetchCommissionPercentage(staff_id) {
        if (staff_id) {
            $.ajax({
                url: admin_url + 'venntech/offertes/get_staff_commission_percentage/' + staff_id,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success && response.commission_percentage !== null) {
                        $('input[name="commission_percentage"]').val(response.commission_percentage);
                    } else {
                        $('input[name="commission_percentage"]').val(''); // Clear if not found or error
                        // console.error('Could not fetch commission percentage or percentage not set.');
                    }
                },
                error: function() {
                    $('input[name="commission_percentage"]').val(''); // Clear on error
                    // console.error('AJAX error fetching commission percentage.');
                }
            });
        } else {
            $('input[name="commission_percentage"]').val(''); // Clear if no staff_id
        }
    }

    function addItemsRow() {
        let allProductsElements = $('#all_verbruiksmaterialen');
        let key = allProductsElements.find('select').length;

        let id_tobe = 'all_verbruiksmaterialen[' + key + '][items_id]';
        let aantal_tobe = 'all_verbruiksmaterialen[' + key + '][qty]';

        let select_options = '<?php echo render_select('CHANGE_SELECT_NAME_AND_ID', $verbruiksmaterialen, ['id', 'name'], _l('product'), '', ['required' => true]) ?>';
        let aantal_input = '<?php echo render_input('CHANGE_INPUT_NAME_AND_ID', _l('aantal'), 1, 'number', ['required' => true]) ?>';

        select_options = select_options.replaceAll('CHANGE_SELECT_NAME_AND_ID', id_tobe);
        aantal_input = aantal_input.replaceAll('CHANGE_INPUT_NAME_AND_ID', aantal_tobe);

        let selectHTML = '<div id="select-all_verbruiksmaterialen-index-' + key + '" class="row">';
        selectHTML += '<div class="col-xs-7">' + select_options + '</div>';
        selectHTML += '<div class="col-xs-3">' + aantal_input + '</div>';
        selectHTML += '<div class="col-xs-2" style="padding-top: 30px"><button type="button" class="btn btn-danger btn-icon" onclick="removeRecordRow(\'all_verbruiksmaterialen\', ' + key + ')"><i class="fa fa-minus"></i></button></div>';
        selectHTML += '</div>'
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
