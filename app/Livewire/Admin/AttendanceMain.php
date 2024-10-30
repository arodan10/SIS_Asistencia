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
        $this->attendances = Attendance::where('date', now()->toDateString())->pluck('study', 'member_id');
        $this->date = Carbon::now()->format('Y-m-d');
        $this->dateLarge = Carbon::now()->locale('es')->translatedFormat('l d \d\e F \d\e\l Y');
        $this->members = $members ?? collect();

        $this->month = Carbon::now()->month;
        $this->year = Carbon::now()->year;
        $this->day = Carbon::now()->day;
    }

    public function render()
    {
        $firstDayOfMonth = Carbon::create($this->year, $this->month, 1);
        $totalDays = $firstDayOfMonth->daysInMonth;
        $startDay = $firstDayOfMonth->dayOfWeek; // 0=Domingo

        $this->members = Member::where('group_id', $this->group_id)->get();

        $groups = Group::all();
        $leaders = Member::where('position', 'MAESTRO(A)')
            ->Where('group_id', $this->group_id)
            ->orWhere('position', 'ASOCIADO(A)')
            ->Where('group_id', $this->group_id)
            ->get();
        return view('livewire.admin.attendance-main', compact('groups', 'leaders', 'totalDays', 'startDay'));
    }

    public function store_study(Member $member, $days)
    {
        $fechaHoy = now()->toDateString();
        $registroExistente = Attendance::where('date', $fechaHoy)
            ->where('member_id', $member->id)
            ->first();
        if ($registroExistente) {
            $registroExistente->study = $days;
            $registroExistente->save();
            $this->notification()->send([
                'icon' => 'info',
                'title' => 'Dias de estudio de ' . $registroExistente->member->firstname . ' ' . $registroExistente->member->lastname . ' actualizado...',
            ]);
        } else {
            Attendance::create([
                'status' => 'P',
                'study' => $days,
                'date' => Carbon::now(),
                'member_id' => $member->id,
            ]);
            $this->notification()->send([
                'icon' => 'success',
                'title' => 'Dias de estudio de ' . $member->firstname . ' ' . $member->lastname . ' registrado...',
            ]);
        }

        $this->attendances = Attendance::where('date', now()->toDateString())->pluck('study', 'member_id');
    }

    public function createRelation()
    {
        $this->isOpenRelation = true;
        $exists = Result::where('date', now()->toDateString())
            ->where('group_id', $this->group_id)
            ->where('indicator_id', 2)
            ->first();
        //dd($exists);
        if ($exists) {
            $resultg = Result::where('date', now()->toDateString())
                ->where('group_id', $this->group_id)
                ->where('indicator_id', 2)
                ->first();
            $resultf = Result::where('date', now()->toDateString())
                ->where('group_id', $this->group_id)
                ->where('indicator_id', 3)
                ->first();
            $this->nrelationGroups = $resultg->quantity;
            $this->nrelationFriends = $resultf->quantity;
        } else {
            $this->nrelationGroups = 0;
            $this->nrelationFriends = 0;
        }
    }

    public function createMission()
    {
        $this->isOpenMission = true;
        $exists = Result::where('date', now()->toDateString())
            ->where('group_id', $this->group_id)
            ->where('indicator_id', 4)
            ->first();
        if ($exists) {
            $results = Result::where('date', now()->toDateString())
                ->where('group_id', $this->group_id)
                ->where('indicator_id', 4)
                ->first();
            $resultv = Result::where('date', now()->toDateString())
                ->where('group_id', $this->group_id)
                ->where('indicator_id', 5)
                ->first();
            $resultp = Result::where('date', now()->toDateString())
                ->where('group_id', $this->group_id)
                ->where('indicator_id', 6)
                ->first();
            $this->nmissionStudies = $results->quantity;
            $this->nmissionVisits = $resultv->quantity;
            $this->nmissionPublications = $resultp->quantity;
        } else {
            $this->nmissionStudies = 0;
            $this->nmissionVisits = 0;
            $this->nmissionPublications = 0;
        }
    }

    public function store_relation()
    {
        try {
            #Verificamos si existe el registro en la misma fecha
            $rEGroups = Result::where('date', now()->toDateString())
                ->where('group_id', $this->group_id)
                ->where('indicator_id', 2)
                ->first();
            $rEFriends = Result::where('date', now()->toDateString())
                ->where('group_id', $this->group_id)
                ->where('indicator_id', 3)
                ->first();
            #Si existe actualizamos los datos
            if (isset($rEGroups) || isset($rEFriends)) {
                $rEGroups->quantity = $this->nrelationGroups;
                $rEGroups->user_id = Auth::user()->id;
                $rEGroups->save();

                $rEFriends->quantity = $this->nrelationFriends;
                $rEFriends->user_id = Auth::user()->id;
                $rEFriends->save();

                $this->notification()->send([
                    'icon' => 'info',
                    'title' => 'Indicadores de comunión actualizados...',
                ]);
            }
            #Si no existe lo creamos
            if (!isset($rEGroups) && !isset($rEFriends)) {
                Result::create([
                    'date' => $this->date,
                    'quantity' => $this->nrelationGroups,
                    'group_id' => $this->group_id,
                    'indicator_id' => 2,
                    'user_id' => Auth::user()->id,
                ]);
                Result::create([
                    'date' => $this->date,
                    'quantity' => $this->nrelationFriends,
                    'group_id' => $this->group_id,
                    'indicator_id' => 3,
                    'user_id' => Auth::user()->id,
                ]);
                $this->notification()->send([
                    'icon' => 'success',
                    'title' => 'Indicadores de relación registrados...',
                ]);
            }
        } catch (Exception $e) {
            $this->notification()->send([
                'icon' => 'error',
                'title' => 'Error Notification!',
                'description' => $e->getMessage(),
            ]);
        }
        $this->reset(['isOpenRelation']);
        DashboardSent::dispatch($this->nrelationGroups);
    }

    public function store_mission()
    {
        //$this->validate();
        try {
            #Verificamos si existe el registro en la misma fecha
            $mEStudy = Result::where('date', now()->toDateString())
                ->where('group_id', $this->group_id)
                ->where('indicator_id', 4)
                ->first();
            $mEVisits = Result::where('date', now()->toDateString())
                ->where('group_id', $this->group_id)
                ->where('indicator_id', 5)
                ->first();
            $mEPublications = Result::where('date', now()->toDateString())
                ->where('group_id', $this->group_id)
                ->where('indicator_id', 6)
                ->first();

            #Si existe actualizamos los datos
            if (isset($mEStudy) || isset($mEVisits) || isset($mEPublications)) {
                $mEStudy->quantity = $this->nmissionStudies;
                $mEStudy->user_id = Auth::user()->id;
                $mEStudy->save();

                $mEVisits->quantity = $this->nmissionVisits;
                $mEVisits->user_id = Auth::user()->id;
                $mEVisits->save();

                $mEPublications->quantity = $this->nmissionPublications;
                $mEPublications->user_id = Auth::user()->id;
                $mEPublications->save();

                $this->notification()->send([
                    'icon' => 'info',
                    'title' => 'Indicadores de comunión actualizados...',
                ]);
            }

            #Si no existe lo creamos
            if (!isset($mEStudy) && !isset($mEVisits) && !isset($mEPublications)) {
                Result::create([
                    'date' => $this->date,
                    'quantity' => $this->nmissionStudies,
                    'group_id' => $this->group_id,
                    'indicator_id' => 4,
                    'user_id' => Auth::user()->id,
                ]);
                Result::create([
                    'date' => $this->date,
                    'quantity' => $this->nmissionVisits,
                    'group_id' => $this->group_id,
                    'indicator_id' => 5,
                    'user_id' => Auth::user()->id,
                ]);
                Result::create([
                    'date' => $this->date,
                    'quantity' => $this->nmissionPublications,
                    'group_id' => $this->group_id,
                    'indicator_id' => 6,
                    'user_id' => Auth::user()->id,
                ]);

                $this->notification()->send([
                    'icon' => 'success',
                    'title' => 'Indicadores de misión registrados...',
                ]);
            }
        } catch (Exception $e) {
            $this->notification()->send([
                'icon' => 'error',
                'title' => 'Error Notification!',
                'description' => $e->getMessage(),
            ]);
        }
        $this->reset(['isOpenMission']);
        DashboardSent::dispatch($this->nrelationGroups);
    }

    public function store_communion()
    {
        $registroExistente = Result::where('date', now()->toDateString())
            ->where('group_id', $this->group_id)
            ->where('indicator_id', 1)
            ->first();
        $quantity = Attendance::join('members', 'attendances.member_id', '=', 'members.id')
            ->where('group_id', $this->group_id)
            ->where('study', '>=', 6)
            ->count();
        if (isset($registroExistente)) {
            $registroExistente->quantity = $quantity;
            $registroExistente->user_id = Auth::user()->id;
            $registroExistente->save();
            $this->notification()->send([
                'icon' => 'info',
                'title' => 'Indicadores de comunión actualizados...',
            ]);
        } else {
            Result::create([
                'date' => $this->date,
                'quantity' => $quantity,
                'group_id' => $this->group_id,
                'indicator_id' => 1,
                'user_id' => Auth::user()->id,
            ]);
            $this->notification()->send([
                'icon' => 'success',
                'title' => 'Indicadores de comunión registrados...',
            ]);
        }
        DashboardSent::dispatch($quantity);
    }

    public function confirmCommunion()
    {
        $quantity = Attendance::join('members', 'attendances.member_id', '=', 'members.id')
            ->where('group_id', $this->group_id)
            ->where('study', '>=', 6)
            ->count();
        $this->dialog()->confirm([
            'icon' => 'info',
            'title' => '¿Deseas registrar (' . $quantity . ') en estudio diario de todo el grupo?',
            'description' => 'Recuerde que primero debe registrar el estudio individual',
            'acceptLabel' => 'Si, registrar',
            'method' => 'store_communion',
        ]);
    }

    public function updatingSearch()
    {
        $this->resetPage();
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
                    ->where('date', $this->date)
                    ->first();

                if ($attendance) {
                    // $this->notification()->error('Asistencia ya registrada', "El miembro {$member->firstname} ya tiene asistencia registrada para hoy.");
                } else {
                    // Crear un nuevo registro de asistencia
                    Attendance::create([
                        'member_id' => $member->id,
                        'status' => 'P',
                        'study' => 5,
                        'date' => $this->date,
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
        // $member = $this->members->where('id', $memberId)->first();
        $member = Attendance::where('member_id', $memberId)
            ->where('date', $this->date)
            ->first();
        // dd($member);
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
        // $this->members = Member::all();
        $this->members = Member::where('firstname', 'LIKE', '%' . $this->search . '%')
            ->where('active', true)
            ->where('group_id', $this->group_id)
            ->get();
    }
}
