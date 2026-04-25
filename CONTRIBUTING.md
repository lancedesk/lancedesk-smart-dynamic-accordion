# Contributing

Thanks for contributing to LanceDesk Smart Dynamic Accordion for Elementor.

## Development Setup

1. Clone the repository.
2. Copy it into your WordPress plugins directory.
3. Activate the plugin and ensure Elementor is active.
4. Test changes in both:
   - Elementor Editor preview
   - Frontend rendering

## Coding Standards

- Follow WordPress PHP coding standards.
- Keep the existing namespace and prefix strategy:
  - Namespace: `LanceDesk\HBDA`
  - Prefix: `ldrj_hbda_`
- Sanitize input and escape output in all new paths.
- Keep logic modular (separate concerns across bootstrap/widget/assets).

## Pull Request Process

1. Create a focused branch for one logical change.
2. Add/update docs when behavior changes.
3. Verify:
   - no PHP syntax errors
   - no linter errors
   - editor + frontend behavior
4. Open a PR using the repository template and complete the checklist.

## Security

If you discover a security issue, do not open a public issue with exploit details. Contact the maintainer directly first.
