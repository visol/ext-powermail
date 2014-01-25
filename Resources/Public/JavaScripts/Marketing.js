jQuery(document).ready(function() {
	var data = '';
	data += 'tx_powermail_pi1[language]=' + $('#powermailLanguage').val();
	data += '&tx_powermail_pi1[pid]=' + $('#powermailPid').val();
	data += '&tx_powermail_pi1[referer]=' + encodeURIComponent(document.referrer);
	jQuery.ajax({
		url: getBaseUrl() + '/index.php?id=5&eID=powermailEidMarketing',
		data: data,
		cache: false,
		success: function(data) { // return values
			if (data) {
				$('form').append(data);
			}
		}
	});
});

/**
 * Return BaseUrl as prefix
 *
 * @return	string	Base Url
 */
function getBaseUrl() {
	var baseurl;
	if (jQuery('base').length > 0) {
		baseurl = jQuery('base').attr('href');
	} else {
		if (window.location.protocol != "https:") {
			baseurl = 'http://' + window.location.hostname;
		} else {
			baseurl = 'https://' + window.location.hostname;
		}
	}
	return baseurl;
}