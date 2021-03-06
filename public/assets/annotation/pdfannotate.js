/**
 * PDFAnnotate v1.0.1
 * Author: Ravisha Heshan
 */

var PDFAnnotate = function(container_id, url, options = {}) {
	this.number_of_pages = 0;
	this.pages_rendered = 0;
	this.active_tool = 1; // 1 - Free hand, 2 - Text, 3 - Arrow, 4 - Rectangle
	this.fabricObjects = [];
	this.fabricObjectsData = [];
	this.color = '#212121';
	this.borderColor = '#000000';
	this.borderSize = 1;
	this.font_size = 32;
	this.active_canvas = 0;
	this.container_id = container_id;
	this.url = url;
        this.scale = 1.3;
	this.pageImageCompression = options.pageImageCompression
    ? options.pageImageCompression.toUpperCase()
    : "NONE";
	var inst = this;

	var loadingTask = pdfjsLib.getDocument(this.url);
	loadingTask.promise.then(function (pdf) {
		this.scale = options.scale ? options.scale : 1.3;
	    inst.number_of_pages = pdf.numPages;

	    for (var i = 1; i <= pdf.numPages; i++) {
	        pdf.getPage(i).then(function (page) {
	            var viewport = page.getViewport({scale: scale});
	            var canvas = document.createElement('canvas');
	            document.getElementById(inst.container_id).appendChild(canvas);
	            canvas.className = 'pdf-canvas';
	            canvas.height = viewport.height;
	            canvas.width = viewport.width;
	            context = canvas.getContext('2d');

	            var renderContext = {
	                canvasContext: context,
	                viewport: viewport
				};
	            var renderTask = page.render(renderContext);
	            renderTask.promise.then(function () {
	                $('.pdf-canvas').each(function (index, el) {
	                    $(el).attr('id', 'page-' + (index + 1) + '-canvas');
	                });
	                inst.pages_rendered++;
	                if (inst.pages_rendered == inst.number_of_pages) inst.initFabric();
	            });
	        });
	    }
	}, function (reason) {
	    console.error(reason);
	});

	this.initFabric = function () {
		var inst = this;
		let canvases = $('#' + inst.container_id + ' canvas')
	    canvases.each(function (index, el) {
	        var background = el.toDataURL("image/png");
	        var fabricObj = new fabric.Canvas(el.id, {
	            freeDrawingBrush: {
	                width: 1,
	                color: inst.color
	            }
	        });
			inst.fabricObjects.push(fabricObj);
			if (typeof options.onPageUpdated == 'function') {
				fabricObj.on('object:added', function() {
					var oldValue = Object.assign({}, inst.fabricObjectsData[index]);
					inst.fabricObjectsData[index] = fabricObj.toJSON()
					options.onPageUpdated(index + 1, oldValue, inst.fabricObjectsData[index]) 
				})
			}
	        fabricObj.setBackgroundImage(background, fabricObj.renderAll.bind(fabricObj));
	        $(fabricObj.upperCanvasEl).click(function (event) {
	            inst.active_canvas = index;
	            inst.fabricClickHandler(event, fabricObj);
			});
			fabricObj.on('after:render', function () {
				inst.fabricObjectsData[index] = fabricObj.toJSON()
				fabricObj.off('after:render')
			})

			if (index === canvases.length - 1 && typeof options.ready === 'function') {
				options.ready()
			}
		});
	}

	this.fabricClickHandler = function(event, fabricObj) {
		var inst = this;
	    if (inst.active_tool == 2) {
	        var text = new fabric.IText('Sample text', {
	            left: event.clientX - fabricObj.upperCanvasEl.getBoundingClientRect().left,
	            top: event.clientY - fabricObj.upperCanvasEl.getBoundingClientRect().top,
	            fill: inst.color,
	            fontSize: inst.font_size,
	            selectable: true
	        });
	        fabricObj.add(text);
	        inst.active_tool = 0;
	    }
	}
}

PDFAnnotate.prototype.enableSelector = function () {
	var inst = this;
	inst.active_tool = 0;
	if (inst.fabricObjects.length > 0) {
	    $.each(inst.fabricObjects, function (index, fabricObj) {
	        fabricObj.isDrawingMode = false;
	    });
	}
}

