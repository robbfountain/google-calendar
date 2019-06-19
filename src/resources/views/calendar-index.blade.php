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
            <div class="container mx-auto w-1/3">
                This is the calendar index.
            </div>
        </div>
    </body>
</html>
