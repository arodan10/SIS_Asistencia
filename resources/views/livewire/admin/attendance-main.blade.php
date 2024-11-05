@inject('carbon', 'Carbon\Carbon')
@section('header', __('Registro'))
@section('section', __('Miembros por grupos'))

<div>
    <div class="grid grid-cols-7 gap-4">
        <x-app.card class="col-span-2">
            <div class="flex items-center justify-center gap-4">

                <div class="flex items-center gap-2 text-sm">
                    <span>Mes: </span>
                    <select wire:model.live="month"
                        class="border rounded-lg px-2 py-1 w-full  bg-gray-600 text-white text-sm">
                        {{-- <option value="">Selecciona un mes</option> --}}
                        @for ($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}">
                                {{ \Carbon\Carbon::create()->month($m)->format('F') }}</option>
                        @endfor
                    </select>
                </div>

                <div class="flex items-center gap-2 text-sm">
                    <span>Año: </span>
                    <select wire:model.live="year"
                        class="border rounded-lg px-2 py-1 w-full bg-gray-600 text-white text-sm">
                        {{-- <option value="">Selecciona un año</option> --}}
                        @for ($y = 2020; $y <= 2030; $y++)
                            <option value="{{ $y }}">{{ $y }}</option>
                        @endfor
                    </select>
                </div>
            </div>

            <div class="mt-4" id="calendarContainer">
                @if ($month && $year)
                    <table class="table-fixed w-full">
                        <thead>
                            <tr class="text-xs text-gray-500 border-b">
                                <th class="py-2">DO</th>
                                <th class="py-2">LU</th>
                                <th class="py-2">MA</th>
                                <th class="py-2">MI</th>
                                <th class="py-2">JU</th>
                                <th class="py-2">VI</th>
                                <th class="py-2">SA</th>
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
                                            class="text-center {{ $dayNumber < 1 || $dayNumber > $totalDays ? 'text-gray-400' : '' }}">
                                            @if ($dayNumber >= 1 && $dayNumber <= $totalDays)
                                                <button wire:click="setDay({{ $dayNumber }})"
                                                    class="text-sm font-normal w-8 h-8 rounded-md {{ $dayNumber == $day ? 'text-white bg-gray-600' : 'hover:bg-gray-200 text-gray-700' }}">{{ $dayNumber }}</button>
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
        </x-app.card>

        <x-app.card class="col-span-5">
            <div class="mb-2">
                <div class="">
                    <!-- Selector de Fecha en el lado derecho -->
                    <div class="flex justify-between">
                        <div class="mb-2">
                            <h3 class="text-gray-800 font-bold">REGISTRO DE ASISTENCIA</h3>
                            <h5 class="text-gray-700 font-medium text-sm">
                                {{ $day . ' ' . $meses[$month] . '. ' . $year }}
                            </h5>
                        </div>
                        <!-- Botón para Importar -->
                        <div class="">

                            @if (!$attendances->isEmpty())
                                <x-dropdown>
                                    <x-slot name="trigger">
                                        <x-button label="Exportar" green right-icon="arrow-down-tray" />
                                    </x-slot>

                                    <x-dropdown.item icon="document" label="PDF" target="_blank"
                                        href="{{ route('attendances.export.pdf', ['group_id' => $grupo_id ?? null, 'year' => $año ?? null, 'month' => $mes ?? null, 'day' => $dia ?? null]) }}" />
                                    <x-dropdown.item separator icon="document" label="Excel"
                                        wire:click="exportToExcel" />
                                    <x-dropdown.item separator icon="document" label="CSV"
                                        wire:click="exportToCsv" />
                                </x-dropdown>
                            @endif

                            <x-button wire:click="$set('showModal', true)" label="Importar" orange
                                right-icon="arrow-up-tray" />

                            <!-- Modal -->
                            <x-modal wire:model="showModal" id="attendanceModal">
                                <form wire:submit.prevent="import" class="bg-white rounded-lg shadow-lg p-6 space-y-4"
                                    x-data="{ isDragging: false, fileName: '' }" x-on:drop.prevent="isDragging = false"
                                    x-on:dragleave="isDragging = false" x-on:dragover.prevent="isDragging = true">

                                    <div>
                                        <label class="block text-gray-700 font-semibold mb-2">Subir
                                            archivo de asistencia:</label>
                                        <div class="relative border-2 rounded-lg p-4 transition-colors duration-300"
                                            :class="isDragging ? 'border-blue-400 bg-blue-50' :
                                                'border-dashed border-gray-300'"
                                            x-on:drop.prevent="isDragging = false; fileName = $event.dataTransfer.files[0].name; $refs.fileInput.files = $event.dataTransfer.files;">

                                            <input type="file" wire:model="archivo"
                                                class="absolute inset-0 opacity-0 w-full h-full cursor-pointer"
                                                x-ref="fileInput"
                                                x-on:change="fileName = $refs.fileInput.files[0]?.name" />

                                            <div class="text-center text-gray-500">
                                                <span class="text-sm" x-show="!fileName">Haz clic o
                                                    arrastra un archivo aquí</span>
                                                <span class="text-sm font-semibold text-blue-600"
                                                    x-show="fileName">Archivo cargado: <strong
                                                        x-text="fileName"></strong></span>
                                            </div>

                                            <div class="text-center text-gray-600 font-semibold mt-2" wire:loading
                                                wire:target="file">
                                                <span>Cargando archivo...</span>
                                            </div>
                                        </div>
                                        @error('archivo')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="mt-4">
                                        <button type="submit" wire:loading.attr="disabled"
                                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-300"
                                            wire:loading.class="bg-blue-400 cursor-not-allowed">
                                            <span wire:loading.remove>Importar Asistencias</span>
                                            <span wire:loading>Importando...</span>
                                        </button>
                                    </div>

                                    @if (session()->has('success'))
                                        <div
                                            class="text-green-600 bg-green-100 p-3 rounded-lg text-center font-semibold mt-4">
                                            {{ session('success') }}
                                        </div>
                                    @endif
                                </form>
                            </x-modal>
                        </div>
                    </div>
                    <div class="grid grid-cols-9 items-center gap-2">
                        @if ($day)
                            <x-native-select wire:model.live="group_id" class="col-span-7">
                                <option value="">Seleccione grupo</option>
                                @foreach ($groups as $group)
                                    <option value="{{ $group->id }}">{{ $group->name }}</option>
                                @endforeach
                            </x-native-select>

                            <x-button sm gray label="Crear Asistencia" icon="plus" class="h-full col-span-2"
                                wire:click="createAttendance" />

                        @endif
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
                    <table class="w-full table-auto">
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($attendances as $item => $attendance)
                                <tr class="text-sm text-gray-900 hover:bg-gray-100">
                                    <td class="px-3 py-2">
                                        {{ $item + 1 }}
                                    </td>
                                    <td class="px-3 py-2">
                                        {{ $attendance->member->document }}
                                    </td>
                                    <td class="px-3 py-2">{{ $attendance->member->firstname }}
                                        {{ $attendance->member->lastname }}
                                    </td>
                                    <td class="px-3 py-2">{{ $attendance->member->birthdate }}</td>
                                    <td class="px-3 py-2">
                                        <div class="text-center">
                                            @php
                                                $color = match ($attendance->status ?? null) {
                                                    'P' => 'text-green-500',
                                                    'T' => 'text-yellow-500',
                                                    'F' => 'text-red-500',
                                                    default => 'text-gray-300',
                                                };
                                                $statusText = match ($attendance->status ?? null) {
                                                    'P' => 'Presente',
                                                    'T' => 'Tarde',
                                                    'F' => 'Falta',
                                                    default => 'Sin Estado',
                                                };
                                                $icon = match ($attendance->status ?? null) {
                                                    'P' => 'fa-check',
                                                    'T' => 'fa-regular fa-clock',
                                                    'F' => 'fa-times',
                                                    default => 'fa-question',
                                                };
                                            @endphp
                                            <button wire:click="toggleAttendanceStatus({{ $attendance->member->id }})"
                                                class="p-1 rounded-lg font-semibold {{ $color }} active:bg-gray-200 transition">
                                                <i class="fa {{ $icon }} fa-fw fa-lg"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

            <div class="mt-2">
                @if (!$attendances->count())
                    <x-alert title="* No existe ningun registro coincidente" secondary />
                @endif
            </div>
        </x-app.card>
    </div>
</div>
