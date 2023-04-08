<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-6">
                <div class="panel_s">
                    <div class="panel-body ">
                        <?php echo form_open('/admin/venntech/inputparameters'); ?>
                        <?php if (staff_can('edit', FEATURE_SETTINGS)) {
                            echo "<div class='form-group'>
                                    <button type='submit' class='btn btn-info'>" . _l('save') . "</button>
                                </div>";
                        } ?>
                        <hr class="hr-panel-heading">
                        <div class="clearfix"></div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="col-md-6 col-sm-6">
                                    <?php echo render_input('venntech_bebat_batterij', _l('bebat_batterij'), $venntech_bebat_batterij, 'number', ['required' => true, 'step' => 'any']); ?>
                                </div>
                                <div class="col-md-6 col-sm-6">
                                    <?php echo render_input('venntech_unit_prijs_per_panel', _l('unit_prijs_per_panel'), $venntech_unit_prijs_per_panel, 'number', ['required' => true, 'step' => 'any']); ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="col-md-6 col-sm-6">
                                    <?php echo render_input('venntech_vollasturen', _l('vollasturen'), $venntech_vollasturen, 'number', ['required' => true, 'step' => 'any']); ?>
                                </div>
                                <div class="col-md-6 col-sm-6">
                                    <?php echo render_input('venntech_premie_tot_31_maart', _l('premie_tot_31_maart'), $venntech_premie_tot_31_maart, 'number', ['required' => true, 'step' => 'any']); ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="col-md-6 col-sm-6">
                                    <?php echo render_input('venntech_premie_4kw', _l('batterijpremie_tot_4_kWh'), $venntech_premie_4kw, 'number', ['required' => true, 'step' => 'any']); ?>
                                </div>
                                <div class="col-md-6 col-sm-6">
                                    <?php echo render_input('venntech_premie_4_6kw', _l('batterijpremie_4-6_kWh'), $venntech_premie_4_6kw, 'number', ['required' => true, 'step' => 'any']); ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="col-md-6 col-sm-6">
                                    <?php echo render_input('venntech_premie_6kw', _l('batterijpremie_>_6_kWh'), $venntech_premie_6kw, 'number', ['required' => true, 'step' => 'any']); ?>
                                </div>
                                <div class="col-md-6 col-sm-6">
                                    <?php echo render_input('venntech_aftopping_premie', _l('aftopping_batterijpremie'), $venntech_aftopping_premie, 'number', ['required' => true, 'step' => 'any']); ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="col-md-6 col-sm-6">
                                    <?php echo render_input('venntech_prijs_1kwh', _l('prijs_1kwh'), $venntech_prijs_1kwh, 'number', ['required' => true, 'step' => 'any']); ?>
                                </div>
                                <div class="col-md-6 col-sm-6">
                                    <?php echo render_input('venntech_hespul_waarde', _l('hespul_waarde'), $venntech_hespul_waarde, 'number', ['required' => true, 'step' => 'any']); ?>

                                </div>
                            </div>
                        </div>
                        <h4><?php echo _l('voor_tarief_zonder_batterij') ?></h4>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <p><b><?php echo _l('pv_installatie_zonder_batterij') ?></b></p>
                                <div class="col-md-12 col-sm-12">
                                    <div class="col-md-3 col-sm-3">
                                        <?php echo render_input('voor_zonder_percentage_zelf', _l('Zelfverbruika %'), $voor_zonder_percentage_zelf, "number", ['required' => true, 'step' => 'any', 'onchange' => 'voor_zonder_batterij()']); ?>
                                        <?php echo render_input('voor_zonder_percentage_injectie', _l('injectie %'), $voor_zonder_percentage_injectie, "number", ['required' => true, 'step' => 'any', 'onchange' => 'voor_zonder_batterij()']); ?>
                                    </div>
                                    <div class="col-md-3 col-sm-3">
                                        <?php echo render_input('voor_zonder_prijs_zelf_verbruik', _l('€/MWh'), $voor_zonder_prijs_zelf_verbruik, "number", ['required' => true, 'step' => 'any', 'onchange' => 'voor_zonder_batterij()']); ?>
                                        <?php echo render_input('voor_zonder_prijs_injectie', _l('€/MWh'), $voor_zonder_prijs_injectie, "number", ['required' => true, 'step' => 'any', 'onchange' => 'voor_zonder_batterij()']); ?>
                                    </div>
                                    <div class="col-md-6 col-sm-6">
                                        <?php echo render_input('venntech_voor_tarief_zonder_batterij', _l('result'), $venntech_voor_tarief_zonder_batterij, 'number', ['required' => true, 'step' => 'any']); ?>
                                    </div>
                                </div>
                                <p><b><?php echo _l('pv_installatie_met_batterij') ?></b></p>
                                <div class="col-md-12 col-sm-12">
                                    <div class="col-md-3 col-sm-3">
                                        <?php echo render_input('voor_met_percentage_zelf', _l('Zelfverbruik %'), $voor_met_percentage_zelf, "number", ['required' => true, 'step' => 'any', 'onchange' => 'voor_met_batterij()']); ?>
                                        <?php echo render_input('voor_met_percentage_injectie', _l('Zelfverbruik %'), $voor_met_percentage_injectie, "number", ['required' => true, 'step' => 'any', 'onchange' => 'voor_met_batterij()']); ?>
                                    </div>
                                    <div class="col-md-3 col-sm-3">
                                        <?php echo render_input('voor_met_prijs_zelf_verbruik', _l('€/MWh'), $voor_met_prijs_zelf_verbruik, "number", ['required' => true, 'step' => 'any', 'onchange' => 'voor_met_batterij()']); ?>
                                        <?php echo render_input('voor_met_prijs_injectie', _l('€/MWh'), $voor_met_prijs_injectie, "number", ['required' => true, 'step' => 'any', 'onchange' => 'voor_met_batterij()']); ?>
                                    </div>
                                    <div class="col-md-6 col-sm-6">
                                        <?php echo render_input('venntech_voor_tarief_met_batterij', _l('result'), $venntech_voor_tarief_met_batterij, 'number', ['required' => true, 'step' => 'any']); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h4><?php echo _l('na_tarief_zonder_batterij') ?></h4>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <p><b><?php echo _l('pv_installatie_zonder_batterij') ?></b></p>
                                <div class="col-md-12 col-sm-12">
                                    <div class="col-md-3 col-sm-3">
                                        <?php echo render_input('na_zonder_percentage_zelf', _l('Zelfverbruika %'), $na_zonder_percentage_zelf, "number", ['required' => true, 'step' => 'any', 'onchange' => 'na_zonder_batterij()']); ?>
                                        <?php echo render_input('na_zonder_percentage_injectie', _l('injectie %'), $na_zonder_percentage_injectie, "number", ['required' => true, 'step' => 'any', 'onchange' => 'na_zonder_batterij()']); ?>
                                    </div>
                                    <div class="col-md-3 col-sm-3">
                                        <?php echo render_input('na_zonder_prijs_zelf_verbruik', _l('€/MWh'), $na_zonder_prijs_zelf_verbruik, "number", ['required' => true, 'step' => 'any', 'onchange' => 'na_zonder_batterij()']); ?>
                                        <?php echo render_input('na_zonder_prijs_injectie', _l('€/MWh'), $na_zonder_prijs_injectie, "number", ['required' => true, 'step' => 'any', 'onchange' => 'na_zonder_batterij()']); ?>
                                    </div>
                                    <div class="col-md-6 col-sm-6">
                                        <?php echo render_input('venntech_na_tarief_zonder_batterij', _l('result'), $venntech_na_tarief_zonder_batterij, 'number', ['required' => true, 'step' => 'any']); ?>
                                    </div>
                                </div>
                                <p><b><?php echo _l('pv_installatie_met_batterij') ?></b></p>
                                <div class="col-md-12 col-sm-12">
                                    <div class="col-md-3 col-sm-3">
                                        <?php echo render_input('na_met_percentage_zelf', _l('Zelfverbruika %'), $na_met_percentage_zelf, "number", ['required' => true, 'step' => 'any', 'onchange' => 'na_met_batterij()']); ?>
                                        <?php echo render_input('na_met_percentage_injectie', _l('injectie %'), $na_met_percentage_injectie, "number", ['required' => true, 'step' => 'any', 'onchange' => 'na_met_batterij()']); ?>
                                    </div>
                                    <div class="col-md-3 col-sm-3">
                                        <?php echo render_input('na_met_prijs_zelf_verbruik', _l('€/MWh'), $na_met_prijs_zelf_verbruik, "number", ['required' => true, 'step' => 'any', 'onchange' => 'na_met_batterij()']); ?>
                                        <?php echo render_input('na_met_prijs_injectie', _l('€/MWh'), $na_met_prijs_injectie, "number", ['required' => true, 'step' => 'any', 'onchange' => 'na_met_batterij()']); ?>
                                    </div>
                                    <div class="col-md-6 col-sm-6">
                                        <?php echo render_input('venntech_na_tarief_met_batterij', _l('result'), $venntech_na_tarief_met_batterij, 'number', ['required' => true, 'step' => 'any']); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
