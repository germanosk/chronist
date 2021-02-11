<!DOCTYPE html>
<html class="no-js">
    <head>
        <link rel="icon" href="{baseurl}/favicon.ico" type="image/x-icon">
        <title>Jot mode</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Compressed CSS -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

        <link rel="stylesheet" href="{baseurl}/assets/foundation-icons/foundation-icons.css" />
        <link rel="stylesheet" href="{baseurl}/assets/css/style.css" />
        <link rel="stylesheet" href="{baseurl}/assets/css/foundation.datepicker.css" />

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/foundation-sites@6.6.3/dist/css/foundation.min.css" integrity="sha256-ogmFxjqiTMnZhxCqVmcqTvjfe1Y/ec4WaRj/aQPvn+I=" crossorigin="anonymous">

        <!-- Compressed JavaScript -->
        <script src="https://cdn.jsdelivr.net/npm/foundation-sites@6.6.3/dist/js/foundation.min.js" integrity="sha256-pRF3zifJRA9jXGv++b06qwtSqX1byFQOLjqa2PTEb2o=" crossorigin="anonymous"></script>
    </head>

    <body>
        <h1 class="text-center">Society's Chronist on jot mode</h1>

        <ul id='report-accordion' class="accordion" data-accordion data-allow-all-closed="true">
            <li class="accordion-item is-active" data-accordion-item>
                <a href="#gm" class="accordion-title">GM's data</a>
                <div class="accordion-content" data-tab-content>
                    <div class="small-12 column">
                        <div class="form-floating-label">
                            <input type="text" id="eventName" name="eventName" required>
                            <label for="eventName">{jotfields}{eventName}{/jotfields}</label>
                        </div>
                    </div>
                    <div class="small-12 column">
                        <div class="form-floating-label">
                            <input type="text" id="eventCode" name="eventCode" required>
                            <label for="eventCode">{jotfields}{eventCode}{/jotfields}</label>
                        </div>
                    </div>
                    <div class="small-12 column">
                        <div class="form-floating-label">
                            <input  class="span2" type="text" id="eventDate" name="eventDate" required>
                            <label for="eventDate">{jotfields}{eventDate}{/jotfields}</label>
                        </div>
                    </div>
                    <div class="small-12 column">
                        <div class="form-floating-label">
                            <input type="text" id="gmName" name="gmName" required>
                            <label for="gmName">{jotfields}{gmName}{/jotfields}</label>
                        </div>
                    </div>
                    <div class="small-12 column">
                        <div class="form-floating-label">
                            <input type="text" id="gmNumber" name="gmNumber" required>
                            <label for="gmNumber">{jotfields}{gmNumber}{/jotfields}</label>
                        </div>
                    </div>
                </div>
                
            </li>

            <li class="accordion-item " data-accordion-item>
                <a href="#adventure" class="accordion-title">Adventure Selection</a>
                <div class="accordion-content" data-tab-content>
                    <label>Adventure
                        <select id="chronicleSelection">
                            <option value="---" selected="true"> -- SELECT -- </option>
                            {sheets}
                            <option value="{idChronicleSheet}">{chronicleName}</option>
                            {/sheets}
                        </select>
                    </label>
                </div>
            </li>
            
            
            <li class="accordion-item " data-accordion-item>
                <a href="#sheet" class="accordion-title">Character's sheets</a>
                <div id='sheet-content' class="accordion-content" data-tab-content>
                    
                    <div class="small-12 column hide" id="players_report">
                        <h3>Download</h3>
                    </div>
                    <div class="small-12 column">
                        <label>Adventure
                            <div class="small-12 column">
                                <a id="submit_report_button" onclick="jsfunction()" href="javascript:void(0);" class="button">Submit Report</a>
                            </div>
                            <div id="pdf-container"></div>
                        </label>
                    </div>
                </div>
            </li>
        </ul>




        <script>
            var baseurl = "{baseurl}"
            var pdf;
            $(document).foundation();

        </script>

        <script src="{baseurl}/assets/js/foundation.datepicker.js" type="text/javascript"></script>
        <script src="{baseurl}/assets/js/script.js" ></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.6.347/pdf.min.js"></script>
        <script>pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.6.347/pdf.worker.min.js';</script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/4.3.0/fabric.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.2.0/jspdf.umd.min.js"></script>

        <script src="{baseurl}/assets/annotation/arrow.fabric.js"></script>
        <script src="{baseurl}/assets/annotation/pdfannotate.js"></script>
    </body>
</html>
