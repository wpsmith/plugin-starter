window.WPS = window.WPS || {};
(function (w, doc, $, wps) {
	"use strict";

	var l10n = null;

	/**
	 * Returns the localization.
	 *
	 * @returns {*}
	 */
	wps.l10n = function () {
		if (null !== l10n) {
			return l10n;
		}

		l10n = $.extend({}, w.wpsL10n, w.wpsAjax);
		return l10n;
	};

	/**
	 * Performs the ajax
	 *
	 * @param {object} data Data to be sent.
	 * @param {function} successFn Callable callback function.
	 * @param {function} failFn Callable callback function.
	 */
	wps.doAjax = function (data, successFn, failFn) {

		if (wps.l10n().loadPlugins) {
			data.loadPlugins = wps.l10n().loadPlugins;
			data.fastAjax = 1;
		}

		// and run our ajax function
		setTimeout(function () {
			$.ajax({
				type: 'post',
				dataType: 'json',
				url: wps.l10n().ajaxUrl,
				data: data,
				success: successFn,
				fail: failFn
			});

		}, 500);

	};

	wps.enableAll = function () {
		$(".feedback :input").attr("disabled", false);
	};

	wps.disableAll = function () {
		$(".feedback :input").attr("disabled", true);
	};

	/**
	 * Starts/stops contextual spinner.
	 *
	 * @param  {object} $context The jQuery parent/context object.
	 * @param  {bool} hide       Whether to hide the spinner (will show by default).
	 *
	 * @return {void}
	 */
	wps.toggleActive = function ($context, hide) {
		var m = hide ? 'removeClass' : 'addClass';
		$context[m]('is-active');
	};

	/**
	 * Safely log things if query var is set. Accepts same parameters as console.log.
	 *
	 * @since  1.0.0
	 *
	 * @return {void}
	 */
	wps.log = function () {
		if (wps.l10n().debug && console && 'function' === typeof console.log) {
			console.log.apply(console, arguments);
		}
	};

	wps.isEmailValid = function (email) {
		var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		return re.test(email.toLowerCase());
		// return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)
	};

})(window, document, jQuery, window.WPS);
