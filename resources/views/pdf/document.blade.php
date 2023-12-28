@extends('pdf.layout')

@section('content')
    <h5 style="margin: 12px 0 10px; font-style: italic; font-size: 12px">Datos del paciente</h5>
    <table class="patient">
        <tbody>
            <tr>
                <td><b>Nombre y Apellido: </b>{{ $client->names }}</td>
                <td><b>Correo: </b>{{ $client->email }}</td>
            </tr>
            <tr>
                <td><b>Teléfono: </b>{{ $client->phone }}</td>
                <td><b>Fecha de Nacimiento: </b>{{ $client->birthday }}</td>
            </tr>
        </tbody>
    </table>
    <h3 style="text-align: center">Cronograma</h3>
    <table class="scheduler">
        <thead>
            <tr style="border: none">
                <th>Fecha</th>
                <th>Terapia</th>
                <th>Sesión</th>
                <th>Observaciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rows as $row)
                <tr>
                    <td class="fitwidth">{{ date('d/m/Y', strtotime($row->date)) }}</td>
                    <td class="fitwidth">{{ $row->therapy_name }} {{ $row->concentration }}</td>
                    <td class="fitwidth" style="text-align: center">{{ $row->session_number }}/{{ $row->sessions }}</td>
                    <td>{{ $row->observations }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
