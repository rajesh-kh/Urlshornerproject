<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->

        <!-- Inline fallback styles (works when Tailwind/Vite assets are not built) -->
        <style>
            /* Buttons and form controls */
            button, input[type="submit"], input[type="button"], a.btn, .btn {
                display: inline-block;
                padding: .375rem .75rem;
                border-radius: .25rem;
                text-decoration: none;
                font-weight: 500;
                border: 1px solid #d1d5db;
                background: #fff;
                color: #111827;
                cursor: pointer;
            }

            button:hover, a.btn:hover, .btn:hover { opacity: .95 }

            /* Primary and outline variants */
            .btn-primary, .btn-primary:link, .btn-primary:visited {
                background: #2563eb;
                color: #fff;
                border-color: #2563eb;
            }

            /* Danger variant (red border + text, white background) */
            .btn-danger, .btn-danger:link, .btn-danger:visited {
                background: #fff;
                color: #dc2626;
                border-color: #dc2626;
            }

            .btn-danger:hover { background:#fff8f8 }
            
            /* Blue variant: white background, blue text/border (for Generate/Invite/Download) */
            .btn-blue, .btn-blue:link, .btn-blue:visited {
                background: #fff;
                color: #2563eb;
                border-color: #2563eb;
            }

            .btn-blue:hover { background:#f0f6ff }

            .btn-outline, .btn-outline:link, .btn-outline:visited {
                background: transparent;
                color: #111827;
                border-color: #6b7280;
            }

            .text-sm{font-size:.875rem}
            .rounded{border-radius:.25rem}
            .border{border:1px solid #e5e7eb}
            .border-red-600{border-color:#dc2626}
            .text-red-600{color:#dc2626}

            /* Flex helpers */
            .flex{display:flex}
            .items-center{align-items:center}
            .justify-between{justify-content:space-between}
            .ml-auto{margin-left:auto}
            .gap-2{gap:.5rem}

            /* Layout utilities */
            .bg-white{background:#fff}
            .shadow{box-shadow:0 1px 2px rgba(0,0,0,.05)}
            .max-w-7xl{max-width:80rem}
            .mx-auto{margin-left:auto;margin-right:auto}
            .p-6{padding:1.5rem}
            .py-6{padding-top:1.5rem;padding-bottom:1.5rem}
        </style>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
