<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asistencias</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
        .status-presente {
            color: green;
            font-weight: bold;
        }
        .status-falta {
            color: red;
            font-weight: bold;
        }
        .status-tarde {
            color: orange;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>Registro de Asistencias</h1>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nombres y Apellidos</th>
                <th>Teléfono</th>
                <th>Estado</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            @php $index = 1; @endphp
            @foreach ($attendances as $attendance)
                <tr>
                    <td>{{ $index++ }}</td>
                    <td>{{ $attendance->member->firstname }} {{ $attendance->member->lastname }}</td>
                    <td>{{ $attendance->member->cellphone }}</td>
                    <td>
                        @if ($attendance->status === 'P')
                            <span class="status-presente">Presente</span>
                        @elseif ($attendance->status === 'F')
                            <span class="status-falta">Falta</span>
                        @elseif ($attendance->status === 'T')
                            <span class="status-tarde">Tarde</span>
                        @else
                            <span>Sin definir</span>
                        @endif
                    </td>
                    <td>{{ $attendance->date }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
