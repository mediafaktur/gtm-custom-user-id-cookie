/**
 * GTM Variable: jsUserIdResolved
 * 
 * Resolves the current Custom User ID ("tkncstm") for tracking purposes.
 * - If available in dataLayer → returns existing value
 * - Otherwise → generates a fallback ID of the format <timestamp>_<random>
 * 
 * The generated fallback is cached on `window._cuicUserId` to avoid duplication during the page session.
 * 
 * Usage: Insert as a Custom JavaScript Variable in GTM → `jsUserIdResolved`
 *
 * @returns {string} Resolved User ID
 *
 * © Florian Pankarter, / MEDIAFAKTUR – Marketing Performance Precision  
 * https://mediafaktur.marketing · fp@mediafaktur.marketing  
 * GitHub: https://github.com/mediafaktur/gtm-custom-user-id-cookie  
 * License: MIT
 */

function() {
  var dl = window.dataLayer || [];

  // Step 1: Try to read from dataLayer
  for (var i = dl.length - 1; i >= 0; i--) {
    if (dl[i] && typeof dl[i].tkncstm !== "undefined") {
      // console.log("✅ CUIC ID from dataLayer:", dl[i].tkncstm);
      return dl[i].tkncstm;
    }
  }

  // Step 2: Generate fallback if nothing found
  if (!window._cuicUserId) {
    var ts = Date.now();
    var rnd = Math.random().toString(36).substring(2, 10);
    window._cuicUserId = ts + "_" + rnd;
    // console.log("⚠️ CUIC fallback ID generated:", window._cuicUserId);
  }

  return window._cuicUserId;
}
