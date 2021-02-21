<?php

namespace App\Controllers;

use App\Models\ChronicleSheetModel;
use App\Models\FactionModel;

class Jot extends BaseController {

    public function index() {
        helper('visit');
        addVisit($this->request->getUserAgent()->getAgentString(),
                 $this->request->getIPAddress(), 
                "jot");
        
        $parser = \Config\Services::parser();

        $sheet = new ChronicleSheetModel();
        $sheets = $sheet->findAll();

        $factionModel = new FactionModel();
        $factions = $factionModel->findAll();
        
        helper('obsfuscator');
        $data = ['sheets' => $sheets,
            'factions' => $factions,
            'baseurl' => base_url(),
            'jotfields' => lang("JotFields.fields")];
        obsfucateIds($data["sheets"], 'idChronicleSheet');
        obsfucateIds($data["factions"], 'idFaction');
        echo $parser->setData($data)->render('jot_mode', ['cascadeData'=>true]);
    }
}
