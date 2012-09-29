var PremiseGoogleWebsiteOptimizerSection = {
	init : function() {},

	insert : function() {
		var embedCode = '[gwo-section id=\"'+document.forms[0].sectionName.value+'\"]{$selection}[/gwo-section]';
		tinyMCEPopup.editor.execCommand('mceReplaceContent', false, embedCode);
		tinyMCEPopup.close();
	}
};

tinyMCEPopup.onInit.add(PremiseGoogleWebsiteOptimizerSection.init, PremiseGoogleWebsiteOptimizerSection);