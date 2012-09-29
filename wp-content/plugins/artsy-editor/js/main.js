/*
Artsy Editor
http://artsyeditor.com/

main.js
Version 1.2.4
@author Stephen Ou and Sean Fisher
http://artsyeditor.com/authors/

Copyright 2011 Stephen Ou and Sean Fisher
This file may not be redistributed without the express written permission of the authors.
*/
var artsy = {};

/**
 To hold our timer
 *
 @global int
**/
var timer1;
var timer2;
var timer3;
var timer4;

/**
 To hold our post title
 
 @global string
**/
artsy.postTitle = '';

/**
 To hold our post content
 
 @global string
**/
artsy.postContent = '';

/**
 To hold our getting started text
 
 @global string
**/
artsy.gettingStartedTitle = 'How to use Artsy Editor?';
artsy.gettingStartedContent = '<p>Few things to help you get started:</p><ul><li>When you select some text, the formatting box show up. You can <strong>bold</strong>, <em>italicize</em>, <a href="http://google.com">link</a>, create list, and a lot more.</li><li>Drag in an image or use bottom right button to choose one, it will be uploaded & inserted automatically.</li><li>Resize it by hovering over image and dragging the handle. Click the image to edit its metadata.</li><li>When the post is ready, click Publish on top right. Or press X on top left to add tags/categories/custom settings.</li><li>Several options (HTML mode, save draft, preview) will show if you hover over + next to Publish button.</li><li>You can customize the interface by clicking Settings icon on bottom left. There is also a list of FAQs and handy keyboard shortcuts.</li></ul><p>Feel free to save this post as a draft for future reference! Hover over + next to Publish button and click Save Draft.</p><p>If you need more help, visit <a target="_blank" href="http://artsyeditor.com/getting-started/">http://artsyeditor.com/getting-started/</a>, we have tutorials that cover bits and pieces.</p>';

/**
 To hold our original image sizes in case we want to revert from resizing
 
 @global array
**/
artsy.originalSizes = new Array();


/**
 To hold the path of different elements
 
 @global string
**/
artsy.pathEditor 			= '#artsy-editor-container';
artsy.pathContent 			= artsy.pathEditor+' #artsy-editor-content';
artsy.pathHTMLContent		= artsy.pathEditor+' #artsy-editor-content-html';
artsy.pathTitle 			= artsy.pathEditor+' #artsy-editor-title';
artsy.pathBackground 		= '#artsy-background';
artsy.pathMenu 				= '#artsy-menu';
artsy.pathLink 				= 'a#edButtonArtsy';
artsy.pathFileUpload 		= artsy.pathEditor+' #artsy-file-upload';
artsy.pathFileUploadTipsDiv = artsy.pathBackground+' .tips-container';
artsy.pathFileUploadTips 	= artsy.pathBackground+' .tips';
artsy.pathEditorBoxButton 	= artsy.pathEditor+' #artsy-close';
artsy.pathSettingsBox 		= '#artsy-editor-settings';
artsy.pathEditorBox 		= '#artsy-editor-box';
artsy.pathSettingsBoxButton = artsy.pathEditor+' #artsy-settings';
artsy.pathPublishButton 	= artsy.pathEditor+' #artsy-publish';
artsy.pathSaveDraftButton 	= artsy.pathEditor+' #artsy-save-draft';
artsy.pathPreviewButton 	= artsy.pathEditor+' #artsy-preview';
artsy.pathMoveToTrashButton = artsy.pathEditor+' #artsy-move-to-trash';
artsy.pathHTMLModeButton	= artsy.pathEditor+' #artsy-html-toggle';
artsy.pathWordCount			= '#artsy-word-count';
artsy.pathMask 				= '#artsy-mask';
artsy.pathResizeImage 		= '.artsy-resize-image';
artsy.pathImageBox 			= '#artsy-image-box';
artsy.pathResizeDimension 	= '#resize-dimensions';
artsy.pathNewParagraph	 	= '#artsy-new-paragraph';
artsy.pathCurrentPosition 	= '#current-position';
artsy.pathCancelSettings 	= '#cancel-settings';
artsy.pathInputLink 		= '#input-link';
artsy.pathInputImageTitle 	= '#input-image-title';
artsy.pathInputImageAlt 	= '#input-image-alt';
artsy.pathInputImageCap 	= '#input-image-cap';
artsy.pathInputImageLink 	= '#input-image-link';
artsy.pathInputImageWidth 	= '#input-image-width';
artsy.pathInputImageHeight 	= '#input-image-height';
artsy.pathOriginalSize	 	= '#original-size-button';
artsy.pathDeleteImage	 	= '#delete-image-button';
artsy.pathNewWindow 		= '#action-newWindow';
artsy.pathUIWrapper 		= '.ui-wrapper';
artsy.pathWPTitle 			= '#post-body-content input#title';
artsy.pathWPContent 		= '#post-body-content textarea#content';
artsy.pathWPWrap 			= '#wpwrap';
artsy.pathWPFooter 			= '#footer';
artsy.pathWPContentArea 	= '#wpcontent';
artsy.pathWPPublish 		= '#publish';
artsy.pathWPSaveDraft 		= '#save-post';
artsy.pathStyleBlock 		= '#artsy-style-block';
artsy.backgroundStyleID		= '#artsy-background-css';

/**
 To hold our default identifier for images & links
 
 @global string
**/
artsy.imageIdentifier 		= 'artsy-image-id';
artsy.linkIdentifier 		= 'artsy-link-id';

// To hold the administration ID path where we append the artsyeditor button, for easy access
artsy.editorAppendage = '#wpwrap #content-tmce';

// To hold the text we've currently selected
artsy.selectedText = '';

// To hold the start of the selection
artsy.rangeStart = 0;

// To hold the end of the selection
artsy.rangeEnd = 0;

// To hold the link destination of the selection
artsy.parentElementHref = '';

// To hold the link target of the selection
artsy.parentElementTarget = '';

// Set what formatting blocks are usable
artsy.formatBlocks = ['p', 'blockquote', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'code', 'pre', 'address'];

//	Current view mode
artsy.viewMode = 'visual';

// A bunch of bools.
artsy.junkOpen = true;
artsy.hasSetup = false;
artsy.justInitiated = false;
artsy.currentlyResizing = false;
artsy.didDraggedOver = false;
artsy.draggedSelectionInContent = false;
artsy.fileUploadSetup = false;
artsy.enteredContent = false;
artsy.enteredDocument = false;
artsy.inputFileActivate = false;
artsy.contentPasted = false;
artsy.autoSaveSetup = false;
artsy.enteredInnerContent = false;
artsy.modifiedKeyPressed = null;

//	End of variables.

//	Calling the init() function to setup artsy
jQuery(document).ready(function () { artsy.init(); });

// Initiation method
artsy.init = function () {
	
	// Prepare data for initiation AJAX call
	var data = {
		action: 'getHTML',
		postID: jQuery('#post_ID').val()
	};
	
	// Post to get data using AJAX
	jQuery.post(ajaxurl, data, function(response) {
	
		response = eval('('+response+')');
		
		// Prepend the HTML accordingly
		jQuery('body').prepend(response.uploadBox);
		jQuery('body').prepend(response.imageBox);
		jQuery('body').prepend(response.settingsBox);
		jQuery('body').prepend(response.editorBox);

		// Add the overlay HTML to the body
		jQuery('body').prepend(response.editor);
		
		// Hide the editor just for now, fade it in later
		jQuery(artsy.pathEditor).add(artsy.pathBackground).css('display', 'none');
		
		//	Hide the HTML mode editor textarea
		jQuery(artsy.pathHTMLContent).css('display', 'none');
		
		// We add a tab to the editor so they can enter Artsy Mode
		jQuery(artsy.editorAppendage).after('<a id="edButtonArtsy" class="hide-if-no-js" onclick="switchEditors.go(\'content\', \'artsy\');">Artsy</a>');
		
		// Set up the escape key for easy access
		var shortcutsEscape = [{
			keys: 'Esc',
			method: function () {
				artsy.escKeyPressed();
			}
		}];
		
		// Initiate the escape key
		jQuery.each(shortcutsEscape, function (index, elem) {
		
			// Add the element key to shortcut.js plugin
			shortcut.add(elem.keys, function () {
				elem.method();
				return false;
			}, { 'type': 'keydown', 'propagate': false });
			
		});
		
		// If it's set open automatically, trigger the link
		if (jQuery('#artsy_open-automatically').text() == 1) {
			
			// Trigger the opening tab automatically
			jQuery(artsy.pathLink).trigger('click');
			// And set the initiation status to true
			artsy.justInitiated = true;
			
		}
		
	});
	
};

