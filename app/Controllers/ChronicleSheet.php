<?php
namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\ChronicleSheetModel;
use App\Models\SheetFieldsModel;
use App\Interfaces\OwnershipCheckable;

class ChronicleSheet extends ResourceController implements OwnershipCheckable {
    
    use ResponseTrait;
    private $errorMessage;
    public function index() {
        helper('obsfuscator');
        $model = new ChronicleSheetModel();
        $data['sheets'] = $model->orderBy('idChronicleSheet', 'DESC')->findAll();
        obsfucateIds($data["sheets"], 'idChronicleSheet');
        return $this->respond($data);
    }

    public function show($id = null, $confirmationCode = null) {
        $ofuscatedID = $id;
        helper('obsfuscator');
        helper('ownership');
        
        desofuscateId($id);
        
        $ownership = CheckOwnership($id, 
                        strtoupper($confirmationCode), 
                        $this->request->getIPAddress(),
                        $this);
        
        if(!$ownership){
            return $this->failValidationError($this->errorMessage);
        }
        
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

    public function onTooManyAttempts() {
        $this->errorMessage = lang("Error.tooManyAttemptsMessage");
    }

    public function onWrongCode() {
        $this->errorMessage = lang("Error.wrongCodeMessage");
    }

}
