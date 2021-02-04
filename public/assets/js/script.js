var reports = [];

$( "#chronicleSelection" ).on('change', function() {
                var selectedId = $( "#chronicleSelection" ).val();
                if(selectedId === "---"){
                    return;
                }
                
                $.get( "./chroniclesheet/"+selectedId)
                    .done(function( data ) {
                        
                            pdf = new PDFAnnotate("pdf-container", baseurl+"/assets/template/" +  data.pdfURL, {
                            onPageUpdated(page, oldData, newData) {
                            },
                            ready() {
                                let report = {};
                                $.each( data.fields, function( key, value ) {
                                    updateCallback = function(val){
                                        report[value.idAdventureField] = val;
                                    };
                                    switch(value.type) {
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
                        
                        
                    });
                   
              });