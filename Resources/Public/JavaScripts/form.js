/**
 * Baseurl
 *
 * @type {string}
 */
var baseurl;

/**
 * Powermail main JavaScript for form validation
 */
jQuery(document).ready(function($) {

	// Read baseURL
	baseurl = getBaseUrl();

	// Tabs
	if ($.fn.powermailTabs) {
		$('.powermail_morestep').powermailTabs();
	}

	// Location field
	if ($('.powermail_fieldwrap_location input').length) {
		getLocationAndWrite();
	}

	// AJAX Form submit
	if ($('form[data-powermail-ajax]').length) {
		// submit is only called after parsley and html5 checks :) - so we don't have to check for errors
		$(document).on('submit', 'form[data-powermail-ajax]', function (e) {
			var $this = $(this);
			var formUid = $this.data('powermail-ajax');

			$.ajax({
				type: 'POST',
				url: $this.prop('action'),
				data: $this.serialize(),
				beforeSend: function() {
					// add progressbar <div class="powermail_progressbar"><div class="powermail_progress"><div class="powermail_progess_inner"></div></div></div>
					var progressBar = $('<div />').addClass('powermail_progressbar').html(
						$('<div />').addClass('powermail_progress').html(
							$('<div />').addClass('powermail_progess_inner')
						)
					);
					$('.powermail_submit', $this).parent().append(progressBar);
					$('.powermail_confirmation_submit, .powermail_confirmation_form', $this).closest('.powermail_confirmation').append(progressBar);
				},
				complete: function() {
					// remove progressbar
					$('.powermail_fieldwrap_submit', $this).find('.powermail_progressbar').remove();
				},
				success: function(data) {
					var html = $('*[data-powermail-form="' + formUid + '"]', data);
					$('.tx-powermail').html(html);
				}
			});

			e.preventDefault();
		});
	}

	// Datepicker field
	if ($.fn.datepicker) {
		$('.powermail_date').datepicker({
			dateFormat: $('.container_datepicker_dateformat:first').val(),
			dayNamesMin: [
				$('.container_datepicker_day_so:first').val(),
				$('.container_datepicker_day_mo:first').val(),
				$('.container_datepicker_day_tu:first').val(),
				$('.container_datepicker_day_we:first').val(),
				$('.container_datepicker_day_th:first').val(),
				$('.container_datepicker_day_fr:first').val(),
				$('.container_datepicker_day_sa:first').val()
			],
			monthNames: [
				$('.container_datepicker_month_jan:first').val(),
				$('.container_datepicker_month_feb:first').val(),
				$('.container_datepicker_month_mar:first').val(),
				$('.container_datepicker_month_apr:first').val(),
				$('.container_datepicker_month_may:first').val(),
				$('.container_datepicker_month_jun:first').val(),
				$('.container_datepicker_month_jul:first').val(),
				$('.container_datepicker_month_aug:first').val(),
				$('.container_datepicker_month_sep:first').val(),
				$('.container_datepicker_month_oct:first').val(),
				$('.container_datepicker_month_nov:first').val(),
				$('.container_datepicker_month_dec:first').val()
			],
			nextText: '&gt;',
			prevText: '&lt;',
			firstDay: 1
		});
	}
});

/**
 * Getting the Location by the browser and write to inputform as address
 *
 * @return void
 */
function getLocationAndWrite() {
	if (navigator.geolocation) { // Read location from Browser
		navigator.geolocation.getCurrentPosition(function(position) {
			var lat = position.coords.latitude;
			var lng = position.coords.longitude;
			var url = baseurl + '/index.php' + '?eID=' + 'powermailEidGetLocation';
			jQuery.ajax({
				url: url,
				data: 'lat=' + lat + '&lng=' + lng,
				cache: false,
				beforeSend: function(jqXHR, settings) {
					jQuery('body').css('cursor', 'wait');
				},
				complete: function(jqXHR, textStatus) {
					jQuery('body').css('cursor', 'default');
				},
				success: function(data) { // return values
					if (data) {
						jQuery('.powermail_fieldwrap_location input').val(data);
					}
				}
			});
		});
	}
}

/**
 * Return BaseUrl as prefix
 *
 * @return	string	Base Url
 */
function getBaseUrl() {
	var baseurl;
	if (jQuery('base').length > 0) {
		baseurl = jQuery('base').prop('href');
	} else {
		if (window.location.protocol != "https:") {
			baseurl = 'http://' + window.location.hostname;
		} else {
			baseurl = 'https://' + window.location.hostname;
		}
	}
	return baseurl;
}