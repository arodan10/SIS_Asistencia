<?php

namespace App\Imports;

use App\Models\Attendance;
use App\Models\Member;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AttendanceImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    protected $presentMembers = []; // Lista de IDs de miembros presentes en el archivo Excel
    public $groupIds = []; // Propiedad pública para almacenar los group_id procesados
    public $groupId;

    public function model(array $row)
    {
        // Busca el ID del miembro basado en el nombre
        $member = Member::where('firstname', $row['name'])->first();

        if (!$member) {
            return null; // Si no se encuentra el miembro, ignóralo
        }

        // Guarda el primer group_id
        if (!$this->groupId) {
            $this->groupId = $member->group_id;
        }

        // Convierte la fecha si es un número de serie de Excel
        $dateValue = $row['date'];
        if (is_numeric($dateValue)) {
            $fecha = Carbon::create(1899, 12, 31)->addDays($dateValue)->timezone('America/Lima')->format('Y-m-d');
        } else {
            $fecha = Carbon::parse($dateValue)->timezone('America/Lima')->format('Y-m-d');
        }

        // Guarda el ID del miembro presente
        $this->presentMembers[] = $member->id;

        // Almacena el group_id en la propiedad pública si aún no está registrado
        if (!in_array($member->group_id, $this->groupIds)) {
            $this->groupIds[] = $member->group_id;
        }

        // Verifica si ya existe el registro de asistencia para el miembro y la fecha
        $attendance = Attendance::where('member_id', $member->id)
            ->whereDate('date', $fecha)
            ->first();

        if ($attendance) {
            // Si el registro ya existe y hay cambios, actualiza
            if ($attendance->status !== $row['status'] || $attendance->study !== $row['study']) {
                $attendance->update([
                    'status' => $row['status'],
                    'study' => $row['study'],
                ]);
            }
        } else {
            // Si no existe, crea un nuevo registro
            Attendance::create([
                'member_id' => $member->id,
                'date' => $fecha,
                'status' => $row['status'],
                'study' => $row['study'],
            ]);
        }

        // Llama a un método para registrar las faltas al final de la importación
        $this->registerAbsences($member->group_id, $fecha);
    }

    protected function registerAbsences($groupId, $fecha)
    {
        // Obtiene los IDs de los miembros del grupo que no están en el archivo Excel
        $absentMembers = Member::where('group_id', $groupId)
            ->whereNotIn('id', $this->presentMembers) // Miembros que no están en la lista de presentes
            ->pluck('id');

        // Registra la falta para cada miembro ausente
        foreach ($absentMembers as $memberId) {
            $attendance = Attendance::where('member_id', $memberId)->whereDate('date', $fecha)->first();

            if (!$attendance) {
                // Si no existe, crea un nuevo registro de falta
                Attendance::create([
                    'member_id' => $memberId,
                    'date' => $fecha,
                    'status' => 'F', // Registra como "F" para falta
                    'study' => 3, // Define el valor de estudio según sea necesario
                ]);
            }
        }
    }
}
