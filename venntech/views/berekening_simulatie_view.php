<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
    <?php echo form_open_multipart('/admin/venntech/berekening_simulatie/edit'); ?>
    <div class="content ">
        <div class="row">
            <div class="col-md-6">
                <div class="panel_s">
                    <div class="panel-body">
                        <!-- header with title -->
                        <h4 class="no-margin"><?php echo $title; ?></h4>
                        <hr class="hr-panel-heading"/>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="col-md-6 col-sm-6">
                                    <?php echo render_select('zonnepaneel_id', $zonnepanelen, ['id', 'name'], _l('zonnepanelen'), '', ['required' => 'true']); ?>
                                </div>
                                <div class="col-md-6 col-sm-6">
                                    <?php echo render_select('omvormer_id', $omvormers, ['id', 'name'], _l('omvormers'), '', ['required' => 'true']); ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="col-md-6 col-sm-6">
                                    <?php echo render_select('plaatsing_id', $plaatsing, ['id', 'name'], _l('installatie '), '', ['required' => 'true']); ?>
                                </div>
                                <div class="col-md-6 col-sm-6">
                                    <?php echo render_select('structuur_id', $structuur, ['id', 'name'], _l('structuur'), '', ['required' => 'true']); ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="col-md-6 col-sm-6">
                                    <?php echo render_select('batterij_id', $batterij, ['id', 'name'], _l('batterij'), '', ['required' => 'true']); ?>
                                </div>
                                <div class="col-md-6 col-sm-6">
                                    <?php echo render_input('hespul_waarde', _l('hespul_waarde'), $hespul_waarde, 'number', ['required' => true, 'step' => 'any']); ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="col-md-6 col-sm-6">
                                    <?php echo render_input('aantal', _l('aantal'), '', "number", ['required' => true, 'step' => 'any']); ?>
                                </div>
                                <div class="col-md-6 col-sm-6">
                                    <?php echo render_input('forfait_bedragen', _l('forfait_bedragen'), $forfait_bedragen, "number", ['required' => true, 'step' => 'any']); ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="col-md-6 col-sm-6">
                                    <?php render_yes_no_option_venntech('is_voor_2021_april', _l('geldig_vanaf_1_april'), '1'); ?>
                                </div>
                                <div class="col-md-6 col-sm-6">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 mbot30">
                            <button type="button" class="btn btn-info"
                                    onclick="call_berekening()"><?php echo _l('bereken'); ?></button>
                        </div>
                        <div class="col-md-12 col-sm-12 img-rounded  mleft4 mtop4 mright5 mbot5 shadow "
                             style="border: 1px solid grey">
                            <h4>Zonnepanelen</h4>
                            <div>
                                <p>Het optimale aantal panelen (<span id="totaal_vermogen_wh"> </span> Wp)</p>
                                <p>De geschatte prijs voor deze installatie vanaf <b><span
                                                id="totaal_investering_span"> </span></b></p>
                                <p><?php echo _l('berekende_premie_zonnepanelen') ?> <b><span id="premie_span"> </span></b>
                                </p>
                                <p>Geschatte kost <b><span id="geschatte_kost_span"> </span></b></p>
                                <p>Geschatte terugverdiendtijd investering met batterij <b><span
                                                id="verdientijd"> </span></b></p>
                                <p>Geschatte terugverdiendtijd investering zonder batterij <b><span
                                                id="verdientijd_zonder_batterij"> </span></b></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel_s">
                    <div class="panel-body align-items-center justify-content-center">
                        <div class="row mbot25">
                            <div class="col-md-12 col-sm-12 img-rounded "
                                 style="border: 1px solid grey">
                                <h4>Terugverdientijd</h4>
                                <p>Rekening houdende met een
                                    <mark>zelfconsumptie*</mark>
                                    van <span id="zelfconsumptie"> </span>%
                                </p>
                                <div style="height: 300px">
                                    <canvas id="myChart"></canvas>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="col-md-4 col-sm-4 text-center mtop15"
                                 style="border-left: 1px solid grey; border-right: 1px solid grey">
                                <p>Rendement</p>
                                <h4 class="text-center"><b><span id="irr"></span><small>%</small></b></h4>
                            </div>
                            <div class="col-md-4 col-sm-4 text-center mtop15"
                                 style="border-left: 1px solid grey; border-right: 1px solid grey">
                                <p>Totale opbrengst </p>
                                <h4 class="text-center"><b><span id="opbrengst"></span><small>€</small></b></h4>
                            </div>
                            <div class="col-md-4 col-sm-4 text-center mtop15"
                                 style="border-left: 1px solid grey; border-right: 1px solid grey">
                                <p>CO2-besparing 25 jaar</p>
                                <h4 class="text-center"><b><span id="besparing"></span><small>kg</small></b></h4>
                            </div>
                            <div class="col-md-4 col-sm-4 text-center mtop15"
                                 style="border-left: 1px solid grey; border-right: 1px solid grey">
                                <p>kWh 25 jaar</p>
                                <h4 class="text-center"><b><span id="kwh"></span><small>kWh</small></b></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body text-center">
                        <img src="/modules/<?php echo VENNTECH_MODULE_NAME ?>/uploads/images/tabel_van_hespul.jpg"
                             class="img img-responsive img-thumbnail"/>
                    </div>
                </div>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
    <?php init_tail(); ?>
