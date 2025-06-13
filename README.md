# GTM Custom User ID Cookie (CUIC)

Consent-based, long-lived first-party ID cookie for privacy-friendly user tracking

## Overview

Custom User ID Cookie enables the creation and persistence of a random user ID across sessions using a secure HTTP-only cookie (tkncstm). The ID is generated client-side in GTM based on user consent and made available immediately – even before the cookie is set – for GA4 tracking and other downstream integrations.

Custom User ID Cookie supports full ITP/ETP compliance, cross-session continuity in Safari and Firefox, and seamless use of user_id in both client- and server-side tracking.

## Key Features

* ID available on first pageview (even before cookie is set)
* Persistant HTTP-only, secure, SameSite cookie
* Fully ITP/ETP-compatible (e.g. Safari)
* JavaScript to Cookie ID via data layer 
* No PII, no fingerprinting, no third-party cookies
* Optional consent-based execution fully controllable via GTM

## Components Overview

#### `jsUserIdCreate.js`

* **Type:** GTM JavaScript Variable
* **Purpose:** Generates a unique user ID as `{timestamp}_{random}`
* **How to use:** Add as a **Custom JavaScript Variable** "jsUserIdCreate" in your Web GTM container.

#### `jsUserIdCheck.js`

* **Type:** GTM JavaScript Variable
* **Purpose:** Returns the current user ID from the Data Layer or fallback to ID create
* **How to use:** Add as a **Custom JavaScript Variable** "jsUserIdCheck" in your Web GTM container.

#### `cuic_controller.html`

* **Type:** GTM Custom HTML Tag
* **Purpose:** Sends the user ID to the server via XHR and triggers fallback `<img>` if needed.
* **How to use:** Add as Custom HTML Tag and trigger on first pageview after valid consent.

**Features**:

* Uses POST (XHR) to `/cuic_cookie-handler.php`
* Falls back to transparent image if blocked or fails
* Cookie is set HTTP-only, SameSite=Lax, Secure

#### `cuic_fallback-img.html`

* **Type:** GTM Custom HTML Tag (Optional)
* **Purpose:** Sends a backup image request to set the cookie when XHR fails or is blocked.
* **How to use:** Use only if you want to separate fallback logic from `cuic_controller.html`

#### `cuic_cookie-handler.php`

* **Type:** Server-side PHP Script
* **Purpose:** Sets the `tkncstm` cookie based on a POST or GET request
* **How to use:** Deploy on your main domain (same origin). 

**Accepts**:

* POST (`tkncstm=...`) from controller
* GET (`?tkncstm=...`) from fallback image

Returns either JSON or 1x1 GIF depending on `Accept` header.

#### `cuic_datalayer-snippet.php`

* **Type:** Server-side PHP Snippet
* **Purpose:** Injects the `tkncstm` cookie value into the JS `dataLayer` early in the page load.
* **How to use:** Include in your `<head>` **before** GTM loads.

## Installation

1. Copy `/client/` and `/server/` files into your project
2. Deploy PHP scripts (`cuic_cookie-handler.php`, `cuic_datalayer-snippet.php`) on your domain
3. Import GTM variables & tags, configure triggers (e.g. after consent)
4. Optionally adjust cookie domain if needed (`.example.com` vs subdomain)
5. Use the `user_id` in GA4 config and events via variable - e.g. `{{DLV - tkncstm}}`

## Consent & Privacy

* Designed to run **only after consent** (fully controllable via GTM)
* **No PII** involved – ID is anonymized
* **No localStorage, no fingerprinting**
* **First-party, HTTP-only cookie** – compliant with ITP / ETP / GDPR

## License

MIT – see [LICENSE](./LICENSE)

## Author

/ MEDIAFAKTUR – Marketing Performance Precision, [https://mediafaktur.marketing](https://mediafaktur.marketing)  
Florian Pankarter, [fp@mediafaktur.marketing](mailto:fp@mediafaktur.marketing)