artsy.runEditor = function () {
	
	// FreshEditor Plugin from FreshCode Software Development
	// http://freshcode.co/plugins/jquery.contenteditable/demo.html
	// Thank you, Petrus Theron!
	artsy.fresheditor = function (method) {
	
		// Method calling logic
		if (methods[method]) {
			return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
		} else if (typeof method === 'object' || !method) {
			return methods.init.apply(this, arguments);
		} else {
			$.error('Method ' + method + ' does not exist on jQuery.contentEditable');
		}
		return;
		
	};

	// Set up all the shortcut keys within the editor
	// Thanks to OpenJS shortcut plugin
	var shortcuts = [
		{ keys: 'Meta+,', method: function () { artsy.toggleSettingsBox(); } }, // Command+,: Settings
		{ keys: 'Meta+b', method: function () { methods.bold.apply(this); } }, // Command+b: Bold
		{ keys: 'Meta+i', method: function () { methods.italicize.apply(this); } }, // Command+i: Italicize
		{ keys: 'Meta+u', method: function () { methods.underline.apply(this); } }, // Command+u: Underline
		{ keys: 'Meta+k', method: function () { methods.createLink.apply(this); } }, // Command+k: Create link
		{ keys: 'Meta+0', method: function () { methods.formatBlock.apply(this, ['<p>']); } }, // Command+0: Paragraph
		{ keys: 'Meta+1', method: function () { methods.formatBlock.apply(this, ['<h1>']); } }, // Command+1: H1
		{ keys: 'Meta+2', method: function () { methods.formatBlock.apply(this, ['<h2>']); } }, // Command+2: H2
		{ keys: 'Meta+3', method: function () { methods.formatBlock.apply(this, ['<h3>']); } }, // Command+3: H3
		{ keys: 'Meta+4', method: function () { methods.formatBlock.apply(this, ['<h4>']); } }, // Command+4: H4
		{ keys: 'Meta+5', method: function () { methods.formatBlock.apply(this, ['<h5>']); } }, // Command+5: H5
		{ keys: 'Meta+6', method: function () { methods.formatBlock.apply(this, ['<h6>']); } }, // Command+6: H6
		
		{ keys: 'Shift+Alt+b', method: function () { methods.bold.apply(this); } }, // Shift+Alt+b: Bold
		{ keys: 'Shift+Alt+i', method: function () { methods.italicize.apply(this); } }, // Shift+Alt+i: Italicize
		{ keys: 'Shift+Alt+k', method: function () { methods.createLink.apply(this); } }, // Shift+Alt+k: Create link
		{ keys: 'Shift+Alt+0', method: function () { methods.formatBlock.apply(this, ['<p>']); } }, // Shift+Alt+0: Paragraph
		{ keys: 'Shift+Alt+1', method: function () { methods.formatBlock.apply(this, ['<h1>']); } }, // Shift+Alt+1: H1
		{ keys: 'Shift+Alt+2', method: function () { methods.formatBlock.apply(this, ['<h2>']); } }, // Shift+Alt+2: H2
		{ keys: 'Shift+Alt+3', method: function () { methods.formatBlock.apply(this, ['<h3>']); } }, // Shift+Alt+3: H3
		{ keys: 'Shift+Alt+4', method: function () { methods.formatBlock.apply(this, ['<h4>']); } }, // Shift+Alt+4: H4
		{ keys: 'Shift+Alt+5', method: function () { methods.formatBlock.apply(this, ['<h5>']); } }, // Shift+Alt+5: H5
		{ keys: 'Shift+Alt+6', method: function () { methods.formatBlock.apply(this, ['<h6>']); } }, // Shift+Alt+6: H6
		{ keys: 'Shift+Alt+d', method: function () { methods.strikethrough.apply(this); } }, // Shift+Alt+d: Strike-through
		{ keys: 'Shift+Alt+a', method: function () { methods.createLink.apply(this); } }, // Shift+Alt+k: Create link
		{ keys: 'Shift+Alt+s', method: function () { methods.unlink.apply(this); } }, // Shift+Alt+k: Remove link
		{ keys: 'Shift+Alt+u', method: function () { methods.unorderedList.apply(this); } }, // Shift+Alt+u: Unordered list
		{ keys: 'Shift+Alt+o', method: function () { methods.orderedList.apply(this); } }, // Shift+Alt+o: Ordered list
		{ keys: 'Shift+Alt+q', method: function () { methods.formatBlock.apply(this, ['<blockquote>']); } }, // Shift+Alt+q: Blockquote
		{ keys: 'Shift+Alt+p', method: function () { methods.formatBlock.apply(this, ['p']); } }, // Shift+Alt+0: Paragraph
		{ keys: 'Shift+Alt+l', method: function () { methods.justifyLeft.apply(this); } }, // Shift+Alt+l: Align left
		{ keys: 'Shift+Alt+c', method: function () { methods.justifyCenter.apply(this); } }, // Shift+Alt+c: Align center
		{ keys: 'Shift+Alt+r', method: function () { methods.justifyRight.apply(this); } }, // Shift+Alt+r: Align right
		{ keys: 'Shift+Alt+j', method: function () { methods.justifyFull.apply(this); } } // Shift+Alt+j: Align justify
	];

	// Set up all the WYSIWYG method
	var methods = {
		bold: function () {
			document.execCommand('bold', false, null);
		},
		italicize: function () {
			document.execCommand('italic', false, null);
		},
		underline: function () {
			document.execCommand('underline', false, null);
		},
		strikethrough: function () {
			document.execCommand('strikethrough', false, null);
		},
		justifyLeft: function () {
			document.execCommand('justifyLeft', false, null);
		},
		justifyCenter: function () {
			document.execCommand('justifyCenter', false, null);
		},
		justifyRight: function () {
			document.execCommand('justifyRight', false, null);
		},
		justifyFull: function () {
			document.execCommand('justifyFull', false, null);
		},
		orderedList: function () {
			document.execCommand('InsertOrderedList', false, null);
		},
		unorderedList: function () {
			document.execCommand('InsertUnorderedList', false, null);
		},
		indent: function () {
			document.execCommand('indent', false, null);
		},
		outdent: function () {
			document.execCommand('outdent', false, null);
		},
		createLink: function () {
			artsy.currentRandomID = artsy.generateRandomID();
			var urlPrompt = 'artsy-link-'+artsy.currentRandomID;
			document.execCommand('createLink', false, urlPrompt);
			jQuery(artsy.pathEditorBox).find(artsy.pathInputLink).focus();
		},
		unlink: function () {
			document.execCommand('unlink', false);
			jQuery(artsy.pathEditorBox).find('input'+artsy.pathInputLink).val('');
			document.getElementById('artsy-editor-content').normalize();
		},
		formatBlock: function (block) {
			if (block == artsy.commonFormatBlock) {
				block = ['<p>'];
			}
			else {
				if (block.substr(1, 1) == 'h') artsy.changeHeaderButton(block);
			}
			document.execCommand('FormatBlock', null, block);
			artsy.getSelected();
		},
		unbind: function (options) {
			jQuery.each(shortcuts, function (index, elem) {
				shortcut.remove(elem.keys);
			});
		},
		init: function (options) {

			/* Bind Toolbar Clicks */
			
			jQuery(artsy.pathEditorBox).find('button').mousedown(function () { artsy.restoreSelection(artsy.savedSelection); });

			jQuery('button#action-bold').click(function () { methods.bold.apply(this); return false; });
			jQuery('button#action-italicize').click(function () { methods.italicize.apply(this); return false; });
			jQuery('button#action-underline').click(function () { methods.underline.apply(this); return false; });
			jQuery('button#action-strikethrough').click(function () { methods.strikethrough.apply(this); return false; });

			jQuery(artsy.pathEditorBox).find(artsy.pathInputLink).mousedown(function () { if (!jQuery(this).hasClass('active')) { jQuery(this).addClass('active'); methods.createLink.apply(this); return false; } });
			jQuery('img#clear-url').click(function () { methods.unlink.apply(this); return false; });

			jQuery('button#action-insertOrderedList').click(function () { methods.orderedList.apply(this); return false; });
			jQuery('button#action-insertUnorderedList').click(function () { methods.unorderedList.apply(this); return false; });

			jQuery('button#action-alignLeft').click(function () { methods.justifyLeft.apply(this); return false; });
			jQuery('button#action-alignMiddle').click(function () { methods.justifyCenter.apply(this); return false; });
			jQuery('button#action-alignRight').click(function () { methods.justifyRight.apply(this); return false; });
			jQuery('button#action-alignWrap').click(function () { methods.justifyFull.apply(this); return false; });

			jQuery('button#action-p').click(function () { methods.formatBlock.apply(this, ['<p>']); return false; });
			jQuery('button#action-quotes').click(function () { methods.formatBlock.apply(this, ['<blockquote>']); return false; });
			jQuery('button.heading-change').click(function () { methods.formatBlock.apply(this, ['<'+jQuery(this).attr('title')+'>']); return false; });
			jQuery('button.show-hidden-row').click(function () { jQuery(artsy.pathEditorBox).find('.hidden-row').slideToggle('fast'); });
			
			document.execCommand('enableObjectResizing', false, 'false');

			jQuery.each(shortcuts, function (index, elem) {
				shortcut.add(elem.keys, function () {
					elem.method();
					artsy.modifiedKeyPressed = true;
					return false;
				}, { 'type': 'keydown', 'propagate': false });
			});
			
		}
	};
	
	// Get the view mode
	if (artsy_editor_mode == 'visual-mode' || artsy_editor_mode == 'html-mode') artsy.viewMode = artsy_editor_mode.replace('-mode', '');

	// Open the editor, finally!
	artsy.openEditor();
	
	// Return false so the button doesn't actually do anything
	return false;
	
};

