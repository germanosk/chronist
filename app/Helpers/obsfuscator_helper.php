<?php

    function obfuscateId($id){
        $encrypter = \Config\Services::encrypter();
        return bin2hex($encrypter->encrypt($id));
    }
    
    function desofuscateId(&$id) {
        $encrypter = \Config\Services::encrypter();
        $id = $encrypter->decrypt(hex2bin($id));
        return $id;
    }

    function obsfucateIds(&$data,$fieldName){
        foreach ($data as &$sheet) {
            $sheet[$fieldName] =  obfuscateId($sheet[$fieldName]);
        }
    }
    
    function desofuscateKeys(&$array){
        $desofuscatedArray = array();
        
        $encrypter = \Config\Services::encrypter();
        
        foreach ($array as $key => $value) {
            $plain = $key;
            desofuscateId($plain);
            $desofuscatedArray[$plain] = $value;
        }
        $array = $desofuscatedArray;
    }
    