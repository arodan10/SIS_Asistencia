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
    }

    public function render()
    {
        $firstDayOfMonth = Carbon::create($this->year, $this->month, 1);
        $totalDays = $firstDayOfMonth->daysInMonth;
        $startDay = $firstDayOfMonth->dayOfWeek; // 0=Domingo

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
        // Buscar el miembro por su ID
        $member = Attendance::where('member_id', $memberId)
            ->where('date', $this->year . '-' . $this->month . '-' . $this->day)
            ->first();

        // Cambiar el estado según el ciclo (P -> T -> F -> P)
        switch ($member->status) {
            case 'P':
                $member->status = 'T';
                break;
            case 'T':
                $member->status = 'F';
                break;
            default:
                $member->status = 'P';
                break;
        }

        // Guardar el nuevo estado en la base de datos (opcional, según tu lógica)
        $member->save();

        $this->attendances = Attendance::where('date', $this->year . '-' . $this->month . '-' . $this->day)->get();
    }

    public function import()
    {
        $this->validate([
            'archivo' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        // Crear instancia de AttendanceImport
        $attendanceImport = new AttendanceImport();
        Excel::import($attendanceImport, $this->archivo->getRealPath());

        // Obtener el group_id del primer miembro importado
        $groupId = $attendanceImport->groupId;

        $this->group_id = $groupId;

        $this->showModal = false;

        $this->dispatch('fileUploaded');

        session()->flash('success', 'Asistencias importadas correctamente.');
    }

    public function exportToPdf($group_id = null, $year, $month, $day)
    {
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
}
