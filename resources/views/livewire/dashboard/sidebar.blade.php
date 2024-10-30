<div :class="sidebarOpen ? 'translate-x-0 ease-out' : '-translate-x-full ease-in'"
    class="fixed inset-y-0 left-0 z-30 w-64 overflow-y-auto transition duration-300 transform p-2.5 lg:translate-x-0 lg:static lg:inset-0">
    <div class="bg-violet-950 h-full rounded-lg">
        <div class="p-2.5">
            <div class="flex items-center gap-2 justify-center ">
                <div class="px-1 pt-1 bg-white rounded-lg overflow-hidden">
                    <img src="assets/img/logo.png" class="w-16">
                </div>
                <div class="flex flex-col gap-1 h-full w-1">
                    <div class="py-2 bg-orange-500"></div>
                    <div class="py-2 bg-green-500"></div>
                    <div class="py-2 bg-blue-500"></div>
                </div>
                <div>
                    <h3 class="text-gray-100 text-xl font-extrabold">SysAsis</h3>
                    <div class="text-xs text-gray-400">Sistema para Control de Asistencias</div>
                </div>
            </div>
        </div>
        <nav class="mt-2 px-2 space-y-3">
            <x-nav-sidebar :active="request()->routeIs('dashboard')"
                href="{{ route('dashboard') }}">
                <i class="fa-solid fa-chart-pie fa-fw"></i>
                <span class="text-sm">Dashboard</span>
            </x-nav-sidebar>

            <x-nav-sidebar :active="request()->routeIs('groups')"
                href="{{ route('groups') }}">
                <i class="fa-solid fa-cubes fa-fw"></i>
                <span class="text-sm">Grupos</span>
            </x-nav-sidebar>

            <x-nav-sidebar :active="request()->routeIs('members')"
                href="{{ route('members') }}">
                <i class="fa-solid fa-users fa-fw"></i>
                <span class="text-sm">Miembros</span>
            </x-nav-sidebar>

            <x-nav-sidebar :active="request()->routeIs('attendance')"
                href="{{ route('attendance') }}">
                <i class="fa-solid fa-list-check fa-fw"></i>
                <span class="text-sm">Registro</span>
            </x-nav-sidebar>

        </nav>
    </div>
</div>
