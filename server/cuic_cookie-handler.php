<?php

/**
 * GTM Custom User ID Cookie (CUIC)
 * Consent-based, cross-session user identification via secure first-party cookie.
 *
 * === Project Overview ===
 * CUIC enables persistent, privacy-friendly user identification in restrictive environments
 * (e.g. Safari, Firefox, GDPR-compliant setups) through a secure, SameSite, HTTP-only cookie.
 * It is designed for use in consent-aware web analytics, especially to support `user_id`
 * attribution in GA4 (client- and server-side).
 *
 * === Components ===
 * - Web GTM HTML Tag (`cuic_controller.html`) to create & dispatch the ID
 * - PHP Cookie Handler (this script) to store the cookie
 * - PHP Data Layer Snippet (`cuic_datalayer-snippet.php`) exposes cookie value early
 *
 * === Script Purpose ===
 * This handler script sets a configurable first-party cookie based on GTM input.
 * While its default configuration is optimized for **long-lived, ITP/ETP-resistant cookies**,
 * it also supports flexible testing via GTM-defined parameters—no code changes required.
 * 
 * This enables clear separation of responsibilities:
 * → Web developers deploy the handler + snippet once  
 * → Analytics teams manage behavior (name, lifespan, visibility, refresh) entirely via GTM
 *
 * === Supported Request Parameters ===
 * - `cuic`      → cookie value (required)
 * - `cn`        → cookie name (default: "tkncstm")
 * - `refresh`   → "1" = always refresh (default), "0" = only set if missing
 * - `httpOnly`  → "1" = HTTP-only (default), "0" = JS-accessible
 * - `maxAge`    → cookie lifespan in seconds (default: 31536000 = 1 year)
 * 
 * === Delivery Modes ===
 * - POST → returns JSON (for XHR-based execution)
 * - GET  → returns 1×1 transparent GIF (fallback via <img>)
 *
 * === Notes ===
 * - No personal data is stored server-side
 * - No third-party cookies, no fingerprinting
 * 
 * ---
 * 
 * © Florian Pankarter · / MEDIAFAKTUR – Marketing Performance Precision  
 * https://mediafaktur.marketing · GitHub: https://github.com/mediafaktur/gtm-custom-user-id-cookie  
 * License: MIT
 * 
 */

// === Determine if client expects JSON response ===
$acceptsJson = strpos($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json') !== false;
$isPost      = $_SERVER['REQUEST_METHOD'] === 'POST';

// === Read config from GET/POST ===
$cookieName   = $_POST['cn']       ?? $_GET['cn']       ?? 'tkncstm';
$httpOnlyRaw  = $_POST['httpOnly'] ?? $_GET['httpOnly'] ?? '1';
$refreshRaw   = $_POST['refresh']  ?? $_GET['refresh']  ?? '1';
$maxAgeRaw    = $_POST['maxAge']   ?? $_GET['maxAge']   ?? 31536000;

// === Normalize parameters ===
$httpOnly = ($httpOnlyRaw === '1');
$refresh  = ($refreshRaw === '1');
$maxAge   = (is_numeric($maxAgeRaw) && (int)$maxAgeRaw > 0) ? (int)$maxAgeRaw : 31536000;

// === Determine base domain (remove "www." for cross-subdomain cookies) ===
$host = $_SERVER['HTTP_HOST'] ?? '';
$baseDomain = preg_replace('/^www\./', '', $host);

// === Determine value to set: existing cookie takes priority ===
$existingCookie = $_COOKIE[$cookieName] ?? null;
$incomingValue  = $_POST['cuic'] ?? $_GET['cuic'] ?? null;
$cookieValue    = $existingCookie ?? $incomingValue;

// === Graceful exit if no value ===
if (!$cookieValue) {
  if ($acceptsJson || $isPost) {
    header_remove('Content-Type');
    header('Content-Type: application/json');
    echo json_encode([
      'message' => 'No value provided – cookie not set'
    ]);
  } else {
    header('Content-Type: image/gif');
    echo base64_decode('R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==');
  }
  exit;
}

// === Set cookie if not set or refresh enabled ===
if ($refresh || !$existingCookie) {
  setcookie($cookieName, $cookieValue, [
    'expires'  => time() + $maxAge,
    'path'     => '/',
    'domain'   => $baseDomain,
    'secure'   => true,
    'httponly' => $httpOnly,
    'samesite' => 'Lax'
  ]);
}

// === Return response ===
if ($acceptsJson || $isPost) {
  // Ensure JSON header is set LAST, after setcookie()
  header_remove('Content-Type'); // remove earlier conflicting headers 
  header('Content-Type: application/json');
  echo json_encode([
    'message' => 'Cookie set or already present',
    'cookie'  => $cookieName,
    'value'   => $cookieValue
  ]);
} else {
  header('Content-Type: image/gif');
  echo base64_decode('R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==');
}

