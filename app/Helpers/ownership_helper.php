<?php
use App\Models\IncidentModel;
use App\Models\ChronicleSheetModel;
use App\Interfaces\OwnershipCheckable;

function CheckOwnership($idChronicleSheet, $confirmationCode, $ip, OwnershipCheckable $ownership){
        $incidentModel = new IncidentModel();
        $incidentData = $incidentModel
                            ->where("ip", $ip)
                            ->where("type", "wrong_proof")
                            ->where("`date` > now() - interval 1 minute")
                            ->findAll();
        
        if(count ($incidentData) >= 6){
            $ownership->onTooManyAttempts();
            return false;
        }
        
        if(empty($confirmationCode)){
            $incidentModel->insert(['ip' => $ip, "type" => "wrong_proof"]);
            $ownership->onWrongCode();
            return false;
        }
        
        $chronicleModel = new ChronicleSheetModel();
        $adventureData = $chronicleModel
                            ->where('idChronicleSheet', $idChronicleSheet)
                            ->where('herolabCode', $confirmationCode)
                            ->first();
        if(!$adventureData){
            $incidentModel->insert(['ip' => $ip, "type" => "wrong_proof"]);
            $ownership->onWrongCode();
            return false;
        }
        
        $adventureData["herolabCode"];
        return true;
    }

