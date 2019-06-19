<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Google Calendar Integration</title>

        <!-- Styles -->
        <link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet">
    </head>
    <body>
        <div id="app">
            <div class="container mx-auto w-1/3 mt-12 border rounded">
                <div class="bg-gray-100 border-b p-3">
                    <h2>Calendar Integration</h2>
                </div>
                <div class="text-center py-8">
                    @if($integrated)
                        <a href="{{route('calendar.oauth.unregister')}}" class="rounded px-4 py-3 text-lg bg-red-500 hover:bg-red-600 text-white">
                            Unlink Calendar
                        </a>
                    @else
                        <a href="{{(new \onethirtyone\GoogleCalendar\Client)->authUrl()}}" class="rounded px-4 py-3 text-lg bg-blue-500 hover:bg-blue-600 text-white">
                           Link Calendar
                        </a>
                    @endif

                    <a href="{{route('calendar.webhook.register')}}" class="rounded px-4 py-3 text-lg bg-blue-500 hover:bg-blue-600 text-white">
                        Enable Webhooks
                    </a>
                </div>
            </div>
        </div>
    </body>
</html>
