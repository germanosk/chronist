<?php
use CodeIgniter\Controller;
use App\Models\VisitModel;

function addVisit($userAgent, $ip, $pageView){
    
    $visitData = [
       'ip' =>  $ip, 
       'userAgent' =>  $userAgent,  
       'pageView' =>  $pageView
    ];
    
    $visitModel = new VisitModel();
    $visitModel->insert($visitData);
}

