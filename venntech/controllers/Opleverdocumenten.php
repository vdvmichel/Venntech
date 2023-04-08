<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Opleverdocumenten extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('opleverdocument_model');
        $this->load->model('opleverdocument_algemeen_model');
        $this->load->model('opleverdocument_installatie_model');
        $this->load->model('opleverdocument_fotos_model');
        $this->load->model('tasks_model');
        $this->load->model('taken_model');

    }

    /* List all available groepen */
    public function index()
    {
        if (!staff_can('view', FEATURE_OPLEVERDOCUMENT)) {
            access_denied(_l('view') . ' VENNTECH ' . _l('Opleverdocument'));
        }
        $data['title'] = _l('Opleverdocumenten');
        $this->load->view('venntech/opleverdocumenten_view', $data);
    }

    public function table()
    {
        if (!staff_can('view', FEATURE_OPLEVERDOCUMENT)) {
            ajax_access_denied();
        }
        $this->app->get_table_data(module_views_path('venntech', 'tables/opleverdocumenten_table'));
    }

    public function table_verbruiksmateriaal($id = '')
    {
        if (!staff_can('view', FEATURE_OPLEVERDOCUMENT)) {
            ajax_access_denied();
        }
        if (isset($id) && $id != '') {
            $aColumns = [
                'id',
                'product_id',
                'aantal'];

            $sIndexColumn = 'id';
            $sTable = db_prefix() . 'pc_opleverdocument_verbruiksmaterialen';
            $join = [];
            $where = ['AND opleverdocument_id=' . $id];

            $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where);
            $output = $result['output'];
            $rResult = $result['rResult'];
            foreach ($rResult as $aRow) {

                // init empty array
                $row = [];

                $row[] = '<a href="/admin/venntech/opleverdocument_verbruiksmaterialen/edit/' . $id . '/' . $aRow['id'] . '">' . $aRow['id'] . '</a>';
                $row[] = '<a href="/admin/venntech/opleverdocument_verbruiksmaterialen/edit/' . $id . '/' . $aRow['id'] . '">' . $aRow['product_id'] . '</a>';
                $row[] = '<a href="/admin/venntech/opleverdocument_verbruiksmaterialen/edit/' . $id . '/' . $aRow['id'] . '">' . $aRow['aantal'] . '</a>';

                // add to next index the data, no need to specify index the item will be added to the end.
                $actions = icon_btn('venntech/pc_opleverdocument_verbruiksmaterialen/delete/' . $aRow['id'], 'remove', 'btn-danger _delete');
                $row[] = $actions;
                $output['aaData'][] = $row;
            }
            echo json_encode($output);
            die();
        }
    }

    public function edit($id = '')
    {

        if (!staff_can('view', FEATURE_OPLEVERDOCUMENT)) {
            access_denied(_l('view') . ' VENNTECH ' . _l('opleverdocument'));
        }
        if ($this->input->post()) {
            $data = $this->input->post();
            // create or edit data with form data
            if (!array_key_exists('document', $data) || $data['document']['id'] == "") {
                // create action
                if (!staff_can('create', FEATURE_OPLEVERDOCUMENT)) {
                    access_denied(_l('create') . ' VENNTECH ' . _l('opleverdocument'));
                }
                $document_id = create_default_oplever_document($data['clientid']);
                redirect(admin_url('venntech/opleverdocumenten/edit/' . $document_id));
            } else {
                // edit action
                if (!staff_can('edit', FEATURE_OPLEVERDOCUMENT)) {
                    access_denied(_l('edit') . ' VENNTECH ' . _l(' opleverdocument'));
                }
                $data['document']['clientid'] = $data['clientid'];
                $medewerkers_arr = $data['algemeen']['medewerkers'];
                $medewerkers_str = join(',', $medewerkers_arr);
                $data['algemeen']['medewerkers'] = $medewerkers_str;

                $success = $this->opleverdocument_model->edit($data['document']);
                $success = $success && $this->opleverdocument_algemeen_model->edit($data['algemeen']);
                $success = $success && $this->opleverdocument_installatie_model->edit($data['installatie']);

                if ($success) {
                    set_alert('success', _l('added_successfully', _l('opleverdocument')));
                }

                $task_id = $data['document']['taskid'];
                if (array_key_exists('complete', $data)) {
                    $this->tasks_model->mark_as(5, $task_id);
                }
                if ($task_id == '') {
                    redirect(admin_url('venntech/opleverdocumenten'));
                } else {
                    redirect(admin_url('venntech/taken'));
                }
            }
        } else {
            // add options to data

            $data = [];
            $data['members'] = get_members_option($this->staff_model->get());;
            $data['installatie_type2_options'] = get_installatie_type2_options();
            $data['micro_optimizers_options'] = get_micro_optimizers_options();
            $data['code_installatie_gegevens_options'] = get_code_installatie_gegevens_options();
            $data['dak_type_options'] = get_dak_type_options();
            $data['members_options'] = get_members_option($this->staff_model->get());
            $data['extra_gegevens_options'] = get_extra_gegevens_options();

            // view add or edit with id
            if ($id == '') {
                //view create
                $item = new stdClass();
                $item->document = new stdClass();
                $item->document->id = '';
                $item->document->clientid = '';
                $item->document->taskid = '';
                $item->document->datum = '';
                $item->document->vervaldatum_datum = '';

                $item->algemeen = new stdClass();
                $item->algemeen->id = '';
                $item->algemeen->name = '';
                $item->algemeen->client = '';
                $item->algemeen->medewerkers = '';
                $item->algemeen->aarding_goed_en_gecontroleerd = '';
                $item->algemeen->totaal_prijs = '';

                $item->installatie = new stdClass();
                $item->installatie->id = '';
                $item->installatie->aantal_panelen = '';
                $item->installatie->aantal_velden = '';
                $item->installatie->aantal_q_relais = '';
                $item->installatie->installatie_type = '';
                $item->installatie->micro_optimizers = '';
                $item->installatie->code = '';
                $item->installatie->code_andere = '';
                $item->installatie->type_dak = '';
                $item->installatie->type_dak_andere = '';
                $item->installatie->aantal_haken_leien_tegelpannen = '';
                $item->installatie->extra_man_andere = '';
                $item->installatie->afgewerkt = '';
                $item->installatie->gecontroleerd = '';


                $item->installatie->extra_materiaal = '';
                $item->installatie->extra_werkuren = '';
                $item->installatie->opmerkingen = '';
                $item->installatie->extra_gegevens = '';
                $item->installatie->extra_gegevens_andere = '';

                $item = new stdClass();
                $item->id = "";
                $item->product_id = "";
                $item->naam = "";
                $item->omschrijving = "";
                $item->actief = "";
                $data['title'] = _l('add_new', _l('product'));
                $data['item'] = $item;
                $data['edit_type'] = 'create';
            } else {
                // view edit
                $document = $this->opleverdocument_model->get($id);
                $algemeen = $this->opleverdocument_algemeen_model->get_by_document_id($id);
                $installatie = $this->opleverdocument_installatie_model->get_by_document_id($id);
                $images = $this->opleverdocument_fotos_model->find_by_document_id($id);

                $task_staffid = $this->taken_model->get_staffid_by_task_id($document->taskid);
                $algemeen->medewerkers = explode(',', $algemeen->medewerkers);

                $item = new stdClass();
                $item->document = $document;
                $item->algemeen = $algemeen;
                $item->installatie = $installatie;
                $item->images = $images;

                if (isset($document->taskid)) {
                    $task = $this->tasks_model->get($document->taskid);
                }
                $data['item'] = $item;
                $data['task'] = $task;
                $data['task_staffid'] = $task_staffid;
                $data['edit_type'] = 'edit';
            }
            $data['title'] = _l('edit', _l('opleverdocument'));
            $this->load->view('venntech/opleverdocument_view', $data);
        }
    }

    public function upload($documentid = '')
    {
        if (isset($_FILES['file']['name']) && is_array($_FILES['file']['name']) && count($_FILES['file']['name']) > 0) {
            for ($i = 0; $i < count($_FILES['file']['name']); $i++) {
                $FILES_name = $_FILES['file']['name'][$i];
                $FILES_tmp_name = $_FILES['file']['tmp_name'][$i];

                $this->uploadFile($documentid, $FILES_name, $FILES_tmp_name);
            }
            echo json_encode([
                'success' => true,
                'documentid' => $documentid
            ]);
        } else if (isset($_FILES['file']['name'])) {
            $this->uploadFile($documentid, $_FILES['file']['name'], $_FILES['file']['tmp_name']);
            echo json_encode([
                'success' => true,
                'documentid' => $documentid
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'documentid' => $documentid,
                'error' => '_FILES file name is empty!'
            ]);
        }
    }

    public function delete($id = '')
    {
        if (!staff_can('delete', FEATURE_OPLEVERDOCUMENT)) {
            access_denied(_l('delete') . ' VENNTECH ' . _l('opleverdocument'));
        }

        $item = $this->opleverdocument_model->get($id);
        $success = $this->opleverdocument_model->delete($id);
        if ($success) {
            set_alert('success', _l('deleted', _l('opleverdocument')));
        }
        redirect(admin_url('venntech/opleverdocumenten'));
    }

    public function delete_image()
    {
        if (!staff_can('delete', FEATURE_OPLEVERDOCUMENT)) {
            access_denied(_l('delete') . ' VENNTECH ' . _l('opleverdocument'));
        }
        $data = $this->input->post();
        $parentid = $data['parentid'];
        $filename = $data['filename'];

        $path = get_upload_path_venntech(IMAGE_TYPE_OPLEVERDOCUMENT, $parentid);
        unlink($path . $filename);
        $this->opleverdocument_fotos_model->delete_by_parentid_filename($parentid, $filename);

        echo json_encode([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * @param $documentid
     * @param $FILES_name
     * @param $FILES_tmp_name
     * @return void
     */
    public function uploadFile($documentid, $name, $tmp_name): void
    {
        $image = [];
        $image['opleverdocument_id'] = $documentid;
        $imageid = $this->opleverdocument_fotos_model->add($image);
        if ($imageid) {
            $filename = handle_venntech_dropzone_upload(IMAGE_TYPE_OPLEVERDOCUMENT, $documentid, $imageid, $name, $tmp_name);
            if ($filename != false) {
                $image['id'] = $imageid;
                $image['filename'] = $filename;
                $this->opleverdocument_fotos_model->edit($image);
            } else {
                // upload was not successful delete db record..
                $this->opleverdocument_fotos_model->delete($imageid);
            }
        }
    }
}
