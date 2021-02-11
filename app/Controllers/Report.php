<?php
namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\ReportModel;
use App\Models\ReportSheetFieldModel;
use App\Models\SheetFieldsModel;
use CodeIgniter\I18n\Time;



class Report extends ResourceController {

    use ResponseTrait;
    
    public function index() {
        echo"INDEX";
    }

    public function create() {
        helper('obsfuscator');
        $report = $this->request->getPost("report");
        desofuscateKeys($report);
        $idChronicleSheet  = $this->request->getPost("sheet");
        desofuscateId($idChronicleSheet );
        
        $reportData = [
            'idChronicleSheet' => $idChronicleSheet , 
            'eventName' =>  $this->request->getPost("eventName"),  
            'eventDate' =>  Time::createFromFormat('d-m-Y', $this->request->getPost("eventDate"))
        ];
        $reportModel= new ReportModel();
        $reportID = $reportModel->insert($reportData);
        
        $reportSheetFieldModel = new ReportSheetFieldModel();
        foreach ($report as $key => $value) {
            $data = [
                'idAdventureField' => $key , 
                'idReport' => $reportID,  
                'value' => $value
            ];
            $reportSheetFieldModel->insert($data);
        }
        
        $result = ["idReport" => obfuscateId($reportID)];
//        $success = ["success" =>$result];
        return $this->respondCreated($result);
    }

}