/** 
 * Open the editor
 * Setup the editor to run
 *
 * @access private
**/
artsy.openEditor = function() {

	// Hide admin bar
	jQuery('#wpadminbar').hide();
	
	// Get title and content
	artsy.getTitleAndContent();

	// Set up all links and images in content
	artsy.openContent();
	
	// Get window & document dimension
	artsy.windowWidth = jQuery(window).width();
	artsy.windowHeight = jQuery(window).height();
	artsy.documentWidth = jQuery(document).width();
	artsy.documentHeight = jQuery(document).height();

	// Give it a correct height
	jQuery(artsy.pathWPContentArea).height(artsy.windowHeight - 50);
	jQuery(artsy.pathWPContentArea).css('overflow', 'hidden');
	
	if (artsy_show_word_count == 0) jQuery(artsy.pathWordCount).hide();

	// Setup fresh editor buttons
	artsy.fresheditor('init');
	
	// Reset status control text
	jQuery('#artsy-publish').add('#publish-control').text(jQuery(artsy.pathWPPublish).val());
	
	// To hold our original settings
	artsy.settingsFontOriginal = jQuery('#artsy_font').text();
	artsy.settingsFontSizeOriginal = jQuery('#artsy_font-size').text();
	artsy.settingsBackgroundOriginal = jQuery('#artsy_background').text();
	artsy.settingsShowWordCountOriginal = jQuery('#artsy_show-word-count').text();
	
	// On blur, autosave once
	jQuery(artsy.pathTitle).blur(function() {artsy.autoSave(true);} );
	
	// On focus, starts auto-saving every 60 seconds, using WP's native auto-save function
    jQuery(artsy.pathContent).focus(function() {
    	if (artsy.autoSaveSetup == false) {
    		artsy.autoSave(true);
    		jQuery.cancel(jQuery.schedule({time: autosaveL10n.autosaveInterval * 1000, func: function() {autosave();}, repeat: true, protect: true}));
    		jQuery.schedule({time: autosaveL10n.autosaveInterval * 500, func: function() {artsy.autoSave(false);}, repeat: true, protect: true});
    		artsy.autoSaveSetup = true;
    	}
    });

	// Blur the title field to prevent incorrect selection
	if (jQuery('#artsy_open-automatically').text() == 1) {
	
		jQuery(artsy.pathWPTitle).blur();
		
	}
	
	// Enable drag-and-drop file uploading
	jQuery(artsy.pathFileUpload).css('display', 'block');
	
	// Remove Save Draft button if it isn't a new post
	if (jQuery(artsy.pathWPSaveDraft).length == 0) {
		jQuery(artsy.pathSaveDraftButton).remove();
	}
	
	// Handle the click event for file upload button
	jQuery(artsy.pathFileUpload).find('input').click(function () {
	
		// Hide all boxes
		jQuery(artsy.pathEditorBox).css('display', 'none');
		jQuery(artsy.pathImageBox).css('display', 'none');
		
		// Initiate or destroy file upload based on status
		if (artsy.draggedSelectionInContent == false) {
			artsy.initFileUpload();
			artsy.inputFileActivate = true;
		} else {
			artsy.destroyFileUpload();
		}
		
	});
	
	// Handle the drag enter event for document
	jQuery(document).bind('dragenter', function (e) {
	
		// Blur the title field
		jQuery(artsy.pathTitle).blur();
		
		e.stopPropagation();
		artsy.enteredDocument = true;
		// Initiate or destroy file upload based on status
		if (artsy.enteredContent !== true) {
			if (artsy.draggedSelectionInContent == false) {
				artsy.initFileUpload();
				artsy.inputFileActivate = true;
			} else {
				artsy.destroyFileUpload();
			}
		}
		
	});
	
	// Handle the drag leave event for document
	jQuery(document).bind('dragleave', function (e) {
	
		e.stopPropagation();
		artsy.enteredDocument = false;
		// Initiate or destroy file upload based on status
		if (artsy.enteredContent !== true) {
			if (artsy.draggedSelectionInContent == false) {
				artsy.destroyFileUpload();
			}
		}
		
	});
	
	// Handle the drag enter event for content area
	jQuery(artsy.pathContent).bind('dragenter', function (e) {
	
		e.stopPropagation();
		if (artsy.enteredDocument == false && artsy.draggedSelectionInContent == false) {
			artsy.initFileUpload();
	    	artsy.inputFileActivate = true;
	    }
		artsy.enteredContent = true;
		
	});
	
	// Handle the drag leave event for content area
	jQuery(artsy.pathContent).bind('dragleave', function (e) {
	
		e.stopPropagation();
	    if (artsy.enteredDocument == false && artsy.draggedSelectionInContent == false && artsy.enteredInnerContent == false) {
	    	artsy.destroyFileUpload();
		}
		artsy.enteredContent = false;
		
	});
	
	// Handle the drag enter event for content area
	jQuery(artsy.pathContent).find('*').bind('dragenter', function (e) {
	
		artsy.enteredInnerContent = true;
		
	});
	
	// Handle the drag enter event for content area
	jQuery(artsy.pathContent).find('*').bind('dragleave', function (e) {
	
		timer3 = setTimeout(function(){artsy.enteredInnerContent = false;}, 1);
		
	});
	
	// Handle the drag start event for content area
	jQuery(artsy.pathContent).bind('dragstart', function () {
	
		if (artsy.inputFileActivate == true) artsy.destroyFileUpload();
		artsy.draggedSelectionInContent = true;
		
	});
	
	// Handle the drag end event for content area
	jQuery(artsy.pathContent).bind('dragend', function () {
	
		artsy.destroyFileUpload();
		artsy.draggedSelectionInContent = false;
		
	});
	
	// Handle the close button on the overlay editor
	jQuery(artsy.pathEditorBoxButton).click(function () {
	
		artsy.closeEditor();
		
	});
	
	// Handle the change in setting form
	jQuery(artsy.pathSettingsBox+' select, '+artsy.pathSettingsBox+' input').change(function () {
	
		artsy.settingsFormChange(this);
		
	});
	
	// Handle the submission of settings form
	jQuery(artsy.pathSettingsBox).find('form').submit(function () {
	
		artsy.submitSettingsForm();
		return false;
		
	});
	
	// Handle the editor link form changes on submit
	jQuery(artsy.pathEditorBox).find('form').submit(function () {
		
		artsy.submitLinkForm();
		return false;
		
	});
	
	// Handle the editor link form changes on focus
	jQuery(artsy.pathEditorBox).find(artsy.pathInputLink).focus(function () {
	
		artsy.savedSelection = artsy.saveSelection();
		
	});
	
	// Handle the editor link form changes on keyup
	jQuery(artsy.pathEditorBox).find(artsy.pathInputLink).keyup(function () {
	
		artsy.editLinkForm();
		return false;
		
	});
	
	// Handle the editor link form changes on new window button change
	jQuery(artsy.pathEditorBox).find(artsy.pathNewWindow).change(function () {
	
		artsy.editLinkForm();
		return false;
		
	});
	
	// Handle the editor link form changes on blur
	jQuery(artsy.pathEditorBox).find(artsy.pathInputLink).blur(function () {
	
		// Remove the active class
		jQuery(this).removeClass('active'); 
		artsy.editLinkBlurForm();
		return false;
		
	});
	
	// Handle the settings button if we click it on the overlay editor
	jQuery(artsy.pathSettingsBoxButton).click(function () {
	
		artsy.toggleSettingsBox();
		
	});
	
	// Handle the overlay mask if we click it
	jQuery('.cancel_settings, '+artsy.pathMask).click(function () {
	
		jQuery(artsy.pathCancelSettings).trigger('click');
		return false;
		
	});
	
	// Slide open the status control dropdown
	jQuery('.toggle-secondary-control').mouseenter(function () {
		
		jQuery('.secondary-control').slideDown('fast');
		
	});
	
	// Slide close the status control dropdown
	jQuery('#artsy-status-control').mouseleave(function () {
		
		jQuery('.secondary-control').slideUp('fast');
		
	});
	
	// Handle any mouseup event inside of editor
	jQuery(artsy.pathEditor).mouseup(function (e) {
	
		// Close the possibly opened image box
		artsy.closeImageBox();
		
		// Grab any highlighted text we may have
		timer3 = setTimeout(function(){artsy.getSelected();artsy.toggleEditorBox(e);}, 1);
		
	});
	
	// Catch keypress event to add <p> tags
	jQuery(artsy.pathContent).keydown(function(e)
	{
		artsy.keyPressed = e.which;
		artsy.wrappedInParagraph = false;
	});
	
	// Catch keypress event to add <p> tags
	jQuery(artsy.pathContent).keypress(function(e)
	{
		artsy.keyPressed = e.which;
		artsy.wrappedInParagraph = false;
	});
	
	// Handle any new divs event inside of content
	jQuery(artsy.pathContent).bind('DOMNodeInserted', function (e) {
	
		if (artsy.wrappedInParagraph == false) {
			// Wrap initial keypress in a paragraph
			jQuery(artsy.pathContent).contents().filter(function() {
				// It has to be in the text node
				if (this.nodeType == 3 && (this.nodeValue != '' || jQuery(artsy.pathEditor).hasClass('browser-safari') == true) && this.nodeValue != '\n') {
					artsy.wrappedInParagraph = true;
					// Focus at the end after 1 millisecond
					timer3 = setTimeout(function(){artsy.focusAtTheEnd(jQuery(artsy.pathNewParagraph).get(0));jQuery(artsy.pathNewParagraph).removeAttr('id');}, 1);
				}
				// Return the conditional statement
				return (this.nodeType == 3 && (this.nodeValue != '' || jQuery(artsy.pathEditor).hasClass('browser-safari') == true) && this.nodeValue != '\n');
			}).wrap('<p id="artsy-new-paragraph" />');
			if (jQuery(artsy.pathNewParagraph).length != 0) {
				// Get text (for Firefox)
				text = jQuery(artsy.pathNewParagraph).text();
				match1 = ' ';
				// Get html (for Webkit)
				html = jQuery(artsy.pathNewParagraph).html();
				match2 = '&nbsp;';
				// If it has the space at the end and keypress is not the space bar
				if ((text != match1 && text.substr(text.length - 1) == match1 || html != match2 && html.substr(html.length - 6) == match2) && e.which != 32) {
					// Delete the space at the end
					jQuery(artsy.pathNewParagraph).text(text.substring(0, text.length - 1));
					// Focus at the end
					artsy.focusAtTheEnd(jQuery(artsy.pathNewParagraph).get(0));
					// Remove new paragraph ID
					jQuery(artsy.pathNewParagraph).removeAttr('id');
				}
			}
		}
		if (artsy.keyPressed == 13) {
			if (artsy.contentPasted == false) {
				if (e.target.nodeName.toLowerCase() == 'div' && jQuery(e.target).text() == '' && jQuery(e.target).hasClass('artsy-resize-image') == false && jQuery(e.target).hasClass('ui-wrapper') == false && jQuery(e.target).hasClass('ui-resizable-handle') == false) {
					jQuery(e.target).replaceWith('<p id="artsy-new-paragraph">&nbsp;</p>');
					artsy.focusAtTheBeginning(jQuery(artsy.pathNewParagraph).get(0));
					jQuery(artsy.pathNewParagraph).removeAttr('id');
				} else if (jQuery(e.target).hasClass('artsy-resize-image') == true) {
					jQuery(e.target.parentNode).after('<p id="artsy-new-paragraph">&nbsp;</p>');
					artsy.focusAtTheBeginning(jQuery(artsy.pathNewParagraph).get(0));
					jQuery(artsy.pathNewParagraph).removeAttr('id');
				} else if (jQuery(artsy.pathEditor).hasClass('browser-gecko') == true && e.target.nodeName.toLowerCase() == 'br' && jQuery(e.target.parentNode).hasClass('artsy-resize-image') == true) {
					jQuery(e.target).attr('id', 'artsy-new-break');
					jQuery(artsy.pathResizeImage).parent().after('<p id="artsy-new-paragraph">&nbsp;</p>');
					artsy.focusAtTheBeginning(jQuery(artsy.pathNewParagraph).get(0));
					jQuery(artsy.pathNewParagraph).removeAttr('id');
				}
			} else if (e.target.nodeName.toLowerCase() == 'span' && e.target.getAttribute('class') == 'Apple-style-span') {
					selection = artsy.saveSelection();
					jQuery(e.target).removeAttr('style');
					jQuery(e.target).find('*').each(function() {
						jQuery(this).removeAttr('style');
					});
					jQuery('.Apple-style-span').replaceWith(jQuery('.Apple-style-span').html());
					artsy.restoreSelection(selection);
			}
			jQuery(artsy.pathContent).find('meta').remove();
			jQuery(artsy.pathContent).find('.Apple-interchange-newline').remove();
			jQuery(artsy.pathContent).find('#artsy-new-break').remove();
		}
		if (artsy.keyPressed == 8 && e.target.nodeName.toLowerCase() == 'br') {
			jQuery(e.target).replaceWith('<p id="artsy-new-paragraph">&nbsp;</p>');
			artsy.focusAtTheBeginning(jQuery(artsy.pathNewParagraph).get(0));
		}
		
	});
	
	// Handle any double-click event inside of content
	jQuery(artsy.pathContent).dblclick(function (e) {
	
		// Close the possibly opened image box
		artsy.closeImageBox();
		artsy.selectedInContent = true;
		timer3 = setTimeout(function(){artsy.openEditorBox(e);}, 1);
		
	});
	
	// Handle any paste event inside of content
	jQuery(artsy.pathContent).bind('paste', function (e) {
		
		artsy.contentPasted = true;
		timer3 = setTimeout(function(){artsy.contentPasted = false;}, 1);

	});
	
	// Handle any dragend event inside of images
	jQuery(artsy.pathContent).find('img').bind('dragend', function (e) {
	
		artsy.imageMoved();
		
	});
	
	// Handle any keyup event inside of content
	jQuery(artsy.pathContent).keyup(function (e) {
	
		// When user starts typing, counts how many words there are
		artsy.doWordCount(false);
		// Then trigger a mouseup event
		if (e.which != 27) jQuery(artsy.pathEditor).trigger('mouseup');
		
	});
	
	// Handle any mousemove event inside of editor
	jQuery(artsy.pathEditor).mousemove(function (e) {
	
		artsy.openJunk();
		
	});
	
	// Handle any resize event in document
	jQuery(window).resize(function () {
		
		// Get window & document dimension
		artsy.windowWidth = jQuery(window).width();
		artsy.windowHeight = jQuery(window).height();
		artsy.documentWidth = jQuery(document).width();
		artsy.documentHeight = jQuery(document).height();
		jQuery(artsy.pathWPContentArea).height(artsy.windowHeight - 50);
	
	});
	
	// Publish the post
	jQuery(artsy.pathPublishButton).click(function () {
	
		artsy.toggleEditor();
		jQuery(artsy.pathWPPublish).trigger('click');
	
	});
	
	// Save the post as draft
	jQuery(artsy.pathSaveDraftButton).click(function () {
	
		artsy.toggleEditor();
		jQuery(artsy.pathWPSaveDraft).trigger('click');
	
	});
	
	// Preview the post
	jQuery(artsy.pathPreviewButton).click(function () {
	
		artsy.saveContent();
		artsy.returnTitleAndContent(true);
		jQuery('a#post-preview').trigger('click');
		artsy.openContent();
	
	});
	
	// Move the post to trash
	jQuery(artsy.pathMoveToTrashButton).click(function () {
	
		artsy.toggleEditor();
		jQuery('#delete-action a.submitdelete.deletion').trigger('click');
	
	});
	
	// Switch mode
	if (artsy.viewMode == 'html') {
		artsy.viewMode = 'visual';
		artsy.toggleViewMode();
		artsy.viewMode = 'html';
	}
	
	// Show the editor
	jQuery(artsy.pathEditor).add(artsy.pathBackground).show();
	
	// Focus on the title or content based on whether the fields are empty
	if (jQuery(artsy.pathTitle).val() == '') jQuery(artsy.pathTitle).focus();
	else if (jQuery(artsy.pathContent).text() == '') jQuery(artsy.pathTitle).select();
	else jQuery(artsy.pathContent).focus();
	
};

// Close the editor
artsy.closeEditor = function() {

	// Show admin bar
	jQuery('#wpadminbar').show();

	// Disable auto-save
	jQuery.cancel(jQuery.schedule({time: autosaveL10n.autosaveInterval * 500, func: function() {artsy.autoSave(false);}, repeat: true, protect: true}));
	jQuery.schedule({time: autosaveL10n.autosaveInterval * 1000, func: function() {autosave();}, repeat: true, protect: true});
	artsy.autoSaveSetup = false;
	
	// Save content to original editor
	artsy.saveContent();
	
	artsy.returnTitleAndContent(true);
	
	artsy.destroyFileUpload();
	
	// Correct the tab highlighting
	jQuery('#edButtonArtsy').removeClass('active');
	jQuery('#'+jQuery('#edButtonArtsy').data('editor_type')).addClass('active');
	
	// Unbind all actions
	jQuery(artsy.pathEditorBoxButton).add(artsy.pathSettingsBoxButton).add(artsy.pathImageBox).add(artsy.pathEditorBox+' button').add('.cancel_settings').add(artsy.pathMask).add(artsy.pathPublishButton).add(artsy.pathSaveDraftButton).unbind('click');
	jQuery(artsy.pathEditor).unbind('mousemove');
	jQuery(window).unbind('resize');
	artsy.fresheditor('unbind');
	
	// Reset height and overflow in admin
	jQuery(artsy.pathWPContentArea).css('height', 'auto');
	jQuery(artsy.pathWPContentArea).css('overflow', '');
	
	// Set initiation status to false
	artsy.justInitiated = false;
	artsy.hasSetup = false;
	
	// Close all potentially opened box
	artsy.closeAllBoxes();
	
	// Fade out everything
	jQuery(artsy.pathEditor).add(artsy.pathBackground).hide();
	
	// Focus in title field
	jQuery(artsy.pathWPTitle).focus();
	
	//	Remove the theme
	//jQuery(artsy.backgroundStyleID).remove();
	
	// And in the background, disable drag-and-drop file uploading
	jQuery(artsy.pathFileUpload).css('display', 'none');
	
};

