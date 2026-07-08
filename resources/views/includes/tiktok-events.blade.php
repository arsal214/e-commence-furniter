{{--
    Browser twins of the server-side TikTok Events API calls.

    Each entry carries the exact event_id its server counterpart used
    (see App\Services\TikTokEventsService), which is what lets TikTok collapse
    the browser + server pair into one conversion instead of counting it twice.

    Queued by the controllers via TikTokEventsService::queueBrowserEvent().
--}}
@php($tiktokEvents = session(\App\Services\TikTokEventsService::BROWSER_QUEUE, []))

@if (!empty($tiktokEvents) && config('services.tiktok.pixel_id'))
    <!-- TikTok Events (deduplicated with server-side Events API) -->
    <script>
        (function () {
            var events = @json($tiktokEvents);
            events.forEach(function (e) {
                ttq.track(e.event, e.properties, { event_id: e.event_id });
            });
        })();
    </script>
@endif
