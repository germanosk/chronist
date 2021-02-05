var reports = [];

$("#chronicleSelection").on('change', function () {
    var selectedId = $("#chronicleSelection").val();
    var containerLocation = "pdf-container";
    $( "#"+containerLocation ).empty();
    if (selectedId === "---") {
        return;
    }
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
                                    let txt = pdf.insertText(updateCallback, value.defaultValue, parseInt(value.posX), parseInt(value.posY), parseInt(value.width), parseInt(value.height));
                                    txt.backgroundColor = '#ff00ff';
                                    report[value.idAdventureField] = value.defaultValue;
                                    break;
                                case "reward":
                                    let block = pdf.insertBlock(updateCallback, parseInt(value.posX), parseInt(value.posY), parseInt(value.width), parseInt(value.height));

                                    report[value.idAdventureField] = false;
                                    break;
                                case "gmField":
                                    let gmField = pdf.insertText(updateCallback, value.defaultValue, parseInt(value.posX), parseInt(value.posY), parseInt(value.width), parseInt(value.height));
                                    gmField.backgroundColor = '#00ffff';
                                    break;
                            }
                        });
                        reports.push(report);
                    },
                    scale: 2,
                    pageImageCompression: "SLOW", // FAST, MEDIUM, SLOW(Helps to control the new PDF file size)
                });
                let formHTML = "<form id='formID' target='_blank' action='./report/create/' method='post'><input type='submit' value='Submit'></form>";
                formHTML = '<a onclick="jsfunction()" href="javascript:void(0);" class="button">Learn More</a>';
                $("#"+containerLocation).append(formHTML);

            });

});

function jsfunction(){
    $.ajax({
        type: "POST",
        url: "./report/create",
        data: reports[0]
    })
    .done(function( data ) {
        alert( "Data Loaded: " + data );
    });
}