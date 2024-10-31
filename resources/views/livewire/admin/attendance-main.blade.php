@inject('carbon', 'Carbon\Carbon')
<div class="py-2">
    <div class="mx-6 mb-4">
        <h2 class="text-3xl font-bold text-gray-800">Miembros por grupos</h2>
        <div class="border-b-2 border-info-600 w-60 mt-2"></div>
    </div>
    <div class="mx-auto sm:px-6 lg:px-8">
        <div
            class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4 dark:bg-gray-800/50 dark:bg-gradient-to-bl">
            <div class="flex gap-5">
                <div class="max-w-80">
                    <div class="flex items-center gap-2">
                        <span class="text-gray-500 font-medium">Selecciona:</span>

                        <select wire:model.live="month" class="border rounded px-2 py-1 w-full max-w-28">
                            {{-- <option value="">Selecciona un mes</option> --}}
                            @for ($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}">
                                    {{ \Carbon\Carbon::create()->month($m)->format('F') }}</option>
                            @endfor
                        </select>

                        <select wire:model.live="year" class="border rounded px-2 py-1 w-full max-w-24">
                            {{-- <option value="">Selecciona un año</option> --}}
                            @for ($y = 2020; $y <= 2030; $y++)
                                <option value="{{ $y }}">{{ $y }}</option>
                            @endfor
                        </select>
                    </div>

                    <div class="mt-4" id="calendarContainer">
                        @if ($month && $year)
                            <table class="table-fixed w-full border border-collapse">
                                <thead>
                                    <tr>
                                        <th class="border">Dom</th>
                                        <th class="border">Lun</th>
                                        <th class="border">Mar</th>
                                        <th class="border">Mié</th>
                                        <th class="border">Jue</th>
                                        <th class="border">Vie</th>
                                        <th class="border">Sáb</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @for ($i = 0; $i < 6; $i++)
                                        <tr>
                                            @for ($j = 0; $j < 7; $j++)
                                                @php
                                                    $dayNumber = $i * 7 + $j - $startDay + 1;
                                                @endphp
                                                <td
                                                    class="border text-center {{ $dayNumber < 1 || $dayNumber > $totalDays ? 'text-gray-400' : '' }}">
                                                    @if ($dayNumber >= 1 && $dayNumber <= $totalDays)
                                                        <button wire:click="setDay({{ $dayNumber }})"
                                                            class="text-blue-500 hover:underline">{{ $dayNumber }}</button>
                                                    @endif
                                                </td>
                                            @endfor
                                        </tr>
                                    @endfor
                                </tbody>
                            </table>
                        @else
                            <p class="text-gray-400">Selecciona un mes y un año para ver el calendario.</p>
                        @endif
                    </div>
                </div>
                <div class="w-full">
                    <div class="bg-indigo-50 p-2 rounded-md mb-2">
                        <div class="flex flex-wrap justify-between gap-1 items-center">
                            <!-- Selector de Fecha en el lado derecho -->
                            <div class="flex-1">


                                <div class="flex items-center gap-2 mt-4">
                                    @if ($day)
                                        <x-native-select wire:model.live="group_id" class="w-48">
                                            <option value="">Seleccione grupo</option>
                                            @foreach ($groups as $group)
                                                <option value="{{ $group->id }}">{{ $group->name }}</option>
                                            @endforeach
                                        </x-native-select>
                                        <x-button sm primary label="Crear Asistencia" icon="plus"
                                            wire:click="createAttendance" />
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabla lista de items -->
                    <div class="flex flex-wrap gap-4 justify-center">
                        @if ($attendances && $attendances->isEmpty())
                            <div class="col-span-full text-center text-gray-500 font-bold">
                                No se creó asistencia
                            </div>
                        @else
                            @foreach ($attendances as $attendance)
                                <div class="w-[192px] relative justify-center">
                                    <div
                                        class="bg-blue-50 rounded-md shadow-lg h-20 motion-safe:hover:scale-[1.01] transition-all duration-250 focus:outline focus:outline-2 focus:outline-red-500">
                                        <div class="text-center font-bold text-indigo-800">
                                            {{ $attendance->member->firstname }},
                                            {{ $attendance->member->lastname }}</div>
                                        <div class="relative flex p-2 gap-4 text-xs">
                                            <div class="col-span-2">
                                                <div><i
                                                        class="text-indigo-400 fa-solid fa-mobile-screen-button text-xl h-6"></i>
                                                </div>
                                                <div><i
                                                        class="text-indigo-400 fa-solid fa-cake-candles text-xl h-6 {{ $carbon::parse($attendance->member->birthdate)->addYears($carbon::now()->format('Y') - $carbon::parse($attendance->member->birthdate)->format('Y'))->format('Y-m-d') <= $carbon::now()->format('Y-m-d') &&$carbon::parse($attendance->member->birthdate)->addYears($carbon::now()->format('Y') - $carbon::parse($attendance->member->birthdate)->format('Y'))->format('Y-m-d') >= $carbon::now()->subDay(7)->format('Y-m-d')? ' text-yellow-600': ' text-gray-500' }}"></i>
                                                </div>
                                            </div>
                                            <div class="col-span-3">
                                                <div class="h-6">{{ $attendance->member->cellphone }}</div>
                                                <div class="h-6">
                                                    {{ $carbon::parse($attendance->member->birthdate)->addYears($carbon::now()->format('Y') - $carbon::parse($attendance->member->birthdate)->format('Y'))->format('Y-m-d') <= $carbon::now()->format('Y-m-d') &&$carbon::parse($attendance->member->birthdate)->addYears($carbon::now()->format('Y') - $carbon::parse($attendance->member->birthdate)->format('Y'))->format('Y-m-d') >= $carbon::now()->subDay(7)->format('Y-m-d')? ' (SI)': ' (NO)' }}
                                                    {{ $carbon::parse($attendance->member->birthdate)->format('d/m') }}
                                                </div>
                                                <!-- Botón de estado de asistencia -->
                                                <div class="absolute col-span-3 text-center top-2 right-2">
                                                    @php
                                                        // $attendance = $member->attendance->first();
                                                        $color = match ($attendance->status ?? null) {
                                                            'P' => 'bg-green-500',
                                                            'T' => 'bg-yellow-500',
                                                            'F' => 'bg-red-500',
                                                            default => 'bg-gray-300',
                                                        };
                                                        $statusText = match ($attendance->status ?? null) {
                                                            'P' => 'Presente',
                                                            'T' => 'Tarde',
                                                            'F' => 'Falta',
                                                            default => 'Sin Estado',
                                                        };
                                                        $icon = match ($attendance->status ?? null) {
                                                            'P' => 'fa-check',
                                                            'T' => 'fa-clock',
                                                            'F' => 'fa-times',
                                                            default => 'fa-question',
                                                        };
                                                    @endphp
                                                    <button
                                                        wire:click="toggleAttendanceStatus({{ $attendance->member->id }})"
                                                        class="px-4 py-2 rounded font-semibold text-white {{ $color }} hover:opacity-90 transition">
                                                        <i class="fa {{ $icon }} fa-fw"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>

                    <div class="mt-2">
                        @if (!$attendances->count())
                            <x-alert title="* No existe ningun registro coincidente" info />
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