</div>
<script type="text/javascript">
    $(function () {
        appValidateForm($('form'), {
            zonnepaneel_id: "required",
            omvormer_id: "required",
            aantal: "required",
            structuur_id: "required",
            plaatsing_id: "required",
            hespul_waarde: "required",
            forfait_bedragen: "required",
            is_voor_2021_april: "required",
        });
    });

    function draw_chart(start_bedrag, eerste_jaar, jaarlijks) {
        start_bedrag = parseFloat(start_bedrag);
        eerste_jaar = parseFloat(eerste_jaar);
        jaarlijks = parseFloat(jaarlijks);
        let labels = [];
        let data = [];

        for (let i = 0; i < 25; i++) {
            labels[i] = '' + i;
            if (i === 0) {
                // default start bedrag
            } else if (i === 1) {
                start_bedrag += eerste_jaar;
            } else {
                start_bedrag += jaarlijks;
            }
            data[i] = (Math.round(start_bedrag * 100) / 100).toFixed(2);
        }

        const ctx = document.getElementById('myChart');
        const myChart = new Chart(ctx, {
            type: 'line',
            maxHeight: 50,
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    label: 'Jaarlijks opbrengst',
                    borderColor: 'rgb(75, 192, 192)',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
            }
        });
    }

    function call_berekening() {

        let totaal_vermogen_kwh = $('#totaal_vermogen_kwh').val();
        let zonnepaneel_investering = $('#zonnepaneel_investering').val();
        let is_voor_2021_april = $("input[name='is_voor_2021_april']:checked").val();
        let omvormer_id = $('#omvormer_id').val();
        let aantal = $('#aantal').val();
        let zonnepaneel_id = $('#zonnepaneel_id').val();
        let hespul_waarde = $('#hespul_waarde').val();
        let plaatsing_id = $('#plaatsing_id').val();
        let structuur_id = $('#structuur_id').val();
        let forfait_bedragen = $('#forfait_bedragen').val();
        let batterij_id = $('#batterij_id').val();

        $.ajax({
            url: '/admin/venntech/berekening_simulatie/berekening',
            method: 'post',
            data: {
                'totaal_vermogen_kwh': totaal_vermogen_kwh,
                'zonnepaneel_investering': zonnepaneel_investering,
                'is_voor_2021_april': is_voor_2021_april,
                'omvormer_id': omvormer_id,
                'aantal': aantal,
                'zonnepaneel_id': zonnepaneel_id,
                'hespul_waarde': hespul_waarde,
                'plaatsing_id': plaatsing_id,
                'structuur_id': structuur_id,
                'forfait_bedragen': forfait_bedragen,
                'batterij_id': batterij_id

            },
            dataType: 'json',
            success: function (response) {
                if (response.success == true) {
                    $('#result').val((response.result).toFixed(2));
                    $('#eerste').val(response.eerste);
                    $('#jaarlijks').val(response.jaarlijks);
                    $('#zonnepaneel_investering').val((response.zonnepaneel_investering).toFixed(2));
                    aantal = parseFloat(aantal);

                    let premie = parseFloat(response.result);

                    $('#totaal_vermogen_wh').text((response.totaal_vermogen_kwh * 1000).toFixed(0));
                    $('#geschatte_kost_span').text((response.zonnepaneel_investering - premie).toFixed(2));
                    $('#premie_span').text((response.result).toFixed(2));
                    $('#totaal_investering_span').text((response.zonnepaneel_investering.toFixed(2)));
                    $('#irr').text((response.irr.toFixed(0)));
                    $('#verdientijd').text(response.verdientijd.toFixed(1));
                    $('#verdientijd_zonder_batterij').text(response.verdientijd_zonder_batterij.toFixed(1));


                    $('#kwh').text((response.kwh.toFixed(0)));
                    $('#besparing').text((response.besparing.toFixed(0)));
                    $('#aantal_paneel').text(aantal);
                    $('#opbrengst').text((response.opbrengst).toFixed(2));
                    $('#zonnepaneel_investering').text((response.zonnepaneel_investering).toFixed(2));
                    $('#zelfconsumptie').text(response.zelfconsumptie);

                    draw_chart(response.zonnepaneel_investering * -1, response.eerste, response.jaarlijks);
                    console.log('response', response);
                } else {
                    alert_float('danger', response.message);
                }
            }
        });
    }
</script>
</body>
</html>
