<?php

namespace App\Controllers;
use App\Models\ChronicleSheetModel;
use App\Models\ReportModel;
use App\Models\SheetFieldsModel;
use App\Models\ReportSheetFieldModel;

class CopyLayout extends BaseController {
    
    public function index() { 
        if( $_SERVER['CI_ENVIRONMENT'] !=="development"){
            throw new \Exception("This page is not found!");
        }
    }
    
    public function Copy($idOriginal,$idCopy){
        if( $_SERVER['CI_ENVIRONMENT'] !== "development"){
            throw new \Exception("This page is not found!");
        }
        if($idOriginal == null ){
            throw new \Exception("Report not found!");
        }
        $fieldModel = new SheetFieldsModel();
        $result = $fieldModel->where('idChronicleSheet', $idOriginal)->findAll();
        foreach ($result as $key => $value) {
            $fieldsData = [
                'posX' => $value['posX'],  
                'posY' => $value['posY'], 
                'height' => $value['height'], 
                'width' => $value['width'], 
                'idChronicleSheet' => $idCopy,
                'type'=> $value['type'], 
                'fieldName'=> $value['fieldName'], 
                'fontSize'=> $value['fontSize']
            ];
            $fieldModel->insert($fieldsData);
        }
    }

}