/**
 * Toggle HTML or WYSIWYG Mode
 *
 * @access public
**/
artsy.toggleViewMode = function()
{
	switch(artsy.viewMode)
	{
		case 'visual' : // Open in HTML
			artsy.saveContent();
			
			jQuery(artsy.pathContent).css('display', 'none');
			jQuery(artsy.pathHTMLContent).css('display', '');
			jQuery(artsy.pathWordCount).hide();
			
			jQuery(artsy.pathHTMLModeButton).text('Visual Mode');
			
			//	Turning it into HTML mode
			//	This is where we could filter it, etc.
			artsy.openContent();
			
			artsy.viewMode = 'html';
		break;
		
		case 'html' : // Open in visual
			artsy.saveContent();
			
			jQuery(artsy.pathContent).css('display', '');
			jQuery(artsy.pathHTMLContent).css('display', 'none');
			jQuery(artsy.pathWordCount).show();
			
			//	Change the button text
			jQuery(artsy.pathHTMLModeButton).text('HTML Mode');
			
			//	Turning it into WYSIWYG mode
			//	This is where we could filter it, etc.
			artsy.openContent();
			
			artsy.viewMode = 'visual';
		break;
	}

}

// Get title and content of the editor

artsy.getTitleAndContent = function() {

	// Get the current title
	artsy.postTitle = jQuery(artsy.pathWPTitle).val();
	
	// Get the current content from either tinyMCE or textarea
	if (tinyMCE.activeEditor != null && !tinyMCE.activeEditor.isHidden() && tinyMCE.activeEditor.getContent() != '') {
		artsy.postContent = tinyMCE.activeEditor.getContent();
	} else {
		artsy.postContent = jQuery(artsy.pathWPContent).val();
	}
	
	// Linkify Twitter username, Twitter hashtag, URL, and email address.
	// Wait till bug is fixed, damn it
	// artsy.postContent = artsy.parseText(artsy.postContent);
    
	// Correctly format the text with WP's native method
	
	// Give it a default title if there is not title
	if (jQuery('#artsy-help-shown').val() == 0 && (artsy.postTitle == '' || artsy.postTitle == '<br />\n')) {
		artsy.postTitle = artsy.gettingStartedTitle;
	}
	
	//	The default post content for new posts
	if (jQuery('#artsy-help-shown').val() == 0 && (artsy.postContent == '' || artsy.postContent == '<br />\n')) {
		artsy.postContent = artsy.gettingStartedContent;
	}
	else if (artsy.postContent == '' || artsy.postContent == '<br />\n') {
		if (jQuery(artsy.pathEditor).hasClass('browser-gecko') == false) artsy.postContent = '';
		else artsy.postContent = '<p>&nbsp;</p>';
	}
	
	// Manipulate caption
	artsy.postContent = artsy.postContent.replace(/\[caption([^\]]+)\]([\s\S]+?)\[\/caption\]/g, '<figure$1>$2</figure>\n\n');
	
	// Setup the title and content
	jQuery(artsy.pathTitle).val(artsy.postTitle);
	jQuery(artsy.pathContent).html(switchEditors._wp_Autop(artsy.postContent));
	jQuery(artsy.pathHTMLContent).val(switchEditors._wp_Nop(artsy.postContent));

};

// Return title and content of the editor
artsy.returnTitleAndContent = function(reopen) {

	// Update title and content back into the admin area
	jQuery(artsy.pathWPTitle).val(artsy.postTitle);
	
	artsy.postContent = switchEditors._wp_Autop(artsy.postContent);
	
	putback = false;
	if (reopen == true) {
		// Put the current editor content back into TinyMCE
		tinyMCE.execCommand('mceSetContent', false, artsy.postContent);
	} else if (tinyMCE.activeEditor != null && !tinyMCE.activeEditor.isHidden()) {
		switchEditors.go('content', 'html');
		putback = true;
	}
	
	jQuery(artsy.pathWPContent).val(switchEditors._wp_Nop(artsy.postContent));
	
	if (putback == true) switchEditors.go('content', 'tinymce');
	
};

// Open content of the editor
artsy.openContent = function() {

	// Do a word count
	artsy.doWordCount(false);
	
	// Initiate all images
	artsy.initImages();
	
	// Setup any links we already have in the content
	jQuery(artsy.pathContent + ' a').each(function () {
		
		// Get a random ID, and set it up
		jQuery(this).attr(artsy.linkIdentifier, artsy.generateRandomID());
		
	});
	
};

// Save content of the editor
artsy.saveContent = function() {

	// Disable all image resizing elements so we can go to barebones HTML on all images
	jQuery(artsy.pathContent).find('img').each(function () {
	
		jQuery(artsy.pathResizeDimension).remove();
		jQuery(this).resizable('destroy');
		jQuery(this).removeAttr('style');
		jQuery(this).unbind('click');
		
	});
	
	// Remove Apple-style-span class
	jQuery(artsy.pathNewParagraph).each(function () {
	
		jQuery(this).removeAttr('id');
		
	});
	
	// Remove Apple-style-span class
	jQuery(artsy.pathContent).find('.Apple-style-span').each(function () {
	
		jQuery(this).removeAttr('class');
		
	});
	
	// Disable all image resizing elements so we can go to barebones HTML on all images
	jQuery(artsy.pathContent+', '+artsy.pathContent+' p'+', '+artsy.pathContent+' blockquote'+', '+artsy.pathContent+' ul'+', '+artsy.pathContent+' ol, '+artsy.pathContent+' li span').each(function () {
	
		jQuery(this).css('font-size', '');
		jQuery(this).css('line-height', '');
		if (jQuery(this).attr('style') == '') jQuery(this).removeAttr('style');
		
	});

	// Remove last empty paragraph
	jQuery(artsy.pathContent).find('p').last().each(function () {
	
		if (jQuery(this).text() == '' || jQuery(this).text() == ' ' || jQuery(this).html() == '\n') jQuery(this).remove();
		
	});
	
	// Remove the image containing div
	jQuery(artsy.pathContent).find(artsy.pathResizeImage).each(function () {
	
		jQuery(this).contents().unwrap();
		
	});

	// Manipulate link and caption
	jQuery(artsy.pathContent).find('img').each(function () {
	
		if (typeof jQuery(this).attr('link') != 'undefined' && jQuery(this).attr('link') != '') {
			jQuery(this).wrap('<a href="'+jQuery(this).attr('link')+'" />');
		}
		
		jQuery(this).removeAttr('link');
		
	});
	
	jQuery(artsy.pathNewParagraph).removeAttr('id');
	
	// Remove any artsy link ID and image ID
	jQuery('a['+artsy.linkIdentifier+']').removeAttr(artsy.linkIdentifier);
	jQuery('img['+artsy.imageIdentifier+']').removeAttr(artsy.imageIdentifier);
	
	//	Remove the style attr
	jQuery(artsy.pathContent+' p, '+artsy.pathContent+' span').removeAttr('style');
	
	// Update title and content back to our variable
	artsy.postTitle = jQuery(artsy.pathTitle).val();
	if (artsy.viewMode == 'visual') artsy.postContent = jQuery(artsy.pathContent).html();
	else artsy.postContent = jQuery(artsy.pathHTMLContent).val();
	artsy.postContent = artsy.postContent.replace(/(\<a href="[^\]]+">)\<img([^\]]+)width="([0-9]+)"([^\]]+)cap="([^\]]+)"([^\]]+)attachment-id="attachment_([0-9]+)">(\<\/a>)/g, '[caption id="attachment_$7" width="$3" caption="$5"]$1<img$2width="$3"$4$6 />$8[/caption]\n\n');
	artsy.postContent = artsy.postContent.replace(/\<img([^\]]+)width="([0-9]+)"([^\]]+)cap="([^\]]+)"([^\]]+)attachment-id="attachment_([0-9]+)">/g, '[caption id="attachment_$6" width="$2" caption="$4"]<img$1width="$2"$3$5 />[/caption]\n\n');
	
	artsy.postContent = switchEditors._wp_Nop(artsy.postContent);
	
	// Update to the official tags used by WP
	artsy.postContent = artsy.postContent.replace(/<span style="font-weight: bold;">([^>]+?)<\/span>/ig, '<strong>$1</strong>');
	artsy.postContent = artsy.postContent.replace(/<span style="font-style: italic;">([^>]+?)<\/span>/ig, '<em>$1</em>');
	artsy.postContent = artsy.postContent.replace(/<b>/ig, '<strong>');
	artsy.postContent = artsy.postContent.replace(/<i>/ig, '<em>');
	artsy.postContent = artsy.postContent.replace(/<u>/ig, '<span style="text-decoration: underline;">');
	artsy.postContent = artsy.postContent.replace(/<strike>/ig, '<span style="text-decoration: line-through;">');
	artsy.postContent = artsy.postContent.replace(/<\/b>/ig, '</strong>');
	artsy.postContent = artsy.postContent.replace(/<\/i>/ig, '</em>');
	artsy.postContent = artsy.postContent.replace(/<\/u>/ig, '</span>');
	artsy.postContent = artsy.postContent.replace(/<\/strike>/ig, '</span>');
	artsy.postContent = artsy.postContent.replace(/<\/a><a [^>]+?>/ig, '');
	
	// Correctly format the text with WP's native method
	
	//  // Remove <p> and <br />
	//	artsy.postContent = artsy.postContent.replace(/\s*<p>/gi, '');
	//	artsy.postContent = artsy.postContent.replace(/\s*<\/p>\s*/gi, '\n\n');
	//	artsy.postContent = artsy.postContent.replace(/\n[\s\u00a0]+\n/g, '\n\n');
	//	artsy.postContent = artsy.postContent.replace(/\s*<br ?\/?>\s*/gi, '\n');
	//
	//	// Fix some block element newline issues
	//	artsy.postContent = artsy.postContent.replace(/\s*<div/g, '\n<div');
	//	artsy.postContent = artsy.postContent.replace(/<\/div>\s*/g, '</div>\n');
	//	artsy.postContent = artsy.postContent.replace(/\s*\[caption([^\[]+)\[\/caption\]\s*/gi, '\n\n[caption$1[/caption]\n\n');
	//	artsy.postContent = artsy.postContent.replace(/caption\]\n\n+\[caption/g, 'caption]\n\n[caption');
	
	if (artsy.viewMode == 'visual') jQuery(artsy.pathHTMLContent).val(artsy.postContent);
	else jQuery(artsy.pathContent).html(switchEditors._wp_Autop(artsy.postContent));
	
};

artsy.autoSave = function(delayed) {

	if (artsy.hasSetup == true) {
		selection = artsy.saveSelection();
		artsy.saveContent();
		artsy.returnTitleAndContent(false);
		if (delayed == true) delayed_autosave();
		else autosave();
		artsy.openContent();
		artsy.restoreSelection(selection);
	}

}

artsy.focusAtTheBeginning = function (div)
{
 	window.setTimeout(function() {
		var sel, range;
		if (window.getSelection && document.createRange) {
			range = document.createRange();
			range.selectNodeContents(div);
			range.collapse(true);
			sel = window.getSelection();
			sel.removeAllRanges();
			sel.addRange(range);
		} else if (document.body.createTextRange) {
			range = document.body.createTextRange();
			range.moveToElementText(div);
			range.collapse(true);
			range.select();
		}
	}, 1);
}

artsy.focusAtTheEnd = function (div)
{
 	window.setTimeout(function() {
		var sel, range;
		if (window.getSelection && document.createRange) {
			range = document.createRange();
			range.selectNodeContents(div);
			range.collapse(false);
			sel = window.getSelection();
			sel.removeAllRanges();
			sel.addRange(range);
		} else if (document.body.createTextRange) {
			range = document.body.createTextRange();
			range.moveToElementText(div);
			range.collapse(false);
			range.select();
		}
	}, 1);
}

artsy.focusAll = function (div)
{
 	window.setTimeout(function() {
		var sel, range;
		if (window.getSelection && document.createRange) {
			range = document.createRange();
			range.selectNodeContents(div);
			sel = window.getSelection();
			sel.removeAllRanges();
			sel.addRange(range);
		} else if (document.body.createTextRange) {
			range = document.body.createTextRange();
			range.moveToElementText(div);
			range.select();
		}
	}, 1);
}

// Toggle the editor
artsy.toggleEditor = function() {
	
	if (artsy.hasSetup == false) {
		artsy.runEditor();
		artsy.hasSetup = true;
	} else if (jQuery(artsy.pathEditor).css('display') == 'none') {
		artsy.openEditor();
	} else {
		artsy.closeEditor();
	}
		
};

