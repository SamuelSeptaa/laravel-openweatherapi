<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>{{ $title }}</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
            integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <link href="https://api.mapbox.com/mapbox-gl-js/v2.14.1/mapbox-gl.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.datatables.net/2.0.7/css/dataTables.dataTables.css" />

        <meta name="csrf-token" content="{{ csrf_token() }}">
        <style>
            /* HTML: <div class="loader"></div> */
            /* Preloder */

            #preloder {
                position: fixed;
                width: 100%;
                height: 100%;
                top: 0;
                left: 0;
                z-index: 999999;
                background: #ffffff;
            }

            .loader {
                width: 40px;
                height: 40px;
                position: absolute;
                top: 50%;
                left: 50%;
                margin-top: -13px;
                margin-left: -13px;
                border-radius: 60px;
                animation: loader 0.8s linear infinite;
                -webkit-animation: loader 0.8s linear infinite;
            }

            @keyframes loader {
                0% {
                    -webkit-transform: rotate(0deg);
                    transform: rotate(0deg);
                    border: 4px solid #f44336;
                    border-left-color: transparent;
                }

                50% {
                    -webkit-transform: rotate(180deg);
                    transform: rotate(180deg);
                    border: 4px solid #673ab7;
                    border-left-color: transparent;
                }

                100% {
                    -webkit-transform: rotate(360deg);
                    transform: rotate(360deg);
                    border: 4px solid #f44336;
                    border-left-color: transparent;
                }
            }

            @-webkit-keyframes loader {
                0% {
                    -webkit-transform: rotate(0deg);
                    border: 4px solid #f44336;
                    border-left-color: transparent;
                }

                50% {
                    -webkit-transform: rotate(180deg);
                    border: 4px solid #673ab7;
                    border-left-color: transparent;
                }

                100% {
                    -webkit-transform: rotate(360deg);
                    border: 4px solid #f44336;
                    border-left-color: transparent;
                }
            }

            .map {
                width: 100%;
                height: 100%;
                min-height: 500px;
                background-color: pink;
            }
        </style>
    </head>

    <body>
        <div id="preloder">
            <div class="loader"></div>
        </div>


        <div class="main-content">

            <section class="section">
                <div class="">
                    @if (auth()->user())
                        @include('navbar')
                    @endif
                </div>
                @yield('content')
            </section>
        </div>
    </body>
    <footer>
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
            integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
        </script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
            integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
        </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
            integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
        </script>
        <script src="https://api.mapbox.com/mapbox-gl-js/v2.14.1/mapbox-gl.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/vue@2.7.14/dist/vue.js"></script>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"
            integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/2.0.7/js/dataTables.js"></script>
        <script>
            const mapboxKey = '{{ env('MAPBOX_KEY') }}';

            function showLoading() {
                $(".loader").show();
                $("#preloder").delay(50).fadeIn("fast");
            }

            function hideLoading() {
                $(".loader").fadeOut();
                $("#preloder").delay(50).fadeOut("fast");
            }
            $(window).on('load', function() {
                $(".loader").fadeOut();
                $("#preloder").delay(200).fadeOut("slow");
            });
        </script>
        @isset($script)
            @include($script)
        @endisset
    </footer>


</html>
