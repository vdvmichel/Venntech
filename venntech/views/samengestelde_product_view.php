<?php
defined('BASEPATH') or exit('No direct script access allowed');

init_head();

if (!isset($item)) {
    exit('No item is set');
}

?>

<div id="wrapper">
    <div class="content ">
        <div class="row ">
            <?php echo form_open_multipart('/admin/venntech/samengestelde_producten/edit'); ?>
            <div class="col-md-6">
                <div class="panel_s">
                    <div class="panel-body">
                        <!-- header with title -->
                        <h4 class="no-margin"><?php echo $title; ?></h4>
                        <hr class="hr-panel-heading"/>

                        <div class="row">
                            <div class="col-md-12">
                                <?php echo render_input('naam', _l('name'), $item->naam, "text", ['required' => true, 'maxLength' => 255]); ?>
                                <?php echo render_textarea('omschrijving', _l('description'), $item->omschrijving, ['maxLength' => 1023]); ?>

                                <div id="all_products_elements">

                                    <?php
                                    foreach ($all_items_id as $key => $itemid) {
                                        echo '<div id="select-index-' . $key . '" class="row">';
                                        echo '<div class="col-xs-10">';
                                        echo render_select('all_items_id[]', $producten, ['id', 'name'], _l('product'), $itemid);
                                        echo '</div>';
                                        echo '<div class="col-xs-2" style="padding-top: 30px"><button type="button" class="btn btn-danger btn-icon" onclick="removeRow(' . $key . ')"><i class="fa fa-minus"></i></button></div>';
                                        echo '</div>';
                                    }
                                    ?>

                                </div>

                                <?php echo form_hidden('id', $item->id); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-success" onclick="addRow()"><i class="fa fa-plus "></i> <?php echo _l('add_new') ?> </button>
                            </div>
                        </div>

                        <!-- sticky footer with submit button -->
                        <div class="btn-bottom-toolbar text-right">
                            <a class="btn btn-info" href="/admin/venntech/samengestelde_producten"
                               role="button"><?php echo _l('cancel'); ?></a>
                            <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
                        </div>

                    </div>

                </div>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>

<?php init_tail(); ?>
<script type="text/javascript">
    $(function () {
        appValidateForm($('form'), {
            naam: "required",
        });
    });

    function addRow() {

        let allProductsElements = $('#all_products_elements');
        let key = allProductsElements.find('select').length;

        let selectHTML = '<div id="select-index-' + key + '" class="row">';
        selectHTML += '<div class="col-xs-10">';
        selectHTML += '<?php echo render_select('all_items_id[]', $producten, ['id', 'name'], _l('product')) ?>';
        selectHTML += '</div>';
        selectHTML += '<div class="col-xs-2" style="padding-top: 30px"><button type="button" class="btn btn-danger btn-icon" onclick="removeRow(' + key + ')"><i class="fa fa-minus"></i></button></div>';
        selectHTML += '</div>'
        allProductsElements.append(selectHTML);

        allProductsElements.find('select').selectpicker('refresh');
    }

    function removeRow(key) {
        let selector = '#select-index-' + key;
        $(selector).remove();

    }
</script>
</body>
</html>