artsy.escKeyPressed = function() {
	
	if (jQuery(artsy.pathSettingsBox).css('display') == 'block') {
		artsy.closeSettingsBox();
	} else if (jQuery(artsy.pathImageBox).css('display') == 'block') {
		artsy.closeImageBox();
	} else if (jQuery(artsy.pathEditorBox).css('display') == 'block') {
		artsy.closeEditorBox();
	} else {
		artsy.toggleEditor();
	}
	
};

// Set up jQuery File Upload by blueimp
// https://github.com/blueimp/jQuery-File-Upload
// Thanks Sebastian Tschan!
artsy.initFileUpload = function () {

	artsy.getCursorPosition();
	if (artsy.fileUploadSetup == false) {
	
		artsy.fileUploadSetup = true;
		jQuery(artsy.pathFileUpload).fileUploadUI({
		
			dropZone: jQuery(document),
			onDragOver: function (event) {
				jQuery(artsy.pathFileUploadTips).text('Release to upload.');
				if (artsy.didDraggedOver == false) {
					jQuery(artsy.pathFileUploadTipsDiv).animate({'top': -1}, 150);
					artsy.didDraggedOver = true;
				}
			},
			onDragLeave: function (event) {
				jQuery(artsy.pathFileUploadTips).text('');
				if (artsy.enteredContent !== true) {
					jQuery(artsy.pathFileUploadTipsDiv).animate({'top': -36}, 150);
				}
				artsy.didDraggedOver = false;
			},
			onDrop: function (event) {
				jQuery(artsy.pathFileUploadTips).text('Invalid data.');
				clearTimeout(timer2);
				timer2 = setTimeout("jQuery(artsy.pathFileUploadTips).text('');jQuery(artsy.pathFileUploadTipsDiv).animate({'top': -36}, 150);", 1000);
			},
			onSend: function (event, files, index, xhr, handler) {
				jQuery(artsy.pathFileUploadTipsDiv).animate({'top': -1}, 150);
				jQuery(artsy.pathFileUploadTips).text('Uploading now.');
			},
			onProgress: function (event, files, index, xhr, handler) {
				jQuery(artsy.pathFileUploadTips).text('Uploaded '+parseInt(event.loaded / event.total * 100, 10)+'%.');
			},
			onError: function (event, files, index, xhr, handler) {
				// TO DO: ERROR HANDLING
				if (handler.originalEvent) {
					console.log(handler.response.error);
				} else {
					console.log(handler.response.error);
				}
				jQuery(artsy.pathFileUploadTips).text('Error.');
				timer2 = setTimeout("jQuery(artsy.pathFileUploadTips).text('');jQuery(artsy.pathFileUploadTipsDiv).animate({'top': -36}, 150);", 2000);
			},
			onComplete: function (event, files, index, xhr, handler) {
				if (handler.response.error == '') {
					content = artsy.getImageTag(handler.response.id, handler.response.url, handler.response.width, handler.response.height, handler.response.title, '', '', handler.response.url, 'none', 'full');
					if (jQuery(artsy.pathCurrentPosition).length > 0) jQuery(artsy.pathCurrentPosition).after(content);
					else jQuery(artsy.pathContent).append(content);
					artsy.didDraggedOver = false;
					jQuery('img['+artsy.imageIdentifier+'='+artsy.currentRandomID+']').each(function() {
						if (jQuery(this).parent().attr('id') != 'artsy-editor-content') {
							html = artsy.getOuterHTML(this);
							tag = this.parentNode.nodeName.toLowerCase();
							jQuery(this.parentNode).replaceWith(artsy.getOuterHTML(this.parentNode).replace(html, '</'+tag+'>'+html+'<'+tag+'>'));
							artsy.focusAtTheEnd(jQuery(this).parent().get(0));
						}
					});
					artsy.setupNewImage(artsy.currentRandomID, handler.response.width, handler.response.height);
					jQuery(artsy.pathContent).find('img').bind('dragend', function (e) {
						artsy.destroyFileUpload();
						artsy.draggedSelectionInContent = false;
						artsy.imageMoved();
					});
					jQuery(artsy.pathFileUploadTips).text('Upload completed.');
					artsy.destroyFileUpload();
					timer2 = setTimeout("jQuery(artsy.pathFileUploadTipsDiv).animate({'top': -36}, 150);", 1000);
				}
				else {
					if (handler.response.error == 'filetype') {
						//jQuery(artsy.pathFileUploadTips).text('Sorry. '+handler.response.filetype+' is not supported.');
						jQuery(artsy.pathFileUploadTips).text('Sorry. Only image is supported.');
						clearTimeout(timer2);
						timer2 = setTimeout("jQuery(artsy.pathFileUploadTips).text('');jQuery(artsy.pathFileUploadTipsDiv).animate({'top': -36}, 150);", 3000);
					}
				}
			}
			
		});
	
	}

};

// Destroy file upload
artsy.destroyFileUpload = function (a) {

	timer2 = setTimeout("jQuery(artsy.pathCurrentPosition).remove();", 1);
	if (artsy.fileUploadSetup == true) {
		jQuery(artsy.pathFileUploadTips).text('');
		jQuery(artsy.pathFileUploadTipsDiv).animate({'top': -36}, 150);
		artsy.fileUploadSetup = false;
		jQuery(artsy.pathFileUpload).fileUploadUI('destroy');
	}
	
};

// When images are being moved
artsy.imageMoved = function () {
	
	jQuery(artsy.pathContent).find('meta').remove();

	jQuery(artsy.pathResizeImage).each(function () {
		if (jQuery(this).find('img').length == 0) jQuery(this).remove();
	});

	// Have to wait until the paste to finish to re-initiate images
	clearTimeout(timer3);
	timer3 = setTimeout("artsy.initImages();", 1);
	
	// Handle any dragend event inside of images
	jQuery(artsy.pathContent).find('img').bind('dragend', function (e) {
	
		artsy.imageMoved();
		
	});
	
};

// Initiate all images
artsy.initImages = function () {
	
	// Setup any images we already have in the content
	jQuery(artsy.pathContent + ' img').each(function () {
	
		// Get a random ID, and set it up
		id = artsy.generateRandomID();
		// Get its original ID
		jQuery(this).attr(artsy.imageIdentifier, id);
		// Find link
		element = jQuery(this).parent().get(0);
		if (element.nodeName.toLowerCase() == 'a') {
			jQuery(this).attr('link', element.getAttribute('href'));
			jQuery(this).unwrap();
		}
		element0 = jQuery(this).parents().get(0);
		element1 = jQuery(this).parents().get(1);
		if (element0.nodeName.toLowerCase() == 'figure') {
			jQuery(this).attr('cap', element0.getAttribute('caption'));
			jQuery(this).attr('attachment-id', element0.getAttribute('id'));
			jQuery(this).unwrap();
		} else if (element1.nodeName.toLowerCase() == 'figure') {
			jQuery(this).attr('cap', element1.getAttribute('caption'));
			jQuery(this).attr('attachment-id', element1.getAttribute('id'));
			jQuery(element0).unwrap();
		}
		// Set it up with width and height
		artsy.setupNewImage(id, jQuery(this).width(), jQuery(this).height());
		
	});

}

// Set up any new images
artsy.setupNewImage = function (imageID) {

	// Get ID, its width, height
	id = imageID;
	imageID = 'img['+artsy.imageIdentifier+'='+imageID+']';
	width = jQuery(imageID).width();
	height = jQuery(imageID).height();
	
	// Save width and height
	jQuery(imageID).data('width', width);
	jQuery(imageID).data('height', height);
	
	// Make sure we save the original size of our image here in the original sizes array so we can revert if necessary
	artsy.originalSizes.push({ 'id': id, 'width': width, 'height': height });
	
	// Replace with the new HTML with the containing div
	jQuery(jQuery(imageID)[0]).wrap('<div class="artsy-resize-image" />');
	
	// Handle the onclick event for our image
	jQuery(imageID).click(function (e) {
	
		// If the image box isn't displayed currently
		artsy.imageID = imageID;
		artsy.toggleImageBox(e);
		
	});
	
	// Add a float style to its parent container
	if (jQuery(imageID).hasClass('alignright') == true) {
		jQuery(imageID).parent().css('float', 'right');
	}
	else if (jQuery(imageID).hasClass('alignleft') == true) {
		jQuery(imageID).parent().css('float', 'left');
	}
	else if (jQuery(imageID).hasClass('aligncenter') == true) {
		jQuery(imageID).parent().css('text-align', 'center');
	}
	
	// Handle any mouseenter event for the image
	jQuery(imageID).mouseenter(function (e) {
		
		// Determine the position of handle
		if (jQuery(imageID).hasClass('alignright') == true) {
			handle = 'sw';
		}
		if (jQuery(imageID).hasClass('aligncenter') == true) {
			handle = 'se, sw';
		}
		else {
			handle = 'se';
		}
	
		// When the mouse enters, initiate jQuery UI resizable
		jQuery(imageID).resizable({
			handles: handle,
			create: function (event, ui) {
			
				// Set position to relative to prevent placement issues
				jQuery(imageID).parent().css('position', 'relative');
				
				if (jQuery(imageID).hasClass('aligncenter') == true) {
				
					margin = (800 - 34 - jQuery(imageID).width()) / 2;
					jQuery(imageID).parent().css('margin-left', margin+'px').css('margin-right', margin+'px');
				
				}
				
			},
			resize: function (event, ui) {
			
				// Disable left margin
				jQuery(artsy.pathUIWrapper).css('left', '0px');
				
				// Update height and width in input
				jQuery(artsy.pathInputImageWidth).val(jQuery(imageID).width());
				jQuery(artsy.pathInputImageHeight).val(jQuery(imageID).height());
				
				// Update the current width and height text
				jQuery(artsy.pathResizeDimension).html('W: ' + jQuery(imageID).width() + ' H: ' + jQuery(imageID).height());
				
				if (jQuery(imageID).hasClass('aligncenter') == true) {
				
					margin = (800 - 34 - jQuery(imageID).width()) / 2;
					jQuery(imageID).parent().css('margin-left', margin+'px').css('margin-right', margin+'px');
				
				}
				
			},
			start: function (event, ui) {
			
				// Insert a div to hold the current width and height text
				jQuery(imageID).parent().parent().append('<div id="resize-dimensions"></div>');
				
				// Set currently resizing to true to prevent conflict
				artsy.currentlyResizing = true;
				
			},
			stop: function (event, ui) {
			
				// Set its width and height
				jQuery(imageID).attr('width', jQuery(imageID).width());
				jQuery(imageID).attr('height', jQuery(imageID).height());
				
				// Remove the div element that shows us our current width and height text
				jQuery(artsy.pathResizeDimension).remove();
				
				// Set currently resizing to false to prevent conflict
				artsy.currentlyResizing = false;
				
			}
		});
	
	
		// Handle any mouseleave event for the image
		jQuery(artsy.pathUIWrapper).mouseleave(function (e) {
		
			// When the mouse leaves, destroy jQuery UI Resizable
			if (artsy.currentlyResizing == false) {
			
				// Remove the styling
				jQuery(jQuery(this).children()).removeAttr('style');
				// And destroy jQuery UI resizable
				jQuery(this).resizable('destroy');
				
			}
			
		});
	
	});
	
};

