
function attachSettingEvent(event) {
	event.preventDefault();
	fileUpload($(this).closest('form'), $('#upload'));
}

function exportBm() {
	window.location = $(this).attr('href');
}

function fileUpload(form, resultDiv) {

	var uploadEventHandler = function () {
		var data = {};
		try {
			data = $.parseJSON(iframe.contents().text());
		} catch (e) {
		}
		if (!data) {
			resultDiv.text(t('bookmark', 'Import error'));
			return;
		}
		if (data.status == 'error') {
			var list = $("<ul></ul>").addClass('setting_error_list');
			console.log(data);
			$.each(data.data, function (index, item) {
				list.append($("<li></li>").text(item));
			});
			resultDiv.html(list);
		} else {
			resultDiv.text(t('bookmark', 'Import completed successfully.'));
			getBookmarks();
		}
	};

	// Create the iframe...
	var iframe;
	if ($('#upload_iframe').length === 1)
		iframe = $('#upload_iframe');
	else {
		iframe = $('<iframe></iframe>').attr({
			id: 'upload_iframe',
			name: 'upload_iframe',
			width: '0',
			height: '0',
			border: '0',
			style: 'display:none'
		}).bind('load', uploadEventHandler);
		form.append(iframe);
	}

	// Set properties of form...
	form.attr({
		target: 'upload_iframe',
		method: 'post',
		enctype: 'multipart/form-data',
		encoding: 'multipart/form-data'
	});

	// Submit the form...
	form.submit();

	resultDiv.text(t('bookmark', 'Uploading...'));
}

$(function () {

	$('#sort-by').val($('#sortbycurr').val());

	$('#sort-by').change(function(e) {
		var url = OC.generateUrl('/apps/bookmarks/config?key='
			+ e.target.id + '&value=' + e.target.value);
		$.post(url).success(function (response) {
			filterTagsChanged();
		});
	});

 	$('#confirm-delete').prop('checked', $('#confirmcurr').val() === '1');

	$('#confirm-delete').change(function(e) {
        var url = OC.generateUrl('/apps/bookmarks/config?key='
            + e.target.id + '&value=' + ($(this).prop('checked') ? '1' : '0'));
	    $.post(url).success(function (response) {
			document.getElementById(confirmcurr).value = e.target.checked ? 1 : 0;
        });
    });

});
