var reports = [];
var selectedId;
var playerName, characterName, organizedPlay;
var reportCount = 0;
var touchedPDF = false;
var sheetData;

$("#chronicleSelection").on('change', function () {
    $('#confirmationCode').focus();
});

$('#switch-toggle-adventure-type [data-toggle-all]' ).click(function () {
  $( '#switch-toggle-adventure-type input[type="checkbox"]').prop('checked', this.checked)
  var active = this.checked;
  $( ".adventure-type-switch" ).each(function() {
     var adventureType = $(this).attr('data-filter');
     filterAdventure(adventureType, active);
  })
});

$('.adventure-type-switch ' ).on('change', function () {
  var adventureType = $(this).attr('data-filter');
  var active = $(this).prop('checked');
  console.log("value "+adventureType+" active "+active);
  filterAdventure(adventureType, active);
});

function filterAdventure(adventureType, active){
    if(active){
        $('option').filter('.'+adventureType).show('3000');
    }
    else{
        $('option').filter('.'+adventureType).hide('3000');
    }
}

function validate(){
    downloadSheet();
}

function downloadSheet(){
    playerName = "", characterName = "",organizedPlay = "";
    selectedId = $("#chronicleSelection").val();
    var confirmationCode = $("#confirmationCode").val();
    if (selectedId === "---") {
        return;
    }
    $("#submit_report_button").attr('disabled','disabled');
    
    $.ajax({
        type: "GET",
        url:"./chroniclesheet/" + selectedId+"/"+confirmationCode,
        headers: {'X-Requested-With': 'XMLHttpRequest'}
    })  
        .fail(function( jqXHR, textStatus ) {
            var responseObject = jQuery.parseJSON(jqXHR.responseText);
            console.log(jqXHR);
            $("#errorHeader").empty();
            $("#errorMessage").empty();
            if(responseObject.error === 400){
                $("#errorHeader").append("Error while checking proof of ownership");
                $("#errorMessage").append(responseObject.messages.error);
            }
            else if(responseObject.error === 404){
                $("#errorHeader").append("PDF template not found");
                $("#errorMessage").append(responseObject.messages.error);
            }
            else{
                $("#errorHeader").append("Uncaugth error!");
                $("#errorMessage").append(textStatus);
            }
            errorPopup.open();
        })
        .done(function (data) {
                console.log("DONE");

                $('#report-accordion').foundation('down', $('#sheet-content'));
                sheetData = data.valueOf();
                loadDetailedPDF(data);
                loadClassicPDF(data);
            });
}


function loadDetailedPDF(data){
    var containerLocation = "pdf-container-detailed";
    $("#" + containerLocation).empty();
    detailed_pdf = new PDFAnnotate(containerLocation, baseurl + "/assets/template/" + data.pdfURL, {
        onPageUpdated(page, oldData, newData) {
        },
        ready() {
            let report = {};
            $.each(data.fields, function (key, value) {
                updateCallback = function (val) {
                    report[value.idAdventureField] = val;
                    if(value.type!=="gmField" && val){
                        touchedPDF = true;
                    }
                };
                switch (value.type) {
                    case "text":
                        let txt = detailed_pdf.insertTextBox(updateCallback, value.defaultValue, parseInt(value.posX), parseInt(value.posY), parseInt(value.width), parseInt(value.height), parseInt(value.fontSize));
                        txt.backgroundColor = "#8fbff178";
                        report[value.idAdventureField] = value.defaultValue;
                        switch (value.fieldName) {
                              case "playerName":
                                   playerName = value.idAdventureField;
                                  break;
                              case "characterName":
                                  characterName = value.idAdventureField;
                                  break;
                              case "playerNumber":
                                  organizedPlay = value.idAdventureField;
                                  break;
                        }
                        break;
                    case "reward":
                        let block = detailed_pdf.insertBlock(updateCallback, parseInt(value.posX), parseInt(value.posY), parseInt(value.width), parseInt(value.height));
                        report[value.idAdventureField] = false;
                        break;
                    case "event":
                        let checkBox = detailed_pdf.insertCheckBox(updateCallback, parseInt(value.posX)-1, parseInt(value.posY)+5, parseInt(value.width), parseInt(value.height));
                        report[value.idAdventureField] = false;
                        break;
                    case "gmField":
                        let gmFieldVal = $("#"+value.fieldName).val()
                        let gmField = detailed_pdf.insertText(updateCallback, gmFieldVal, parseInt(value.posX), parseInt(value.posY), parseInt(value.width), parseInt(value.height), parseInt(value.fontSize));
                        gmField.backgroundColor = 'transparent';
                        gmField.selectable = false;
                        $("#"+value.fieldName).change(function() {
                            gmField.setText($(this).val())
                        });
                        break;
                }
            });
            reports[0] = (report);
            $("#submit_report_button").removeAttr('disabled');
        },
        scale: 2,
        pageImageCompression: "SLOW", // FAST, MEDIUM, SLOW(Helps to control the new PDF file size)
    });
}

