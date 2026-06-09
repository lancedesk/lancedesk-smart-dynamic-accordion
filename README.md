# LanceDesk Smart Dynamic Accordion for Elementor

Secure, modular, and Elementor-native accordion widget for WordPress with both manual repeater content and smart dynamic post-driven rendering.

[![CI](https://github.com/lancedesk/lancedesk-smart-dynamic-accordion/actions/workflows/ci.yml/badge.svg)](https://github.com/lancedesk/lancedesk-smart-dynamic-accordion/actions/workflows/ci.yml)
[![Release ZIP](https://github.com/lancedesk/lancedesk-smart-dynamic-accordion/actions/workflows/release-zip.yml/badge.svg)](https://github.com/lancedesk/lancedesk-smart-dynamic-accordion/actions/workflows/release-zip.yml)

## Highlights

- Manual accordion items with title + rich content editor.
- Dynamic mode from public post types.
- Advanced query controls:
  - post type, count, order, order by, offset
  - include/exclude post IDs
  - optional category and tag filters with AND/OR relation
  - advanced custom taxonomy slug + terms (slug or term ID)
- Load more for limited dynamic lists:
  - show-more button with remaining count (`%d` placeholder)
  - infinite scroll mode
  - full Elementor style controls for the load-more button
- Dynamic field mapping:
  - title from post title or custom field
  - content from post content, excerpt, or custom field
  - meta value type auto detection (text/html/image URL)
- Elementor-native controls:
  - expand/collapse icons + icon position
  - normal/hover/active state colors
  - typography, spacing, optional dividers, title/content padding, content background
- Optional dynamic "Read more" links:
  - custom label
  - open in new tab
  - nofollow
- Optional FAQ schema output with guardrails:
  - minimum valid Q&A item count
  - optional requirement that title includes `?`

## Security and Quality

- Namespaced architecture (`LanceDesk\HBDA`) with unique prefix (`ldrj_hbda_`).
- Sanitization and escaping across controls and rendering.
- Optimized dynamic queries (`no_found_rows`, reduced cache overhead where appropriate).
- Accessible accordion markup (`aria-expanded`, `aria-controls`, keyboard-friendly button triggers).

## Installation

1. Copy this plugin folder to `wp-content/plugins/`.
2. Activate **LanceDesk Smart Dynamic Accordion for Elementor** in WordPress.
3. Make sure Elementor is installed and active.
4. Open Elementor and search for **Smart Dynamic Accordion** in the **Lance Desk** category.

## Quick Usage

### Manual mode

1. Set `Source` to `Manual Items`.
2. Add repeater items.
3. Set icons and style controls to match your design.

### Dynamic mode

1. Set `Source` to `Dynamic from Posts`.
2. Choose post type and query filters.
3. Configure field mapping (title/content/meta).
4. Optionally enable `Read More` and `FAQ Schema`.

## Project Structure

```text
dynamic-accordion/
├─ assets/
│  ├─ css/ldrj-hbda-accordion.css
│  └─ js/ldrj-hbda-accordion.js
├─ includes/
│  ├─ class-plugin.php
│  ├─ class-query.php
│  ├─ class-ajax.php
│  └─ widgets/class-smart-accordion-widget.php
├─ lancedesk-smart-dynamic-accordion.php
├─ readme.txt
├─ README.md
├─ uninstall.php
└─ LICENSE
```

## Roadmap

- Dynamic tag support for richer Elementor integrations.
- Additional content templates for post cards/media blocks inside accordion bodies.
- Comprehensive unit and integration tests.

## Contributing

Contributions are welcome. Please review `CONTRIBUTING.md` and use the issue/PR templates in `.github/`.

## License

MIT — see `LICENSE`.

If you plan to distribute via WordPress.org directory, consider switching to GPL-compatible licensing.

## Maintainer

Lance Desk  
[Lance Desk](https://lancedesk.com)
