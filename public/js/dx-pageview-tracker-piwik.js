(function( $ ) {
    'use strict';
    $( window ).load(function() {

        var _paq = _paq || [];

        _paq.push(['trackPageView']);
        _paq.push(['enableHeartBeatTimer', EXTERNAL_PARAMS.dx_pvt_piwik_heartbeat_timer]);
        _paq.push(['enableLinkTracking']);

        (  function() {
            var u="//" + EXTERNAL_PARAMS.dx_pvt_piwik_url ;
            _paq.push(['setTrackerUrl', u+'js/index.php']);
            _paq.push(['setSiteId', EXTERNAL_PARAMS.dx_pvt_piwik_site_id]);
            var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
            g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'js/index.php'; s.parentNode.insertBefore(g,s);
            }
        )();

    });
})( jQuery );
/**
 * EXTERNAL_PARAMS.dx_pvt_piwik_url
 * EXTERNAL_PARAMS.dx_pvt_piwik_site_id
 * EXTERNAL_PARAMS.dx_pvt_piwik_heartbeat_timer
 */

/**
 * Heres a copy of sample tracking code from piwik docs
 * @see http://developer.piwik.org/guides/tracking-javascript-guide
 *
 *
 * <!-- Piwik -->
 *   <script type="text/javascript">
 *  var _paq = _paq || [];
 *  _paq.push(['trackPageView']);
 *  _paq.push(['enableLinkTracking']);
 *  (function() {
 *   var u="//{$PIWIK_URL}/";
 *   _paq.push(['setTrackerUrl', u+'piwik.php']);
 *   _paq.push(['setSiteId', {$IDSITE}]);
 *   var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
 *   g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);
 *  })();
 * </script>
 * <!-- End Piwik Code -->
 */