PDFAnnotate.prototype.enablePencil = function () {
	var inst = this;
	inst.active_tool = 1;
	if (inst.fabricObjects.length > 0) {
	    $.each(inst.fabricObjects, function (index, fabricObj) {
	        fabricObj.isDrawingMode = true;
	    });
	}
}

PDFAnnotate.prototype.enableAddText = function () {
	var inst = this;
	inst.active_tool = 2;
	if (inst.fabricObjects.length > 0) {
	    $.each(inst.fabricObjects, function (index, fabricObj) {
	        fabricObj.isDrawingMode = false;
	    });
	}
}

PDFAnnotate.prototype.enableRectangle = function () {
	var inst = this;
	var fabricObj = inst.fabricObjects[inst.active_canvas];
	inst.active_tool = 4;
	if (inst.fabricObjects.length > 0) {
		$.each(inst.fabricObjects, function (index, fabricObj) {
			fabricObj.isDrawingMode = false;
		});
	}

	var rect = new fabric.Rect({
		width: 100,
		height: 100,
		fill: inst.color,
		stroke: inst.borderColor,
		strokeSize: inst.borderSize
	});
	fabricObj.add(rect);
}

PDFAnnotate.prototype.enableAddArrow = function () {
	var inst = this;
	inst.active_tool = 3;
	if (inst.fabricObjects.length > 0) {
	    $.each(inst.fabricObjects, function (index, fabricObj) {
	        fabricObj.isDrawingMode = false;
	        new Arrow(fabricObj, inst.color, function () {
	            inst.active_tool = 0;
	        });
	    });
	}
}

PDFAnnotate.prototype.addImageToCanvas = function () {
	var inst = this;
	var fabricObj = inst.fabricObjects[inst.active_canvas];

	if (fabricObj) {
		var inputElement = document.createElement("input");
		inputElement.type = 'file'
		inputElement.accept = ".jpg,.jpeg,.png,.PNG,.JPG,.JPEG";
		inputElement.onchange = function() {
			var reader = new FileReader();
			reader.addEventListener("load", function () {
				inputElement.remove()
				var image = new Image();
				image.onload = function () {
					fabricObj.add(new fabric.Image(image))
				}
				image.src = this.result;
			}, false);
			reader.readAsDataURL(inputElement.files[0]);
		}
		document.getElementsByTagName('body')[0].appendChild(inputElement)
		inputElement.click()
	} 
}

PDFAnnotate.prototype.deleteSelectedObject = function () {
	var inst = this;
	var activeObject = inst.fabricObjects[inst.active_canvas].getActiveObject();
	if (activeObject)
	{
	    if (confirm('Are you sure ?')) inst.fabricObjects[inst.active_canvas].remove(activeObject);
	}
}

PDFAnnotate.prototype.savePdf = function (fileName) {
	var inst = this;
	var doc = new jspdf.jsPDF();
	if (typeof fileName === 'undefined') {
		fileName = `${new Date().getTime()}.pdf`;
	}

	inst.fabricObjects.forEach(function (fabricObj, index) {
		if (index != 0) {
			doc.addPage();
			doc.setPage(index + 1);
		}
		doc.addImage(
			fabricObj.toDataURL({
				format: 'png'
			}), 
			inst.pageImageCompression == "NONE" ? "PNG" : "JPEG", 
			0, 
			0,
			doc.internal.pageSize.getWidth(), 
			doc.internal.pageSize.getHeight(),
			`page-${index + 1}`, 
			["FAST", "MEDIUM", "SLOW"].indexOf(inst.pageImageCompression) >= 0
			? inst.pageImageCompression
			: undefined
		);
		if (index === inst.fabricObjects.length - 1) {
			doc.save(fileName);
		}
	})
}

PDFAnnotate.prototype.setBrushSize = function (size) {
	var inst = this;
	$.each(inst.fabricObjects, function (index, fabricObj) {
	    fabricObj.freeDrawingBrush.width = size;
	});
}

PDFAnnotate.prototype.setColor = function (color) {
	var inst = this;
	inst.color = color;
	$.each(inst.fabricObjects, function (index, fabricObj) {
        fabricObj.freeDrawingBrush.color = color;
    });
}

