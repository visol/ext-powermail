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
		ajaxFormSubmit();
	}

	// Datepicker field
	if ($.fn.datetimepicker) {
		$('.powermail_date').each(function() {
			var $this = $(this);
			// stop javascript datepicker, if browser supports type="date" or "datetime-local" or "time"
			if ($this.prop('type') === 'date' || $this.prop('type') === 'datetime-local' || $this.prop('type') === 'time') {
				if ($this.data('datepicker-force')) {
					// rewrite input type
					$this.prop('type', 'text');
				} else {
					// stop js datepicker
					return;
				}
			}

			var datepickerStatus = true;
			var timepickerStatus = true;
			if ($this.data('datepicker-settings') === 'date') {
				timepickerStatus = false;
			} else if ($this.data('datepicker-settings') === 'time') {
				datepickerStatus = false;
			}

			// create datepicker
			$this.datetimepicker({
				format: $this.data('datepicker-format'),
				timepicker: timepickerStatus,
				datepicker: datepickerStatus,
				lang: 'en',
				i18n:{
					en:{
						months: $this.data('datepicker-months').split(','),
						dayOfWeek: $this.data('datepicker-days').split(',')
					}
				}
			});
		});
	}
});

/**
 * Allow AJAX Submit for powermail
 *
 * @return void
 */
function ajaxFormSubmit() {
	// submit is called after parsley and html5 validation - so we don't have to check for errors
	$(document).on('submit', 'form[data-powermail-ajax]', function (e) {
		var $this = $(this);
		var formUid = $this.data('powermail-form');

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
				var html = $('*[data-powermail-form="' + formUid + '"]:first', data);
				$('.tx-powermail').html(html);
				// fire tabs and parsley again
				if ($.fn.powermailTabs) {
					$('.powermail_morestep').powermailTabs();
				}
				if ($.fn.parsley) {
					$('form[data-parsley-validate="data-parsley-validate"]').parsley();
				}
			}
		});

		e.preventDefault();
	});
}

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