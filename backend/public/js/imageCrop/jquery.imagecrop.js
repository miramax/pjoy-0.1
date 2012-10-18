
(function($){
	$.imageCrop = function(object, customOptions) {
		var defaultOptions = {
			allowMove : true,
			allowResize : true,
			allowSelect : true,
			aspectRatio : 0,
			displayPreview : false,
			displaySizeHint : false,
			minSelect : [0, 0],
			minSize : [0, 0],
			maxSize : [0, 0],
			outlineOpacity : 0.5,
			overlayOpacity : 0.5,
			previewBoundary : 90,
			previewFadeOnBlur : 1,
			previewFadeOnFocus : 0.35,
			selectionPosition : [0, 0],
			selectionWidth : 0,
			selectionHeight : 0,

			onChange : function(){},
			onSelect : function(){}
		};

		var options = defaultOptions;

		mergeOptions(customOptions);

		// merge custom and default options
		function mergeOptions(customOptions) {
			options = $.extend(options, customOptions);
		};

		// init images
		var $image = $(object);

		// init image holder and setup
		var $holder = $('<div>')
			.css('position','relative')
			.width($image.width())
			.height($image.height());

		// wrap holder around image
		$image.wrap($holder)
			.css('position', 'absolute');

		// init image crop overlay and puts it after the image
		var $overlay = $('<div id="image-crop-overlay" />')
			.css({
				position : 'absolute',
				opacity : options.overlayOpacity
			})
			.width($image.width())
			.height($image.height())
			.insertAfter($image);

		// invisible layer for handling events
		var $trigger = $('<div />')
			.css({
				backgroundColor : '#000000',
				opacity : 0,
				position : 'absolute'
			})
			.width($image.width())
			.height($image.height())
			.insertAfter($overlay);

		// outline layer
		var $outline = $('<div id="image-crop-outline" />')
			.css({
				opacity : options.outlineOpacity,
				position : 'absolute'
			})
			.insertAfter($trigger);

		// selection layer
		var $selection = $('<div />')
			.css({
				background : 'url('+ $image.attr('src') +') no-repeat',
				position : 'absolute'
			})
			.insertAfter($outline);

		var $sizeHintBackground = $('<div id="image-crop-size-hint-background" />')
				.css({
					opacity : 0.35,
					position : 'absolute'
				})
				.insertAfter($selection);

		var $sizeHintForeground = $('<span id="image-crop-size-hint-foreground" />')
				.css({
					position : 'absolute'
				})
				.insertAfter($sizeHintBackground);

		// resize handlers
		// Initialize a north/west resize handler and place it above the
		// selection layer
		var $nwResizeHandler = $('<div class="image-crop-resize-handler" id="image-crop-nw-resize-handler" />')
		        .css({
		            opacity : 0.5,
		            position : 'absolute'
		        })
		        .insertAfter($selection);

		// Initialize a north resize handler and place it above the selection
		// layer
		var $nResizeHandler = $('<div class="image-crop-resize-handler" id="image-crop-n-resize-handler" />')
		        .css({
		            opacity : 0.5,
		            position : 'absolute'
		        })
		        .insertAfter($selection);

		// Initialize a north/east resize handler and place it above the
		// selection layer
		var $neResizeHandler = $('<div class="image-crop-resize-handler" id="image-crop-ne-resize-handler" />')
		        .css({
		            opacity : 0.5,
		            position : 'absolute'
		        })
		        .insertAfter($selection);

		// Initialize an west resize handler and place it above the selection
		// layer
		var $wResizeHandler = $('<div class="image-crop-resize-handler" id="image-crop-w-resize-handler" />')
		        .css({
		            opacity : 0.5,
		            position : 'absolute'
		        })
		        .insertAfter($selection);

		// Initialize an east resize handler and place it above the selection
		// layer
		var $eResizeHandler = $('<div class="image-crop-resize-handler" id="image-crop-e-resize-handler" />')
		        .css({
		            opacity : 0.5,
		            position : 'absolute'
		        })
		        .insertAfter($selection);

		// Initialize a south/west resize handler and place it above the
		// selection layer
		var $swResizeHandler = $('<div class="image-crop-resize-handler" id="image-crop-sw-resize-handler" />')
		        .css({
		            opacity : 0.5,
		            position : 'absolute'
		        })
		        .insertAfter($selection);

		// Initialize a south resize handler and place it above the selection
		// layer
		var $sResizeHandler = $('<div class="image-crop-resize-handler" id="image-crop-s-resize-handler" />')
		        .css({
		            opacity : 0.5,
		            position : 'absolute'
		        })
		        .insertAfter($selection);

		// Initialize a south/east resize handler and place it above the
		// selection layer
		var $seResizeHandler = $('<div class="image-crop-resize-handler" id="image-crop-se-resize-handler" />')
		        .css({
		            opacity : 0.5,
		            position : 'absolute'
		        })
		        .insertAfter($selection);

		// Initialize a preview holder and place it after the outline layer
		var $previewHolder = $('<div id="image-crop-preview-holder" />')
		        .css({
		            opacity : options.previewFadeOnBlur,
		            overflow : 'hidden',
		            position : 'absolute'
		        })
		        .insertAfter($outline);

		// Initialize a preview image and append it to the preview holder
		var $preview = $('<img alt="Crop preview" id="image-crop-preview" />')
		        .css({
		            position : 'absolute'
		        })
		        .attr('src', $image.attr('src'))
		        .appendTo($previewHolder);


		// Initialize plug-in variables
		var resizeHorizontally = true;
		var resizeVertically = true;
		var selectionExists;
		var selectionOffset = [0, 0];
		var selectionOrigin = [0, 0];
		var selectionExists;
		var selectionOffset = [0, 0];
		var selectionOrigin = [0, 0];

		if (options.allowMove){
		    // Bind an event handler to the 'mousedown' event of the selection layer
		    $selection.mousedown(pickSelection);
		}

		if (options.allowResize) {
		    // Bind an event handler to the 'mousedown' event of the resize handlers
		    $('div.image-crop-resize-handler').mousedown(pickResizeHandler);
		}

		if ( options.selectionWidth > options.minSelect[0] &&
			 options.selectionHeight > options.minSelect[1] ) {
			selectionExists = true;
		} else {
			selectionExists = false;
		}

		updateInterface();

		if(options.allowSelect) {
			$trigger.mousedown(setSelection);
		}

		// Update the size hint
		function updateSizeHint(action) {
		    switch (action) {
		        case 'fade-out' :
		            // Fade out the size hint
		            $sizeHintBackground.fadeOut('slow');
		            $sizeHintForeground.fadeOut('slow');

		            break;
		        default :
		            var display = (selectionExists && options.displaySize) ? 'block' : 'none';

		            // Update the foreground layer
		            $sizeHintForeground.css({
		                    cursor : 'default',
		                    display : display,
		                    left : options.selectionPosition[0] + 4,
		                    top : options.selectionPosition[1] + 4
		                })
		                .html(options.selectionWidth + 'x' + options.selectionHeight);

		            // Update the background layer
		            $sizeHintBackground.css({
		                    cursor : 'default',
		                    display : display,
		                    left : options.selectionPosition[0] + 1,
		                    top : options.selectionPosition[1] + 1
		                })
		                .width($sizeHintForeground.width() + 6)
		                .height($sizeHintForeground.height() + 6);
		    }
		};


		// Update the resize handlers
		function updateResizeHandlers(action) {
		    switch (action) {
		        case 'hide-all' :
		            $('.image-crop-resize-handler').each(function() {
		                $(this).css({
		                        display : 'none'
		                    });
		            });

		            break;
		        default :
		            var display = (selectionExists && options.allowResize) ? 'block' : 'none';

		            $nwResizeHandler.css({
		                    cursor : 'nw-resize',
		                    display : display,
		                    left : options.selectionPosition[0] - Math.round($nwResizeHandler.width() / 2),
		                    top : options.selectionPosition[1] - Math.round($nwResizeHandler.height() / 2)
		                });

		            $nResizeHandler.css({
		                    cursor : 'n-resize',
		                    display : display,
		                    left : options.selectionPosition[0] + Math.round(options.selectionWidth / 2 - $neResizeHandler.width() / 2) - 1,
		                    top : options.selectionPosition[1] - Math.round($neResizeHandler.height() / 2)
		                });

		            $neResizeHandler.css({
		                    cursor : 'ne-resize',
		                    display : display,
		                    left : options.selectionPosition[0] + options.selectionWidth - Math.round($neResizeHandler.width() / 2) - 1,
		                    top : options.selectionPosition[1] - Math.round($neResizeHandler.height() / 2)
		                });

		            $wResizeHandler.css({
		                    cursor : 'w-resize',
		                    display : display,
		                    left : options.selectionPosition[0] - Math.round($neResizeHandler.width() / 2),
		                    top : options.selectionPosition[1] + Math.round(options.selectionHeight / 2 - $neResizeHandler.height() / 2) - 1
		                });

		            $eResizeHandler.css({
		                    cursor : 'e-resize',
		                    display : display,
		                    left : options.selectionPosition[0] + options.selectionWidth - Math.round($neResizeHandler.width() / 2) - 1,
		                    top : options.selectionPosition[1] + Math.round(options.selectionHeight / 2 - $neResizeHandler.height() / 2) - 1
		                });

		            $swResizeHandler.css({
		                    cursor : 'sw-resize',
		                    display : display,
		                    left : options.selectionPosition[0] - Math.round($swResizeHandler.width() / 2),
		                    top : options.selectionPosition[1] + options.selectionHeight - Math.round($swResizeHandler.height() / 2) - 1
		                });

		            $sResizeHandler.css({
		                    cursor : 's-resize',
		                    display : display,
		                    left : options.selectionPosition[0] + Math.round(options.selectionWidth / 2 - $seResizeHandler.width() / 2) - 1,
		                    top : options.selectionPosition[1] + options.selectionHeight - Math.round($seResizeHandler.height() / 2) - 1
		                });

		            $seResizeHandler.css({
		                    cursor : 'se-resize',
		                    display : display,
		                    left : options.selectionPosition[0] + options.selectionWidth - Math.round($seResizeHandler.width() / 2) - 1,
		                    top : options.selectionPosition[1] + options.selectionHeight - Math.round($seResizeHandler.height() / 2) - 1
		                });
		    }
		};


		// Update the preview
		function updatePreview(action) {
		    switch (action) {
		        case 'focus' :
		            // Fade in the preview holder layer
		            $previewHolder.stop()
		                .animate({
		                    opacity : options.previewFadeOnFocus
		                });

		            break;
		        case 'blur' :
		            // Fade out the preview holder layer
		            $previewHolder.stop()
		                .animate({
		                    opacity : options.previewFadeOnBlur
		                });

		            break;
		        case 'hide' :
		            // Hide the preview holder layer
		            $previewHolder.css({
		                display : 'none'
		            });

		            break;
		        default :
		            var display = (selectionExists && options.displayPreview) ? 'block' : 'none';

		            // Update the preview holder layer
		            $previewHolder.css({
		                    display : display,
		                    left : options.selectionPosition[0],
		                    top : options.selectionPosition[1] + options.selectionHeight + 10
		                });

		            // Update the preview size
		            if (options.selectionWidth > options.selectionHeight) {
		                if (options.selectionWidth && options.selectionHeight) {
		                    // Update the preview image size
		                    $preview.width(Math.round($image.width() * options.previewBoundary / options.selectionWidth));
		                    $preview.height(Math.round($image.height() * $preview.width() / $image.width()));

		                    // Update the preview holder layer size
		                    $previewHolder.width(options.previewBoundary)
		                    .height(Math.round(options.selectionHeight * $preview.height() / $image.height()));
		                }
		            } else {
		                if (options.selectionWidth && options.selectionHeight) {
		                    // Update the preview image size
		                    $preview.height(Math.round($image.height() * options.previewBoundary / options.selectionHeight));
		                    $preview.width(Math.round($image.width() * $preview.height() / $image.height()));

		                    // Update the preview holder layer size
		                    $previewHolder.width(Math.round(options.selectionWidth * $preview.width() / $image.width()))
		                        .height(options.previewBoundary);
		                }
		            }

		            // Update the preview image position
		            $preview.css({
		                left : - Math.round(options.selectionPosition[0] * $preview.width() / $image.width()),
		                top : - Math.round(options.selectionPosition[1] * $preview.height() / $image.height())
		            });
		    }
		};





		function getElementOffset(object) {
			var offset = $(object).offset();

			return [offset.left, offset.top];
		};

		function getMousePosition(event) {
			var imageOffset = getElementOffset($image);

			var x = event.pageX - imageOffset[0];
			var y = event.pageY - imageOffset[1];
			x = (x < 0) ? 0 : (x > $image.width()) ? $image.width() : x;
			y = (y < 0) ? 0 : (y > $image.height()) ? $image.height() : y;

			return [x, y];
		};

		function updateOverlayLayer() {
			$overlay.css({
				display : selectionExists ? 'block' : 'none'
			});
		};

		function updateTriggerLayer() {
			$trigger.css({
				cursor : options.allowSelect ? 'crosshair' : 'default'
			});
		};

		function updateSelection() {
			$outline.css({
				cursor : 'default',
				display : selectionExists ? 'block' : 'none',
				left : options.selectionPosition[0],
				top : options.selectionPosition[1]
			})
			.width(options.selectionWidth)
			.height(options.selectionHeight);

			$selection.css({
				backgroundPosition : ( - options.selectionPosition[0] - 1 ) + 'px ' +( - options.selectionPosition[1] - 1 ) + 'px',
				cursor : options.allowMove ? 'move' : 'default',
				display : selectionExists ? 'block' : 'none',
				left : options.selectionPosition[0] + 1,
				top : options.selectionPosition[1] + 1
			})
			.width((options.selectionWidth - 2 > 0) ? (options.selectionWidth - 2) : 0)
			.height((options.selectionHeight - 2 > 0) ? (options.selectionHeight - 2) : 0);
		};

		function updateCursor(cursorType) {
			$trigger.css({
				cursor : cursorType
			});

			$outline.css({
				cursor : cursorType
			});

			$selection.css({
				cursor : cursorType
			});

			$sizeHintBackground.css({  
	            cursor : cursorType  
	        });  
	  
	    	$sizeHintForeground.css({  
	            cursor : cursorType  
	        });

		};

		function updateInterface(sender) {
			switch( sender ) {
				case 'setSelection' :
						updateOverlayLayer();
						updateSelection();
						updateResizeHandlers('hide-all');
						updatePreview('hide');
					break;


				case 'pickSelection' :
						updateResizeHandlers('hide-all');
					break;


				case 'pickResizeHandler' :  
            			updateSizeHint();  
            			updateResizeHandlers('hide-all');
            		break;


				case 'resizeSelection' :  
        				updateSelection();  
            			updateSizeHint();  
            			updateResizeHandlers('hide-all');  
            			updatePreview();  
            			updateCursor('crosshair');  
  					break;


		        case 'moveSelection' :  
		            	updateSelection();  
		            	updateResizeHandlers('hide-all');  
		            	updatePreview();  
		            	updateCursor('move');  
		  			break;  


    			case 'releaseSelection' :  
            			updateTriggerLayer();  
            			updateOverlayLayer();  
            			updateSelection();  
            			updateSizeHint('fade-out');  
            			updateResizeHandlers();  
            			updatePreview(); 
            		break;


				default :
						updateTriggerLayer();  
			            updateOverlayLayer();  
			            updateSelection();  
			            updateResizeHandlers();  
			            updatePreview(); 

			}
		};

		function setSelection(event) {
			event.preventDefault();
			event.stopPropagation();

			$(document).mousemove(resizeSelection);
			$(document).mouseup(releaseSelection);

			if (options.displayPreview) {  
			        // Bind an event handler to the 'mouseenter' event of the preview  
			        // holder  
			        $previewHolder.mouseenter(function() {  
			            updatePreview('focus');  
			         });  
			  
			         // Bind an event handler to the 'mouseleave' event of the preview  
			         // holder  
			         $previewHolder.mouseleave(function() {  
			             updatePreview('blur');  
			         });  
			} 

			selectionExists = true;

			options.selectionWidth = 0;
			options.selectionHeight = 0;

			selectionOrigin = getMousePosition(event);

			options.selectionPosition[0] = selectionOrigin[0];
			options.selectionPosition[1] = selectionOrigin[1];

			updateInterface('setSelection');
		};

				// Pick the current selection  
		function pickSelection(event) {  
		    // Prevent the default action of the event  
		    event.preventDefault();  
		  
		    // Prevent the event from being notified  
		    event.stopPropagation();  
		  
		    // Bind an event handler to the 'mousemove' event  
		    $(document).mousemove(moveSelection);  
		  
		    // Bind an event handler to the 'mouseup' event  
		    $(document).mouseup(releaseSelection);  
		  
		    var mousePosition = getMousePosition(event);  
		  
		    // Get the selection offset relative to the mouse position  
		    selectionOffset[0] = mousePosition[0] - options.selectionPosition[0];  
		    selectionOffset[1] = mousePosition[1] - options.selectionPosition[1];  
		  
		    // Update only the needed elements of the plug-in interface  
		    // by specifying the sender of the current call  
		    updateInterface('pickSelection');  
		};

		// Pick one of the resize handlers  
		function pickResizeHandler(event) {  
		// Prevent the default action of the event  
		    event.preventDefault();  
		  
		    // Prevent the event from being notified  
		    event.stopPropagation();  
		  
		    switch (event.target.id) {  
		        case 'image-crop-nw-resize-handler' :  
		            selectionOrigin[0] += options.selectionWidth;  
		            selectionOrigin[1] += options.selectionHeight;  
		            options.selectionPosition[0] = selectionOrigin[0] - options.selectionWidth;  
		            options.selectionPosition[1] = selectionOrigin[1] - options.selectionHeight;  
		            break;

		        case 'image-crop-n-resize-handler' :  
		            selectionOrigin[1] += options.selectionHeight;  
		            options.selectionPosition[1] = selectionOrigin[1] - options.selectionHeight;
		            resizeHorizontally = false;  
		            break;

		        case 'image-crop-ne-resize-handler' :  
		            selectionOrigin[1] += options.selectionHeight;  
		            options.selectionPosition[1] = selectionOrigin[1] - options.selectionHeight;  
		            break;  

		        case 'image-crop-w-resize-handler' :  
		            selectionOrigin[0] += options.selectionWidth;  
		            options.selectionPosition[0] = selectionOrigin[0] - options.selectionWidth;  
		  
		            resizeVertically = false;  
		            break;  

		        case 'image-crop-e-resize-handler' :  
		            resizeVertically = false;  
		            break; 

		        case 'image-crop-sw-resize-handler' :  
		            selectionOrigin[0] += options.selectionWidth;  
		            options.selectionPosition[0] = selectionOrigin[0] - options.selectionWidth;  
		            break;  

		        case 'image-crop-s-resize-handler' :  
		            resizeHorizontally = false;  
		            break;

		    }  
		  
		    // Bind an event handler to the 'mousemove' event  
		    $(document).mousemove(resizeSelection);  
		  
		    // Bind an event handler to the 'mouseup' event  
		    $(document).mouseup(releaseSelection);  
		  
		    // Update only the needed elements of the plug-in interface  
		    // by specifying the sender of the current call  
		    updateInterface('pickResizeHandler');  
		};

		// Resize the current selection  
		function resizeSelection(event) {  
		    // Prevent the default action of the event  
		    event.preventDefault();  
		  
		    // Prevent the event from being notified  
		    event.stopPropagation();  
		  
		    var mousePosition = getMousePosition(event);  
		  
		    // Get the selection size  
		    var height = mousePosition[1] - selectionOrigin[1],  
		        width = mousePosition[0] - selectionOrigin[0];  
		  
		    // If the selection size is smaller than the minimum size set it  
		    // accordingly  
		    if (Math.abs(width) < options.minSize[0])  
		        width = (width >= 0) ? options.minSize[0] : - options.minSize[0];  
		  
		    if (Math.abs(height) < options.minSize[1])  
		        height = (height >= 0) ? options.minSize[1] : - options.minSize[1];  
		  
		    // Test if the selection size exceeds the image bounds  
		    if (selectionOrigin[0] + width < 0 || selectionOrigin[0] + width > $image.width())  
		        width = - width;  
		  
		    if (selectionOrigin[1] + height < 0 || selectionOrigin[1] + height > $image.height())  
		        height = - height;  
		  
		    if (options.maxSize[0] > options.minSize[0] &&  
		        options.maxSize[1] > options.minSize[1]) {  
		        // Test if the selection size is bigger than the maximum size  
		        if (Math.abs(width) > options.maxSize[0])  
		            width = (width >= 0) ? options.maxSize[0] : - options.maxSize[0];  
		  
		        if (Math.abs(height) > options.maxSize[1])  
		            height = (height >= 0) ? options.maxSize[1] : - options.maxSize[1];  
		    }  
		  
		    // Set the selection size  
		    if (resizeHorizontally)  
		        options.selectionWidth = width;  
		  
		    if (resizeVertically)  
		        options.selectionHeight = height;  
		  
		    // If any aspect ratio is specified  
		    if (options.aspectRatio) {  
		        // Calculate the new width and height  
		        if ((width > 0 && height > 0) || (width < 0 && height < 0))  
		            if (resizeHorizontally)  
		                height = Math.round(width / options.aspectRatio);  
		            else  
		                width = Math.round(height * options.aspectRatio);  
		        else  
		            if (resizeHorizontally)  
		                height = - Math.round(width / options.aspectRatio);  
		            else  
		                width = - Math.round(height * options.aspectRatio);  
		  
		        // Test if the new size exceeds the image bounds  
		        if (selectionOrigin[0] + width > $image.width()) {  
		            width = $image.width() - selectionOrigin[0];  
		            height = (height > 0) ? Math.round(width / options.aspectRatio) : - Math.round(width / options.aspectRatio);  
		        }  
		  
		        if (selectionOrigin[1] + height < 0) {  
		            height = - selectionOrigin[1];  
		            width = (width > 0) ? - Math.round(height * options.aspectRatio) : Math.round(height * options.aspectRatio);  
		        }  
		  
		        if (selectionOrigin[1] + height > $image.height()) {  
		            height = $image.height() - selectionOrigin[1];  
		            width = (width > 0) ? Math.round(height * options.aspectRatio) : - Math.round(height * options.aspectRatio);  
		        }  
		  
		        // Set the selection size  
		        options.selectionWidth = width;  
		        options.selectionHeight = height;  
		    }  
		  
		    if (options.selectionWidth < 0) {  
		        options.selectionWidth = Math.abs(options.selectionWidth);  
		        options.selectionPosition[0] = selectionOrigin[0] - options.selectionWidth;  
		    } else  
		        options.selectionPosition[0] = selectionOrigin[0];  
		  
		    if (options.selectionHeight < 0) {  
		        options.selectionHeight = Math.abs(options.selectionHeight);  
		        options.selectionPosition[1] = selectionOrigin[1] - options.selectionHeight;  
		    } else  
		        options.selectionPosition[1] = selectionOrigin[1];  
		  
		    // Trigger the 'onChange' event when the selection is changed  
		    options.onChange(getCropData());  
		  
		    // Update only the needed elements of the plug-in interface  
		    // by specifying the sender of the current call  
		    updateInterface('resizeSelection');  
		};

		// Move the current selection  
		function moveSelection(event) {  
		    // Prevent the default action of the event  
		    event.preventDefault();  
		  
		    // Prevent the event from being notified  
		    event.stopPropagation();  
		  
		    var mousePosition = getMousePosition(event);  
		  
		    // Set the selection position on the x-axis relative to the bounds  
		    // of the image  
		    if (mousePosition[0] - selectionOffset[0] > 0)  
		        if (mousePosition[0] - selectionOffset[0] + options.selectionWidth < $image.width())  
		            options.selectionPosition[0] = mousePosition[0] - selectionOffset[0];  
		        else  
		            options.selectionPosition[0] = $image.width() - options.selectionWidth;  
		    else  
		        options.selectionPosition[0] = 0;  
		  
		    // Set the selection position on the y-axis relative to the bounds  
		    // of the image  
		    if (mousePosition[1] - selectionOffset[1] > 0)  
		        if (mousePosition[1] - selectionOffset[1] + options.selectionHeight < $image.height())  
		            options.selectionPosition[1] = mousePosition[1] - selectionOffset[1];  
		        else  
		            options.selectionPosition[1] = $image.height() - options.selectionHeight;  
		        else  
		            options.selectionPosition[1] = 0;  
		  
		    // Trigger the 'onChange' event when the selection is changed  
		    options.onChange(getCropData());  
		  
		    // Update only the needed elements of the plug-in interface  
		    // by specifying the sender of the current call  
		    updateInterface('moveSelection');  
		};
		// Release the current selection  
		function releaseSelection(event) {  
		    // Prevent the default action of the event  
		    event.preventDefault();  
		  
		    // Prevent the event from being notified  
		    event.stopPropagation();  
		  
		    // Unbind the event handler to the 'mousemove' event  
		    $(document).unbind('mousemove');  
		  
		    // Unbind the event handler to the 'mouseup' event  
		    $(document).unbind('mouseup');  
		  
		    // Update the selection origin  
		    selectionOrigin[0] = options.selectionPosition[0];  
		    selectionOrigin[1] = options.selectionPosition[1];  
		  
		    // Reset the resize constraints  
		    resizeHorizontally = true;  
		    resizeVertically = true;  
		  
		    // Verify if the selection size is bigger than the minimum accepted  
		    // and set the selection existence accordingly  
		    if (options.selectionWidth > options.minSelect[0] &&  
		        options.selectionHeight > options.minSelect[1])  
		        selectionExists = true;  
		    else  
		        selectionExists = false;  
		  
		    // Trigger the 'onSelect' event when the selection is made  
		    options.onSelect(getCropData());  
		  
		    // If the selection doesn't exist  
		    if (!selectionExists) {  
		        // Unbind the event handler to the 'mouseenter' event of the  
		        // preview  
		        $previewHolder.unbind('mouseenter'); 
		 
		        // Unbind the event handler to the 'mouseleave' event of the 
		        // preview 
		        $previewHolder.unbind('mouseleave'); 
		    } 
		 
		    // Update only the needed elements of the plug-in interface 
		    // by specifying the sender of the current call 
		    updateInterface('releaseSelection');  
		};  

		// Return an object containing information about the plug-in state  
		function getCropData() {  
		    return {  
		        selectionX : options.selectionPosition[0],  
		        selectionY : options.selectionPosition[1],  
		        selectionWidth : options.selectionWidth,  
		        selectionHeight : options.selectionHeight,  
		  
		        selectionExists : function() {  
		            return selectionExists;  
		        }  
		    };   
		};
	};

	$.fn.imageCrop = function(customOptions) {
		this.each(function(){
			var currentObject = this;
			var image = new Image();

			image.onload = function() {
				$.imageCrop(currentObject, customOptions);
			};

			image.src = currentObject.src;
		});

		return this;
	};
}) (jQuery);