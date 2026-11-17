(function ($) {
	'use strict';

	function selectHighlightLang() {
		let fileName	= $('[name="fileupload"]')[0].files[0].name
		let fileSuffix	= fileName.substring(fileName.lastIndexOf('.') + 1);

		$('[name="snippet_highlight"] option[data-autosyntax~="' + fileSuffix + '"]').prop('selected', true);
	};

	$(function () {
		$('[name="fileupload"]').on('change', selectHighlightLang);
	});
})(jQuery);
