<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            max-width: 800px;
            margin: auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0;
            width: 100%;
        }

        header img {
            max-width: 150px;
            /* Tamaño máximo del logo */
            height: auto;
        }

        #fecha {
            color: #000;
            /* Color de texto negro */
        }

        main {
            padding: 1px 70px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 8px;
            text-align: left;
        }

        footer {
            text-align: center;
            padding: 10px;
            color: #333;
            position: absolute;
            bottom: 0;
            width: 100%;
        }

        @page {
            header: page-header;
            footer: page-footer;
        }

        td.fitwidth {
            width: 1px;
            white-space: nowrap;
        }

        p {
            margin-left: 28px;
            font-size: 10px;
        }

        ul li {
            margin-left: 10px;
            font-size: 10px;
        }

        h4 {
            margin: 12px 20px 10px; font-style: italic; font-size: 10px
        }

        h5 {
            margin: 12px 0 10px; font-style: italic; font-size: 12px
        }

        .page-break {
            page-break-after: always;
        }

        header .top-bar {
            background-color: #316fb2;
            width: 100%;
            height: 45px;
            display: flex;
        }

        table.patient td{
            border: 1px solid #565657;
            color: #565657;
        }

        table.scheduler thead tr{
            background-color: #316fb2;
        }
        table.scheduler thead th{
            color: white;
            border: 1px solid white;
        }

        table.scheduler tbody td{
            border: none;
            border-bottom: 1px solid #000;
        }

        /* .table-rounded {
            border-collapse: initial;
            border-spacing: 0;
        }

        th:first-child {
            border-top-left-radius: 7px;
        }

        th:nth-last-child(1) {
            border-top-right-radius: 7px;
        }

        .ultima_fila td:first-child {
            border-bottom-left-radius: 7px;
        }

        .ultima_fila td:nth-last-child(1) {
            border-bottom-right-radius: 7px;
        } */
    </style>
</head>

<body>
    <htmlpageheader name="page-header" style="width: 100%;">
        <header class="header">
            <div class="top-bar"></div>
            <div style="padding: 15px 70px 10px;">
                <img src="{{ asset('assets/images/dr_ozono.svg') }}" width="188" alt="Dr. Ozono"
                    style="margin-bottom: 10px">
                <div style="display: block;">
                    <div style="width:29.5%; height: 10px; background-color: #44a8dd; float: left"></div>
                    <div style="width:70.5%; height: 4px; background-color: #e4e8e9; float: right; margin-top: 3px;">
                    </div>
                </div>
            </div>
        </header>
    </htmlpageheader>

    <main>
        <!-- Contenido del informe aquí -->
        @yield('content')
    </main>
</body>

</html>
