var reports = [];
var selectedId;

$("#chronicleSelection").on('change', function () {
    selectedId = $("#chronicleSelection").val();
    var containerLocation = "pdf-container";
    $("#" + containerLocation).empty();
    if (selectedId === "---") {
        return;
    }
    $('#report-accordion').foundation('down', $('#sheet-content'));
    $.get("./chroniclesheet/" + selectedId)
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
                                    let txt = pdf.insertTextBox(updateCallback, value.defaultValue, parseInt(value.posX), parseInt(value.posY), parseInt(value.width), parseInt(value.height));
                                    txt.backgroundColor = '#ff00ff';
                                    report[value.idAdventureField] = value.defaultValue;
                                    break;
                                case "reward":
                                    let block = pdf.insertBlock(updateCallback, parseInt(value.posX), parseInt(value.posY), parseInt(value.width), parseInt(value.height));

                                    report[value.idAdventureField] = false;
                                    break;
                                case "gmField":
                                    let gmFieldVal = $("#"+value.fieldName).val()
                                    let gmField = pdf.insertText(updateCallback, gmFieldVal, parseInt(value.posX), parseInt(value.posY), parseInt(value.width), parseInt(value.height));
                                    gmField.backgroundColor = 'transparent';
                                    gmField.selectable = false;
                                    break;
                            }
                        });
                        reports.push(report);
                    },
                    scale: 2,
                    pageImageCompression: "SLOW", // FAST, MEDIUM, SLOW(Helps to control the new PDF file size)
                });
                let formHTML = "<form id='formID' target='_blank' action='./report/create/' method='post'><input type='submit' value='Submit'></form>";
                formHTML = '<a onclick="jsfunction()" href="javascript:void(0);" class="button">Submit</a>';
                $("#" + containerLocation).append(formHTML);

            });

});

function jsfunction() {
    allMainFieldsFilled =  checkIsFilled("#eventName","You need to fill the Event name")
                        && checkIsFilled("#eventCode","You need to fill the Event code")
                        && checkIsFilled("#eventDate","You need to fill the Event date")
                        && checkIsFilled("#gmName","You need to fill the Game master's name")
                        && checkIsFilled("#gmNumber", "You need to fill the Game master's Organized Play number") 
    
    if(!allMainFieldsFilled){
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
            gmNumber:$("#gmNumber").val()}
    })
            .done(function (data) {
                //alert( "Data Loaded: " + data );

                var newWindow = window.open("", "new window", "width=200, height=100");

                //write the data to the document of the newWindow
                newWindow.document.write(data);
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
		format: 'mm-dd-yyyy',
		disableDblClickSelection: true,
		leftArrow:'<<',
		rightArrow:'>>',
		closeIcon:'X',
		closeButton: true
	}).on("changeDate", function(){
            $(this).parent().addClass('has-value');
            console.log("foi")
        });