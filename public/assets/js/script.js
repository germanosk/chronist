var reports = [];
var selectedId;
var playerName, characterName, organizedPlay;
var reportCount = 0;
$("#chronicleSelection").on('change', function () {
    downloadSheet();
});
function downloadSheet(){
    playerName = "", characterName = "",organizedPlay = "";
    selectedId = $("#chronicleSelection").val();
    var containerLocation = "pdf-container";
    $("#" + containerLocation).empty();
    if (selectedId === "---") {
        return;
    }
    $("#submit_report_button").attr('disabled','disabled');

    $('#report-accordion').foundation('down', $('#sheet-content'));
    
    $.ajax({
        type: "GET",
        url:"./chroniclesheet/" + selectedId,
        headers: {'X-Requested-With': 'XMLHttpRequest'}
    })  
        .done(function (data) {
                pdf = new PDFAnnotate(containerLocation, baseurl + "/assets/template/" + data.pdfURL, {
                    onPageUpdated(page, oldData, newData) {
                    },
                    ready() {
                        let report = {};
                        $.each(data.fields, function (key, value) {
                            updateCallback = function (val) {
                                report[value.idAdventureField] = val;
                            };
                            switch (value.type) {
                                case "text":
                                    let txt = pdf.insertTextBox(updateCallback, value.defaultValue, parseInt(value.posX), parseInt(value.posY), parseInt(value.width), parseInt(value.height), parseInt(value.fontSize));
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
                                    let block = pdf.insertBlock(updateCallback, parseInt(value.posX), parseInt(value.posY), parseInt(value.width), parseInt(value.height));
                                    report[value.idAdventureField] = false;
                                    break;
                                case "gmField":
                                    let gmFieldVal = $("#"+value.fieldName).val()
                                    let gmField = pdf.insertText(updateCallback, gmFieldVal, parseInt(value.posX), parseInt(value.posY), parseInt(value.width), parseInt(value.height), parseInt(value.fontSize));
                                    gmField.backgroundColor = 'transparent';
                                    gmField.selectable = false;
                                    $("#"+value.fieldName).change(function() {
                                        gmField.setText($(this).val())
                                        console.log($(this).val())
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
            });
}

function sendReport() {
    allMainFieldsFilled =  checkIsFilled("#eventName","You need to fill the Event name")
                        && checkIsFilled("#eventCode","You need to fill the Event code")
                        && checkIsFilled("#eventDate","You need to fill the Event date")
                        && checkIsFilled("#gmName","You need to fill the Game master's name")
                        && checkIsFilled("#gmNumber", "You need to fill the Game master's Organized Play number") 
    
    if(!allMainFieldsFilled || $("#submit_report_button").attr('disabled') === "disabled"){
        return;
    }
    
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