<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Inspectie_rapporten extends AdminController
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('inspectie_rapport_model');
        $this->load->model('inspectie_rapport_algemeen_model');
        $this->load->model('inspectie_rapport_elektriciteit_model');
        $this->load->model('inspectie_rapport_info_dak_model');
        $this->load->model('inspectie_rapport_info_pv_model');
        $this->load->model('inspectie_rapport_image_model');
        $this->load->model('tasks_model');
        $this->load->model('taken_model');
    }

    /* List all available groepen */
    public function index()
    {
        if (!staff_can('view', FEATURE_INSPECTIE_RAPPORT)) {
            access_denied(_l('view') . ' VENNTECH ' . _l('inspectie_rapport'));
        }

        $data['title'] = _l('inspectie_rapporten');
        $this->load->view('venntech/inspectie_rapporten_view', $data);
    }

    public function table()
    {
        if (!staff_can('view', FEATURE_INSPECTIE_RAPPORT)) {
            ajax_access_denied();
        }
        $this->app->get_table_data(module_views_path('venntech', 'tables/inspectie_rapport_table'));
    }

    public function edit($id = '')
    {

        if (!staff_can('edit', FEATURE_INSPECTIE_RAPPORT)) {
            access_denied(_l('edit') . ' VENNTECH ' . _l('inspectie_rapport'));
        }

        if ($this->input->post()) {
            $data = $this->input->post();


            // create or edit data with form data
            if ($data['rapport']['id'] == "") {
                // create action
                if (!staff_can('create', FEATURE_INSPECTIE_RAPPORT)) {
                    access_denied(_l('create') . ' VENNTECH ' . _l('inspectie_rapport'));
                }

                // create
                $data['rapport']['clientid'] = $data['clientid'];
                $rapport_id = $this->inspectie_rapport_model->add($data['rapport']);
                $data['algemeen'] ['inspectie_rapport_id'] = $rapport_id;
                $success = $this->inspectie_rapport_algemeen_model->add($data['algemeen']);
                $data['elektriciteit'] ['inspectie_rapport_id'] = $rapport_id;
                $success = $this->inspectie_rapport_elektriciteit_model->add($data['elektriciteit']);
                $data['info_dak'] ['inspectie_rapport_id'] = $rapport_id;
                $success = $this->inspectie_rapport_info_dak_model->add($data['info_dak']);
                $data['info_pv'] ['inspectie_rapport_id'] = $rapport_id;
                $success = $this->inspectie_rapport_info_pv_model->add($data['info_pv']);

                redirect(admin_url('venntech/inspectie_rapporten/edit/' . $rapport_id));
            } else {
                // edit action
                if (!staff_can('edit', FEATURE_INSPECTIE_RAPPORT)) {
                    access_denied(_l('edit') . ' VENNTECH ' . _l('inspectie_rapport'));
                }
                $data['rapport']['clientid'] = $data['clientid'];
                $rapport_id = $data['rapport']['id'];
                $success = $this->inspectie_rapport_model->edit($data['rapport']);
                $success = $this->inspectie_rapport_algemeen_model->edit($data['algemeen']);
                $success = $this->inspectie_rapport_elektriciteit_model->edit($data['elektriciteit']);
                $success = $this->inspectie_rapport_info_dak_model->edit($data['info_dak']);
                $success = $this->inspectie_rapport_info_pv_model->edit($data['info_pv']);

                $task_id = $data['rapport']['taskid'];
                if (array_key_exists('complete', $data)) {
                    // complete task!
                    $this->tasks_model->mark_as(5, $task_id);
                }
                if ($task_id == '') {
                    redirect(admin_url('venntech/inspectie_rapporten'));
                } else {
                    redirect(admin_url('venntech/taken'));
                }
            }
        } else {
            // add options to data
            $data = [];
            $data['members'] = get_members_option($this->staff_model->get());;

            $data['type_aansluiting_options'] = get_type_aansluiting_options();
            $data['materiaal_dakgoot_options'] = get_materiaal_dakgoot_options();
            $data['dakvoorvoer_options'] = get_dakvoorvoer_options();
            $data['woning_options'] = get_woning_options();
            $data['schaduw_options'] = get_schaduw_options();
            $data['orientatie_options'] = get_orientatie_options();
            $data['kabeltraject_options'] = get_kabeltraject_options();
            $data['plaats_omvormer_options'] = get_plaats_omvormer_options();
            $data['hellend_options'] = get_hellend_options();
            $data['plat_dak_options'] = get_plat_dak_options();
            $data['monitoring_options'] = get_monitoring_options();
            $data['hindernissen_options'] = get_hindernissen_options();
            $data['differentieel_300ma_aanwezig_options'] = get_differentieel_300ma_aanwezig_options();
            $data['differentieel_30ma_aanwezig_options'] = get_differentieel_30ma_aanwezig_options();

            // view add or edit with id
            if ($id == '') {
                //view create
                $item = new stdClass();
                $item->rapport = new stdClass();
                $item->rapport->id = '';
                $item->rapport->clientid = '';

                $item->algemeen = new stdClass();
                $item->algemeen->id = '';
                $item->algemeen->ean_nr = '';
                $item->algemeen->dag_verbruik = '';
                $item->algemeen->dal_verbruik = '';
                $item->algemeen->dag_injectie = '';
                $item->algemeen->dal_injectie = '';
                $item->algemeen->gemiddelde_nacht_verbruik = '';

                $item->elektriciteit = new stdClass();
                $item->elektriciteit->id = '';
                $item->elektriciteit->aardingsonderbreker_aanwezig = '';
                $item->elektriciteit->digitale_meter_aanwezig = '';
                $item->elektriciteit->type_aansluiting = '';
                $item->elektriciteit->ampere = '';
                $item->elektriciteit->differentieel_300ma_aanwezig = '';
                $item->elektriciteit->ampere_300 = '';
                $item->elektriciteit->differentieel_30ma_aanwezig = '';
                $item->elektriciteit->ampere_30 = '';
                $item->elektriciteit->extra_zekeringkast_nodig = '';

                $item->info_dak = new stdClass();
                $item->info_dak->id = '';
                $item->info_dak->hellend = '';
                $item->info_dak->hellend_andere = '';
                $item->info_dak->onderdak = '';
                $item->info_dak->gemetste_nok_en_gevelpannen = '';
                $item->info_dak->sarking_dak = '';
                $item->info_dak->hoogte_dakgoot = '';
                $item->info_dak->materiaal_dakgoot = '';
                $item->info_dak->plat_dak = '';
                $item->info_dak->dakdoorvoer = '';

                $item->info_pv = new stdClass();
                $item->info_pv->id = '';
                $item->info_pv->woning = '';
                $item->info_pv->schaduw = '';
                $item->info_pv->orientatie = '';
                $item->info_pv->hellingsgraad = '';
                $item->info_pv->lengte_ac = '';
                $item->info_pv->lengte_dc = '';
                $item->info_pv->kabeltraject = '';
                $item->info_pv->type_paneel = '';
                $item->info_pv->aantal_paneel = '';
                $item->info_pv->type_omvormers = '';
                $item->info_pv->aantal_omvormers = '';
                $item->info_pv->plaats_omvormer = '';
                $item->info_pv->plaats_omvormer_andere = '';
                $item->info_pv->batterij = '';
                $item->info_pv->smart_meter = '';
                $item->info_pv->monitoring = '';
                $item->info_pv->hindernissen = '';
                $item->info_pv->hindernissen_andere = '';
                $item->info_pv->opmerking_veiligheid = '';
                $item->info_pv->specifieke_afspraken_en_opmerkingen = '';

                $item->images = [];

                $item->task_staffid = '';

                $data['item'] = $item;
                $data['edit_type'] = 'create';
                $data['title'] = _l('add_new', _l('inspectie_rapport'));
                $this->load->view('venntech/inspectie_rapport_view', $data);
            } else {
                // view edit
                $rapport = $this->inspectie_rapport_model->get($id);
                $algemeen = $this->inspectie_rapport_algemeen_model->get_by_rapport_id($rapport->id);
                $elektriciteit = $this->inspectie_rapport_elektriciteit_model->get_by_rapport_id($rapport->id);
                $info_dak = $this->inspectie_rapport_info_dak_model->get_by_rapport_id($rapport->id);
                $info_pv = $this->inspectie_rapport_info_pv_model->get_by_rapport_id($rapport->id);
                $images = $this->inspectie_rapport_image_model->find_by_rapport_id($rapport->id);
                $task_staffid = $this->taken_model->get_staffid_by_task_id($rapport->taskid);

                $item = new stdClass();
                $item->rapport = $rapport;
                $item->algemeen = $algemeen;
                $item->elektriciteit = $elektriciteit;
                $item->info_dak = $info_dak;
                $item->info_pv = $info_pv;
                $item->images = $images;

                $task = null;
                if (isset($rapport->taskid)) {
                    $task = $this->tasks_model->get($rapport->taskid);
                }

                $data['item'] = $item;
                $data['task'] = $task;
                $data['task_staffid'] = $task_staffid;
                $data['edit_type'] = 'edit';
                $data['title'] = _l('edit', _l('inspectie_rapport'));
                $this->load->view('venntech/inspectie_rapport_view', $data);
            }
        }

    }

    public function upload($rapportid = '')
    {
        if (isset($_FILES['file']['name']) && is_array($_FILES['file']['name']) && count($_FILES['file']['name']) > 0) {

            for ($i = 0; $i < count($_FILES['file']['name']); $i++) {

                $FILES_name = $_FILES['file']['name'][$i];
                $FILES_tmp_name = $_FILES['file']['tmp_name'][$i];

                $this->uploadFile($rapportid, $FILES_name, $FILES_tmp_name);
            }

            echo json_encode([
                'success' => true,
                'rapportid' => $rapportid
            ]);
        } else if (isset($_FILES['file']['name'])) {
            $this->uploadFile($rapportid, $_FILES['file']['name'], $_FILES['file']['tmp_name']);
            echo json_encode([
                'success' => true,
                'rapportid' => $rapportid
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'rapportid' => $rapportid,
                'error' => '_FILES file name is empty!'
            ]);
        }
    }


    public function delete($id = '')
    {
        if (!staff_can('delete', FEATURE_INSPECTIE_RAPPORT)) {
            access_denied(_l('delete') . ' VENNTECH ' . _l('inspectie_rapport'));
        }
        $images = $this->inspectie_rapport_image_model->find_by_rapport_id($id);

        // delete rapport with id
        $this->inspectie_rapport_model->delete($id);

        foreach ($images as $image) {
            handle_venntech_image_delete(IMAGE_TYPE_INSPECTIE, $id, $image['filename']);
        }
        redirect(admin_url('venntech/inspectie_rapporten'));
    }

    public function delete_image()
    {
        if (!staff_can('delete', FEATURE_INSPECTIE_RAPPORT)) {
            access_denied(_l('delete') . ' VENNTECH ' . _l('inspectie_rapport'));
        }
        $data = $this->input->post();
        $parentid = $data['parentid'];
        $filename = $data['filename'];

        $path = get_upload_path_venntech(IMAGE_TYPE_INSPECTIE, $parentid);

        unlink($path . $filename);

        $this->inspectie_rapport_image_model->delete_by_parentid_filename($parentid, $filename);

        echo json_encode([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * @param $rapportid
     * @param $FILES_name
     * @param $FILES_tmp_name
     * @return void
     */
    public function uploadFile($rapportid, $name, $tmp_name): void
    {
        $image = [];
        $image['inspectie_rapport_id'] = $rapportid;
        $imageid = $this->inspectie_rapport_image_model->add($image);
        if ($imageid) {
            $filename = handle_venntech_dropzone_upload(IMAGE_TYPE_INSPECTIE, $rapportid, $imageid, $name, $tmp_name);
            if ($filename != false) {
                $image['id'] = $imageid;
                $image['filename'] = $filename;
                $this->inspectie_rapport_image_model->edit($image);
            } else {
                // upload was not successful delete db record..
                $this->inspectie_rapport_image_model->delete($imageid);
            }
        }
    }
}
