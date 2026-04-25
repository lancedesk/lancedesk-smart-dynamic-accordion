(function () {
	'use strict';

	function escapeSelector(value) {
		if (window.CSS && typeof window.CSS.escape === 'function') {
			return window.CSS.escape(value);
		}
		return value.replace(/([^\w-])/g, '\\$1');
	}

	function setExpanded(trigger, panel, expanded) {
		trigger.setAttribute('aria-expanded', expanded ? 'true' : 'false');
		panel.hidden = !expanded;
		trigger.closest('.ldrj-hbda-item').classList.toggle('is-open', expanded);
	}

	function initAccordion(root) {
		var multiOpen = root.getAttribute('data-multi-open') === 'yes';
		var triggers = root.querySelectorAll('.ldrj-hbda-trigger');

		triggers.forEach(function (trigger) {
			trigger.addEventListener('click', function () {
				var panelId = trigger.getAttribute('aria-controls');
				var panel = panelId ? root.querySelector('#' + escapeSelector(panelId)) : null;

				if (!panel) {
					return;
				}

				var isExpanded = trigger.getAttribute('aria-expanded') === 'true';

				if (!multiOpen) {
					triggers.forEach(function (otherTrigger) {
						if (otherTrigger === trigger) {
							return;
						}
						var otherPanelId = otherTrigger.getAttribute('aria-controls');
						var otherPanel = otherPanelId ? root.querySelector('#' + escapeSelector(otherPanelId)) : null;
						if (otherPanel) {
							setExpanded(otherTrigger, otherPanel, false);
						}
					});
				}

				setExpanded(trigger, panel, !isExpanded);
			});
		});
	}

	document.addEventListener('DOMContentLoaded', function () {
		document.querySelectorAll('.ldrj-hbda-accordion').forEach(initAccordion);
	});
})();
