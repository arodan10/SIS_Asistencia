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
                                                    class="text-sm font-medium w-8 h-8 rounded-md {{ $dayNumber == $day ? 'text-white bg-gray-600' : 'hover:bg-gray-200 text-gray-700' }}">{{ $dayNumber }}</button>
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
            <div class="bg-gray-50 p-2 rounded-md mb-2">
                <div class="">
                    <!-- Selector de Fecha en el lado derecho -->
                    <div class="mb-2">
                        <h3 class="text-gray-800 font-bold">REGISTRO DE ASISTENCIA</h3>
                        <h5 class="text-gray-700 font-medium text-sm">{{ $day . ' ' . $meses[$month] . '. ' . $year }}
                        </h5>
                    </div>
                    <div class="grid grid-cols-9 items-center gap-2">
                        @if ($day)
                            <x-native-select wire:model.live="group_id" class="col-span-3">
                                <option value="">Seleccione grupo</option>
                                @foreach ($groups as $group)
                                    <option value="{{ $group->id }}">{{ $group->name }}</option>
                                @endforeach
                            </x-native-select>

                            <!-- Botón para Importar -->
                            <div class="col-span-2">
                                <!-- Botón para abrir el modal -->
                                <button wire:click="$set('showModal', true)"
                                    class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">
                                    Importar
                                </button>

                                <!-- Modal -->
                                <x-modal wire:model="showModal" id="attendanceModal">
                                    <form wire:submit.prevent="import"
                                        class="bg-white rounded-lg shadow-lg p-6 space-y-4"
                                        x-data="{ isDragging: false, fileName: '' }" x-on:drop.prevent="isDragging = false"
                                        x-on:dragleave="isDragging = false"
                                        x-on:dragover.prevent="isDragging = true">

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

                                                <div class="text-center text-gray-600 font-semibold mt-2"
                                                    wire:loading wire:target="file">
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

                            <!-- Botón para Exportar -->
                            <div class="col-span-2 relative inline-block text-left">
                                <div>
                                    <button type="button"
                                        class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-blue-600 text-white font-semibold hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                                        id="menu-button" aria-expanded="true" aria-haspopup="true">
                                        Exportar
                                        <!-- Icono de flecha hacia abajo -->
                                        <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd"
                                                d="M5.23 7.21a.75.75 0 011.06 0L10 10.293l3.71-3.07a.75.75 0 111.02 1.1l-4 3.333a.75.75 0 01-.99 0l-4-3.333a.75.75 0 010-1.1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>

                                <div class="absolute right-0 z-10 mt-2 w-20 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none hidden"
                                    role="menu" aria-orientation="vertical" aria-labelledby="menu-button"
                                    tabindex="-1" id="menu-options">
                                    <div class="py-1" role="none">
                                        <a target="_blank"
                                            href="{{ route('attendances.export.pdf', ['group_id' => $grupo_id ?? null, 'year' => $año ?? null, 'month' => $mes ?? null, 'day' => $dia ?? null]) }}"
                                            class="block w-full text-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            PDF
                                        </a>
                                        <button wire:click="exportToExcel"
                                            class="block w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                            role="menuitem">
                                            XLSX
                                        </button>
                                        <button wire:click="exportToCsv"
                                            class="block w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                            role="menuitem">
                                            CSV
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <script>
                                // Script para mostrar y ocultar el menú
                                document.addEventListener('DOMContentLoaded', () => {
                                    const button = document.getElementById('menu-button');
                                    const menu = document.getElementById('menu-options');

                                    button.addEventListener('click', () => {
                                        menu.classList.toggle('hidden');
                                    });

                                    // Cierra el menú al hacer clic fuera de él
                                    window.addEventListener('click', (event) => {
                                        if (!button.contains(event.target) && !menu.contains(event.target)) {
                                            menu.classList.add('hidden');
                                        }
                                    });
                                });
                            </script>

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
