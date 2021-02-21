<?php

namespace App\Controllers;
use App\Models\ChronicleSheetModel;
use App\Models\ReportModel;
use App\Models\SheetFieldsModel;
use App\Models\ReportSheetFieldModel;

class Download extends BaseController {
    
    public function index() {
        helper('visit');
        addVisit($this->request->getUserAgent()->getAgentString(),
                 $this->request->getIPAddress(), 
                "download home");
        
        $parser = \Config\Services::parser();

        $sheet = new ChronicleSheetModel();
        $sheets = $sheet->orderBy('chronicleName', 'asc')->findAll();

        helper('obsfuscator');
        $data = ['sheets' => $sheets,
            'baseurl' => base_url(),
            'jotfields' => lang("JotFields.fields")];
        obsfucateIds($data["sheets"], 'idChronicleSheet');
        echo $parser->setData($data)->render('download_home', ['cascadeData'=>true]);
    }
    
    public function Report($id = null){
        helper('visit');
        addVisit($this->request->getUserAgent()->getAgentString(),
                 $this->request->getIPAddress(), 
                "download report");
        
        if($id == null ){
            throw new \Exception("Report not found!");
        }
        try{
            helper('obsfuscator');
            desofuscateId($id);
        }catch(\Exception $e){
            throw new \Exception("Report not found!");
        }
        $reportModel = new ReportModel();
        $reportData = $reportModel->where('idReport', $id)->first();
        $reportSheetFieldModel= new ReportSheetFieldModel();
        
        $result = $reportSheetFieldModel->where('idReport', $id)->findAll();
        if(!$result){
            throw new \Exception("Report not found!");
        }
        foreach ($result as $key => $value) {
            $reportSheetFieldData[$value['idAdventureField']] = $value;
        }
        
        $this->GeneratePDF($reportData['idChronicleSheet'], $reportSheetFieldData); 
    }
    
    public function Sheet($id = null){
        helper('visit');
        addVisit($this->request->getUserAgent()->getAgentString(),
                 $this->request->getIPAddress(), 
                "download sheet");
        
        helper('obsfuscator');
        desofuscateId($id);
        
        $this->GeneratePDF($id);        
    }
    
    private function GeneratePDF($idChronicleSheet, $reportData = array()) {
        
        $chronicleModel = new ChronicleSheetModel();
        $adventureData = $chronicleModel->where('idChronicleSheet', $idChronicleSheet)->first();
        if(!$adventureData){
            throw new \Exception("Adventure not found!");
        }
        
        $fieldModel = new SheetFieldsModel();
        $result = $fieldModel->where('idChronicleSheet', $idChronicleSheet)->findAll();
        $fieldsData = array();
       
        $name = $adventureData["chronicleCode"]."_".$adventureData["chronicleName"];
        foreach ($result as $key => $value) {
            $fieldsData[$value['idAdventureField']] = $value;
            if($value["fieldName"]==="characterName" && !empty($reportData[$value['idAdventureField']]) &&!empty($reportData[$value['idAdventureField']]["value"]) ){
                $name = $name."_".$reportData[$value['idAdventureField']]["value"];
            }
        }
        $name = str_replace(" ", "_", $name);

        helper('pdf');
        $this->response->setHeader('Content-Type', 'application/pdf'); 
        return openPDF($fieldsData, $reportData, ROOTPATH."/public/assets/template/".$adventureData["pdfURL"], $name);
    }

}
