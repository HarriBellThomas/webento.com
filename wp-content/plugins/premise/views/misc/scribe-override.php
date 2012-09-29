<script type="text/javascript">
if (typeof(ecordia) == 'object') {
	ecordia.ecordia_dependency = 'user-defined';
	ecordia.elementIds['title'] = 'premise-seo-title';
	ecordia.elementIds['description'] = 'premise-seo-description';

	ecordia.elementIds['content'] = 'premise-combined-content';

	var old_ecordia_blur_event = ecordia.blurEvent;
	ecordia.blurEvent = function() {
		premise_scribe_focus_out();
		old_ecordia_blur_event();
	};

	var old_ecordia_ecordia_addTinyMCEEvent = ecordia_addTinyMCEEvent;
	ecordia_addTinyMCEEvent = function(ed) {
		old_ecordia_ecordia_addTinyMCEEvent(ed);
		ed.onKeyUp.add(function(ed, e) { ecordia.blurEvent(); });
	}

	function premise_scribe_focus_out() {
		var $ = jQuery;
		var $combined = $('#premise-combined-content').val('');
		var string = '';
		$('form#post textarea:not(#premise-combined-content)').each(function() {
			var $textarea = $(this);
			var id = $textarea.attr('id');
			var ed = typeof(tinyMCE) == 'undefined' ? null : tinyMCE.get(id);
			if(ed) {
				if(!ed.isHidden()) {
					string += ed.getContent();
				} else {
					string += $textarea.val();
				}
			} else {
				string += $textarea.val();
			}
		});

		$combined.val($.trim(string));
	};

	jQuery(document).ready(function($) {
		$('form#post').append($('<textarea id="premise-combined-content"></textarea>').hide());

		$('form#post textarea').live('focusout', function() { ecordia.blurEvent(); });

		ecordia.blurEvent();
	});
}
</script>
