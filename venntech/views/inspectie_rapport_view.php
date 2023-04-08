<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
// initialize style classes
$existing_image_class = 'col-md-4';
$input_file_class = 'col-md-8';
if (empty($items_extra->image_path)) {
    $existing_image_class = 'col-md-12';
    $input_file_class = 'col-md-12';
}
$disabled = isset($item->rapport->id) && $item->rapport->id != '' ? '' : 'disabled';
$disabled_taak = !isset($task->id) || $task->id ==  '0' ;

init_head();
?>
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
        <div class="row ">
            <div class="col-lg-6">
                <div class="panel_s">
                    <div class="panel-body">
                        <?php echo form_open('/admin/venntech/inspectie_rapporten/edit', array('id' => 'inspectie-rapport-form')); ?>
                        <?php echo form_hidden('rapport[id]', $item->rapport->id); ?>
                        <?php if (isset($task)) {
                            echo form_hidden('rapport[taskid]', $task->id);
                        } ?>
                        <?php echo form_hidden('algemeen[id]', $item->algemeen->id); ?>
                        <?php echo form_hidden('elektriciteit[id]', $item->elektriciteit->id); ?>
                        <?php echo form_hidden('info_dak[id]', $item->info_dak->id); ?>
                        <?php echo form_hidden('info_pv[id]', $item->info_pv->id); ?>
                        <?php echo form_hidden('algemeen[inspectie_rapport_id]', $item->rapport->id); ?>
                        <?php echo form_hidden('elektriciteit[inspectie_rapport_id]', $item->rapport->id); ?>
                        <?php echo form_hidden('info_dak[inspectie_rapport_id]', $item->rapport->id); ?>
                        <?php echo form_hidden('info_pv[inspectie_rapport_id]', $item->rapport->id); ?>
                        <!-- header with title -->
                        <div class="row">
                            <div class="col-md-12">
                                <h4 class="no-margin"><?php echo $title; ?></h4>
                            </div>
                        </div>
                        <br/>
                        <div class="horizontal-tabs">
                            <ul class="nav nav-tabs profile-tabs row customer-profile-tabs nav-tabs-horizontal"
                                role="tablist">
                                <li role="presentation" class="active">
                                    <a href="#algemeen" aria-controls="algemeen" role="tab" data-toggle="tab"
                                       aria-expanded="true">
                                        Algemeen </a>
                                </li>
                                <li role="presentation" class="<?php echo $disabled ?>">
                                    <a class="nav-link <?php echo $disabled ?> " href="#elektriciteit"
                                       aria-controls="elektriciteit" role="tab"
                                       data-toggle="tab" aria-expanded="false">
                                        ELEKTRICITEIT </a>
                                </li>
                                <li role="presentation" class="<?php echo $disabled ?>">
                                    <a class="nav-link <?php echo $disabled ?> " href="#info_dak"
                                       aria-controls="info_dak" role="tab"
                                       data-toggle="tab" aria-expanded="false">
                                        INFO DAK </a>
                                </li>
                                <li role="presentation" class="<?php echo $disabled ?>">
                                    <a class="nav-link <?php echo $disabled ?> " href="#info_pv" aria-controls="info_pv"
                                       role="tab"
                                       data-toggle="tab" aria-expanded="false">
                                        INFO PV </a>
                                </li>
                                <li role="presentation" class="<?php echo $disabled ?>">
                                    <a class="nav-link <?php echo $disabled ?> " href="#fotos" aria-controls="fotos"
                                       role="tab"
                                       data-toggle="tab" aria-expanded="false">
                                        Fotos </a>
                                </li>
                            </ul>
                        </div>
                        <div class="tab-content mtop15">
                            <div role="tabpanel" class="tab-pane active" id="algemeen">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h3>Algemeen gegevens van klant</h3>
                                        <br/>
                                        <div class="f_client_id">
                                            <div class="form-group select-placeholder">
                                                <label for="clientid"
                                                       class="control-label"><?php echo _l('estimate_select_customer'); ?> </label>
                                                <select id="clientid" name="clientid" data-live-search="true"
                                                        data-width="100%"
                                                        class="ajax-search<?php if (empty($item->rapport->clientid)) {
                                                            echo ' customer-removed';
                                                        } ?>"
                                                        data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>"
                                                        required>
                                                    <?php
                                                    $selected = $item->rapport->clientid;
                                                    if ($selected != '') {
                                                        $rel_data = get_relation_data('customer', $selected);
                                                        $rel_val = get_relation_values($rel_data, 'customer');
                                                        echo '<option value="' . $rel_val['id'] . '" selected>' . $rel_val['name'] . '</option>';
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <?php
                                        if ($disabled == '') {
                                            echo render_input('algemeen[ean_nr]', _l('ean_nr'), $item->algemeen->ean_nr);
                                            echo render_input('algemeen[dag_verbruik]', _l('dag_verbruik'), $item->algemeen->dag_verbruik, "number");
                                            echo render_input('algemeen[dal_verbruik]', _l('dal_verbruik'), $item->algemeen->dal_verbruik, "number");
                                            echo render_input('algemeen[dag_injectie]', _l('dag_injectie'), $item->algemeen->dag_injectie, "number");
                                            echo render_input('algemeen[dal_injectie]', _l('dal_injectie'), $item->algemeen->dal_injectie, "number");
                                            echo render_input('algemeen[gemiddelde_nacht_verbruik]', _l('gemiddelde_nacht_verbuik'), $item->algemeen->gemiddelde_nacht_verbruik, "number");
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="elektriciteit">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h3>ELEKTRICITEIT</h3>
                                        <br/>
                                        <?php render_yes_no_option_venntech('elektriciteit[aardingsonderbreker_aanwezig]', _l('aardingsonderbreker_aanwezig'), $item->elektriciteit->aardingsonderbreker_aanwezig); ?>
                                        <?php render_yes_no_option_venntech('elektriciteit[digitale_meter_aanwezig]', _l('digitale_meter_aanwezig'), $item->elektriciteit->digitale_meter_aanwezig); ?>
                                        <?php echo render_select('elektriciteit[type_aansluiting]', $type_aansluiting_options, ['id', 'name'], _l('type_aansluiting'), $item->elektriciteit->type_aansluiting); ?>
                                        <?php echo render_input('elektriciteit[ampere]', _l('ampère'), $item->elektriciteit->ampere); ?>
                                        <?php echo render_select('elektriciteit[differentieel_300ma_aanwezig]', $differentieel_300ma_aanwezig_options, ['id', 'name'], _l('differentieel_300mA_aanwezig'), $item->elektriciteit->differentieel_300ma_aanwezig); ?>
                                        <?php echo render_input('elektriciteit[ampere_300]', _l('Ampère 300mA?'), $item->elektriciteit->ampere_300); ?>
                                        <?php echo render_select('elektriciteit[differentieel_30ma_aanwezig]', $differentieel_30ma_aanwezig_options, ['id', 'name'], _l('differentieel_30mA_aanwezig'), $item->elektriciteit->differentieel_30ma_aanwezig); ?>
                                        <?php echo render_input('elektriciteit[ampere_30]', _l('Ampère 30mA?'), $item->elektriciteit->ampere_30); ?>
                                        <?php render_yes_no_option_venntech('elektriciteit[extra_zekeringkast_nodig]', _l('extra_zekeringkast_nodig'), $item->elektriciteit->extra_zekeringkast_nodig); ?>
                                    </div>
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="info_dak">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h3>INFO DAK</h3>
                                        <br/>
                                        <?php echo render_select('info_dak[hellend]', $hellend_options, ['id', 'name'], _l('hellend'), $item->info_dak->hellend); ?>
                                        <?php echo render_input('info_dak[hellend_andere]', _l('andere'), $item->info_dak->hellend_andere); ?>
                                        <?php echo render_input('info_dak[onderdak]', _l('onderdak'), $item->info_dak->onderdak); ?>
                                        <?php render_yes_no_option_venntech('info_dak[gemetste_nok_en_gevelpannen]', _l('gemetste_nok_en_gevelpannen'), $item->info_dak->gemetste_nok_en_gevelpannen); ?>
                                        <?php render_yes_no_option_venntech('info_dak[sarking_dak]', _l('sarking_dak'), $item->info_dak->sarking_dak); ?>
                                        <?php echo render_input('info_dak[hoogte_dakgoot]', _l('hoogte_dakgoot'), $item->info_dak->hoogte_dakgoot); ?>
                                        <?php echo render_select('info_dak[materiaal_dakgoot]', $materiaal_dakgoot_options, ['id', 'name'], _l('materiaal_dakgoot'), $item->info_dak->materiaal_dakgoot); ?>
                                        <?php echo render_select('info_dak[plat_dak]', $plat_dak_options, ['id', 'name'], _l('plat_dak'), $item->info_dak->plat_dak); ?>
                                        <?php echo render_select('info_dak[dakdoorvoer]', $dakvoorvoer_options, ['id', 'name'], _l('dakdoorvoer'), $item->info_dak->dakdoorvoer); ?>
                                    </div>
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="info_pv">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h3>INFO PV</h3>
                                        <br/>
                                        <?php echo render_select('info_pv[woning]', $woning_options, ['id', 'name'], _l('woning'), $item->info_pv->woning); ?>
                                        <?php echo render_select('info_pv[schaduw]', $schaduw_options, ['id', 'name'], _l('schaduw'), $item->info_pv->schaduw); ?>
                                        <?php echo render_select('info_pv[orientatie]', $orientatie_options, ['id', 'name'], _l('orientatie'), $item->info_pv->orientatie); ?>
                                        <?php echo render_input('info_pv[hellingsgraad]', _l('hellingsgraad'), $item->info_pv->hellingsgraad); ?>
                                        <?php echo render_input('info_pv[lengte_ac]', _l('lengte_ac'), $item->info_pv->lengte_ac); ?>
                                        <?php echo render_input('info_pv[lengte_dc]', _l('lengte_dc'), $item->info_pv->lengte_dc); ?>
                                        <?php echo render_select('info_pv[kabeltraject]', $kabeltraject_options, ['id', 'name'], _l('kabeltraject'), $item->info_pv->kabeltraject); ?>
                                        <?php echo render_input('info_pv[type_paneel]', _l('type_paneel'), $item->info_pv->type_paneel); ?>
                                        <?php echo render_input('info_pv[aantal_paneel]', _l('aantal'), $item->info_pv->aantal_paneel); ?>
                                        <?php echo render_input('info_pv[type_omvormers]', _l('type_omvormer'), $item->info_pv->type_omvormers); ?>
                                        <?php echo render_input('info_pv[aantal_omvormers]', _l('aantal'), $item->info_pv->aantal_omvormers); ?>
                                        <?php echo render_select('info_pv[plaats_omvormer]', $plaats_omvormer_options, ['id', 'name'], _l('plaats_omvormer(s)'), $item->info_pv->plaats_omvormer); ?>
                                        <?php echo render_input('info_pv[plaats_omvormer_andere]', _l('andere'), $item->info_pv->plaats_omvormer_andere); ?>
                                        <?php render_yes_no_option_venntech('info_pv[batterij]', 'batterij', $item->info_pv->batterij); ?>
                                        <?php render_yes_no_option_venntech('info_pv[smart_meter]smart_meter', _l('smart_meter'), $item->info_pv->smart_meter); ?>
                                        <?php echo render_select('info_pv[monitoring]', $monitoring_options, ['id', 'name'], _l('monitoring'), $item->info_pv->monitoring); ?>
                                        <?php echo render_select('info_pv[hindernissen]', $hindernissen_options, ['id', 'name'], _l('hindernissen'), $item->info_pv->hindernissen); ?>
                                        <?php echo render_input('info_pv[hindernissen_andere]', _l('andere'), $item->info_pv->hindernissen_andere); ?>
                                        <?php echo render_textarea('info_pv[opmerking_veiligheid]', _l('opmerking_veiligheid'), $item->info_pv->opmerking_veiligheid); ?>
                                        <?php echo render_textarea('info_pv[specifieke_afspraken_en_opmerkingen]', _l('specifieke_afspraken_en_opmerkingen'), $item->info_pv->specifieke_afspraken_en_opmerkingen); ?>

                                    </div>
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="fotos">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h3>Foto's</h3>


                                        <?php
                                        foreach ($item->images as $key => $image) {
                                            if ($key % 3 == 0) {
                                                ?>
                                                <div class="row">
                                                <?php
                                            }
                                            render_image(IMAGE_TYPE_INSPECTIE, $item->rapport->id, $image['filename'], '');

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
                            <a class="btn btn-info" href="/admin/venntech/inspectie_rapporten"
                               role="button"><?php echo _l('cancel'); ?></a>

                            <?php if (($edit_type == "edit" && staff_can('edit', FEATURE_INSPECTIE_RAPPORT))
                                || ($edit_type == "create" && staff_can('create', FEATURE_INSPECTIE_RAPPORT))) { ?>

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
                                <li><?php echo _l('foto_netaansluiting') ?></li>
                                <li><?php echo _l('foto_zekeringkast') ?></li>
                                <li><?php echo _l('foto_aardingsonderbreker') ?></li>
                                <li><?php echo _l('foto_hoofddiff') ?></li>
                                <li><?php echo _l('foto_diff_vochtige_ruimtes') ?></li>
                                <li><?php echo _l('foto_dak') ?></li>
                                <li><?php echo _l('foto_satteliet') ?></li>
                                <li><?php echo _l('foto_legplan') ?></li>
                                <li><?php echo _l('foto_plaats_omvormer') ?></li>
                                <li><?php echo _l('foto_plaats_batterij') ?></li>
                                <li><?php echo _l('foto_kabeltraject') ?></li>
                                <li><?php echo _l('foto_hindernissen') ?></li>
                            </ul>

                            <?php echo form_open_multipart(admin_url('venntech/inspectie_rapporten/upload/' . $item->rapport->id), array('id' => 'inspectie-rapport-dropzone', 'class' => 'dropzone')); ?>
                            <div class="dropzone-previews"></div>

                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
<?php
init_tail();
?>
<script type="text/javascript">
    $(function () {
        appValidateForm($('form'), {
            'items[group_id]': 'required',
            'items[description]': 'required',
            'items[rate]': 'required'
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
        reader.addEventListener("load", function (event){
            resizeImageAndEnqueueFile(event, dropzone, origFile);
        });
        reader.readAsDataURL(origFile);
    });

    function remove_image(parentid, filename, file_id) {

        $.ajax({
            url: '/admin/venntech/inspectie_rapporten/delete_image',
            method: 'post',
            data: {'parentid': parentid, 'filename': filename},
            dataType: 'json',
            success: function (response) {
                let selector = '#' + file_id;
                $(selector).remove();
            }
        });
    }

</script>

</body>
</html>
