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
        <div class="grid-container">
            <div class="grid-x grid-padding-x">
                <div class="medium-8 small-12 cell medium-offset-2">
                    <h1 class="text-center">Pathfinder Society's Chronist</h1>
                </div>
                    
                <div class="medium-8 small-12 cell medium-offset-2">
                    <a href="./jot" class="button large expanded"> Fast Mode </a>
                </div>

                <div class="medium-8 small-12 cell medium-offset-2">
                    <a href="./download" class="button large expanded"> Download sheets as form </a>
                </div>
            </div>
        </div>

        <script>
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