// Open the editor box
artsy.openEditorBox = function(e) {

	// Only open if the text is selected in content area
	if (artsy.selectedInContent == true && artsy.modifiedKeyPressed != true) {
	
		// Hide the hidden row
		if (artsy.commonFormatBlock == '<h1>' || artsy.commonFormatBlock == '<h2>' || artsy.commonFormatBlock == '<h3>' || artsy.commonFormatBlock == '<h4>' || artsy.commonFormatBlock == '<h5>' || artsy.commonFormatBlock == '<h6>') {
			jQuery(artsy.pathEditorBox).find('.hidden-row').css('display', 'block');
		}
		else {
			jQuery(artsy.pathEditorBox).find('.hidden-row').css('display', 'none');
		}
		
		// Hide the image box
		jQuery(artsy.pathImageBox).css('display', 'none');
		
		// Populate URL field
		if (artsy.parentElementHref == '') value = 'http://';
		else value = artsy.parentElementHref;
		jQuery(artsy.pathEditorBox).find(artsy.pathInputLink).val(value);
		
		// Get and set link target
		if (jQuery().jquery >= '1.6') {
			if (artsy.parentElementTarget == '_blank') jQuery(artsy.pathEditorBox).find(artsy.pathNewWindow).prop('checked', true);
			else jQuery(artsy.pathEditorBox).find(artsy.pathNewWindow).prop('checked', false);
		}
		else {
			if (artsy.parentElementTarget == '_blank') jQuery(artsy.pathEditorBox).find(artsy.pathNewWindow).attr('checked', 'checked');
			else jQuery(artsy.pathEditorBox).find(artsy.pathNewWindow).removeAttr('checked');
		}
		
		// Fade in the box
		jQuery(artsy.pathBackground).fadeIn('fast');
		jQuery(artsy.pathEditorBox).fadeIn('fast');
		// the image box in and push it a little below our current mouse cursor position
		if (typeof e.clientY == 'undefined') clientY = artsy.windowHeight / 2;
		else clientY = e.clientY;
		if (typeof e.clientX == 'undefined') clientX = artsy.windowWidth / 2;
		else clientX = e.clientX;
		if (typeof e.pageY == 'undefined') pageY = artsy.documentHeight / 2;
		else pageY = e.pageY;
		if (typeof e.pageX == 'undefined') pageX = artsy.documentWidth / 2;
		else pageX = e.pageX;
		var documentTop = pageY + 20;
		var documentLeft = pageX - 170;
		var documentBottom = artsy.documentHeight - pageY;
		var documentRight = artsy.documentWidth - pageX;
		if (documentLeft < 10) documentLeft = 10;
		if (documentRight < 190) documentLeft = artsy.documentWidth - 350;
		if (artsy.windowHeight - clientY < 200) documentTop = pageY - 200;
		if (typeof e.pageX == 'undefined' || typeof e.pageY == 'undefined') {
			documentTop = jQuery('body').scrollTop() + (clientY / 2);
			documentLeft = clientX - 180;
		}
		jQuery(artsy.pathEditorBox).css('left', documentLeft + 'px').css('top', documentTop + 'px');
		
		// Set selected in content to false because box is already opened
		artsy.selectedInContent = false;
		
	} else if (artsy.modifiedKeyPressed == true) {
		artsy.modifiedKeyPressed = null;
	}
	
};

// Close the editor box
artsy.closeEditorBox = function() {
	
	// Clear out any empty links
	artsy.clearLink('a[href="artsy-link-'+artsy.currentRandomID+'"]');
	artsy.clearLink('a[href="http://"]');
	
	// Just fade it out
	jQuery(artsy.pathEditorBox).fadeOut('fast');
		
};

// Toggle the editor box
artsy.toggleEditorBox = function(e) {

	// Open or close depends of if there are text selected
	if (artsy.selectedText.length > 0) {
		artsy.openEditorBox(e);
	} else if (jQuery(artsy.pathEditorBox).css('display') != 'none') {
		artsy.closeEditorBox();
	}

};

// Handle the edit of the link form
artsy.editLinkForm = function () {

	artsy.savedSelection = artsy.saveSelection();
	// Get URL and new window value
	url = jQuery(artsy.pathEditorBox).find('form '+artsy.pathInputLink).val();
	newWindow = jQuery(artsy.pathEditorBox).find('form '+artsy.pathNewWindow+':checked').val();
	
	// Set it up if there is an URL
	if (url != '') {
		jQuery('a[href="artsy-link-'+artsy.currentRandomID+'"]').attr(artsy.linkIdentifier, artsy.currentRandomID);
		jQuery('a['+artsy.linkIdentifier+'="'+artsy.currentRandomID+'"]').attr('href', url);
		if (newWindow == 1) jQuery('a['+artsy.linkIdentifier+'="'+artsy.currentRandomID+'"]').attr('target', '_blank');
		else jQuery('a['+artsy.linkIdentifier+'="'+artsy.currentRandomID+'"]').removeAttr('target');
	}

};

// Handle the blur event of the link form
artsy.editLinkBlurForm = function () {

	// Perform the editLinkForm actions first
	artsy.editLinkForm();
	url = jQuery(artsy.pathEditorBox).find(artsy.pathInputLink).val();
	
	// Clear out link if empty
	if (url == '') {
		artsy.clearLink('a['+artsy.linkIdentifier+'="'+artsy.currentRandomID+'"]');
	}

};

// Handle the submission of the link form
artsy.submitLinkForm = function () {

	artsy.editLinkForm();
	artsy.closeEditorBox();

};

// Handle the edit of the image form
artsy.editImageForm = function () {

	// Set the title and alt value
	jQuery(artsy.imageID).attr('title', jQuery(artsy.pathImageBox).find(artsy.pathInputImageTitle).val());
	jQuery(artsy.imageID).attr('alt', jQuery(artsy.pathImageBox).find(artsy.pathInputImageAlt).val());
	jQuery(artsy.imageID).attr('cap', jQuery(artsy.pathImageBox).find(artsy.pathInputImageCap).val());
	jQuery(artsy.imageID).attr('link', jQuery(artsy.pathImageBox).find(artsy.pathInputImageLink).val());
	width = jQuery(artsy.pathImageBox).find(artsy.pathInputImageWidth).val();
	height = jQuery(artsy.pathImageBox).find(artsy.pathInputImageHeight).val();
	if (isNaN(width) == false && width > 0) {
		jQuery(artsy.imageID).attr('width', width);
		jQuery(artsy.imageID).css('width', width);
	}
	if (isNaN(height) == false && height > 0) {
		jQuery(artsy.imageID).attr('height', height);
		jQuery(artsy.imageID).css('height', height);
	}

};

// Handle the submission of the image form
artsy.submitImageForm = function () {

	artsy.editImageForm();
	artsy.closeImageBox();

};

// Clear image title
artsy.clearImageTitle = function () {

	jQuery(artsy.pathImageBox).find(artsy.pathInputImageTitle).val('');
	jQuery(artsy.pathImageBox).find(artsy.pathInputImageTitle).focus();
	jQuery(artsy.imageID).attr('title', '');

}

// Clear image alt
artsy.clearImageAlt = function () {

	jQuery(artsy.pathImageBox).find(artsy.pathInputImageAlt).val('');
	jQuery(artsy.pathImageBox).find(artsy.pathInputImageAlt).focus();
	jQuery(artsy.imageID).attr('alt', '');

}

// Clear image caption
artsy.clearImageCap = function () {

	jQuery(artsy.pathImageBox).find(artsy.pathInputImageCap).val('');
	jQuery(artsy.pathImageBox).find(artsy.pathInputImageCap).focus();
	jQuery(artsy.imageID).attr('cap', '');

}

// Clear image link
artsy.clearImageLink = function () {

	jQuery(artsy.pathImageBox).find(artsy.pathInputImageLink).val('');
	jQuery(artsy.pathImageBox).find(artsy.pathInputImageLink).focus();
	jQuery(artsy.imageID).attr('link', '');

}

// Original size
artsy.originalSize = function (imageID) {

	width = jQuery(imageID).data('width');
	height = jQuery(imageID).data('height');
	jQuery(artsy.imageID).attr('width', width);
	jQuery(artsy.imageID).attr('height', height);
	jQuery(artsy.imageID).css('width', width);
	jQuery(artsy.imageID).css('height', height);
	jQuery(artsy.pathInputImageWidth).val(width);
	jQuery(artsy.pathInputImageHeight).val(height);

}

// Delete image
artsy.deleteImage = function (imageID) {

	parent = jQuery(artsy.imageID).parent();
	if (jQuery(parent).attr('class') == 'artsy-resize-image') {
		jQuery(parent).remove();
	} else if (jQuery(parent).parent().attr('class') == 'artsy-resize-image') {
		jQuery(parent).parent().remove();
	}
	artsy.toggleImageBox();

}

// Open the image box
artsy.openImageBox = function(e) {
	
	// Clear link if emptied
	artsy.clearLink('a[href="artsy-link-'+artsy.currentRandomID+'"]');
	
	// Just fade it out
	jQuery(artsy.pathEditorBox).css('display', 'none');
	jQuery(artsy.pathImageBox).fadeIn('fast');
	
	// the image box in and push it a little below our current mouse cursor position
	if (typeof e.clientY == 'undefined') clientY = artsy.windowHeight / 2;
	else clientY = e.clientY;
	if (typeof e.clientX == 'undefined') clientX = artsy.windowWidth / 2;
	else clientX = e.clientX;
	if (typeof e.pageY == 'undefined') pageY = artsy.documentHeight / 2;
	else pageY = e.pageY;
	if (typeof e.pageX == 'undefined') pageX = artsy.documentWidth / 2;
	else pageX = e.pageX;
	var documentTop = pageY + 20;
	var documentLeft = pageX - 150;
	var documentBottom = artsy.documentHeight - pageY;
	var documentRight = artsy.documentWidth - pageX;
	if (documentLeft < 10) documentLeft = 10;
	if (documentRight < 170) documentLeft = artsy.documentWidth - 330;
	if (artsy.windowHeight - clientY < 140) documentTop = pageY - 140;
	if (typeof e.pageX == 'undefined' || typeof e.pageY == 'undefined') {
		documentTop = clientY;
		documentLeft = clientX - 140;
	}
	jQuery(artsy.pathImageBox).css('left', documentLeft + 'px').css('top', documentTop + 'px');
	
	// Focus on the title field
	jQuery(artsy.pathInputImageTitle).focus();
	
	// Handle the revert button
	jQuery(artsy.pathImageBox).find('#bottom2 #return-to-default').click(function () {
		artsy.restoreImageSize(artsy.imageID);
	});
	
	// Get value for title and alt
	jQuery(artsy.pathImageBox).find(artsy.pathInputImageTitle).val(jQuery(artsy.imageID).attr('title'));
	jQuery(artsy.pathImageBox).find(artsy.pathInputImageAlt).val(jQuery(artsy.imageID).attr('alt'));
	jQuery(artsy.pathImageBox).find(artsy.pathInputImageCap).val(jQuery(artsy.imageID).attr('cap'));
	jQuery(artsy.pathImageBox).find(artsy.pathInputImageLink).val(jQuery(artsy.imageID).attr('link'));
	jQuery(artsy.pathImageBox).find(artsy.pathInputImageWidth).val(jQuery(artsy.imageID).attr('width'));
	jQuery(artsy.pathImageBox).find(artsy.pathInputImageHeight).val(jQuery(artsy.imageID).attr('height'));
	
	// Handle submission event on form
	jQuery(artsy.pathImageBox).find('form').submit(function () {
		artsy.submitImageForm();
		return false;
	});
	
	// Handle keyup event on form
	jQuery(artsy.pathImageBox+' '+artsy.pathInputImageAlt+', '+artsy.pathImageBox+' '+artsy.pathInputImageTitle+', '+artsy.pathImageBox+' '+artsy.pathInputImageCap+', '+artsy.pathImageBox+' '+artsy.pathInputImageLink).keyup(function () {
		artsy.editImageForm();
	});
	
	// Handle blur event on form
	jQuery(artsy.pathImageBox+' '+artsy.pathInputImageWidth+', '+artsy.pathImageBox+' '+artsy.pathInputImageHeight).blur(function () {
		artsy.editImageForm();
	});
	
	// Handle click event on title field
	jQuery(artsy.pathImageBox).find('#clear-input-image-title').click(function () {
		artsy.clearImageTitle();
		return false;
	});
	
	// Handle click event on alt field
	jQuery(artsy.pathImageBox).find('#clear-input-image-alt').click(function () {
		artsy.clearImageAlt();
		return false;
	});
	
	// Handle click event on caption field
	jQuery(artsy.pathImageBox).find('#clear-input-image-cap').click(function () {
		artsy.clearImageCap();
		return false;
	});
	
	// Handle click event on link field
	jQuery(artsy.pathImageBox).find('#clear-input-image-link').click(function () {
		artsy.clearImageLink();
		return false;
	});
	
	// Handle the alignment buttons
	jQuery(artsy.pathImageBox).find('#top .artsy-button').each(function () {
		jQuery(this).click(function () {
			artsy.doImageAlign(jQuery(this).attr('id'), artsy.imageID);
		});
	});
	
	// Handle original size image button
	jQuery(artsy.pathOriginalSize).click(function () {
		artsy.originalSize(artsy.imageID);
		return false;
	});
	
	// Handle delete image button
	jQuery(artsy.pathDeleteImage).click(function () {
		artsy.deleteImage(artsy.imageID);
		return false;
	});
	
};

