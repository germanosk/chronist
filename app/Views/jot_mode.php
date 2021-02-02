<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html class="no-js">
    <head>
        <title>Jot mode</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Compressed CSS -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        
        <link rel="stylesheet" href="{baseurl}/assets/foundation-icons/foundation-icons.css" />
        
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/foundation-sites@6.6.3/dist/css/foundation.min.css" integrity="sha256-ogmFxjqiTMnZhxCqVmcqTvjfe1Y/ec4WaRj/aQPvn+I=" crossorigin="anonymous">

        <!-- Compressed JavaScript -->
        <script src="https://cdn.jsdelivr.net/npm/foundation-sites@6.6.3/dist/js/foundation.min.js" integrity="sha256-pRF3zifJRA9jXGv++b06qwtSqX1byFQOLjqa2PTEb2o=" crossorigin="anonymous"></script>
    </head>
    
    <body>
        <h1 class="text-center">Society's Scribe on jotting mode</h1>
        <label>Chronicle
            <select id="chronicleSelection">
                <option value="---" selected="true"> -- SELECT -- </option>
        {sheets}
              <option value="{idChronicleSheet}">{chronicleName}</option>
        {/sheets}
            </select>
        </label>
        
        <div id="pdf-container"></div>
        <script>
            var pdfLocation = "{fileLocation}"
            var pdf;
            $(document).foundation();
            $( "#chronicleSelection" ).on('change', function() {
                var selectedId = $( "#chronicleSelection" ).val();
                if(selectedId === "---"){
                    return;
                }
                
                $.get( "./chroniclesheet/"+selectedId)
                    .done(function( data ) {
                        console.log( "Data Loaded: " + data.pdfURL );
                        
                            pdf = new PDFAnnotate("pdf-container", "{baseurl}/assets/template/" +  data.pdfURL, {
                            onPageUpdated(page, oldData, newData) {
                                console.log(page, oldData, newData);
                            },
                            ready() {
                                console.log("Plugin initialized successfully");
                                                                
                                $.each( data.fields, function( key, value ) {
                                    let txt = pdf.insertText("success",parseInt(value.posX), parseInt(value.posY), parseInt(value.width), parseInt(value.height));
                                    txt.backgroundColor = '#ff00ff';
                                    
                                });
                            },
                            scale: 2,
                            pageImageCompression: "SLOW", // FAST, MEDIUM, SLOW(Helps to control the new PDF file size)
                        });
                        
                        
                    });
                   
              });
        </script>
        

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.6.347/pdf.min.js"></script>
<script>pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.6.347/pdf.worker.min.js';</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/4.3.0/fabric.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.2.0/jspdf.umd.min.js"></script>

    <script src="{baseurl}/assets/annotation/arrow.fabric.js"></script>
    <script src="{baseurl}/assets/annotation/pdfannotate.js"></script>
    </body>
</html>