PDFAnnotate.prototype.setBorderColor = function (color) {
	var inst = this;
	inst.borderColor = color;
}

PDFAnnotate.prototype.setFontSize = function (size) {
	this.font_size = size;
}

PDFAnnotate.prototype.setBorderSize = function (size) {
	this.borderSize = size;
}

PDFAnnotate.prototype.clearActivePage = function () {
	var inst = this;
	var fabricObj = inst.fabricObjects[inst.active_canvas];
	var bg = fabricObj.backgroundImage;
	if (confirm('Are you sure?')) {
	    fabricObj.clear();
	    fabricObj.setBackgroundImage(bg, fabricObj.renderAll.bind(fabricObj));
	}
}

PDFAnnotate.prototype.serializePdf = function() {
	var inst = this;
	return JSON.stringify(inst.fabricObjects, null, 4);
}

PDFAnnotate.prototype.insertText = function(updateCallback, content, posX, posY, w, h, defaultFontSize){
    defaultFontSize = Number.isNaN(defaultFontSize) ? 16 : defaultFontSize;
    defaultFontSize *= 2;

    var inst = this;
    var fabricObj = this.fabricObjects[inst.active_canvas];
    
    var text = new fabric.IText(content, {
        left: posX,
        top: posY,
        width : w,
        height : h,
        fill: this.color,
        fontSize: defaultFontSize,
        defaultFontSize: defaultFontSize,
        selectable: true,
        lockMovementX : true,
        lockMovementY: true,
        lockScalingX: true,
        lockScalingY: true,
        lockRotation: true,
        fixedWidth: w
    });
    text.setText= function(content){ this.text = content; fabricObj.renderAll();findBestFit(text, updateCallback);fabricObj.renderAll();}
    text.on('changed', function(opt) {
         findBestFit(text, updateCallback);
    });
    findBestFit(text, updateCallback);
    fabricObj.add(text);
    return text;
}

PDFAnnotate.prototype.insertTextBox = function(updateCallback, content, posX, posY, w, h, defaultFontSize){
    defaultFontSize = Number.isNaN(defaultFontSize) ? 16 : defaultFontSize;
    defaultFontSize *= 2;
    var inst = this;
    var fabricObj = this.fabricObjects[inst.active_canvas];
    
    var text = new fabric.Textbox(content, {
        left: posX,
        top: posY,
        width : w,
        height : h,
        fill: this.color,
        fontSize: defaultFontSize,
        defaultFontSize: defaultFontSize,
        selectable: true,
        lockMovementX : true,
        lockMovementY: true,
        lockScalingX: true,
        lockScalingY: true,
        lockRotation: true,
        fixedWidth: w
    });
    text.setText= function(content){ this.text = content; fabricObj.renderAll();findBestFit(text, updateCallback);fabricObj.renderAll();}
    text.on('changed', function(opt) {
         findBestFit(text, updateCallback);
    });
    findBestFit(text, updateCallback);
    fabricObj.add(text);
    return text;
}

function findBestFit(text, updateCallback){
    var t1 = text;
    updateCallback(t1.text);
    if (t1.width > t1.fixedWidth) {
      t1.fontSize *= t1.fixedWidth / (t1.width + 1);
      t1.width = t1.fixedWidth;
    }else{
        t1.fontSize = t1.defaultFontSize;
        t1.width = t1.fixedWidth;
    }
}