// Close the image box
artsy.closeImageBox = function() {
	
	// Imagebox is already displayed, let's unbind the button clicks and fade it out
	jQuery(artsy.pathImageBox).find('#top .artsy-button').each(function () { jQuery(this).unbind('click'); });
	
	// Just fade it out
	jQuery(artsy.pathImageBox).fadeOut('fast');
		
};

// Toggle the image box
artsy.toggleImageBox = function(e) {

	if (jQuery(artsy.pathImageBox).css('display') == 'none') {
		artsy.openImageBox(e);
	} else {
		artsy.closeImageBox();
	}
	
};

// Close the settings box
artsy.openSettingsBox = function() {

	jQuery('.artsy-submit').find('div').text('Save!');
	
	jQuery(artsy.pathSettingsBox).find('.action-links').find('a').click(function() {
	
		action = jQuery(this).attr('id').split('-');
		jQuery(this).siblings().removeClass('active');
		jQuery(this).addClass('active');
		jQuery(artsy.pathSettingsBox).find('div[class^="group-"]').hide();
		jQuery('.group-'+action[1]).show();
		jQuery(artsy.pathSettingsBox).center(true).css('z-index', '1005');
	
	});

	// Change the button from button to reset
	cloneHTML = artsy.getOuterHTML(artsy.pathCancelSettings);
	jQuery(jQuery(artsy.pathCancelSettings)[0]).after(cloneHTML.replace('type="button" ', 'type="reset" '));
	jQuery(jQuery(artsy.pathCancelSettings)[0]).remove();
	
	// Handle click event on the cancel link
	jQuery(artsy.pathCancelSettings).click(function () {
		artsy.toggleSettingsBox();
	});
	
	// Just fade it in
	jQuery(artsy.pathSettingsBox).fadeIn('fast').center(true).css('z-index', '1005');
	jQuery(artsy.pathMask).fadeIn('fast');
	
};

// Close the settings box
artsy.closeSettingsBox = function() {

	if (jQuery(artsy.pathSettingsBox).css('display') != 'none') {
	
		// Put the original settings back on
		jQuery('#artsy_font').text(artsy.settingsFontOriginal);
		jQuery('#artsy_font-size').text(artsy.settingsFontSizeOriginal);
		jQuery('#artsy_background').text(artsy.settingsBackgroundOriginal);
		jQuery('#artsy_show-word-count').text(artsy.settingsShowWordCountOriginal);
		jQuery(artsy.pathSettingsBox).find('#font').val(artsy.settingsFontOriginal);
		jQuery(artsy.pathSettingsBox).find('#font-size').val(artsy.settingsFontSizeOriginal);
		jQuery(artsy.pathSettingsBox).find('#background').val(artsy.settingsBackgroundOriginal);
		
		// Submit the resetted form
		artsy.settingsFormChange('#font');
		artsy.settingsFormChange('#font-size');
		artsy.settingsFormChange('#background');
		artsy.settingsFormChange('#show-word-count');
		
		// Just fade it out
		jQuery(artsy.pathSettingsBox).fadeOut('fast');
		jQuery(artsy.pathMask).fadeOut('fast');
	
	}
		
};

// Toggle the setting box
artsy.toggleSettingsBox = function() {
	
	if (jQuery(artsy.pathSettingsBox).css('display') == 'none') artsy.openSettingsBox();
	else artsy.closeSettingsBox();
		
};

// Close all boxes opened
artsy.closeAllBoxes = function() {

	//jQuery(artsy.pathStyleBlock).remove();
	artsy.closeSettingsBox();
	artsy.closeEditorBox();
	artsy.closeImageBox();

}

/**
 * When they change the settings in the settings form, make it change with them.
 *
 * @access private
**/
artsy.settingsFormChange = function(element) {

	// Get value and ID
	val = jQuery(element).val();
	id = jQuery(element).attr('id');
	
	// Put value back on
	jQuery('#artsy_'+id).html(val);
	
	// Style change for font
	if (id == 'font') {
		jQuery(artsy.pathContent).css('font-family', val+', sans-serif');
		jQuery(artsy.pathTitle).css('font-family', val+', sans-serif');
		artsy.settingsFont = val;
	}
	
	// Style change for font size
	if (id == 'font-size') {
		titleVal = val - 1 + 5;
		lineHeightVal = val * 1.5;
		element = artsy.pathContent+', '+artsy.pathContent+' p'+', '+artsy.pathContent+' blockquote'+', '+artsy.pathContent+' ul'+', '+artsy.pathContent+' ol';
		jQuery(element).css('font-size', val+'px');
		jQuery(element).css('line-height', lineHeightVal+'px');
		jQuery(artsy.pathTitle).css('font-size', titleVal+'px');
		artsy.settingsFontSize = val;
	}
	
	// Style change for background
	if (id == 'background') {
		//jQuery(artsy.pathBackground).css('background-color', '#'+artsy.determineColor(val));
		//jQuery(artsy.pathEditor).add(artsy.pathTitle).css('color', '#'+artsy.determineColorText(val));
		
		//	Removing the old background sheet
		jQuery(artsy.backgroundStyleID).remove();
		
		//	Adding the new one
		var css_file = str_replace(' ', '-', strtolower(val));
		jQuery(artsy.pathStyleBlock).append('<link rel="stylesheet" media="screen" href="'+artsy_plugin_path+'/css/backgrounds/'+css_file+'.css" id="artsy-background-css" />');
		artsy.settingsBackground = val;
	}
	
	if (id == 'show-word-count') {
		val = jQuery('input[name="show-word-count"]:checked').val();
		if (val == 0) jQuery(artsy.pathWordCount).hide();
		else jQuery(artsy.pathWordCount).show();
		artsy.settingsBackground = val;
	}
	
	// ADD MUSIC AND VOLUME CONTROL

}

// Handle the submission of the setting form
artsy.submitSettingsForm = function () {

	// Prepare data for submission
	var data = {
		action: 'submitSettings',
		settings: {
			font: jQuery('#artsy_font').text(),
			font_size: jQuery('#artsy_font-size').text(),
			background: jQuery('#artsy_background').text(),
			show_word_count: jQuery('#artsy_show-word-count').text(),
			open_automatically: jQuery('#artsy_open-automatically').text(),
			open_in: jQuery('#artsy_open-in').text()
		}
	};
	
	jQuery('.artsy-submit').find('div').text('Working');
	// Submit an AJAX post request
	jQuery.post(ajaxurl, data, function(response) {
	
		// Get updated settings when finished
		artsy.settingsFontOriginal = jQuery('#artsy_font').text();
		artsy.settingsFontSizeOriginal = jQuery('#artsy_font-size').text();
		artsy.settingsBackgroundOriginal = jQuery('#artsy_background').text();
		artsy.settingsShowWordCountOriginal = jQuery('#artsy_show-word-count').text();
		
		// Replace with the new HTML
		response = eval('(' + response + ')');
		jQuery(artsy.pathSettingsBox).find('.group-settings').html(response);
	
		jQuery('.artsy-submit').find('div').text('Good!');
		clearTimeout(timer4);
		timer4 = setTimeout("artsy.toggleSettingsBox();", 250);
		
		// Set up the change event again
		jQuery(artsy.pathSettingsBox+' select, '+artsy.pathSettingsBox+' input').change(function () {
			artsy.settingsFormChange(this);
		});
		
		// Set up the change click again
		jQuery(artsy.pathCancelSettings).click(function () {
			artsy.toggleSettingsBox();
		});
		
	});
	
};

/**
 * Align an image
 *
 * @access public
**/
artsy.doImageAlign = function (theAction, element) {

	var actionName = theAction.split('-');
	jQuery(element).removeClass('alignnone alignleft aligncenter alignright');
	switch (actionName[1]) {
		case 'alignImageLeft':
			jQuery(element).addClass('alignleft');
			jQuery(element).parent().css('float', 'left');
			jQuery(element).parent().css('text-align', 'left');
			jQuery(element).next().removeClass('ui-resizable-sw').addClass('ui-resizable-se');
			break;
		case 'alignImageMiddle':
			jQuery(element).addClass('aligncenter');
			jQuery(element).parent().css('float', 'none');
			jQuery(element).parent().css('text-align', 'center');
			jQuery(element).next().removeClass('ui-resizable-sw').addClass('ui-resizable-se');
			break;
		case 'alignImageRight':
			jQuery(element).addClass('alignright');
			jQuery(element).parent().css('float', 'right');
			jQuery(element).parent().css('text-align', 'right');
			jQuery(element).next().removeClass('ui-resizable-se').addClass('ui-resizable-sw');
			break;
		case 'alignImageNone':
			jQuery(element).addClass('alignnone');
			jQuery(element).parent().css('float', 'none');
			jQuery(element).parent().css('text-align', 'left');
			jQuery(element).next().removeClass('ui-resizable-sw').addClass('ui-resizable-se');
			break;
	}
	
};

/**
 * Parse the content to linkfy anything possible
 *
 * @return string
**/
artsy.parseText = function(text) {

	text = artsy.parseURL(text);
	text = artsy.parseEmail(text);
	text = artsy.parseUsername(text);
	text = artsy.parseHashtag(text);
	text = artsy.preventLinkWithinLinks(text);
	return text;
	
};

// Linkify URL
artsy.parseURL = function(text) {

	return text.replace(/[A-Za-z]+:\/\/[A-Za-z0-9-_]+\.[A-Za-z0-9-_:%&~\?\/.=]+/g, function(url) {
		return url.link(url);
	});
	
};

/**
 * Linkify email address
 *
 * @return string
**/
artsy.parseEmail = function(text) {

	return text.replace(/[0-9a-zA-Z]+@[0-9a-zA-Z]+[\.]{1}[0-9a-zA-Z]+[\.]?[0-9a-zA-Z]{2,4}/g, function(t) {
		return t.link("mailto:"+t);
	});
	
};

/**
 * Linkify Twitter username
 *
 * @return string
**/
artsy.parseUsername = function(text) {

	return text.replace(/[@]+([A-Za-z0-9_]+)+[^A-Za-z0-9._]/g, function(u) {
		u = u.replace(' ', '');
		var username = u.replace("@","");
		return u.link("http://twitter.com/"+username)+' ';
	});
	
};

/**
 * Linkify Twitter hashtag
 *
 * @return string
**/
artsy.parseHashtag = function(text) {

	return text.replace(/[#]+([A-Za-z0-9-_]+)+[^A-Za-z0-9_]/g, function(t) {
		t = t.replace(' ', '');
		var tag = t.replace("#","%23");
		return t.link("http://search.twitter.com/search?q="+tag)+' ';
	});
	
};

/**
 * Prevent any possible link within links
 *
 * @return string
**/
artsy.preventLinkWithinLinks = function(text) {
	return text.replace(/<a [^>]+?(<a [^>]+?>)[^>]+?<\/a>[^>]+?>([^>]+?)<\/a>/ig, '$1$2</a>');
};

// Delete a link
artsy.clearLink = function(activeLink) {

	jQuery(activeLink).after(jQuery(activeLink).text());
	jQuery(activeLink).remove();
	
};

// Get cursor (caret) position
artsy.getCursorPosition = function() {

	jQuery(artsy.pathCurrentPosition).remove();
	selection = artsy.saveSelection();
	var sel, range, html;
	if (window.getSelection) {
		sel = window.getSelection();
		if (sel.getRangeAt && sel.rangeCount) {
			range = sel.getRangeAt(0);
			span = document.createElement('span');
			span.setAttribute('id', 'current-position');
			range.insertNode(span);
		}
		else jQuery(artsy.pathContent).prepend('<span id="current-position"></span>');
	} else if (document.selection && document.selection.createRange) {
		range = document.selection.createRange();
		range.pasteHTML(text);
	}
	artsy.restoreSelection(selection);
	
};

artsy.saveSelection = function() {
	if (window.getSelection) {
		sel = window.getSelection();
		if (sel.getRangeAt && sel.rangeCount) {
			return sel.getRangeAt(0);
		}
	} else if (document.selection && document.selection.createRange) {
		return document.selection.createRange();
	}
	return null;
}

artsy.restoreSelection = function(range) {
	if (range) {
		if (window.getSelection) {
			sel = window.getSelection();
			sel.removeAllRanges();
			sel.addRange(range);
		} else if (document.selection && range.select) {
			range.select();
		}
	}
}

