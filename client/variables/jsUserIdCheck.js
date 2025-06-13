/**
 * GTM Variable: jsUserIdCheck
 * Checks if a Custom User ID ("tkncstm") is available in the dataLayer.
 * If not, it falls back to a randomly generated ID (via jsUserIdCreate).
 * 
 * Ensures consistent user identification within the GTM Web Container for tag and trigger logic.
 *
 * @returns {string} Resolved User ID (existing or fallback)
 *
 * © Florian Pankarter, / MEDIAFAKTUR - Marketing Performance Precision  
 * https://mediafaktur.marketing · fp@mediafaktur.marketing  
 * GitHub: https://github.com/mediafaktur/gtm-custom-user-id-cookie  
 * License: MIT
 */

function() {
  var dl = window.dataLayer || [];
  var userIdFromDL = null;

  // Iterate backwards through the dataLayer to find the most recent "tkncstm" value
  for (var i = dl.length - 1; i >= 0; i--) {
    if (dl[i] && typeof dl[i].tkncstm !== "undefined") {
      userIdFromDL = dl[i].tkncstm;
      // console.log("✅ userIdCheck from Data Layer:", userIdFromDL);
      break;
    }
  }

  // Return value from dataLayer if available
  if (userIdFromDL) {
    return userIdFromDL;
  }

  // Fallback: generate a temporary ID if no value was found
  var fallback = {{jsUserIdCreate}};
  // console.log("⚠️ userIdCheck Fallback for userIdCreate:", fallback);
  return fallback;

}
