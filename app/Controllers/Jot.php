<?php

namespace App\Controllers;

use App\Models\ChronicleSheetModel;

class Jot extends BaseController {

    public function index() {
        $parser = \Config\Services::parser();

        $sheet = new ChronicleSheetModel();
        $sheets = $sheet->findAll();

        helper('obsfuscator');
        $data = ['sheets' => $sheets,
            'baseurl' => base_url(),
            'fileLocation' => $this->request->getFile("/")];
        obsfucateIds($data["sheets"], 'idChronicleSheet');
        echo $parser->setData($data)->render('jot_mode');
    }

}
