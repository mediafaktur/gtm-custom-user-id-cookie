# GTM Custom User ID Cookie (CUIC)

Consent-based, long-lived first-party ID cookie for privacy-friendly user tracking in restrictive environments.

## Overview

**Custom User ID Cookie** enables the generation and secure persistence of a random user ID across sessions using an HTTP-only, first-party cookie. The ID is generated client-side in GTM (based on user consent, if applied) and made available immediately – even before the cookie is set – for GA4 tracking and other downstream integrations.

CUIC is fully compatible with ITP/ETP restrictions and enables stable cross-session identification in Safari and Firefox. It supports the use of user_id in both client- and server-side tracking architectures.

All key behaviors — such as cookie name, expiration, refresh policy, and visibility — are controlled centrally via GTM. No PHP code changes are needed to switch between test and production setups.

**Best results** are achieved by combining all CUIC components:
- **GTM Tag**: creates and dispatches the ID (`cuic_controller.html`)
- **Cookie Handler**: securely stores the cookie (`cuic_cookie-handler.php`)
- **Data Layer Snippet**: exposes the cookie value to GTM (`cuic_datalayer-snippet.php`)

## Key Features

* Persistent first-party, secure, HTTP-only cookie (SameSite=Lax)
* ID available on first pageview – usable before the cookie is stored
* Fully consent-controlled via GTM
* No fingerprinting, no localStorage, no third-party cookies
* Compatible with Safari/Firefox ITP/ETP restrictions
* HTTP-only cookie value accessible via GTM data layer 
* Entire behavior (refresh, name, expiration) controlled via GTM
* Optional fallback via <img> if XHR is blocked
* Ideal for GA4 user_id and server-side tracking setups
* Supports both production and test scenarios

## Components Overview

#### `jsUserIdCreate.js`

* **Type:** GTM JavaScript Variable
* **Purpose:** Generates a unique user ID as `{timestamp}_{random}`
* **Usage:** Add as a **Custom JavaScript Variable** "jsUserIdCreate" in GTM

#### `jsUserIdCheck.js`

* **Type:** GTM JavaScript Variable
* **Purpose:** Returns the current user ID from the Data Layer or fallback to ID create
* **Usage:** Add as a **Custom JavaScript Variable** "jsUserIdCheck" in GTM

#### `cuic_controller.html`

- **Type:** GTM Custom HTML Tag  
- **Purpose:** Sends the ID to the server via XHR + optional `<img>` fallback  
- **Usage:** Trigger once after valid consent  
- **Features:**
  - Uses `POST` to `/cuic_cookie-handler.php`
  - Automatically falls back to GET if needed
  - All config (cookie name, refresh, expiration, visibility) passed via POST

#### `cuic_cookie-handler.php`

- **Type:** Server-side PHP Script  
- **Purpose:** Receives and stores the user ID cookie with configurable behavior  
- **Usage:** Deploy on your own domain (same origin required)  
- **Configurable via GTM parameters:**
  - `cuic` → cookie value (required)
  - `cn` → cookie name (default: `tkncstm`)
  - `refresh` → `1` = always refresh (default), `0` = set once
  - `httpOnly` → `1` = HTTP-only (default), `0` = readable by JS
  - `maxAge` → cookie lifetime in seconds (default: 31536000 = 1 year)

Returns either JSON or 1x1 GIF depending on `Accept` header.

#### `cuic_datalayer-snippet.php`

- **Type:** PHP snippet  
- **Purpose:** Exposes the cookie value to the `dataLayer` before GTM loads  
- **Usage:** Include in `<head>`, before GTM script tag  
- **Why it matters:** Needed to make HTTP-only cookie values accessible to GTM

## Installation

1. Copy `/client/` and `/server/` files into your project
2. Deploy PHP scripts (`cuic_cookie-handler.php`, `cuic_datalayer-snippet.php`) on your domain
3. Import GTM variables & tags, configure triggers (e.g. after consent)
4. Optionally adjust cookie domain if needed (`.example.com` vs subdomain)
5. Use the `user_id` in GA4 config and events via variable - e.g. `{{DLV - tkncstm}}`

## Consent & Privacy

* CUIC should only run after user consent (fully controlled via GTM)
* No personally identifiable information (PII) is stored
* No fingerprinting, no localStorage
* Fully compliant with ITP, ETP, GDPR, and ePrivacy standards

## Testing Scenarios

CUIC supports flexible testing across browsers and scenarios without code changes to the server scripts. By adjusting GTM parameters like `maxAge`, `httpOnly`, and `refresh`, you can simulate:

- ITP/ETP impact in Safari/Firefox
- Repeated visits and session boundaries
- Differences between short-lived and persistent cookies

This enables flexible QA setups without modifying server code or redeploying scripts.

## License

MIT – see [LICENSE](./LICENSE)

## Author

/ MEDIAFAKTUR – Marketing Performance Precision, [https://mediafaktur.marketing](https://mediafaktur.marketing)  
Florian Pankarter, [fp@mediafaktur.marketing](mailto:fp@mediafaktur.marketing)