function loadClassicPDF(data){
    var containerLocation = "pdf-container-classic";
    
    $("#" + containerLocation).empty();
    $("#event-grid").empty();
    $("#reward-grid").empty();
    $("#event-grid").append('<div class="small-12 cell "> <h4>Chronicle events</h4> </div>');
    $("#reward-grid").append('<div class="small-12 cell"> <h4>Rewards</h4> </div>');
    $("#event-grid").hide();
    $("#reward-grid").hide();
    
    classic_pdf = new PDFAnnotate(containerLocation, baseurl + "/assets/template/" + data.pdfURL, {
        onPageUpdated(page, oldData, newData) {
        },
        ready() {
            let label = "A";
            let eventLabel = 1;
             $.each(data.fields, function (key, value) {
                 switch (value.type) {
                        case "reward":
                            $("#reward-grid").show();
                            var switchText = genSwitchText(value.fieldName, 'reward', 'Reward '+label,'rewardSwitch');
                            $("#reward-grid").append(switchText);
                            classic_pdf.insertRewardLabel("Reward "+label, parseInt(value.posX), parseInt(value.posY), parseInt(value.width), parseInt(value.height), "#dd8615d0", value.fieldName);
                        label = String.fromCharCode(label.charCodeAt() + 1);                       
                        break;
                        case "event":
                            $("#event-grid").show();
                            var switchText = genSwitchText(value.fieldName, 'event', 'Event E'+eventLabel,'eventSwitch');
                            $("#event-grid").append(switchText);
                            classic_pdf.insertRewardLabel("E"+eventLabel, parseInt(value.posX)-10, parseInt(value.posY), parseInt(value.width), parseInt(value.height), "#8500ffd0", value.fieldName);
                            eventLabel++;
                            break;
                 }
             });
        },
        scale: 2,
        pageImageCompression: "SLOW", 
    });
}

function submitReport() {
    allMainFieldsFilled =  checkIsFilled("#eventName","You need to fill the Event name")
                        && checkIsFilled("#eventCode","You need to fill the Event code")
                        && checkIsFilled("#eventDate","You need to fill the Event date")
                        && checkIsFilled("#gmName","You need to fill the Game master's name")
                        && checkIsFilled("#gmNumber", "You need to fill the Game master's Organized Play number") 
    
    if(!allMainFieldsFilled || $("#submit_report_button").attr('disabled') === "disabled"){
        return;
    }
    if(!touchedPDF){
       popup.open();
       return; 
    }
    sendReport();
}

