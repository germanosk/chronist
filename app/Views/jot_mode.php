<!DOCTYPE html>
<html class="no-js">
    <head>
        <link rel="icon" href="{baseurl}/favicon.ico" type="image/x-icon">
        <title>Chronist - on jot mode</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Compressed CSS -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

        <link rel="stylesheet" href="{baseurl}/assets/foundation-icons/foundation-icons.css" />
        <link rel="stylesheet" href="{baseurl}/assets/css/style.css" />
        <link rel="stylesheet" href="{baseurl}/assets/css/foundation.datepicker.css" />

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/foundation-sites@6.6.3/dist/css/foundation.min.css" integrity="sha256-ogmFxjqiTMnZhxCqVmcqTvjfe1Y/ec4WaRj/aQPvn+I=" crossorigin="anonymous">

        <!-- Compressed JavaScript -->
        <script src="{baseurl}/assets/js/vendor.js"></script>
        <script src="{baseurl}/assets/js/foundation.js"></script>
    </head>

    <body>
        <h1 class="text-center">Society's Chronist on fast mode</h1>

        <nav aria-label="You are here:" role="navigation">
            <ul class="breadcrumbs">
                <li><a href="{baseurl}">Home</a></li>
                <li>Fast Mode</li>
            </ul>
        </nav>
        <ul id='report-accordion' class="accordion" data-accordion data-allow-all-closed="true">
            <li class="accordion-item is-active" data-accordion-item>
                <a href="#gm" class="accordion-title"><h4>GM's data</h4></a>
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
                <a href="#adventure" class="accordion-title"><h4>Adventure Selection</h4></a>
                <div class="accordion-content" data-tab-content>
                    
                    <div class="grid-x">
                        <div class="small-12 column">
                            <div class="grid-x" id="switch-toggle-adventure-type">
                                <div class="small-12 column">
                                    <div class="switch-toggle-wrapper">
                                        <div class="switch">
                                            <input class="switch-input" id="exampleSwitch1" type="checkbox" name="allSwitch" data-toggle-all checked="checked">
                                            <label class="switch-paddle" for="exampleSwitch1">
                                                <span class="show-for-sr">Toggle All</span>
                                            </label>
                                        </div>
                                        <span>Toggle All</span>
                                    </div>
                                </div>
                                
                                <div class="small-2 column">
                                    <div class="switch-toggle-wrapper">
                                        <div class="switch">
                                            <input data-filter="ap" class="adventure-type-switch switch-input" id="apSwitch" type="checkbox" name="apSwitch" checked="checked">
                                            <label class="switch-paddle" for="apSwitch">
                                                <span class="show-for-sr">Adventure Path</span>
                                            </label>
                                        </div>
                                        <span>Adventure Path</span>
                                    </div>
                                </div>
                                
                                <div class="small-2 column">
                                    <div class="switch-toggle-wrapper">
                                        <div class="switch">
                                            <input data-filter="bounty" class="adventure-type-switch switch-input" id="bountySwitch" type="checkbox" name="bountySwitch" checked="checked">
                                            <label class="switch-paddle" for="bountySwitch">
                                                <span class="show-for-sr">Bounty</span>
                                            </label>
                                        </div>
                                        <span>Bounty</span>
                                    </div>
                                </div>
                                
                                <div class="small-2 column">
                                    <div class="switch-toggle-wrapper">
                                        <div class="switch">
                                            <input data-filter="module" class="adventure-type-switch switch-input" id="moduleSwitch" type="checkbox" name="moduleSwitch" checked="checked">
                                            <label class="switch-paddle" for="moduleSwitch">
                                                <span class="show-for-sr">Module</span>
                                            </label>
                                        </div>
                                        <span>Module</span>
                                    </div>
                                </div>
                                
                                <div class="small-2 column">
                                    <div class="switch-toggle-wrapper">
                                        <div class="switch">
                                            <input data-filter="oneshot" class="adventure-type-switch switch-input" id="oneshotSwitch" type="checkbox" name="oneshotSwitch" checked="checked">
                                            <label class="switch-paddle" for="oneshotSwitch">
                                                <span class="show-for-sr">One-Shot</span>
                                            </label>
                                        </div>
                                        <span>One-Shot</span>
                                    </div>
                                </div>
                                
                                <div class="small-2 column">
                                    <div class="switch-toggle-wrapper">
                                        <div class="switch">
                                            <input data-filter="quest" class="adventure-type-switch switch-input" id="questSwitch" type="checkbox" name="questSwitch" checked="checked">
                                            <label class="switch-paddle" for="questSwitch">
                                                <span class="show-for-sr">Quest</span>
                                            </label>
                                        </div>
                                        <span>Quest</span>
                                    </div>
                                </div>
                                
                                <div class="small-2 column">
                                    <div class="switch-toggle-wrapper">
                                        <div class="switch">
                                            <input data-filter="scenario" class="adventure-type-switch switch-input" id="scenarioSwitch" type="checkbox" name="scenarioSwitch" checked="checked">
                                            <label class="switch-paddle" for="scenarioSwitch">
                                                <span class="show-for-sr">Scenario</span>
                                            </label>
                                        </div>
                                        <span>Scenario</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="small-12 column">
                            
                            <label>Adventure
                                <select id="chronicleSelection">
                                    <option value="---" selected="true"> -- SELECT -- </option>
                                    {sheets}
                                    <option class="{type}" value="{idChronicleSheet}">{chronicleCode} - {chronicleName}</option>
                                    {/sheets}
                                </select>
                            </label>
                        </div>

                    </div>
                </div>
            </li>


            <li class="accordion-item " data-accordion-item>
                <a href="#sheet" class="accordion-title"><h4>Character's sheets</h4></a>
                <div id='sheet-content' class="accordion-content" data-tab-content>

                    <div class="small-12 column hide" id="players_report">
                        <h3>Download</h3>
                    </div>
                    <div class="small-12 column">
                        <h2>Adventure</h2>
                        <div class="switch large">
                            <input class="switch-input" id="classic-detailed" type="checkbox" name="exampleSwitch">
                            <label class="switch-paddle" for="classic-detailed">
                                <span class="show-for-sr">MODE</span>
                                <span class="switch-active" aria-hidden="true">Detailed</span>
                                <span class="switch-inactive" aria-hidden="true">Classic</span>
                            </label>
                        </div>

                        <div id="classic-mode">
                            <div class="grid-x">
                                <div class="large-3 cell ">
                                    <div class="form-floating-label">
                                        <input type="text" id="playerName" name="playerName" required>
                                        <label for="playerName">Player Name</label>
                                    </div>
                                </div>
                                <div class="large-3 cell ">
                                    <div class="form-floating-label">
                                        <input type="text" id="characterName" name="characterName" required>
                                        <label for="characterName">Character Name</label>
                                    </div>
                                </div>
                                <div class="large-3 cell ">
                                    <div class="form-floating-label">
                                        <input type="text" id="playerNumber" name="playerNumber" required>
                                        <label for="playerNumber">Player number</label>
                                    </div>
                                </div>
                                <div class="large-3 cell ">
                                    <div class="form-floating-label">
                                        <input type="text" id="characterNumber" name="characterNumber" required>
                                        <label for="characterNumber">Character number</label>
                                    </div>
                                </div>
                            </div>

                            <div class="grid-x">
                                <div class="large-3 cell ">
                                    <div class="form-floating-label">
                                        <input type="text" id="gainedXP" name="gainedXP" required>
                                        <label for="gainedXP">XP gained</label>
                                    </div>
                                </div>
                                <div class="large-3 cell ">
                                    <div class="form-floating-label">
                                        <input type="text" id="gainedGP" name="gainedGP" required>
                                        <label for="gainedGP">GP gained</label>
                                    </div>
                                </div>
                                <div class="large-3 cell ">
                                    <div class="form-floating-label">
                                        <input type="text" id="earnIncome" name="earnIncome" required>
                                        <label for="earnIncome">Earn Income</label>
                                    </div>
                                </div>
                                <div class="large-3 cell ">
                                    <div class="form-floating-label">
                                        <input type="text" id="fameEarned" name="fameEarned" required>
                                        <label for="fameEarned">Fame</label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="grid-x">
                                <div class="large-2 cell ">
                                    <select id="faction1">
                                        <option value="" selected="true"> -- SELECT -- </option>
                                        {factions}
                                            <option value="{nameFaction}">{nameFaction}</option>
                                        {/factions}
                                    </select>
                                </div>
                                <div class="large-2 cell ">
                                    <div class="form-floating-label">
                                        <input type="text" id="reputation1" name="reputation1" required>
                                        <label for="reputation1">Reputation</label>
                                    </div>
                                </div>
                                <div class="large-2 cell ">
                                    <select id="faction2">
                                        <option value="" selected="true"> -- SELECT -- </option>
                                        {factions}
                                            <option value="{nameFaction}">{nameFaction}</option>
                                        {/factions}
                                    </select>
                                </div>
                                <div class="large-2 cell ">
                                    <div class="form-floating-label">
                                        <input type="text" id="reputation2" name="reputation2" required>
                                        <label for="reputation2">Reputation</label>
                                    </div>
                                </div>
                                <div class="large-2 cell ">
                                    <select id="faction3">
                                        <option value="" selected="true"> -- SELECT -- </option>
                                        {factions}
                                            <option value="{nameFaction}">{nameFaction}</option>
                                        {/factions}
                                    </select>
                                </div>
                                <div class="large-2 cell ">
                                    <div class="form-floating-label">
                                        <input type="text" id="reputation3" name="reputation3" required>
                                        <label for="reputation3">Reputation</label>
                                    </div>
                                </div>
                            </div>
                            <div id="reward-grid" class="grid-x">
                                
                            </div>
                            <div >
                                <div class="large-12 cell">
                                    <a id="submit_report_button" onclick="submitClassicReport()" href="javascript:void(0);" class="button">Submit Report</a>
                                </div>
                            </div>
                            <div id="pdf-container-classic"></div>

                        </div>

                        <div id="detailed-mode">
                            <div class="small-12 column">
                                <a id="submit_report_button" onclick="submitReport()" href="javascript:void(0);" class="button">Submit Report</a>
                            </div>
                            <div id="pdf-container-detailed"></div>
                        </div>
                    </div>
                </div>
            </li>
        </ul>

        <div class="reveal" id="modalEmptyPDF" data-reveal>
            <h1>Hey your report is empty!</h1>
            <p class="lead">You can click in the fields of the PDF to fill it, don't worry if you don't fill all fields. We will add some form fields to the PDF file to be filled later.</p>
            <p class="lead">You can click on boons / rewards to block it in the final PDF.</p>
            <button class="button alert"  onclick="submitReport()" href="javascript:void(0);">SEND ANYWAY</button>
            <button class="button"  onclick="popup.close()" href="javascript:void(0);">CLOSE</button>
            <button class="close-button" data-close aria-label="Close reveal" type="button">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>


        <script>
            var baseurl = "{baseurl}"
            var detailed_pdf,classic_pdf;
            $(document).foundation();
            var popup = new Foundation.Reveal($('#modalEmptyPDF'));
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
