<?php

namespace App\Livewire\Admin;

use App\Imports\AttendanceImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Attendance;
use App\Models\Group;
use App\Models\Member;
use Carbon\Carbon;
use Exception;
use Livewire\Component;
use Livewire\WithPagination;
use WireUi\Traits\WireUiActions;
use Livewire\WithFileUploads;
use Dompdf\Dompdf;
use Dompdf\Options;

class AttendanceMain extends Component
{
    use WithPagination;
    use WireUiActions;
    use WithFileUploads;

    public $isOpenRelation = false,
        $isOpenMission = false;
    public $search, $group_id, $grupo_id, $members;
    public $attendances, $studies;
    public $date, $dateLarge;

    public $archivo;
    public $month, $mes;
    public $year, $año;
    public $day, $dia;


    public $selectedAttendanceId;
    public $justificationReason;

    public $showJustificationModal = false;

    public $showImportModal = false;

    public $isImportModalOpen = false;
    public $isJustificationModalOpen = false;


    public $meses = [
        1 => 'ene',
        2 => 'feb',
        3 => 'mar',
        4 => 'abr',
        5 => 'may',
        6 => 'jun',
        7 => 'jul',
        8 => 'ago',
        9 => 'sep',
        10 => 'oct',
        11 => 'nov',
        12 => 'dic'
    ];

    public $showModal = false;

    public function updatedMonth()
    {
        $this->validate(['month' => 'required|integer|min:1|max:12']);
        $this->render(); // Forzar el renderizado
    }

    public function updatedYear()
    {
        $this->validate(['year' => 'required|integer|min:2020|max:2030']);
        $this->render(); // Forzar el renderizado
    }

    public function updatedDay()
    {
        if ($this->day && $this->group_id) {
            $this->members = Member::where('group_id', $this->group_id)->get();
        }
    }

    public function setDay($day)
    {
        $this->day = $day;

        // Reiniciar miembros al cambiar de día o grupo
        $this->members = $members ?? collect();
    }

    public function mount()
    {
        // $this->attendances = Attendance::where('date', now()->toDateString())->pluck('study', 'member_id');
        $this->date = Carbon::now('America/Lima')->format('Y-m-d');
        $this->dateLarge = Carbon::now('America/Lima')->locale('es')->translatedFormat('l d \d\e F \d\e\l Y');
        $this->members = $members ?? collect();

        $this->month = Carbon::now('America/Lima')->month;
        $this->year = Carbon::now('America/Lima')->year;
        $this->day = Carbon::now('America/Lima')->day;
        $this->fetchAttendances();
    }

    public function render()
    {
        $firstDayOfMonth = Carbon::create($this->year, $this->month, 1);
        $totalDays = $firstDayOfMonth->daysInMonth;
        $startDay = $firstDayOfMonth->dayOfWeek; // 0=Domingo
        // Filtrar asistencias con estado 'J' (justificaciones)


        $this->members = Member::where('group_id', $this->group_id)->get();
        $this->attendances = collect();

        $this->grupo_id = $this->group_id;
        $this->año = $this->year;
        $this->mes = $this->month;
        $this->dia = $this->day;
        foreach ($this->members as $member) {
            $attendances = Attendance::where('member_id', $member->id)
                ->where('date', $this->year . '-' . $this->month . '-' . $this->day)
                ->get();
            $this->attendances = $this->attendances->merge($attendances);
        }
        $groups = Group::all();
        $leaders = Member::where('position', 'MAESTRO(A)')
            ->Where('group_id', $this->group_id)
            ->orWhere('position', 'ASOCIADO(A)')
            ->Where('group_id', $this->group_id)
            ->get();
        return view('livewire.admin.attendance-main', compact('groups', 'leaders', 'totalDays', 'startDay'));
    }


    public function createAttendance()
    {
        // Obtener todos los miembros que coinciden con los criterios de búsqueda
        $this->members = Member::where('firstname', 'LIKE', '%' . $this->search . '%')
            ->where('active', true)
            ->where('group_id', $this->group_id)
            ->get();

        // Verificar si existen miembros en la colección
        if ($this->members->isEmpty()) {
            $this->notification()->error('Sin miembros', 'No se encontraron miembros para registrar asistencia.');
            return;
        }

        try {
            foreach ($this->members as $member) {
                // Verificar si ya existe un registro de asistencia para el miembro en la fecha actual
                $attendance = Attendance::where('member_id', $member->id)
                    ->where('date', $this->year . '-' . $this->month . '-' . $this->day)
                    ->first();

                if ($attendance) {
                    // $this->notification()->error('Asistencia ya registrada', "El miembro {$member->firstname} ya tiene asistencia registrada para hoy.");
                } else {
                    // Crear un nuevo registro de asistencia
                    Attendance::create([
                        'member_id' => $member->id,
                        'status' => 'P',
                        'study' => 5,
                        'date' => $this->year . '-' . $this->month . '-' . $this->day,
                    ]);

                    // $this->notification()->success('Asistencia registrada', "La asistencia para {$member->firstname} ha sido registrada correctamente.");
                }
            }
            $this->notification()->success('Asistencias registradas', 'La asistencia para hoy día ha sido registrada correctamente.');
        } catch (Exception $e) {
            // Mostrar el mensaje específico del error en la notificación
            $this->notification()->error('Error al registrar asistencia', 'Ocurrió un error: ' . $e->getMessage());
        }
    }

