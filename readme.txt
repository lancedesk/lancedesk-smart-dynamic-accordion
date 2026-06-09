=== LanceDesk Smart Dynamic Accordion for Elementor ===
Contributors: lancedesk
Tags: elementor, accordion, faq, dynamic content, widget
Requires at least: 6.4
Tested up to: 6.8
Requires PHP: 7.4
Stable tag: 1.0.9
License: MIT
License URI: https://opensource.org/licenses/MIT

A secure and modular Elementor accordion widget with manual repeater items and dynamic post-driven rendering.

== Description ==

LanceDesk Smart Dynamic Accordion for Elementor provides a flexible accordion widget with:

* Manual repeater mode for FAQ and custom content.
* Dynamic mode from any public post type.
* Optional category and tag filters with AND/OR relation.
* Load more via button (with remaining count) or infinite scroll.
* Dynamic title/content mapping from post fields or custom meta.
* Smart rendering for HTML and image URL content.
* Optional read-more link controls (new tab and nofollow).
* Optional FAQ schema output guard.
* Elementor-native style controls for typography, colors, spacing, and icon states.

Security and compatibility:

* Proper sanitization and escaping across controls and output.
* Minimal query overhead with optimized post query flags.
* Namespaced and uniquely prefixed architecture to avoid collisions.

== Installation ==

1. Upload the plugin folder to `/wp-content/plugins/`.
2. Activate the plugin through the WordPress admin.
3. Ensure Elementor is installed and active.
4. In Elementor, drag **Smart Dynamic Accordion** from the **Lance Desk** category.

== Frequently Asked Questions ==

= Does this plugin require Elementor? =
Yes. The widget loads only when Elementor is active.

= Can I render accordion items from custom post types? =
Yes. Select any public post type in Dynamic mode.

= Can I pull content from custom fields? =
Yes. Use Dynamic Content Source set to Custom Field and provide a meta key.

== Changelog ==

= 1.0.9 =
* Restore collision-safe `class-ldrj-hbda-*` include filenames and exclude them from PHPCS filename checks.
* Fix PHPCS errors for text domain literals, icon output escaping annotations, and Yoda conditions.

= 1.0.8 =
* Add optional category and tag filters with AND/OR relation for dynamic queries.
* Add load-more support via show-more button or infinite scroll when item count is limited.
* Add full Elementor style controls for the load-more button (colors, typography, spacing, alignment).
* Extract shared query logic and secure AJAX load-more endpoint with nonce verification.

= 1.0.7 =
* Intelligent dividers: single line between items (no doubled 4px gaps from top+bottom borders).
* Advanced divider options: optional border at top and border at bottom toggles.
* Fix title padding and background resetting on hover/focus/active states.
* Replace per-item border group with dedicated divider color, width, and style controls.

= 1.0.5 =
* Add optional dividers toggle (off by default) with Elementor border controls for width, color, and style.
* Fix divider styles so Elementor controls apply correctly (remove hardcoded CSS overrides).
* Add title padding and content background color style controls.
* Fix expand/collapse icon picker compatibility in Elementor 4.x.
* Restore font-icon rendering and remove unsupported Icons_Manager enqueue call that caused fatal errors.

= 1.0.2 =
* Fix long title wrapping in accordion triggers to prevent horizontal overflow on mobile.
* Keep expand/collapse icons aligned in a stable column for long multi-line titles.
* Add duplicate-plugin activation and runtime conflict guards with clear admin notices.

= 1.0.0 =
* Initial release.
* Manual and dynamic accordion modes.
* Dynamic query filters, read-more links, and FAQ schema controls.
* Elementor icon and style state controls.