PDFAnnotate.prototype.insertBlock = function(updateCallback, posX, posY, w, h){
        
	var fabricObj = this.fabricObjects[this.active_canvas];
	this.active_tool = 4;
	if (this.fabricObjects.length > 0) {
		$.each(this.fabricObjects, function (index, fabricObj) {
			fabricObj.isDrawingMode = false;
		});
	}

	var rect = new fabric.Rect({
                left: posX,
                top: posY,
		width: w,
		height: h,
		fill: "transparent",
		stroke: this.borderColor,
		strokeSize: 4,
                selectable: true,
                lockMovementX : true,
                lockMovementY: true,
                lockScalingX: true,
                lockScalingY: true,
                lockRotation: true,
                isBlock: false
	});
        var text = new fabric.Textbox("click to lock this", {
            left: posX,
            top: posY,
            width : w,
            height : h,
            fill: '#2c1c1c',
            backgroundColor:"#5cb25cd0",
            fontSize: 20,
            defaultFontSize: 50,
            selectable: true,
            lockMovementX : true,
            lockMovementY: true,
            lockScalingX: true,
            lockScalingY: true,
            lockRotation: true,
            textAlign : "center",
            fixedWidth: w
        });
        text.on('mousedown', function(opt) {
            rect.isBlock = !rect.isBlock;
            updateCallback(rect.isBlock);
            if(rect.isBlock){
                text.text = ("click to unlock this")
                rect.set("fill",'#dc3232e0');
            }else{
                text.text = ("click to lock this")
                rect.set("fill",'transparent');
            }
            fabricObj.discardActiveObject();
            fabricObj.renderAll(); 
        });
        rect.on('mousedown', function(opt) {
            rect.isBlock = !rect.isBlock;
            updateCallback(rect.isBlock);
            if(rect.isBlock){
                text.text = ("click to unlock this")
                rect.set("fill",'#dc3232e0');
            }else{
                text.text = ("click to lock this")
                rect.set("fill",'transparent');
            }
            fabricObj.discardActiveObject();
            fabricObj.renderAll(); 
        });
	fabricObj.add(rect);
        fabricObj.add(text);
}


PDFAnnotate.prototype.insertCheckBox = function(updateCallback, posX, posY, w, h){
        
	var fabricObj = this.fabricObjects[this.active_canvas];
	
	var rect = new fabric.Rect({
                left: posX,
                top: posY,
		width: w,
		height: h,
		fill: "transparent",
		stroke: this.borderColor,
		strokeSize: 4,
                selectable: true,
                lockMovementX : true,
                lockMovementY: true,
                lockScalingX: true,
                lockScalingY: true,
                lockRotation: true,
                isBlock: false
	});
        rect.on('mousedown', function(opt) {
            rect.isBlock = !rect.isBlock;
            updateCallback(rect.isBlock);
            if(rect.isBlock){
                rect.set("fill",'#000000');
            }else{
                rect.set("fill",'transparent');
            }
            fabricObj.discardActiveObject();
            fabricObj.renderAll(); 
        });
	fabricObj.add(rect);
}


PDFAnnotate.prototype.insertRewardLabel = function(label, posX, posY, w, h, color, id){
        
	var fabricObj = this.fabricObjects[this.active_canvas];
	this.active_tool = 4;
	if (this.fabricObjects.length > 0) {
		$.each(this.fabricObjects, function (index, fabricObj) {
			fabricObj.isDrawingMode = false;
		});
	}

	var rect = new fabric.Rect({
                left: posX,
                top: posY,
		width: w,
		height: h,
		fill: "#4c3c3cd0",
		stroke: this.borderColor,
		strokeSize: 4,
                selectable: true,
                lockMovementX : true,
                lockMovementY: true,
                lockScalingX: true,
                lockScalingY: true,
                lockRotation: true,
                isBlock: false
	});
        var text = new fabric.Textbox(label, {
            left: posX,
            top: posY,
            width : w,
            height : h,
            fill: '#2c1c1c',
            backgroundColor:color,
            fontSize: 20,
            defaultFontSize: 50,
            selectable: true,
            lockMovementX : true,
            lockMovementY: true,
            lockScalingX: true,
            lockScalingY: true,
            lockRotation: true,
            textAlign : "center",
            fixedWidth: w
        });
	fabricObj.add(rect);
        fabricObj.add(text);
        $("#"+id).change(function () 
               {
                   if(this.checked){
                        text.set("backgroundColor", color);
                    }else{
                        text.set("backgroundColor",'#fefefed0');
                    }
                    fabricObj.discardActiveObject();
                    fabricObj.renderAll(); 
               });
}

PDFAnnotate.prototype.loadFromJSON = function(jsonData) {
	var inst = this;
	$.each(inst.fabricObjects, function (index, fabricObj) {
		if (jsonData.length > index) {
			fabricObj.loadFromJSON(jsonData[index], function () {
				inst.fabricObjectsData[index] = fabricObj.toJSON()
			})
		}
	})
}
