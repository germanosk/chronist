<?php
use setasign\Fpdi\PdfReader;
use setasign\Fpdi\Tcpdf\Fpdi;
use tecnickcom\TCPDF;

function openPDF($fieldsData, $reportData, $filePath){
    $obj = new TCPDFModel();
    $obj->generateTemplate($fieldsData, $reportData, $filePath);
}



class TCPDFModel extends setasign\Fpdi\Tcpdf\Fpdi{
    
    public function generateTemplate($fieldsData, $reportData, $filePath){
        $pageW = 1206; 
        $pageH = 1566;
        $this->setSourceFile($filePath);
        $pageId = $this->importPage(1);
        $this->SetPrintHeader(false);
        $this->SetPrintFooter(false);
        
        $s = $this->getTemplatesize($pageId);
        $this->addPage($s['orientation'], $s);
        $this->useImportedPage($pageId);
        
        foreach ($fieldsData as $key => $value) {
            switch ($value['type']) {
                case 'text':
                case 'gmField':
                    if(empty($reportData[$key]) || empty($reportData[$key]["value"]))
                    {
                        $fSize = empty($value["fontSize"]) ? 20 : $value["fontSize"];
                        $this->addTextField($value["posX"], $value["posY"], $value["width"], $value["height"],  $pageW, $pageH, $value["fieldName"], $fSize);
                    }else{
                        $this->addCel($value["posX"], $value["posY"], $value["width"], $value["height"],  $pageW, $pageH, $reportData[$key]["value"]);
                    }
                    break;
                case 'reward':
                    if(empty($reportData[$key]["value"]) || $reportData[$key]["value"] === "false"){
                        break;
                    }
                    $this->SetFillColor(255,255,255);
                    $this->adjustPositions($_varX, $_varY, $_varW, $_varH, $value["posX"], $value["posY"], $value["width"], $value["height"], $pageW, $pageH);
                    $this->Rect($_varX, $_varY, $_varW, $_varH, 'F');
                    break;

                default:
                    break;
            }
        }
        
        ob_end_clean();

        return $this->Output('generated.pdf', 'I');
    }
    
    private function addCel($posX, $posY, $cellW, $cellH, $pageW, $pageH, $text){
        
        $this->adjustPositions($_varX, $_varY, $_varW, $_varH, $posX, $posY, $cellW, $cellH, $pageW, $pageH);
        
        $this->SetAutoPageBreak(false);
        $this->SetLeftMargin($_varX);
        $this->SetY($_varY);
        
        $this->cMargin = 0;
        
        $this->SetBestFontSize($_varW, 16, $text);
        $this->Cell($_varW, $_varH, $text,0,0,"L"); 
        $this->SetAutoPageBreak(true);
    }
    
    private function addTextField($posX, $posY, $cellW, $cellH, $pageW, $pageH, $fieldId, $fontSize =16){
        
        $this->adjustPositions($_varX, $_varY, $_varW, $_varH, $posX, $posY, $cellW, $cellH, $pageW, $pageH);
        
        $this->SetAutoPageBreak(false);
        $this->SetLeftMargin($_varX);
        $this->SetY($_varY);
        
        $this->cMargin = 0;
        
        $this->SetFontSize($fontSize);
        $this->TextField($fieldId, $_varW, $_varH);
        $this->SetAutoPageBreak(true);
    }
    
    private function adjustPositions(&$_varX,&$_varY,&$_varW,&$_varH, $posX, $posY, $cellW, $cellH, $pageW, $pageH){
        $_varX = $posX / $pageW * $this->w;
        $_varY = $posY / $pageH * $this->h;
        
        $_varW = $cellW/ $pageW * $this->w;
        $_varH = $cellH/ $pageH * $this->h;
    }
    
    private function SetBestFontSize($width, $defaultFontSize, $text){
        $x = $defaultFontSize;    // Will hold the font size
        $utfText = utf8_decode( $text );
        $this->SetFontSize($x);
        while( $this->GetStringWidth($utfText) > $width ){
            $x-=0.1;   // Decrease the variable which holds the font size
            $this->SetFontSize($x);
        }
    }
}