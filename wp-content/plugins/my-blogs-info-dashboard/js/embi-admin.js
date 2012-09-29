(function ($) {
$(function () {

/* ----- This is on My Sites page ----- */

$(".embi-item_trigger").click(function () {
	var $me = $(this);
	var $item = $me.parents(".embi-item_actions").next(".embi-item");
	if (!$item.length) return false;
	
	var oldMsg = $me.text();
	var newMsg = $me.attr("data-embi-msg");
	if ($item.is(":visible")) {
		$item.hide();
		$me
			.attr("data-embi-msg", oldMsg)
			.text(newMsg)
		;
	} else {
		$item.fadeIn('slow');
		$me
			.attr("data-embi-msg", oldMsg)
			.text(newMsg)
		;
	}
	return false;
});

$("#embi-show_pending").click(function () {
	$("table.widefat.fixed tbody td").each(function () {
		var $td = $(this);
		if ($td.parents('.embi-item').length) return true;
		if ($td.find('.embi-has_pending').length) return true;
		$td.hide();
	});
	return false;
});
$("#embi-show_all").click(function () {
	$("table.widefat.fixed tbody td").show();
	return false;
});


/* ----- This is on Dashboard page ----- */

// "Change" links for editable fields
$(".embi-editable_field-trigger").each(function () {
	var $me = $(this);
	var $td = $me.parents('.embi-item_value');
	var $source = $td.find('.embi-source_field');
	var $rpl = $td.find('.embi-editable_field');
	
	$me.click(function () {
		if ($source.is(":visible")) {
			$source.hide();
			$rpl.show();
		} else {
			$source.show();
			$rpl.hide();
		}
		return false;
	});
});

// OK/Cancel buttons
$(".embi-editable_field").each(function () {
	var $me = $(this);
	var $ok = $me.find('.embi-editable_field-ok');
	var $cancel = $me.find('.embi-editable_field-cancel');
	var $field = $me.find('.embi-editable_field-data');
	
	$ok.click(function () {
		$.post(ajaxurl, {
			"action": "update_editable_field_value",
			"name": $field.attr("name"),
			"value": $field.val()
		}, function (resp) {
			$field.val(resp);
			$me.parents('.embi-item_value').find('.embi-source_field-data').text(resp);
			$cancel.click();
		});
		return false;
	});
	
	$cancel.click(function () {
		$me.parents('.embi-item_value').find('.embi-editable_field-trigger').click();
		return false;
	});
	$me.hide();
});

// ----- Edublogs EasyBlogging related fix -----
function expand_buttons () {
	var expand = ($(window).width() >= 1200);
	$(
		"#embi_dashboard_this_blog_widget a.button," + 
		"#embi_dashboard_my_account_widget a.button"
	).each(function () {
		var $me = $(this);
		if (expand && $me.attr("title")) {
			$me.text($me.attr("title"));
		} else if (!expand) {
			var str = $me.text();
			var chunks = str.split(' ');
			if (chunks.length > 1) $me.attr("title", str);
			$me.html(chunks[0]);
		}
	});
		
}
if ($("#wdeb-mode").length) {
	expand_buttons();
	$(window).resize(expand_buttons);
}
// ----- End fix -----
	
});
})(jQuery);