    public function toggleAttendanceStatus($memberId)
    {
        // Buscar el registro de asistencia por miembro y fecha
        $attendance = Attendance::where('member_id', $memberId)
            ->where('date', "{$this->year}-{$this->month}-{$this->day}")
            ->first();

        if ($attendance) {
            // Cambiar el estado cíclicamente (P -> T -> F -> J -> P)
            switch ($attendance->status) {
                case 'P':
                    $attendance->status = 'T';
                    break;
                case 'T':
                    $attendance->status = 'F';
                    break;
                case 'F':
                    $attendance->status = 'J';
                    break;
                default:
                    $attendance->status = 'P';
                    break;
            }

            // Guardar cambios
            $attendance->save();

            // Si cambia a "Justificación", abre el modal de justificación
            if ($attendance->status === 'J') {
                $this->selectedAttendanceId = $attendance->id;
                $this->justificationReason = $attendance->justification_reason; // Cargar motivo si ya existe
                $this->isJustificationModalOpen = true; // Mostrar el modal de justificación
            }
        }

        // Actualizar la lista de asistencias
        $this->attendances = Attendance::whereDate('date', "{$this->year}-{$this->month}-{$this->day}")
            ->whereIn('member_id', $this->members->pluck('id'))
            ->get();
    }





    public function import()
    {
        $this->validate([
            'archivo' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        try {
            // Crear instancia de AttendanceImport
            $attendanceImport = new AttendanceImport();
            Excel::import($attendanceImport, $this->archivo->getRealPath());

            // Obtener el group_id del primer miembro importado
            $groupId = $attendanceImport->groupId;

            $this->group_id = $groupId;

            // Cerrar el modal de importación
            $this->isImportModalOpen = false;

            // Enviar un evento al frontend (si es necesario)
            $this->dispatch('fileUploaded');

            session()->flash('success', 'Asistencias importadas correctamente.');
            $this->notification()->success('Importación Completa', 'Las asistencias fueron importadas correctamente.');
        } catch (\Exception $e) {
            $this->notification()->error('Error en la Importación', $e->getMessage());
        }

        // Limpiar el archivo cargado
        $this->reset(['archivo']);
    }


    public function exportToPdf($group_id, $year, $month, $day)
    {
        if (!$group_id) {
            abort(400, 'Seleccione un grupo antes de exportar.');
        }

        // Consulta los miembros, o filtra si se ha pasado un ID específico
        $miembros = Member::where('group_id', $group_id)->get();

        // dd($year, $month, $day);
        // dd($miembros);
        if ($miembros->isEmpty()) {
            abort(404, 'No se encontraron miembros para el criterio especificado');
        }

        $attendances = collect();

        foreach ($miembros as $member) {
            // Ajusta la consulta para obtener las asistencias específicas para el miembro y la fecha
            $attendanceRecords = Attendance::where('member_id', $member->id)
                ->whereDate('date', "{$year}-{$month}-{$day}")
                ->get();

            $attendances = $attendances->merge($attendanceRecords);
        }

        // Configuración de Dompdf
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($options);

        // Cargar la vista de la plantilla para el PDF
        $html = view('exports.attendance_pdf', compact('attendances'))->render();

        // Cargar el contenido HTML en Dompdf
        $dompdf->loadHtml($html);

        // Configurar el tamaño y la orientación del papel
        $dompdf->setPaper('A4', 'portrait');

        // Renderizar el PDF
        $dompdf->render();

        // Salida del PDF al navegador
        $dompdf->stream('asistencias.pdf', ['Attachment' => false]);
    }

    public function exportToExcel()
    {
        // Lógica para exportar a Excel
    }

    public function exportToCsv()
    {
        // Lógica para exportar a CSV
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }


    // ----------------------
    public function fetchAttendances()
    {
        $this->members = Member::where('group_id', $this->group_id)->get();
        $this->attendances = Attendance::whereDate('date', "{$this->year}-{$this->month}-{$this->day}")
            ->whereIn('member_id', $this->members->pluck('id'))
            ->get();
    }


    public function setJustificationAttendanceId($attendanceId)
    {
        $this->selectedAttendanceId = $attendanceId;
    }
    public function saveJustification()
    {
        $this->validate([
            'justificationReason' => 'required|string|max:255',
        ]);

        $attendance = Attendance::find($this->selectedAttendanceId);

        if ($attendance) {
            $attendance->justification_reason = $this->justificationReason;
            $attendance->save();

            $this->reset(['justificationReason', 'selectedAttendanceId', 'isJustificationModalOpen']);
            $this->notification()->success('Justificación Registrada', 'El motivo ha sido guardado correctamente.');
        }
    }


    public function openImportModal()
    {
        $this->isImportModalOpen = true;
    }

    public function closeImportModal()
    {
        $this->isImportModalOpen = false;
    }
}
