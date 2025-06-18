# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/)  
and this project adheres to [Semantic Versioning](https://semver.org/).

---

## [1.1.0] – 2025-06-18

### Added
- Unified and parameterized configuration for cookie behavior via GTM:
  - `cn` (cookie name)
  - `cuic` (cookie value)
  - `httpOnly`
  - `refresh`
  - `maxAge`
- Centralized PHP handler can now be reused for **multiple GTM tags or test environments** via GTM-only configuration (no server changes required)
- Full support for HTTP POST with fallback to GET `<img>` request if blocked
- Optional fallback event `cuic_fallback_used` to GTM `dataLayer`
- `cuic_cookie_set` event with full return payload (cookie name & value)

### Changed
- **`jsUserIdCreate.js` and `jsUserIdCheck.js` were merged** into a single GTM variable: `jsUserIdResolve.js`
- Refactored PHP handler:
  - Centralized header control
  - Simplified cookie logic with `refresh` parameter
  - Prioritization of existing cookie value
  - Full support for both JSON and image fallback responses based on `Accept` header
- GTM tag (`cuic_controller.html`) rewritten:
  - Uses `POST` with full config payload
  - Robust fallback logic on network/parse error
  - Clean event push on success/failure
- JS fallback logic now uses `new Image()` instead of static `<img>` HTML
- Renamed all identifiers from `tkncstm` to `cuic` internally and in GTM

### Fixed
- Bug: duplicate requests due to image fallback triggering despite successful XHR
- Bug: inconsistent `Content-Type` handling in PHP handler leading to fallback failure
- Bug: cookie set logic ignored existing cookie if present – now properly refreshed only if `refresh = 1`

### Removed
- **Standalone fallback tag (`cuic_fallback-img.html`)** – fallback is now fully integrated into `cuic_controller.html`
- Legacy naming (`tkncstm`) in GTM and PHP handler
- Unused logging and console statements in GTM tags
- Hardcoded fallback logic without configurability

---

## [1.0.0] – Initial Release

- First version of the CUIC components
- Basic support for one cookie, set via XHR or fallback `<img>`
- No parameterization, single cookie name (`tkncstm`)
- Separate GTM tags and variables for ID creation/check
- Basic PHP cookie handler with fixed config