// To grab our currently selected text, and many of its companion element
artsy.getSelected = function() {

	if (window.getSelection) { // WebKit
		artsy.selection = window.getSelection();
		artsy.selectedText = artsy.selection.toString();
	} else if (document.getSelection) { // Gecko
		artsy.selection = document.getSelection();
		artsy.selectedText = artsy.selection.getSelection();
	} else if (document.selection) { // This is for IE :(
		artsy.selection = document.selection.createRange();
		artsy.selectedText = artsy.selection.text;
	}
	if (artsy.selectedText != '') range = artsy.selection.getRangeAt(0);
	if (typeof artsy.selection == 'undefined') artsy.commonAncestorContainer = artsy.selection.getRangeAt(0).commonAncestorContainer.parentNode;
	if (artsy.selectedText != '') {
		artsy.rangeStart = range.startOffset;
		artsy.rangeEnd = range.endOffset;
		artsy.rangeElementStart = range.startContainer;
		artsy.rangeElementEnd = range.endContainer;
		if (range.commonAncestorContainer.nodeName == 'A') {
			artsy.parentElementHref = range.commonAncestorContainer.getAttribute('href');
			artsy.parentElementTarget = range.commonAncestorContainer.getAttribute('target');
		} else if (range.commonAncestorContainer.parentNode.nodeName == 'A') {
			artsy.parentElementHref = range.commonAncestorContainer.parentNode.getAttribute('href');
			artsy.parentElementTarget = range.commonAncestorContainer.parentNode.getAttribute('target');
		} else if (range.commonAncestorContainer.parentNode.parentNode.nodeName == 'A') {
			artsy.parentElementHref = range.commonAncestorContainer.parentNode.parentNode.getAttribute('href');
			artsy.parentElementTarget = range.commonAncestorContainer.parentNode.parentNode.getAttribute('target');
		} else {
			artsy.parentElementHref = '';
			artsy.parentElementTarget = '';
		}
		if (jQuery(range.commonAncestorContainer).attr('id') == 'artsy-editor-content') artsy.selectionParents = jQuery(range.commonAncestorContainer).get();
		else artsy.selectionParents = jQuery(range.commonAncestorContainer).parents();
		
		artsy.selectedInContent = false;
		artsy.foundFormatBlock = false;
		artsy.commonFormatBlock = false;
		if (artsy.browser == 'gecko') artsy.selectionParents = jQuery(artsy.selectionParents).get().reverse();
		jQuery(artsy.selectionParents).each(function () {
			if (artsy.selectedInContent == false && jQuery(this).attr('id') == 'artsy-editor-content') {
				artsy.selectedInContent = true;
			}
			if (artsy.foundFormatBlock == false && jQuery.inArray(this.nodeName.toLowerCase(), artsy.formatBlocks) != -1) {
				artsy.commonFormatBlock = ['<'+this.nodeName.toLowerCase()+'>'];
				artsy.foundFormatBlock = true;
			}
		});
		artsy.doWordCount(artsy.selectedText);
	} else {
		artsy.doWordCount(false);
	}
	
};

// Open junk
artsy.openJunk = function () {

	if (artsy.junkOpen == false) {
		artsy.junkOpen = true;
		jQuery(artsy.pathMenu).fadeIn();
		color = '#cccccc';
		jQuery(artsy.pathTitle).add(artsy.pathContent).animate({'border-top-color': color, 'border-left-color': color, 'border-right-color': color, 'border-bottom-color': color}, 400);
	}
	clearTimeout(timer1);
	timer1 = setTimeout("artsy.closeJunk()", 3000);
	
};

// Close junk
artsy.closeJunk = function () {

	if (artsy.junkOpen == true) {
		artsy.junkOpen = false;
		jQuery(artsy.pathMenu).fadeOut('slow');
		color = '#'+artsy.determineColor(jQuery('#artsy_background').text());
		jQuery(artsy.pathTitle).add(artsy.pathContent).animate({'border-top-color': color, 'border-left-color': color, 'border-right-color': color, 'border-bottom-color': color}, 600);
	}
	
};

// Do word count
artsy.doWordCount = function (original) {

	if (original == false) text = jQuery(artsy.pathContent).text();
	else text = original;
	var word = text.split(' ').length;
	var character = text.length;
	if (word < 0) word = 0;
	if (character < 0) character = 0;
	if (original == false) {
		word = word - 1;
		character = character - 1;
	}
	jQuery(artsy.pathWordCount).text('Word: '+word+' - Character: '+character);
	
};

// To restore the an image size during a revert process
artsy.restoreImageSize = function (imageID) {

	imageID = jQuery(imageID).attr(artsy.imageIdentifier);
	for (var i in artsy.originalSizes) {
		if (artsy.originalSizes[i].id == imageID) {
			imageID = 'img['+artsy.imageIdentifier+'='+imageID+']';
			jQuery(imageID).width(artsy.originalSizes[i].width);
			jQuery(imageID).parent('div').width(artsy.originalSizes[i].width);
			jQuery(imageID).height(artsy.originalSizes[i].height);
			jQuery(imageID).parent('div').height(artsy.originalSizes[i].height);
		}
	}
	
};

// Change the value/background of the header button
artsy.changeHeaderButton = function (header) {

	header = header.substr(1, 2);
	jQuery(artsy.pathEditorBox).find('.show-header').attr('title', header);
	jQuery(artsy.pathEditorBox).find('.show-header').attr('id', 'action-'+header);

}

// Generate image HTML tag
artsy.getImageTag = function (id, src, width, height, title, alt, caption, link, align, size) {

	if (id != '') {
		wpid = ' wp-image-'+id;
	}
	if (width > 774) {
		percentage = Math.round(774 / width * 100);
		height = Math.round(percentage * height / 100);
		width = 774;
	}
	return '<img '+artsy.imageIdentifier+'="'+artsy.generateRandomID()+'" class="align'+align+' size-'+size+wpid+'" title="'+title+'" src="'+src+'" alt="'+alt+'" link="'+link+'" width="'+width+'" height="'+height+'" cap="'+caption+'" attachment-id="attachment_'+id+'">';
	
};

/**
 * Load the theme's CSS File
 *
 * @access private
**/
artsy.loadBackgroundCSS = function(theme)
{
	//	Load the default if one wasn't passed
	if (! theme)
		var theme = artsy_background;
	
	var theme = str_replace(' ', '-', strtolower(theme));
	
	jQuery(artsy.backgroundStyleID).remove();
	jQuery('body').append('<link rel="stylesheet" media="screen" href="'+artsy_plugin_path+'/css/backgrounds/'+theme+'.css" id="artsy-background-css" />');
	artsy.settingsBackground = theme;
}

/**
 * To convert color string to hex code for the background
 *
 * @return string
**/
artsy.determineColor = function(color)
{
	color = strtolower(color);
	switch(color)
	{
		case 'light yellow':
			return 'EEEEDE';
		break;
		case 'light green':
			return 'F3FFEE';
		break;
		case 'light blue':
			return 'EEF9FF';
		break;
		case 'light grey':
			return 'F3F3F3';
		break;
		case 'dark grey':
			return '333333';
		break;
		case 'white':
			return 'FFFFFF';
		break;
		default :
			return 'EEEEDE';
		break;
	}
};

/**
 * To convert color string to hex code for the text
 *
 * @access string
**/
artsy.determineColorText = function(color)
{
	color = strtolower(color);
	if (color == 'white')
		return '000000';
	
	if (color.substr(0, 5) == 'light')
		return '333333';
	else
		return 'EEEEEE';	
};

// Get the complete HTML of an element
artsy.getOuterHTML = function (ID) {

	return jQuery(ID).clone().wrap('<div></div>').parent().html();

}

// Generate a random ID
artsy.generateRandomID = function () {

	artsy.currentRandomID = Math.floor(Math.random() * 10000);
	return artsy.currentRandomID;
	
};

//	Deleting the old go() function.
delete switchEditors.go;

/**
 * Built into WordPress is the switchEditors class.
 * It is a way to switch editors in the post.
 *
 * We override the go() function, which changes the editor
 * We make it able to use the artsy mode.
 *
 * @access public
 * @param string
 * @param string
**/
switchEditors.go = function(id, mode) {
	id = id || 'content';
		mode = mode || 'toggle';

		var t = this, ed = tinyMCE.get(id), wrap_id, txtarea_el, dom = tinymce.DOM;

		wrap_id = 'wp-'+id+'-wrap';
		txtarea_el = dom.get(id);

		if ( 'toggle' == mode ) {
			if ( ed && !ed.isHidden() )
				mode = 'html';
			else
				mode = 'tmce';
		}

		if ( 'tmce' == mode || 'tinymce' == mode ) {
			if ( ed && ! ed.isHidden() )
				return false;

			if ( typeof(QTags) != 'undefined' )
				QTags.closeAllTags(id);

			if ( tinyMCEPreInit.mceInit[id] && tinyMCEPreInit.mceInit[id].wpautop )
				txtarea_el.value = t.wpautop( txtarea_el.value );

			if ( ed ) {
				ed.show();
			} else {
				ed = new tinymce.Editor(id, tinyMCEPreInit.mceInit[id]);
				ed.render();
			}

			dom.removeClass(wrap_id, 'html-active');
			dom.addClass(wrap_id, 'tmce-active');
			setUserSetting('editor', 'tinymce');

		} else if ( 'html' == mode ) {

			if ( ed && ed.isHidden() )
				return false;

			if ( ed ) {
				txtarea_el.style.height = ed.getContentAreaContainer().offsetHeight + 20 + 'px';
				ed.hide();
			}

			dom.removeClass(wrap_id, 'tmce-active');
			dom.addClass(wrap_id, 'html-active');
			setUserSetting('editor', 'html');
		} else if ('artsy' == mode )
		{
			jQuery('#edButtonArtsy').data('editor_type', jQuery('#editor-toolbar').find('a[class^=active]').attr('id'));
			jQuery('#edButtonArtsy').addClass('active');
			setUserSetting( 'editor', 'artsy' );
			this.mode = 'artsy';
			dom.removeClass(wrap_id, 'html-active');
			dom.removeClass(wrap_id, 'tmce-active');
			artsy.runEditor();
			artsy.hasSetup = true;
		}
		return false;
	};

//	Helper functions
//	-------------------------------------
function strtolower (str) { return (str + '').toLowerCase(); }
function str_replace (search, replace, subject, count) {
	var i = 0,
		j = 0,
		temp = '',
		repl = '',
		sl = 0,
		fl = 0,
		f = [].concat(search),
		r = [].concat(replace),
		s = subject,
		ra = Object.prototype.toString.call(r) === '[object Array]',
		sa = Object.prototype.toString.call(s) === '[object Array]';
	s = [].concat(s);
	if (count) {
		this.window[count] = 0;
	}

	for (i = 0, sl = s.length; i < sl; i++) {
		if (s[i] === '') {
			continue;
		}
		for (j = 0, fl = f.length; j < fl; j++) {
			temp = s[i] + '';
			repl = ra ? (r[j] !== undefined ? r[j] : '') : r[0];
			s[i] = (temp).split(f[j]).join(repl);
			if (count && s[i] !== temp) {
				this.window[count] += (temp.length - s[i].length) / f[j].length;
			}
		}
	}
	return sa ? s : s[0];
}
function strip_tags (input, allowed) {
	allowed = (((allowed || "") + "").toLowerCase().match(/<[a-z][a-z0-9]*>/g) || []).join(''); // making sure the allowed arg is a string containing only tags in lowercase (<a><b><c>)
	var tags = /<\/?([a-z][a-z0-9]*)\b[^>]*>/gi,
		commentsAndPhpTags = /<!--[\s\S]*?-->|<\?(?:php)?[\s\S]*?\?>/gi;
	return input.replace(commentsAndPhpTags, '').replace(tags, function ($0, $1) {
		return allowed.indexOf('<' + $1.toLowerCase() + '>') > -1 ? $0 : '';
	});
}

jQuery.fn.center=function(absolute){
	return this.each(function(){var t=jQuery(this);t.css({position:absolute?'absolute':'fixed',left:'50%',top:'50%',zIndex:'99'}).css({marginLeft:'-'+(t.outerWidth()/2)+'px',marginTop:'-'+(t.outerHeight()/2)+'px'});if(absolute){t.css({marginTop:parseInt(t.css('marginTop'),10)+jQuery(window).scrollTop(),marginLeft:parseInt(t.css('marginLeft'),10)+jQuery(window).scrollLeft()})}})
};