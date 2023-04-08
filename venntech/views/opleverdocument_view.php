<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
// initialize style classes
$existing_image_class = 'col-md-4';
$input_file_class = 'col-md-8';
if (empty($items_extra->image_path)) {
    $existing_image_class = 'col-md-12';
    $input_file_class = 'col-md-12';
}
$disabled = isset($item->document->id) && $item->document->id != '' ? '' : 'disabled';
$disabled_taak = !isset($task->id) || $task->id ==  '0' ;

init_head(); ?>

<div id="wrapper">
    <div class="content ">
        <?php include 'top_task_view.php' ?>
        <?php if ($disabled_taak) { ?>
            <div class="col-lg-12">
                <div class="panel_s da">
                    <div class="panel-body text-danger">
                        <h3 class=" mbot5">Opgelet!</h3>
                        <p> Deze opdracht is zichtbaar enkel voor admin omdat het niet verbonden is aan een taak. </p>
                    </div>
                </div>
            </div>
        <?php } ?>
        <div class="row">
            <div class="col-lg-6">
                <div class="panel_s">
                    <div class="panel-body">
                        <?php echo form_open('/admin/venntech/opleverdocumenten/edit', array('id' => 'opleverdocument-form')); ?>
                        <?php if (isset($item->document)) {
                            echo form_hidden('document[id]', $item->document->id);
                            echo form_hidden('algemeen[id]', $item->algemeen->id);
                            echo form_hidden('installatie[id]', $item->installatie->id);
                        } ?>
                        <?php if (isset($task)) {
                            echo form_hidden('document[taskid]', $task->id);
                        } ?>
                        <!-- header with title -->
                        <h4 class="no-margin"><?php echo $title; ?></h4>
                        <br/>
                        <div class="horizontal-tabs">
                            <ul class="nav nav-tabs profile-tabs row customer-profile-tabs nav-tabs-horizontal"
                                role="tablist">
                                <li role="presentation" class="active">
                                    <a href="#algemeen" aria-controls="algemeen" role="tab" data-toggle="tab"
                                       aria-expanded="true">
                                        Algemeen gegevens</a>
                                </li>
                                <li role="presentation" class="<?php echo $disabled ?>">
                                    <a class="nav-link  <?php echo $disabled ?>" href="#installatie"
                                       aria-controls="installatie" role="tab"
                                       data-toggle="tab" aria-expanded="false">
                                        Installatie </a>
                                </li>
                                <li role="presentation" class="<?php echo $disabled ?>">
                                    <a class="nav-link  <?php echo $disabled ?>" href="#extra"
                                       aria-controls="extra" role="tab"
                                       data-toggle="tab" aria-expanded="false">
                                        Extra's </a>
                                </li>
                                <li role="presentation" class="<?php echo $disabled ?> ">
                                    <a class="nav-link   <?php echo $disabled ?>" href="#fotos" aria-controls="fotos"
                                       role="tab"
                                       data-toggle="tab" aria-expanded="false">
                                        Foto's </a>
                                </li>
                            </ul>
                        </div>
                        <div class="tab-content mtop15">
                            <div role="tabpanel" class="tab-pane active" id="algemeen">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h3>Algemeen gegevens van klant</h3>
                                        <br/>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="f_client_id">
                                                    <div class="form-group select-placeholder">
                                                        <label for="clientid"
                                                               class="control-label"><?php echo _l('estimate_select_customer'); ?> </label>
                                                        <select id="clientid" name="clientid" data-live-search="true"
                                                                data-width="100%"
                                                                class="ajax-search<?php if (empty($item->document->clientid)) {
                                                                    echo ' customer-removed';
                                                                } ?>"
                                                                data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>"
                                                                required>
                                                            <?php
                                                            $selected = $item->document->clientid;
                                                            if ($selected != '') {
                                                                $rel_data = get_relation_data('customer', $selected);
                                                                $rel_val = get_relation_values($rel_data, 'customer');
                                                                echo '<option value="' . $rel_val['id'] . '" selected>' . $rel_val['name'] . '</option>';
                                                            } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                        if ($disabled == '') { ?>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <?php echo render_date_input('document[datum]', _l('datum_van_plaatsing'), $item->document->datum); ?>
                                                </div>
                                                <div class="col-md-6">
                                                    <?php echo render_date_input('document[verval_datum]', _l('verval_datum'), $item->document->verval_datum); ?>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <?php echo render_select('algemeen[medewerkers][]', $members_options, ['id', 'name'], _l('staff'), $item->algemeen->medewerkers, ['multiple' => 'true']); ?>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="installatie">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h3>Installatie gegevens</h3>
                                        <br/>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <?php echo render_input('installatie[aantal_panelen]', _l('aantal_panelen'), $item->installatie->aantal_panelen, "number"); ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <?php echo render_input('installatie[aantal_velden]', _l('aantal_velden'), $item->installatie->aantal_velden, "number"); ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <?php echo render_select('installatie[installatie_type]', $installatie_type2_options, ['id', 'name'], _l('type'), $item->installatie->installatie_type, ['onchange' => 'changeAndere(this, \'aantal_q_relais\',\'1,2\')']); ?>
                                            </div>
                                            <div class="col-md-6">
                                                <?php echo render_input('installatie[aantal_q_relais]', _l('aantal_q_relais'), $item->installatie->aantal_q_relais, 'number', [], $item->installatie->installatie_type != 1 ? ['style' => 'display:none;'] : []); ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <?php echo render_select('installatie[micro_optimizers]', $micro_optimizers_options, ['id', 'name'], _l('micro/optimizers'), $item->installatie->micro_optimizers); ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <?php echo render_select('installatie[code]', $code_installatie_gegevens_options, ['id', 'name'], _l('code'), $item->installatie->code, ['onchange' => 'changeAndere(this, \'code_andere\')']); ?>
                                            </div>
                                            <div class="col-md-6">
                                                <?php echo render_input('installatie[code_andere]', _l('andere'), $item->installatie->code_andere, 'text', [], $item->installatie->code != 1 ? ['style' => 'display:none;'] : []); ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <?php echo render_select('installatie[type_dak]', $dak_type_options, ['id', 'name'], _l('type_dak'), $item->installatie->type_dak, ['onchange' => 'changeAndere(this, \'type_dak_andere\')']); ?>
                                            </div>
                                            <div class="col-md-6">
                                                <?php echo render_input('installatie[type_dak_andere]', _l('andere'), $item->installatie->type_dak_andere, 'text', [], $item->installatie->type_dak != 1 ? ['style' => 'display:none;'] : []); ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <?php echo render_input('installatie[aantal_haken_leien_tegelpannen]', _l('aantal_haken_leien_tegelpannen'), $item->installatie->aantal_haken_leien_tegelpannen, "number"); ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <?php render_yes_no_option_venntech('installatie[afgewerkt]', _l('afgewerkt'), $item->installatie->afgewerkt); ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <?php render_yes_no_option_venntech('installatie[gecontroleerd]', _l('aarding_goed_en_gecontroleerd'), $item->installatie->gecontroleerd); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="extra">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h3>Extra</h3>
                                        <br/>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <?php echo render_textarea('installatie[extra_materiaal]', _l('extra_materiaal'), $item->installatie->extra_materiaal); ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <?php echo render_textarea('installatie[extra_werkuren]', _l('extra_werkuren'), $item->installatie->extra_werkuren); ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <?php echo render_textarea('installatie[opmerkingen]', _l('opmerkingen'), $item->installatie->opmerkingen); ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <?php echo render_select('installatie[extra_gegevens]', $extra_gegevens_options, ['id', 'name'], _l('extra_gegevens'), $item->installatie->extra_gegevens, ['onchange' => 'changeAndere(this, \'extra_gegevens_andere\')']); ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <?php echo render_input('installatie[extra_gegevens_andere]', _l('andere'), $item->installatie->extra_gegevens_andere, 'text', [], $item->installatie->extra_gegevens != 1 ? ['style' => 'display:none;'] : []); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="fotos">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h3>Foto's</h3>
                                        <br/>
                                        <?php
                                        foreach ($item->images as $key => $image) {
                                            if ($key % 3 == 0) {
                                                ?>
                                                <div class="row">
                                                <?php
                                            }
                                            render_image(IMAGE_TYPE_OPLEVERDOCUMENT, $item->document->id, $image['filename'], '');
                                            if ($key % 3 == 2 || $key == count($item->images) - 1) {
                                                ?>
                                                </div>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- sticky footer with submit button -->
                        <div class="btn-bottom-toolbar text-right">
                            <a class="btn btn-info" href="/admin/venntech/opleverdocumenten"
                               role="button"><?php echo _l('cancel'); ?></a>
                            <?php if (($edit_type == "edit" && staff_can('edit', FEATURE_OPLEVERDOCUMENT))
                                || ($edit_type == "create" && staff_can('create', FEATURE_OPLEVERDOCUMENT))) { ?>
                                <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
                                <?php if (isset($task->id) && $task->status != 5) { ?>
                                    <button type="submit" class="btn btn-success" name="complete"
                                            value="Complete"><?php echo _l('task_single_mark_as_complete'); ?></button>
                                <?php } ?>
                            <?php } ?>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
            <?php if (!$disabled) { ?>
                <div class="col-lg-6">
                    <div class="panel_s">
                        <div class="panel-body">
                            <h4 class="no-margin">Upload Foto's</h4>
                            <br/>
                            <b>Zorg ervoor dat voor alle types minstens 1 foto is geupload.</b>
                            <ul>
                                <li><?php echo _l('foto_dak_na_plaatsing') ?></li>
                                <li><?php echo _l('foto_aangepaste_zekeringkast') ?></li>
                                <li><?php echo _l('foto_weerstand_aarding') ?></li>
                                <li><?php echo _l('foto_kabeltraject') ?></li>
                                <li><?php echo _l('foto_omvormer_locatie') ?></li>
                                <li><?php echo _l('foto_omvormer_etiket') ?></li>
                                <li><?php echo _l('foto_batteri_locatie') ?></li>
                                <li><?php echo _l('foto_werking_omvormer') ?></li>
                                <li><?php echo _l('foto_SN_zonnepanelen') ?></li>
                            </ul>
                            <?php echo form_open_multipart(admin_url('venntech/opleverdocumenten/upload/' . $item->document->id), array('id' => 'opleverdocumenten-dropzone', 'class' => 'dropzone')); ?>
                            <div class="dropzone-previews"></div>
                            <?php echo form_close(); ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script type="text/javascript">
    $(function () {
        let url = "<?php echo admin_url('venntech/opleverdocumenten/table_verbruiksmateriaal/' . $item->document->id) ?>";
        initDataTable('.table-opleverdocument_verbruiksmaterialen', url);
    });
    $(function () {
        appValidateForm($('form'), {
            'items[group_id]': 'required',
            'items[description]': 'required',
            'items[name]': 'required'
        });
    });
    Dropzone.autoDiscover = false;
    let dropzone = new Dropzone(".dropzone", {
        uploadMultiple: false,
        clickable: true,
        acceptedFiles: 'image/*',
        previewsContainer: '.dropzone-previews',
        autoProcessQueue: true,
        addRemoveLinks: false,
        paramName: 'file',
        autoQueue: false,
        sending: function (file, xhr, formData) {
            // some script if needed
        },
        success: function (files, response) {
            response = JSON.parse(response);
            if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {

            }
        }
    });

    dropzone.on("addedfile", function (origFile) {
        let reader = new FileReader();
        reader.addEventListener("load", function (event) {
            resizeImageAndEnqueueFile(event, dropzone, origFile);
        });
        reader.readAsDataURL(origFile);
    });

    function remove_image(parentid, filename, file_id) {
        $.ajax({
            url: '/admin/venntech/opleverdocumenten/delete_image',
            method: 'post',
            data: {'parentid': parentid, 'filename': filename},
            dataType: 'json',
            success: function (response) {
                let selector = '#' + file_id;
                $(selector).remove();
            }
        });
    }

    function changeAndere(selectObject, fieldWrapper, intArr = '') {
        var value = selectObject.value;
        var selector = $("div[app-field-wrapper*='" + fieldWrapper + "']");
        if (intArr != '') {
            let splitted = intArr.split(',');
            if (splitted.includes(value)) {
                selector.show();
            } else {
                selector.hide()
            }
        } else {
            if (value == 1) {
                selector.show();
            } else {
                selector.hide()
            }
        }
    }
</script>
</body>
</html>
