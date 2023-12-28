@extends('pdf.layout')

@section('content')
    <section style="padding: 0 20px">
        <h3 style="text-align: center">HISTORIA CLÍNICA</h3>
        <h5>Datos del paciente</h5>
        <table class="patient">
            <tbody>
                <tr>
                    <td><b>Nombre y Apellido: </b>{{ $clinicHistory['client']['names'] }}</td>
                    <td><b>Correo: </b>{{ $clinicHistory['client']['email'] }}</td>
                </tr>
                <tr>
                    <td><b>Teléfono: </b>{{ $clinicHistory['client']['phone'] }}</td>
                    <td><b>Fecha de Nacimiento: </b>{{ $clinicHistory['client']['birthday'] }}</td>
                </tr>
                <tr>
                    <td><b>
                        {{ ($clinicHistory['client']['document_type'] === 'cc') ? 'Cédula de ciudadanía' : '' }}
                        {{ ($clinicHistory['client']['document_type'] === 'ce') ? 'Cédula de extranjería' : '' }}
                        {{ ($clinicHistory['client']['document_type'] === 'passport') ? 'Pasaporte' : '' }}
                    </b>{{ $clinicHistory['client']['document_id'] }}</td>
                    <td><b>Fecha de creación: </b>{{ $clinicHistory['client']['created_at']->format('d/m/Y') }}</td>
                </tr>
            </tbody>
        </table>
        <h3 style="text-align: center; margin-top: 50px">Detalle de la historia clínica</h3>
        <h5>1.- Medidas Antropometricas:</h5>
        <ul>
            <li>Peso: {{ $clinicHistory['client']['lastMeasure'][0]['weight'] ?? '-' }}kg</li>
            <li>Talla: {{ $clinicHistory['client']['lastMeasure'][0]['size'] ?? '-' }}cm</li>
            <li>Presión arterial: {{ $clinicHistory['client']['lastMeasure'][0]['systolic'] ?? '-' }}/{{ $clinicHistory['client']['lastMeasure'][0]['diastolic'] ?? '-'}}</li>
            <li>Temperatura: {{ $clinicHistory['client']['lastMeasure'][0]['temperature'] ?? '-' }}</li>
            <li>Saturación: {{ $clinicHistory['client']['lastMeasure'][0]['saturation'] ?? '-' }}</li>
            <li>Frecuencia Respiratoria: {{ $clinicHistory['client']['lastMeasure'][0]['breathing_frequency'] ?? '-' }}</li>
        </ul>

        <h5>2.- Motivo de la consulta:</h5>
        <p>{{ $clinicHistory['consultation_reason'] }}</p>

        <h5>3.- Antecedentes personales:</h5>

        <h4>Alergias a medicamentos, alimentos:</h4>
        <p>{{ $clinicHistory['allergies'] }}</p>

        <h4>Hábitos:</h4>
        <ul>
            <li>Café: {{ $clinicHistory['drink_coffe'] ? 'Si' : 'No' }}</li>
            <li>Alcohol: {{ $clinicHistory['drink_alcohol'] ? 'Si' : 'No' }}</li>
            <li>Tabaco: {{ $clinicHistory['smoke'] ? 'Si' : 'No' }}</li>
            <li>Drogas: {{ $clinicHistory['use_drugs'] ? 'Si' : 'No' }}</li>
            <li>Medicamentos: {{ $clinicHistory['use_medicine'] ? 'Si' : 'No' }}</li>
            <li>Trastornos del sueño: {{ $clinicHistory['sleep_disorder'] ? 'Si' : 'No' }}</li>
        </ul>
        @if ($clinicHistory['use_medicine'])
            <p>Medicamentos: {{ $clinicHistory['which_medicine'] }}</p>
        @endif

        <h4>¿Cuántas veces hace la deposición al día?</h4>
        <p>{{ $clinicHistory['bowel_movements'] }}</p>

        @if ($clinicHistory['client']['sex'] == 'F')
            <h5>4.- Antecedentes Gineco-obstétricos:</h5>
            <ul>
                <li>
                    Menarquía: {{ $clinicHistory['menarche']
                    ? 'Si, fecha: ' . $clinicHistory['menarche_date']
                    : 'No' }}
                </li>
                <li>
                    FUM: {{ $clinicHistory['lmp']
                    ? 'Si, fecha: ' . $clinicHistory['lmp_date']
                    : 'No' }}
                </li>
                <li>
                    Gesta: {{ $clinicHistory['gestation_quantity']
                    ? 'Si, total: ' . $clinicHistory['gestation_quantity']
                    : 'No' }}
                </li>
                <li>
                    Parto: {{ $clinicHistory['birth']
                    ? 'Si, total: ' . $clinicHistory['birth_quantity']
                    : 'No' }}
                </li>
                <li>
                    Aborto: {{ $clinicHistory['abortion']
                    ? 'Si, total: ' . $clinicHistory['abortion_quantity']
                    : 'No' }}
                </li>
                <li>
                    Horizontal: {{ $clinicHistory['horizontal']
                    ? 'Si, total: ' . $clinicHistory['horizontal_quantity']
                    : 'No' }}
                </li>
                <li>
                    Cesárea: {{ $clinicHistory['c_section']
                    ? 'Si, total: ' . $clinicHistory['c_section_quantity']
                    : 'No' }}
                </li>
                <li>
                    Nº Hijos: {{ $clinicHistory['children'] }}
                </li>
                <li>
                    Vivos: {{ $clinicHistory['children_live'] }}
                </li>
                <li>
                    Muertos: {{ $clinicHistory['children_no_live'] }}
                </li>
            </ul>
        @else
            <h5>4.- Descendencia:</h5>
            <ul>
                <li>
                    Nº Hijos: {{ $clinicHistory['children'] }}
                </li>
                <li>
                    Vivos: {{ $clinicHistory['children_live'] }}
                </li>
                <li>
                    Muertos: {{ $clinicHistory['children_no_live'] }}
                </li>
            </ul>
        @endif

        <div class="page-break"></div>

        <h5>5.- Otros:</h5>
            <h4>¿Falleció algún familiar o amigo últimamente?</h4>
            <p>{{ $clinicHistory['recent_deaths'] }}</p>

            <h4>¿Usted guarda algún resentimiento, ira, rencor, o tiene problemas con alguien?</h4>
            <p>{{ $clinicHistory['emotional_issues'] }}</p>

            <h4>¿Cómo se siente usted en su trabajo?</h4>
            <p>{{ $clinicHistory['job_satisfaction'] }}</p>

            <h4>¿A qué le teme usted?</h4>
            <p>{{ $clinicHistory['fears'] }}</p>

            <h4>¿Qué aspecto le produce inseguridad?</h4>
            <p>{{ $clinicHistory['areas_of_insecurity'] }}</p>

            <h4>¿Usted prefiere la soledad?</h4>
            <p>{{ $clinicHistory['preference_for_solitude'] }}</p>

            <h4>¿Cuál es su comportamiento ante las penas?</h4>
            <p>{{ $clinicHistory['behavior_with_sorrows'] }}</p>

            <h4>¿A qué causas le atribuye la desesperación que le agobia?</h4>
            <p>{{ $clinicHistory['causes_of_desperation'] }}</p>

            <h4>Impresión diagnóstica</h4>
            <p>{{ $clinicHistory['diagnostic_impression'] }}</p>

            <h4>Exámenes complementarios</h4>
            <p>{{ $clinicHistory['complementary_exams'] }}</p>

            <h4>MÉDICO QUE REFIERE</h4>
            <p>{{ $clinicHistory['referring_physician'] }}</p>
    </section>
@endsection
