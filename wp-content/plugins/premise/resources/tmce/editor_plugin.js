(function() {
	tinymce.create('tinymce.plugins.PremiseInfoPlugin', {

		init : function(ed, url) {
			ed.addCommand('MCEPremiseSampleCopy', function() {
				insert_sample_content(ed.id);
			});
			ed.addButton('PremiseSampleCopy', {
				title: 'Insert Sample Copy',
				cmd: 'MCEPremiseSampleCopy',
				image: url + '/img/icon_sample-copy.png'
			});
			
			ed.addCommand('MCEPremiseInsertGraphic', function() {
				show_graphic_library(ed.id);
			});
			ed.addButton('PremiseInsertGraphic', {
				title: 'Insert Graphic',
				cmd: 'MCEPremiseInsertGraphic',
				image: url + '/img/icon_graphics-library.png'
			});
			
			ed.addCommand('MCEPremiseInsertOptIn', function() {
				show_opt_in_inserter(ed.id);
			});
			ed.addButton('PremiseInsertOptIn', {
				title: 'Insert Opt In Code',
				cmd: 'MCEPremiseInsertOptIn',
				image: url + '/img/icon_opt-in-form.png'
			});
			
			ed.addCommand('MCEPremiseInsertNoticeBox', function() {
				premise_send_to_editor('<div class="notice">Put your notice text here.</div>', ed.id);
			});
			ed.addButton('PremiseInsertNoticeBox', {
				title: 'Insert Notice Box',
				cmd: 'MCEPremiseInsertNoticeBox',
				image: url + '/img/icon_notice-box.png'
			});
			
			ed.addCommand('MCEPremiseInsertButton', function() {
				show_button_usage_inserter(ed.id);
			});
			ed.addButton('PremiseInsertButton', {
				title: 'Insert Custom Button',
				cmd: 'MCEPremiseInsertButton',
				image: url + '/img/icon_insert-button.png'
			});
			
			ed.addCommand('MCEPremiseInsertGWOSection', function() {
				ed.windowManager.open({
					file : url + '/section.htm',
					width : 260 ,
					height : 220,
					inline : 1
				}, {
					plugin_url : url, // Plugin absolute URL
				});
			});
			ed.addButton('PremiseInsertGWOSection', {
				title : 'Insert Google Website Optimizer Section',
				cmd : 'MCEPremiseInsertGWOSection',
				image : url + '/img/icon_section.gif'
			});
			
			ed.addCommand('MCEPremiseGWOAddConversionLink', function() {
				var ed = tinyMCE.activeEditor, s = ed.selection;
				var dom =tinyMCE.activeEditor.dom;
				if (s.isCollapsed()){
					var link=s.getNode();					
					if(link.nodeName=='A'){
						var attrib=dom.getAttrib(link,'onclick');
						if(!attrib){
							dom.setAttrib(link,'onclick','return ConversionCount();');
						}
					}
				}
			});
			ed.addButton('PremiseGWOAddConversionLink', {
				title : 'Insert Google Website Optimizer Conversion Link',
				cmd : 'MCEPremiseGWOAddConversionLink',
				image : url + '/img/icon_conversion-link.gif'
			});
			
			ed.addCommand('MCEPremiseGWORemoveConversionLink', function() {
				var ed = tinyMCE.activeEditor, s = ed.selection;
				var dom =tinyMCE.activeEditor.dom;
				if (s.isCollapsed()){
					var link=s.getNode();					
					if(link.nodeName=='A'){
						var attrib=dom.getAttrib(link,'onclick');
						if(attrib){
							dom.setAttrib(link,'onclick',null);
						}
					}
				}
			});
			ed.addButton('PremiseGWORemoveConversionLink', {
				title : 'Remove Google Website Optimizer Conversion Link',
				cmd : 'MCEPremiseGWORemoveConversionLink',
				image : url + '/img/icon_conversion-link-remove.gif'
			});
			
			ed.onNodeChange.add(function(ed, cm, n) {					
				if(n.nodeName=='A'){
					
					var attrib=ed.dom.getAttrib(n,'onclick');
					if(attrib){
						cm.setDisabled('PremiseGWOAddConversionLink',true);
						cm.setDisabled('PremiseGWORemoveConversionLink', false);
					}else{
						cm.setDisabled('PremiseGWOAddConversionLink',false);
						cm.setDisabled('PremiseGWORemoveConversionLink', true);
					}
				}
				else{
					cm.setDisabled('PremiseGWOAddConversionLink', true);
					cm.setDisabled('PremiseGWORemoveConversionLink', true);
				}
			});
		},

		createControl : function(n, cm) {
			return null;
		},

		
		getInfo : function() {
			return {
				longname : 'Premise',
				author   :  'Copyblogger Media',
				authorurl : 'http://www.copyblogger.com',
				infourl : 'http://www.copyblogger.com',
				version : "1.0.0"
			};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('PremiseInfo', tinymce.plugins.PremiseInfoPlugin);
})();
