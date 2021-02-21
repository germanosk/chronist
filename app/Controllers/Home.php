<?php

namespace App\Controllers;

class Home extends BaseController
{
	public function index()
	{
            helper('visit');
            addVisit($this->request->getUserAgent()->getAgentString(),
                     $this->request->getIPAddress(), 
                    "home");
            return view('welcome_message');
	}
}
