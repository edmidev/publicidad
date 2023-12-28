@include('./partials/html')

<head>
    @include('./partials/title-meta', ['title' => 'Administrar publicidad - Sistema'])

    @include('./partials/head-css')

    <!-- Daterangepicker css -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/daterangepicker/daterangepicker.css') }}">

    <!-- Vector Map css -->
    <link rel="stylesheet"
        href="{{ asset('assets/vendor/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css') }}">

    <style>
        .authentication-bg {
            background-image: url("{{ asset('assets/images/auth-bg.jpg') }}");
            background-size: cover;
            height: 100vh;
        }

        .vs__dropdown-toggle {
            border: none !important;
        }

        .pointer {
            cursor: pointer;
        }

        .fc-daygrid-dot-event {
            background-color: #3788d8 !important;
        }

        .dropzone__progress {
            display: none;
        }

        .v-select {
            padding: 1px 0 2px;
        }

        .invalid {
            border-color: var(--tz-form-invalid-border-color);
            padding-right: calc(1.5em + .9rem);
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23d03f3f'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23d03f3f' stroke='none'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(.375em + .225rem) center;
            background-size: calc(.75em + .45rem) calc(.75em + .45rem);
        }
        .card{
            box-shadow: none !important;
        }
        .vld-container{
            position: absolute;
            z-index: 99999;
        }
    </style>
    <!-- Select2 css -->
    <link href="{{ asset('assets/vendor/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />

    @vite('resources/js/app.js')
</head>

<body class="position-relative">
    <div id="app"></div>

    <!-- App js -->
    <script src="{{ asset('assets/js/app.min.js') }}" defer></script>

    @include('./partials/footer-scripts')

    <!--  Select2 Plugin Js -->
    <script src="{{ asset('assets/vendor/select2/js/select2.min.js') }}"></script>



</body>

</html>
