<?php

namespace App\Livewire\Admin;

use App\Events\Broadcast;
use App\Events\DashboardSent;
use App\Livewire\Dashboard\Main;
use App\Models\Attendance;
use App\Models\Group;
use App\Models\Member;
use App\Models\Result;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;
use WireUi\Traits\WireUiActions;

class AttendanceMain extends Component
{
    use WithPagination;
    use WireUiActions;
    public $isOpenRelation = false,
        $isOpenMission = false;
    public $search, $group_id, $members;
    public $attendances, $studies;
    public $date, $dateLarge;
    #Relations vars
    public $nrelationGroups = 0,
        $nrelationFriends = 0;
    #Mission vars
    public $nmissionStudies = 0,
        $nmissionVisits = 0,
        $nmissionPublications = 0;

    public $month;
    public $year;
    public $day;

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

        // Actualizar la colección de miembros
        // $this->members = Member::where('firstname', 'LIKE', '%' . $this->search . '%')
        //     ->where('active', true)
        //     ->where('group_id', $this->group_id)
        //     ->get();

        $this->attendances = Attendance::where('date', $this->year . '-' . $this->month . '-' . $this->day)->get();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
}
