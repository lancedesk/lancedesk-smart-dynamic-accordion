=== LanceDesk Smart Dynamic Accordion for Elementor ===
Contributors: lancedesk
Tags: elementor, accordion, faq, dynamic content, widget
Requires at least: 6.4
Tested up to: 6.8
Requires PHP: 7.4
Stable tag: 1.0.2
License: MIT
License URI: https://opensource.org/licenses/MIT

A secure and modular Elementor accordion widget with manual repeater items and dynamic post-driven rendering.

== Description ==

LanceDesk Smart Dynamic Accordion for Elementor provides a flexible accordion widget with:

* Manual repeater mode for FAQ and custom content.
* Dynamic mode from any public post type.
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

= 1.0.2 =
* Fix long title wrapping in accordion triggers to prevent horizontal overflow on mobile.
* Keep expand/collapse icons aligned in a stable column for long multi-line titles.
* Add duplicate-plugin activation and runtime conflict guards with clear admin notices.

= 1.0.0 =
* Initial release.
* Manual and dynamic accordion modes.
* Dynamic query filters, read-more links, and FAQ schema controls.
* Elementor icon and style state controls.
