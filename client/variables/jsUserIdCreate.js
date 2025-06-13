/**
 * GTM Variable: jsUserIdCreate
 * Generates a random Custom User ID and stores it temporarily in the window object.
 * Used to set a long-lived, HTTP-only first-party cookie ("tkncstm") via a server-side endpoint.
 * 
 * Typically triggered on first page view or used as fallback when no ID is available in the dataLayer.
 *
 * @returns {string} Generated ID in the format <timestamp>_<random>
 *
 * © Florian Pankarter, / MEDIAFAKTUR - Marketing Performance Precision  
 * https://mediafaktur.marketing · fp@mediafaktur.marketing  
 * GitHub: https://github.com/mediafaktur/gtm-custom-user-id-cookie  
 * License: MIT
 */

function() {
  // Check if ID has already been generated and stored in the global scope
  if (!window._userIdCreate) {
    var timestamp = Date.now(); // Current timestamp in milliseconds
    var random = Math.random().toString(36).substring(2, 10); // Random 8-char string
    window._userIdCreate = timestamp + "_" + random; // Combine to create unique ID
  }

  // Return the cached or newly generated ID
  return window._userIdCreate;
}