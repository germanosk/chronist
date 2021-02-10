<?php

namespace App\Controllers;
use App\Models\ChronicleSheetModel;
use App\Models\ReportModel;
use App\Models\SheetFieldsModel;
use App\Models\ReportSheetFieldModel;

class Download extends BaseController {
    
    public function index() {
        
        $parser = \Config\Services::parser();

        $sheet = new ChronicleSheetModel();
        $sheets = $sheet->findAll();

        helper('obsfuscator');
        $data = ['sheets' => $sheets,
            'baseurl' => base_url(),
            'jotfields' => lang("JotFields.fields")];
        obsfucateIds($data["sheets"], 'idChronicleSheet');
        echo $parser->setData($data)->render('download_home', ['cascadeData'=>true]);
    }
    
    public function Report($id = null){
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
       
        foreach ($result as $key => $value) {
            $fieldsData[$value['idAdventureField']] = $value; 
        }
        
        helper('pdf');
        $pdf = openPDF($fieldsData, $reportData, ROOTPATH."/public/assets/template/".$adventureData["pdfURL"]);
        $name = 'Sheet.pdf';
        return $response->download($name, $pdf);
    }

}
