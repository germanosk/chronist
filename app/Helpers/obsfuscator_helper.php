<?php

    function obsfucateIds(&$data,$fieldName){
        $encrypter = \Config\Services::encrypter();
        foreach ($data as &$sheet) {
            $sheet[$fieldName] =  bin2hex($encrypter->encrypt($sheet[$fieldName]));
        }
    }
    
    function desofuscateId(&$id) {
        
        $encrypter = \Config\Services::encrypter();
        $id = $encrypter->decrypt(hex2bin($id));
        
    }