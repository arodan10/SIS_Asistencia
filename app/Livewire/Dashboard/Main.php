<?php

namespace App\Livewire\Dashboard;

use App\Models\Attendance;
use App\Models\Group;
use App\Models\Member;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class Main extends Component
{
    use WithPagination;

    // Public properties for component state and data
    public $presentPercentage;            // Percentage of present workers
    public $latePercentage;               // Percentage of late workers
    public $absentPercentage;             // Percentage of absent workers
    public $totalWorkers;                 // Total number of workers
    public $justifiedPercentage;          // Percentage of justified absences
    public $attendanceData = [];          // Data for attendance visualization
    public $dailyAttendanceTrend = [];    // Daily attendance trend (for line charts)
    public $areaDistribution = [];        // Distribution of workers by area
    public $areaAttendanceComparison = []; // Attendance comparison across areas
    public $selectedGroupId;              // Selected group for filtering
    public $individualAttendance = [];    // Individual worker attendance data
    public $groups;                       // List of groups
    public $scatterData = [];             // Scatter plot data for absences
    public $selectedArea = null;          // Selected area for filtering
    public $selectedStatus = 'P';         // Default status for filtering ('P', 'T', 'F', or 'J')
    public $topWorkers = [];              // Top workers per area by attendance
    public $areas = [];                   // List of areas for filtering
    public $search = '';                  // Search term for filtering workers

    public $workers = [];                 // Filtered workers based on criteria
    public $workersByArea = [];           // Worker attendance by area and status
    public $attendanceTrend = [];         // Attendance trend over time

    // Modal state
    public $showModal = false;            // Controls modal visibility
    public $modalWorkers = [];            // Workers displayed in the modal
    public $totalJustifications;    // Attendance data for the selected worker
    public $justificationPercentage;

    // Livewire event listeners
    protected $listeners = ['openWorkerModal'];

    public $weeklyAbsences = [];

    public $weeklyAttendanceData = [];

    /**
     * Listener function to open a modal with worker data.
     *
     * @param array $workers
     */
    public function openWorkerModal($workers)
    {
        $this->modalWorkers = $workers;
        $this->showModal = true;
    }

    /**
     * Generates attendance trends over time for line charts.
     */
    public function generateAttendanceTrend()
    {
        $trendData = Attendance::selectRaw("
            DATE_FORMAT(date, '%Y-%m') as period,
            SUM(CASE WHEN status = 'P' THEN 1 ELSE 0 END) * 100.0 / COUNT(*) as present_percentage,
            SUM(CASE WHEN status = 'T' THEN 1 ELSE 0 END) * 100.0 / COUNT(*) as late_percentage,
            SUM(CASE WHEN status = 'F' THEN 1 ELSE 0 END) * 100.0 / COUNT(*) as absent_percentage,
            SUM(CASE WHEN status = 'J' THEN 1 ELSE 0 END) * 100.0 / COUNT(*) as justification_percentage
        ")
            ->groupByRaw("DATE_FORMAT(date, '%Y-%m')")
            ->orderByRaw("DATE_FORMAT(date, '%Y-%m') ASC")
            ->get();

        $this->attendanceTrend = $trendData->map(function ($item) {
            return [
                'period' => $item->period,
                'present' => round($item->present_percentage, 2),
                'late' => round($item->late_percentage, 2),
                'absent' => round($item->absent_percentage, 2),
                'justifications' => round($item->justification_percentage, 2), // Justificaciones
            ];
        })->toArray();
    }

    /**
     * Lifecycle hook for initializing component state.
     */

    public function loadWeeklyAttendanceData()
    {

        DB::statement("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");

        $this->weeklyAttendanceData = DB::table('attendances')
            ->selectRaw('DAYNAME(date) as day, COUNT(*) as total')
            ->where('status', 'F')
            ->groupByRaw('DAYNAME(date)')
            ->orderByRaw('FIELD(DAYNAME(date), "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday")')
            ->get()
            ->pluck('total', 'day')
            ->toArray();

        // Restaurar sql_mode
        DB::statement("SET SESSION sql_mode=(SELECT CONCAT(@@sql_mode, ',ONLY_FULL_GROUP_BY'));");

    }
    public function mount()
    {

        $this->loadWeeklyAttendanceData();
        $this->getTopWorkersByArea();
        $this->generateAttendanceTrend();
    }

    /**
     * Retrieves the top 5 workers per area for each attendance status.
     */
    public function getTopWorkersByArea()
    {
        $states = ['P' => 'Puntual', 'T' => 'Tarde', 'F' => 'Falta', 'J' => 'Justificaciones'];
        $result = [];

        foreach ($states as $status => $label) {
            $data = Attendance::join('members', 'attendances.member_id', '=', 'members.id')
                ->join('groups', 'members.group_id', '=', 'groups.id')
                ->selectRaw("groups.name as area, CONCAT(members.firstname, ' ', members.lastname) as worker, COUNT(attendances.id) as count")
                ->where('attendances.status', $status)
                ->groupBy('area', 'worker')
                ->orderBy('area')
                ->orderByDesc('count')
                ->get()
                ->groupBy('area')
                ->map(function ($group) {
                    return $group->take(5); // Limitar a los primeros 5
                })
                ->toArray();

            $result[$label] = $data;
        }

        $this->workersByArea = $result;
    }

    /**
     * Generates scatter plot data for absences grouped by area and day.
     */
    public function generateScatterData()
    {
        $results = Attendance::join('members', 'attendances.member_id', '=', 'members.id')
            ->join('groups', 'members.group_id', '=', 'groups.id')
            ->selectRaw("groups.name as area, DATE(attendances.created_at) as day, COUNT(*) as faltas")
            ->where('attendances.status', 'F')
            ->groupBy('groups.name', 'day')
            ->get();

        $this->scatterData = $results->map(function ($item) {
            return [
                'x' => $item->day,
                'y' => $item->faltas,
                'area' => $item->area,
            ];
        })->toArray();
    }

    /**
     * Renders the view with calculated data.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        if (!$this->groups) {
            $this->groups = Group::pluck('name', 'id')->toArray();
        }

        if (empty($this->scatterData)) {
            $this->generateScatterData();
        }

        $totalAttendances = Attendance::count();

        $presentCount = Attendance::where('status', 'P')->count();
        $lateCount = Attendance::where('status', 'T')->count();
        $absentCount = Attendance::where('status', 'F')->count();
        $justifiedCount = Attendance::where('status', 'J')->count();

        $this->presentPercentage = $totalAttendances > 0 ? round(($presentCount / $totalAttendances) * 100, 2) : 0;
        $this->latePercentage = $totalAttendances > 0 ? round(($lateCount / $totalAttendances) * 100, 2) : 0;
        $this->absentPercentage = $totalAttendances > 0 ? round(($absentCount / $totalAttendances) * 100, 2) : 0;
        $this->justifiedPercentage = $totalAttendances > 0 ? round(($justifiedCount / $totalAttendances) * 100, 2) : 0;

        $this->totalWorkers = Member::count();

        $this->attendanceData = Attendance::join('members', 'attendances.member_id', '=', 'members.id')
            ->join('groups', 'members.group_id', '=', 'groups.id')
            ->selectRaw('groups.name as area, status, COUNT(*) as count')
            ->groupBy('groups.name', 'status')
            ->get()
            ->groupBy('area')
            ->map(function ($item) {
                return [
                    'Presente' => $item->where('status', 'P')->sum('count'),
                    'Tarde' => $item->where('status', 'T')->sum('count'),
                    'Falta' => $item->where('status', 'F')->sum('count'),
                    'Justificaciones' => $item->where('status', 'J')->sum('count') // Agregamos las Justificaciones
                ];
            })
            ->toArray();


        $groups = Group::pluck('name', 'id')->toArray();

        $this->areaDistribution = Member::selectRaw('group_id, COUNT(*) as total')
            ->groupBy('group_id')
            ->get()
            ->mapWithKeys(function ($item) use ($groups) {
                return [$groups[$item->group_id] ?? 'Sin Grupo' => $item->total];
            })
            ->toArray();

        $this->areaAttendanceComparison = Attendance::selectRaw("
                group_id,
                SUM(CASE WHEN status = 'P' THEN 1 ELSE 0 END) * 100.0 / COUNT(*) as present_percentage,
                SUM(CASE WHEN status = 'T' THEN 1 ELSE 0 END) * 100.0 / COUNT(*) as late_percentage,
                SUM(CASE WHEN status = 'F' THEN 1 ELSE 0 END) * 100.0 / COUNT(*) as absent_percentage,
                SUM(CASE WHEN status = 'J' THEN 1 ELSE 0 END) * 100.0 / COUNT(*) as justified_percentage
            ")
            ->join('members', 'attendances.member_id', '=', 'members.id')
            ->groupBy('group_id')
            ->get()
            ->mapWithKeys(function ($item) use ($groups) {
                $groupName = $groups[$item->group_id] ?? 'Sin Grupo';
                return [
                    $groupName => [
                        'present_percentage' => $item->present_percentage,
                        'late_percentage' => $item->late_percentage,
                        'absent_percentage' => $item->absent_percentage,
                        'justified_percentage' => $item->justified_percentage,
                    ],
                ];
            })
            ->toArray();

        $this->areas = Group::pluck('name', 'id')->toArray();


        $totalAttendances = Attendance::count();
        $justificationCount = Attendance::where('status', 'J')->count();
        $this->justificationPercentage = $totalAttendances > 0 ? round(($justificationCount / $totalAttendances) * 100, 2) : 0;

        return view('livewire.dashboard.main', [
            'groups' => $this->groups,
            'presentPercentage' => $this->presentPercentage,
            'latePercentage' => $this->latePercentage,
            'absentPercentage' => $this->absentPercentage,
            'justifiedPercentage' => $this->justifiedPercentage,
            'totalWorkers' => $this->totalWorkers,
            'attendanceData' => $this->attendanceData,
            'dailyAttendanceTrend' => $this->dailyAttendanceTrend,
            'areaDistribution' => $this->areaDistribution,
            'areaAttendanceComparison' => $this->areaAttendanceComparison,
            'individualAttendance' => $this->individualAttendance,
            'scatterData' => $this->scatterData,
            'areas' => $this->areas,
            'workers' => $this->workers,
            'workersByArea' => $this->workersByArea,
            'polarChartData' => $this->weeklyAttendanceData,
        ]);
    }
}
