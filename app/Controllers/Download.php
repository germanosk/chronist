<?php

namespace App\Controllers;
use App\Models\ChronicleSheetModel;
use App\Models\ReportModel;
use App\Models\SheetFieldsModel;
use App\Models\ReportSheetFieldModel;

class Download extends BaseController {
    
    public function index() {
        helper('obsfuscator');
        echo obfuscateId(1);
//        throw new \Exception("This page is not found!");
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
        $reportModel= new ReportModel();
        $data = $reportModel->where('idChronicleSheet', $id)->first();
        if(!$data){
            throw new \Exception("Report not found!");
        }
    }
    
    public function Sheet($id = null){
        $ofuscatedID = $id;
        helper('obsfuscator');
        desofuscateId($id);
        $chronicleModel = new ChronicleSheetModel();
        $adventureData = $chronicleModel->where('idChronicleSheet', $id)->first();
        if(!$adventureData){
            throw new \Exception("Adventure not found!");
        }
        
        $fieldModel = new SheetFieldsModel();
        $result = $fieldModel->where('idChronicleSheet', $id)->findAll();
        $fieldsData = array();
        $reportData = array();
       
        foreach ($result as $key => $value) {
            $fieldsData[$value['idAdventureField']] = $value; 
        }
        
        helper('pdf');
        $pdf = openPDF($fieldsData, $reportData, ROOTPATH."/public/assets/template/".$adventureData["pdfURL"]);
        $name = 'Sheet.pdf';
        return $response->download($name, $pdf);
    }

}
