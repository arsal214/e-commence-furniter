{{-- Analytics and pixels shared by the marketplace layout. Same snippets as layouts/main.blade.php so
     events keep their ids and dedupe with the server-side calls. --}}
        {{-- ── Third-party fetch scheduler ──────────────────────────────────
             Analytics and pixel libraries were already async, so they never
             blocked rendering — but they still competed with the LCP image for
             bandwidth on mobile. pgDefer holds back only the network fetch; each
             vendor's stub (fbq / ttq / dataLayer) is still defined synchronously
             below, so any event fired during page load queues and flushes the
             moment its library arrives. Nothing is dropped.

             The trigger is window.load (LCP is already decided by then, so this
             costs nothing measurable) or the first real interaction, whichever
             comes first. The only visitors whose events could be lost are those
             who leave before onload — the same ones async loading would already
             have missed. --}}
        <script>
            (function () {
                var fired = false, queue = [];
                function run() {
                    if (fired) return;
                    fired = true;
                    for (var i = 0; i < queue.length; i++) { try { queue[i](); } catch (e) {} }
                    queue = [];
                }
                window.pgDefer = function (fn) { fired ? fn() : queue.push(fn); };

                function schedule() {
                    // requestIdleCallback keeps it off a busy main thread, but the
                    // timeout guarantees it runs even if the thread never idles.
                    window.requestIdleCallback ? requestIdleCallback(run, { timeout: 2000 }) : setTimeout(run, 200);
                }
                document.readyState === 'complete' ? schedule() : window.addEventListener('load', schedule);

                ['pointerdown', 'keydown', 'touchstart', 'scroll'].forEach(function (evt) {
                    window.addEventListener(evt, run, { once: true, passive: true });
                });
            })();
        </script>
        {{-- Analytics is async and never blocks the parser, but it still competes
             for bandwidth with the CSS above — so it is requested after it. --}}
        <!-- Google Analytics -->
        <script>
            // dataLayer + gtag() stay synchronous so the pageview is recorded
            // immediately; only gtag.js itself waits for pgDefer.
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', 'G-X57MYCJ0B8');
            window.pgDefer(function () {
                var s = document.createElement('script');
                s.async = true;
                s.src = 'https://www.googletagmanager.com/gtag/js?id=G-X57MYCJ0B8';
                document.head.appendChild(s);
            });
        </script>
        <!-- Ahrefs Analytics -->
        <script>
            window.pgDefer(function () {
                var s = document.createElement('script');
                s.async = true;
                s.src = 'https://analytics.ahrefs.com/analytics.js';
                s.setAttribute('data-key', 'QlGio/G4Sr1krD5aawauYg');
                document.head.appendChild(s);
            });
        </script>
        <!-- Microsoft Clarity -->
        {{-- Deliberately NOT routed through pgDefer like the ad pixels below:
             Clarity records the session, so holding the tag back until onload or
             first interaction would lose the opening moments of every visit. The
             tag is injected async and never blocks rendering. --}}
        <script>
        (function (c, l, a, r, i, t, y) {
          c[a] = c[a] || function () { (c[a].q = c[a].q || []).push(arguments) };
          t = l.createElement(r); t.async = 1; t.src = "https://www.clarity.ms/tag/" + i;
          y = l.getElementsByTagName(r)[0]; y.parentNode.insertBefore(t, y);
        })(window, document, "clarity", "script", @json(config('services.clarity.project_id')));
        </script>
        <!-- End Microsoft Clarity -->
        <!-- TikTok Pixel Code Start -->
        <script>
        !function (w, d, t) {
          w.TiktokAnalyticsObject=t;var ttq=w[t]=w[t]||[];ttq.methods=["page","track","identify","instances","debug","on","off","once","ready","alias","group","enableCookie","disableCookie","holdConsent","revokeConsent","grantConsent"],ttq.setAndDefer=function(t,e){t[e]=function(){t.push([e].concat(Array.prototype.slice.call(arguments,0)))}};for(var i=0;i<ttq.methods.length;i++)ttq.setAndDefer(ttq,ttq.methods[i]);ttq.instance=function(t){for(
        var e=ttq._i[t]||[],n=0;n<ttq.methods.length;n++)ttq.setAndDefer(e,ttq.methods[n]);return e},ttq.load=function(e,n){var r="https://analytics.tiktok.com/i18n/pixel/events.js",o=n&&n.partner;ttq._i=ttq._i||{},ttq._i[e]=[],ttq._i[e]._u=r,ttq._t=ttq._t||{},ttq._t[e]=+new Date,ttq._o=ttq._o||{},ttq._o[e]=n||{};n=document.createElement("script")
        ;n.type="text/javascript",n.async=!0,n.src=r+"?sdkid="+e+"&lib="+t;e=document.getElementsByTagName("script")[0];e.parentNode.insertBefore(n,e)};


          // ttq.load() is what injects events.js, so it is the only part held
          // back. ttq.page() still runs now and queues on the ttq array, which
          // the SDK drains once it loads.
          window.pgDefer(function () {
            ttq.load(@json(config('services.tiktok.pixel_id', 'D95OHCRC77UFCF7AKUBG')));
            @if (config('services.tiktok.pixel_id_2'))
            ttq.load(@json(config('services.tiktok.pixel_id_2')));
            @endif
          });
          ttq.page();
        }(window, document, 'ttq');
        </script>
        <!-- TikTok Pixel Code End -->
        @include('includes.tiktok-events')
        {{-- Organization schema: present on every page --}}
        @php
        $schemaOrg = ['@context'=>'https://schema.org','@type'=>'Organization','name'=>'PeytonGhalib','url'=>url('/'),
            'logo'=>['@type'=>'ImageObject','url'=>asset('assets/img/favicon.png')],
            'description'=>\App\Support\SiteCopy::get('brand.description'),
            'telephone'=>'+19294699864',
            'address'=>['@type'=>'PostalAddress','streetAddress'=>'200 Orient Ave STE 2B','addressLocality'=>'Jersey City','addressRegion'=>'NJ','postalCode'=>'07305','addressCountry'=>'US'],
            'contactPoint'=>['@type'=>'ContactPoint','contactType'=>'customer service','telephone'=>'+19294699864','url'=>url('/contact')],
            'sameAs'=>['https://www.facebook.com/profile.php?id=61590448962752','https://www.instagram.com/peytonghalibexpress/','https://www.tiktok.com/@peytonghalib','https://www.pinterest.com/peytonexpress/']];
        @endphp
        <script type="application/ld+json">{!! json_encode($schemaOrg, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) !!}</script>
        <!-- Meta Pixel Code -->
<script>
// Meta's standard snippet, expanded so the stub half stays synchronous and only
// the fbevents.js fetch is deferred. fbq() therefore exists immediately and any
// call made before the SDK lands — including the Purchase event on
// /thank-you — accumulates in n.queue and is replayed when it executes.
// Do not collapse this back into the one-line snippet: that version injects the
// script inline, and its `if(f.fbq)return` guard means a pre-defined stub would
// stop fbevents.js from ever loading.
!function (f, b, e, v) {
  if (f.fbq) return;
  var n = f.fbq = function () {
    n.callMethod ? n.callMethod.apply(n, arguments) : n.queue.push(arguments);
  };
  if (!f._fbq) f._fbq = n;
  n.push = n; n.loaded = !0; n.version = '2.0'; n.queue = [];
  window.pgDefer(function () {
    var t = b.createElement(e); t.async = !0; t.src = v;
    var s = b.getElementsByTagName(e)[0];
    s.parentNode.insertBefore(t, s);
  });
}(window, document, 'script', 'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', @json(config('services.meta.pixel_id')));
fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id={{ urlencode(config('services.meta.pixel_id')) }}&ev=PageView&noscript=1"
/></noscript>
<!-- End Meta Pixel Code -->
