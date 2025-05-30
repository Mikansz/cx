<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        {{ $head ?? '' }}
    </head>
    <body>
        {{ $slot }}

        @stack('scripts')

        <!-- Additional scripts -->
        <script defer>
            document.addEventListener('DOMContentLoaded', function() {
                // Add script loader for office location pages
                if (window.location.pathname.includes('/backoffice/offices/')) {
                    const script = document.createElement('script');
                    script.src = "{{ asset('js/location-search.js') }}";
                    document.body.appendChild(script);
                }
            });
        </script>
    </body>
</html>