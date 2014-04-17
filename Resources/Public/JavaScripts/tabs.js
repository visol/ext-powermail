/***************************************************************
 *  Copyright notice
 *
 *  (c) 2012 Alexander Kellner <alexander.kellner@in2code.de>, in2code
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

jQuery(document).ready(function() {
	$.fn.powermailTabs = function(options) {
		'use strict';
		var $this = jQuery(this);
		options = jQuery.extend({
			container: 'fieldset',
			header: 'legend',
			tabs: true,
			navigation: true
		}, options);

		// initial show first fieldset
		$this.children(options.container).hide();
		$this.find(options.container).first().show();

		// Stop submit
		$this.submit(function(e) {
			//e.preventDefault();
		});

		generateTabNavigation($this, options);
		generateButtonNavigation($this, options);
	};

	/**
	 * Generate Button Navigation
	 *
	 * @param object element
	 * @param array options
	 * @return void
	 */
	function generateButtonNavigation(element, options) {
		if (!options.navigation) {
			return;
		}

		// buttons
		element.children(options.container).each(function(i) {
			console.log(i);
			var navigationContainer = $('<div />')
				.addClass('powermail_fieldwrap')
				.addClass('powermail_tab_navigation')
				.appendTo($(this));
			;
			if (i > 0) {
				navigationContainer.append(createPreviousButton());
			}
			if (i < (element.children(options.container).length - 1)) {
				navigationContainer.append(createNextButton());
			}
		});
	}

	/**
	 * Create next button
	 *
	 * @return void
	 */
	function createPreviousButton() {
		return $('<a />')
			.prop('href', '#')
			.addClass('powermail_tab_navigation_previous')
			.html('<');
	}

	/**
	 * Create next button
	 *
	 * @return void
	 */
	function createNextButton() {
		return $('<a />')
			.prop('href', '#')
			.addClass('powermail_tab_navigation_next')
			.html('>');
	}

	/**
	 * Generate Tabs
	 *
	 * @param object element
	 * @param array options
	 * @return void
	 */
	function generateTabNavigation(element, options) {
		if (!options.tabs) {
			return;
		}

		// generate menu
		var $ul = jQuery('<ul />', {
			'id': 'powermail_tabmenu',
			'class': 'powermail_tabmenu'
		}).insertBefore(
				element.children(options.container).filter(':first')
		);

		//all containers
		element.children(options.container).each(function(i, $fieldset){
			//tab_menu
			$ul.append(
				jQuery('<li/>')
					.html($(this).children(options.header).html())
					.addClass((i==0) ? 'act' : '')
					.click({
						container: element.children(options.container),
						fieldset: $($fieldset)
					}, function(e){
						jQuery('.powermail_tabmenu li', element).removeClass('act');
						jQuery(this).addClass('act');
						e.data.container.hide();
						e.data.fieldset.show()
					})
			)
		});
	}
});