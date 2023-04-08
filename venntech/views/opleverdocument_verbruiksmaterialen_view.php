<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
if (!isset($item)) {
    exit('No item is set');
}
init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12 ">
                <div class="panel_s">
                    <div class="panel-body ">
                        <div class="col-md-12">
                            <button type="button" class="btn btn-success mbot5" data-toggle="modal"
                                    data-target="#verbruiksmateriaalModal">
                                <?php echo _l('add_new', _l('verbruiksmateriaal')) ?>
                            </button>
                        </div>
                        <hr class="hr-panel-heading">
                        <br/>
                        <div class="clearfix"></div>
                        <div class="clearfix mtop20"></div>
                        <?php
                        // render datatable initializes the structure of the table without data..
                        render_datatable(array(
                            '#',
                            _l('product'),
                            _l('aantal'),
                            _l('prijs'),
                            _l('actions'),
                        ), 'opleverdocumenten_verbruiksmaterialen');
                        ?>
                    <div class="btn-bottom-toolbar text-right">
                        <a class="btn btn-info" href="/admin/venntech/opleverdocumenten"
                           role="button"><?php echo _l('submit'); ?></a>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- MODAL START -->
<div class="row mbot15">
    <div class="modal" id="verbruiksmateriaalModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <?php echo form_open('/admin/venntech/opleverdocumenten_verbruiksmaterialen/add', array('id' => 'opleverdocument-verbruiksmaterialen-form')); ?>
                <?php echo form_hidden('opleverdocument_id', $item->opleverdocument_id) ?>
                <!-- Modal Header -->
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">  <?php echo _l('add_new', _l('verbruiksmateriaal')) ?></h4>
                </div>
                <!-- Modal body -->
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xs-10">
                            <?php echo render_select('item_id', $item->verbruiksmaterialen, ['id', 'name'], _l('product')); ?>
                        </div>
                        <div class="col-xs-2">
                            <?php echo render_input('aantal', _l('aantal'), 1, "number"); ?>
                        </div>
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-info" data-dismiss="modal">
                        <?php echo _l('cancel'); ?>
                    </button>
                    <button type="button" onclick="add_item(item_id)"
                            class="btn btn-info"><?php echo _l('add_new'); ?></button>
                    <button type="submit" class="btn btn-info"><?php echo _l('add_and_save'); ?></button>
                </div>
                <?php echo form_close() ?>
            </div>
        </div>
    </div>
</div>
<!-- MODAL END -->
<?php
init_tail();
?>
<script>

    let url = "<?php echo admin_url('venntech/opleverdocumenten_verbruiksmaterialen/table/' . $item->opleverdocument_id) ?>";

    $(function () {
        initDataTable('.table-opleverdocumenten_verbruiksmaterialen', url);
    });

    function add_item() {
        let opleverdocument_id = '<?php echo $item->opleverdocument_id ?>';
        let item_id = $('#item_id').val();
        let aantal = $('#aantal').val();

        $.ajax({
            url: "<?php echo admin_url('venntech/opleverdocumenten_verbruiksmaterialen/add_item/') ?>",
            method: 'post',
            data: {
                'item_id': item_id,
                'opleverdocument_id': opleverdocument_id,
                'aantal': aantal
            },
            dataType: 'json',
            success: function (response) {

                if (response.success == true) {
                    alert_float('success', response.message);

                    let selector = '.table-opleverdocumenten_verbruiksmaterialen';
                    if ($.fn.DataTable.isDataTable(selector)) {
                        $(selector).DataTable().ajax.reload(null, false);
                    }
                } else {
                    alert_float('danger', response.message);
                }
            }
        });

    }
</script>
</body>
</html>
