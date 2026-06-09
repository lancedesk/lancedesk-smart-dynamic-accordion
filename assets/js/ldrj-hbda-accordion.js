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

	function bindTrigger(root, trigger) {
		if (trigger.getAttribute('data-hbda-bound') === 'yes') {
			return;
		}

		trigger.setAttribute('data-hbda-bound', 'yes');
		trigger.addEventListener('click', function () {
			var panelId = trigger.getAttribute('aria-controls');
			var panel = panelId ? root.querySelector('#' + escapeSelector(panelId)) : null;

			if (!panel) {
				return;
			}

			var multiOpen = root.getAttribute('data-multi-open') === 'yes';
			var isExpanded = trigger.getAttribute('aria-expanded') === 'true';

			if (!multiOpen) {
				root.querySelectorAll('.ldrj-hbda-trigger').forEach(function (otherTrigger) {
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
	}

	function initAccordion(root) {
		if (!root || root.getAttribute('data-hbda-init') === 'yes') {
			return;
		}

		root.setAttribute('data-hbda-init', 'yes');
		root.querySelectorAll('.ldrj-hbda-trigger').forEach(function (trigger) {
			bindTrigger(root, trigger);
		});

		initLoadMore(root);
	}

	function formatLoadMoreLabel(template, remaining) {
		if (template.indexOf('%d') !== -1) {
			return template.replace('%d', String(Math.max(0, remaining)));
		}

		if (remaining > 0) {
			return template + ' (' + remaining + ')';
		}

		return template;
	}

	function getLoadMoreInsertTarget(root) {
		var buttonWrap = root.querySelector('.ldrj-hbda-load-more-wrap');
		if (buttonWrap) {
			return buttonWrap;
		}

		var sentinel = root.querySelector('.ldrj-hbda-load-more-sentinel');
		if (sentinel) {
			return sentinel;
		}

		return null;
	}

	function appendLoadedItems(root, html) {
		var container = document.createElement('div');
		container.innerHTML = html;
		var items = container.querySelectorAll('.ldrj-hbda-item');
		var insertTarget = getLoadMoreInsertTarget(root);

		items.forEach(function (item) {
			if (insertTarget) {
				root.insertBefore(item, insertTarget);
			} else {
				root.appendChild(item);
			}

			item.querySelectorAll('.ldrj-hbda-trigger').forEach(function (trigger) {
				bindTrigger(root, trigger);
			});
		});
	}

	function setLoadMoreLoading(root, isLoading) {
		var button = root.querySelector('.ldrj-hbda-load-more-btn');
		var status = root.querySelector('.ldrj-hbda-load-more-status');

		if (button) {
			button.disabled = isLoading;
			button.classList.toggle('is-loading', isLoading);
		}

		if (status) {
			status.hidden = !isLoading;
		}

		root.setAttribute('data-load-more-loading', isLoading ? 'yes' : 'no');
	}

	function updateLoadMoreUi(root, remaining, hasMore) {
		root.setAttribute('data-remaining', String(Math.max(0, remaining)));

		var button = root.querySelector('.ldrj-hbda-load-more-btn');
		var buttonWrap = root.querySelector('.ldrj-hbda-load-more-wrap');
		var sentinel = root.querySelector('.ldrj-hbda-load-more-sentinel');
		var template = root.getAttribute('data-button-template') || 'Show %d more';

		if (button) {
			if (hasMore) {
				button.textContent = formatLoadMoreLabel(template, remaining);
			}
		}

		if (buttonWrap) {
			buttonWrap.hidden = !hasMore;
		}

		if (sentinel) {
			sentinel.hidden = !hasMore;
		}
	}

	function requestMoreItems(root) {
		if (root.getAttribute('data-load-more') !== 'yes') {
			return Promise.resolve(null);
		}

		if (root.getAttribute('data-load-more-loading') === 'yes') {
			return Promise.resolve(null);
		}

		var remaining = parseInt(root.getAttribute('data-remaining') || '0', 10);
		if (remaining < 1) {
			return Promise.resolve(null);
		}

		if (!window.ldrjHbdaAjax || !window.ldrjHbdaAjax.ajaxUrl || !window.ldrjHbdaAjax.nonce) {
			return Promise.resolve(null);
		}

		setLoadMoreLoading(root, true);

		var body = new URLSearchParams();
		body.set('action', 'ldrj_hbda_load_more');
		body.set('nonce', window.ldrjHbdaAjax.nonce);
		body.set('offset', root.getAttribute('data-offset') || '0');
		body.set('widget_id', root.getAttribute('data-widget-id') || '');
		body.set('settings', root.getAttribute('data-settings') || '');

		return fetch(window.ldrjHbdaAjax.ajaxUrl, {
			method: 'POST',
			credentials: 'same-origin',
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
			},
			body: body.toString()
		})
			.then(function (response) {
				return response.json();
			})
			.then(function (payload) {
				if (!payload || !payload.success || !payload.data) {
					throw new Error('Load more failed');
				}

				if (payload.data.html) {
					appendLoadedItems(root, payload.data.html);
				}

				root.setAttribute('data-offset', String(payload.data.offset || 0));
				updateLoadMoreUi(root, payload.data.remaining || 0, !!payload.data.has_more);
				return payload.data;
			})
			.catch(function () {
				return null;
			})
			.finally(function () {
				setLoadMoreLoading(root, false);
			});
	}

	function initLoadMore(root) {
		if (root.getAttribute('data-load-more') !== 'yes' || root.getAttribute('data-load-more-bound') === 'yes') {
			return;
		}

		root.setAttribute('data-load-more-bound', 'yes');

		var button = root.querySelector('.ldrj-hbda-load-more-btn');
		if (button && button.getAttribute('data-hbda-bound') !== 'yes') {
			button.setAttribute('data-hbda-bound', 'yes');
			button.addEventListener('click', function () {
				requestMoreItems(root);
			});
		}

		var sentinel = root.querySelector('.ldrj-hbda-load-more-sentinel');
		if (sentinel && 'IntersectionObserver' in window) {
			var observer = new IntersectionObserver(function (entries) {
				entries.forEach(function (entry) {
					if (!entry.isIntersecting) {
						return;
					}

					if (root.getAttribute('data-load-more-mode') !== 'infinite_scroll') {
						return;
					}

					requestMoreItems(root);
				});
			}, {
				root: null,
				rootMargin: '120px 0px',
				threshold: 0
			});

			observer.observe(sentinel);
			root.setAttribute('data-load-more-observer', 'yes');
		}
	}

	function initAllAccordions(scope) {
		var context = scope || document;
		context.querySelectorAll('.ldrj-hbda-accordion').forEach(initAccordion);
	}

	function boot() {
		initAllAccordions(document);

		if (window.elementorFrontend && window.elementorFrontend.hooks) {
			window.elementorFrontend.hooks.addAction('frontend/element_ready/global', function ($scope) {
				if ($scope && $scope[0]) {
					initAllAccordions($scope[0]);
				}
			});
		}
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', boot);
	} else {
		boot();
	}

	document.addEventListener('elementor/popup/show', function () {
		document.querySelectorAll('.ldrj-hbda-accordion').forEach(initAccordion);
	});
})();
