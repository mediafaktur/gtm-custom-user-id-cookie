<?php

/**
 * GTM Custom User ID Cookie (CUIC)
 * Consent-based, cross-session user identification via secure first-party cookie.
 *
 * data-layer-snippet.php
 *
 * Injects the Custom User ID ("tkncstm") from an HTTP-only cookie into the JavaScript dataLayer.
 * Used for making the ID available to the Google Tag Manager (GTM) on subsequent page views.
 * 
 * This script is typically included server-side in the <head> of your HTML template.
 * Ensures that the ID is present before GTM is initialized, allowing it to be reused across tags.
 *
 * Key Features:
 * - Reads secure HTTP-only cookie ("tkncstm") on server-side
 * - Pushes its value into the client-side dataLayer (if present)
 * - Works independently or in combination with existing GTM or Consent Mode snippets
 *
 * Privacy Notes:
 * - Requires prior user consent (set and used only after opt-in)
 * - No personal data is processed or stored server-side
 *
 * Integration:
 * - Place this snippet before the GTM container script in the <head>
 * - Can be combined with existing `dataLayer` and `gtag()` initialization logic (e.g. for Consent Mode)
 *
 * Example (combined with gtag consent snippet):
 * <script>
 *   window.dataLayer = window.dataLayer || [];
 *   function gtag() { dataLayer.push(arguments); }
 *   gtag('consent', 'default', { analytics_storage: 'denied' });
 *   <?php if (isset($_COOKIE['tkncstm'])):
 *     $cstmid = htmlspecialchars($_COOKIE['tkncstm'], ENT_QUOTES, 'UTF-8'); ?>
 *     window.dataLayer.push({ tkncstm: "<?= $cstmid ?>" });
 *   <?php endif; ?>
 * </script>
 *
 * © Florian Pankarter, / MEDIAFAKTUR – Marketing Performance Precision  
 * https://mediafaktur.marketing · fp@mediafaktur.marketing  
 * GitHub: https://github.com/mediafaktur/gtm-custom-user-id-cookie  
 * License: MIT
 */
?>

<script>
  window.dataLayer = window.dataLayer || [];
  <?php if (isset($_COOKIE['tkncstm'])):
    $cstmid = htmlspecialchars($_COOKIE['tkncstm'], ENT_QUOTES, 'UTF-8');
  ?>
  window.dataLayer.push({ tkncstm: "<?= $cstmid ?>" });
  <?php endif; ?>
</script>