<?php
init_tail();
?>
<script>
    $(function () {
        appValidateForm($('form'), {});
    });

    function voor_zonder_batterij() {

        let voor_zonder_percentage_zelf = $('#voor_zonder_percentage_zelf').val();
        let voor_zonder_percentage_injectie = $('#voor_zonder_percentage_injectie').val();
        let voor_zonder_prijs_zelf_verbruik = $('#voor_zonder_prijs_zelf_verbruik').val();
        let voor_zonder_prijs_injectie = $('#voor_zonder_prijs_injectie').val();


        let euro = (voor_zonder_percentage_zelf / 100) * voor_zonder_prijs_zelf_verbruik;
        let injectie = (voor_zonder_percentage_injectie / 100) * voor_zonder_prijs_injectie;
        let voor_zonder_result = euro + injectie;
        $('#venntech_voor_tarief_zonder_batterij').val(voor_zonder_result);
    }

    function voor_met_batterij() {

        let voor_met_percentage_zelf = $('#voor_met_percentage_zelf').val();
        let voor_met_percentage_injectie = $('#voor_met_percentage_injectie').val();
        let voor_met_prijs_zelf_verbruik = $('#voor_met_prijs_zelf_verbruik').val();
        let voor_met_prijs_injectie = $('#voor_met_prijs_injectie').val();

        let euro = (voor_met_percentage_zelf / 100) * voor_met_prijs_zelf_verbruik;
        let injectie = (voor_met_percentage_injectie / 100) * voor_met_prijs_injectie;
        let voor_met_result = euro + injectie;
        $('#venntech_voor_tarief_met_batterij').val(voor_met_result);
    }

    function na_zonder_batterij() {

        let na_zonder_percentage_zelf = $('#na_zonder_percentage_zelf').val();
        let na_zonder_percentage_injectie = $('#na_zonder_percentage_injectie').val();
        let na_zonder_prijs_zelf_verbruik = $('#na_zonder_prijs_zelf_verbruik').val();
        let na_zonder_prijs_injectie = $('#na_zonder_prijs_injectie').val();


        let euro = (na_zonder_percentage_zelf / 100) * na_zonder_prijs_zelf_verbruik;
        let injectie = (na_zonder_percentage_injectie / 100) * na_zonder_prijs_injectie;
        let na_zonder_result = euro + injectie;
        $('#venntech_na_tarief_zonder_batterij').val(na_zonder_result);
    }

    function na_met_batterij() {

        let na_met_percentage_zelf = $('#na_met_percentage_zelf').val();
        let na_met_percentage_injectie = $('#na_met_percentage_injectie').val();
        let na_met_prijs_zelf_verbruik = $('#na_met_prijs_zelf_verbruik').val();
        let na_met_prijs_injectie = $('#na_met_prijs_injectie').val();


        let euro = (na_met_percentage_zelf / 100) * na_met_prijs_zelf_verbruik;
        let injectie = (na_met_percentage_injectie / 100) * na_met_prijs_injectie;
        let na_met_result = euro + injectie;
        $('#venntech_na_tarief_met_batterij').val(na_met_result);
    }
</script>
</body>
</html>
