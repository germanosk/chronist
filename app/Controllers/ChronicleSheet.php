<?php
namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\ChronicleSheetModel;
use App\Models\SheetFieldsModel;

class ChronicleSheet extends ResourceController {

    use ResponseTrait;

    public function index() {
        helper('obsfuscator');
        $model = new ChronicleSheetModel();
        $data['sheets'] = $model->orderBy('idChronicleSheet', 'DESC')->findAll();
        obsfucateIds($data["sheets"], 'idChronicleSheet');
        return $this->respond($data);
    }

    public function show($id = null) {
        $ofuscatedID = $id;
        helper('obsfuscator');
        desofuscateId($id);
        $chronicleModel = new ChronicleSheetModel();
        $data = $chronicleModel->where('idChronicleSheet', $id)->first();
        $fieldModel = new SheetFieldsModel();
        $data['fields'] = $fieldModel->where('idChronicleSheet', $id)->findAll();
        obsfucateIds($data["fields"], 'idAdventureField');
        $data['idChronicleSheet'] = $ofuscatedID;
        if ($data) {
            return $this->respond($data);
        } else {
            return $this->failNotFound('No chronicle sheet found');
        }
    }

}