function submitClassicReport(){
    allMainFieldsFilled =  checkIsFilled("#eventName","You need to fill the Event name")
                        && checkIsFilled("#eventCode","You need to fill the Event code")
                        && checkIsFilled("#eventDate","You need to fill the Event date")
                        && checkIsFilled("#gmName","You need to fill the Game master's name")
                        && checkIsFilled("#gmNumber", "You need to fill the Game master's Organized Play number") 
    
    if(!allMainFieldsFilled || $("#submit_report_button").attr('disabled') === "disabled"){
        return;
    }
    
    $.each(sheetData.fields, function (key, value) {
        if(value.type === "reward"){
          reports[0][value.idAdventureField] = $('#'+value.fieldName).is(":checked")
          ? "false" : "true";
        }else if(value.type === "event")
        {
            reports[0][value.idAdventureField] = $('#'+value.fieldName).is(":checked")
          ? "true" : "false"; 
        }
        else{
            if($( "#"+value.fieldName ).length){
                reports[0][value.idAdventureField] = $('#'+value.fieldName).val();
            }else{
                if(value.fieldName === "earnIncomeX"){
                      reports[0][value.idAdventureField] ="Earn income " + $('#earnIncome').val();
                }
                else if(value.fieldName.includes("reputation_")){
                    var res = value.fieldName.split("_");
                    if( res[1] <= "3" 
                        && $('#faction'+res[1] ).val()
                        && $('#reputation'+res[1] ).val()){
                            reports[0][value.idAdventureField] =$('#faction'+res[1] ).val()+" "+$('#reputation'+res[1] ).val();    
                    }
                }
            }
        }
      });
      
      sendReport();
    
    //resetting fields that are not gm fields
    $.each(sheetData.fields, function (key, value) {
        if($( "#"+value.fieldName ).length){
            if(value.type === "reward"){
                $('#'+value.fieldName).checked = true;
            }
            else if (value.type !== "gmField"){
                $('#'+value.fieldName).val("");
            }
        }else{
            if(value.fieldName === "earnIncomeX"){
                $('#earnIncome').val("");
            }
            else if(value.fieldName.includes("reputation_")){
                var res = value.fieldName.split("_");
                if(res[1] <= "3"){
                    $('#faction'+res[1] ).val("");
                    $('#reputation'+res[1] ).val("");
                }
            }
        }
      });
}

function setupDetailsSwitch(){
    $('#classic-detailed').change(function () 
                {
                    detailSwitch(this.checked);
                });
    detailSwitch(this.checked);
}

function detailSwitch(active){
    if (active) {
        $('#classic-mode').hide();
        $('#detailed-mode').show();
    }
    else{
        $('#classic-mode').show();
        $('#detailed-mode').hide();
    }
}

setupDetailsSwitch();

function sendReport(){
    
    $.ajax({
        type: "POST",
        url: "./report/create",
        data: {sheet: selectedId, 
            report: reports[0],
            eventName:$("#eventName").val(),
            eventCode:$("#eventCode").val(),
            eventDate:$("#eventDate").val(),
            gmName:$("#gmName").val(),
            gmNumber:$("#gmNumber").val()},
        success : function(response){ 
            $("#players_report").removeClass("hide");
            reportCount++;
            var sufix = reports[0][characterName] ? reports[0][characterName] +"'s Report" : "";
            sufix = !sufix && reports[0][playerName] ? reports[0][playerName] +"'s Report": sufix;
            sufix = !sufix && reports[0][organizedPlay] ? reports[0][organizedPlay]+"'s Report" : sufix;
            sufix = sufix ? " report #"+reportCount+" - "+sufix :" report #"+reportCount;
            var linkName = "Download "+sufix;
            $("#players_report").append('<p><a href="' + baseurl + '/download/report/' + response.idReport + '" target="_blank">'+linkName+'</a></p>');
            downloadSheet();
        }
    })
    .done(function (data) {
        $("#submit_report_button").removeAttr('disabled');
    });
}

function checkIsFilled(id,message)
{
    if($(id).val() === ""){
        alert(message);
        return false;
    }
    return true;
}

function checkPDFEmpty(){
     for (var key in reports[0]) {
         if(reports[0][key] ){
             return false;
         }
     }
     return true;
}

function genSwitchText(id, className, content, name ){
    var switchText = ' <div class="switch-toggle-wrapper small-6 medium-2 grid">'+
        '<div class="switch small '+className+'">'+
            '<input class="switch-input " id="'+id+'" type="checkbox" name="'+name+'" checked="checked">'+
            '<label class="switch-paddle " for="'+id+'">'+
              '<span class="show-for-sr">'+content+'</span>'+
            '</label>'+
        '</div>'+
        '<span>'+content+'</span>'+
        '</div>';
    return switchText;
}

// Foundation related
$('.form-floating-label input, .form-floating-label textarea').focusin(function () {
    $(this).parent().addClass('has-value');
});

$('.form-floating-label input, .form-floating-label textarea').blur(function () {
    if (!$(this).val().length > 0) {
        $(this).parent().removeClass('has-value');
    }
});

$('#eventDate').fdatepicker({
		initialDate: '',
		format: 'dd-mm-yyyy',
		disableDblClickSelection: true,
		leftArrow:'<<',
		rightArrow:'>>',
		closeIcon:'X',
		closeButton: true
	}).on("changeDate", function(){
            $(this).parent().addClass('has-value');
        });