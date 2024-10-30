@section('header', __('Dashboard'))
@section('section', __('Dashboard'))

<div class="mx-auto">
    <div class="">
        <div class="grid md:grid-cols-2 xl:grid-cols-4 gap-4">
            <x-app.card>
                <div class="flex items-center gap-3">
                    <div class="p-3 bg-green-600 bg-opacity-75 rounded-lg">
                        <i class="text-white fa-regular fa-face-smile-beam text-2xl"></i>
                    </div>
                    <div>
                        <h5 class="text-lg font-medium text-gray-700">42%</h5>
                        <div class="text-green-600 font-bold">PRESENTE</div>
                    </div>
                </div>
            </x-app.card>
            <x-app.card>
                <div class="flex items-center gap-3">
                    <div class="p-3 bg-yellow-500 bg-opacity-75 rounded-lg">
                        <i class="text-white fa-regular fa-clock text-2xl"></i>
                    </div>

                    <div>
                        <h5 class="text-lg font-medium text-gray-700">12%</h5>
                        <div class="text-yellow-500 font-bold">TARDE</div>
                    </div>
                </div>
            </x-app.card>

            <x-app.card>
                <div class="flex items-center gap-3">
                    <div class="p-3 bg-red-600 bg-opacity-75 rounded-lg">
                        <i class="text-white fa-regular fa-face-frown-open text-2xl"></i>
                    </div>

                    <div>
                        <h5 class="text-lg font-medium text-gray-700">25%</h5>
                        <div class="text-red-600 font-bold">FALTA</div>
                    </div>
                </div>
            </x-app.card>

            <x-app.card>
                <div class="flex items-center gap-3">
                    <div class="p-3 bg-violet-700 bg-opacity-75 rounded-lg">
                        <i class="text-white fa-solid fa-users text-2xl"></i>
                    </div>

                    <div>
                        <h5 class="text-lg font-medium text-gray-700">152</h5>
                        <div class="text-violet-700 font-bold">MIEMBROS</div>
                    </div>
                </div>
            </x-app.card>
        </div>
    </div>

    <div class="grid md:grid-cols-2 gap-4">
        <x-app.card>
            <div id="container-bar"></div>
        </x-app.card>
        <x-app.card>
            <div id="container-pie"></div>
        </x-app.card>
    </div>
    {{-- <div  class="flex flex-col mt-8 mx-4">
        <div class="py-2 -my-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
            <div
                class="inline-block min-w-full overflow-hidden align-middle border-b border-gray-200 shadow sm:rounded-lg">
                <table class="min-w-full">
                    <thead>
                        <tr>
                            <th
                                class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 bg-gray-50">
                                Name</th>
                            <th
                                class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 bg-gray-50">
                                Title</th>
                            <th
                                class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 bg-gray-50">
                                Status</th>
                            <th
                                class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 bg-gray-50">
                                Role</th>
                            <th class="px-6 py-3 border-b border-gray-200 bg-gray-50"></th>
                        </tr>
                    </thead>

                    <tbody class="bg-white">
                        <tr>
                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                <div class="flex items-center gap-3">
                                    <div class="flex-shrink-0 w-10 h-10">
                                        <img class="w-10 h-10 rounded-lg"
                                            src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=2&amp;w=256&amp;h=256&amp;q=80"
                                            alt="">
                                    </div>

                                    <div class="ml-4">
                                        <div class="text-sm font-medium leading-5 text-gray-900">John Doe
                                        </div>
                                        <div class="text-sm leading-5 text-gray-500">john@example.com</div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                <div class="text-sm leading-5 text-gray-900">Software Engineer</div>
                                <div class="text-sm leading-5 text-gray-500">Web dev</div>
                            </td>

                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                <span
                                    class="inline-flex px-2 text-xs font-semibold leading-5 text-green-800 bg-green-100 rounded-lg">Active</span>
                            </td>

                            <td
                                class="px-6 py-4 text-sm leading-5 text-gray-500 whitespace-no-wrap border-b border-gray-200">
                                Owner</td>

                            <td
                                class="px-6 py-4 text-sm font-medium leading-5 text-right whitespace-no-wrap border-b border-gray-200">
                                <a href="#" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                <div class="flex items-center gap-3">
                                    <div class="flex-shrink-0 w-10 h-10">
                                        <img class="w-10 h-10 rounded-lg"
                                            src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=2&amp;w=256&amp;h=256&amp;q=80"
                                            alt="">
                                    </div>

                                    <div class="ml-4">
                                        <div class="text-sm font-medium leading-5 text-gray-900">John Doe
                                        </div>
                                        <div class="text-sm leading-5 text-gray-500">john@example.com</div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                <div class="text-sm leading-5 text-gray-900">Software Engineer</div>
                                <div class="text-sm leading-5 text-gray-500">Web dev</div>
                            </td>

                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                <span
                                    class="inline-flex px-2 text-xs font-semibold leading-5 text-green-800 bg-green-100 rounded-lg">Active</span>
                            </td>

                            <td
                                class="px-6 py-4 text-sm leading-5 text-gray-500 whitespace-no-wrap border-b border-gray-200">
                                Owner</td>

                            <td
                                class="px-6 py-4 text-sm font-medium leading-5 text-right whitespace-no-wrap border-b border-gray-200">
                                <a href="#" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                <div class="flex items-center gap-3">
                                    <div class="flex-shrink-0 w-10 h-10">
                                        <img class="w-10 h-10 rounded-lg"
                                            src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=2&amp;w=256&amp;h=256&amp;q=80"
                                            alt="">
                                    </div>

                                    <div class="ml-4">
                                        <div class="text-sm font-medium leading-5 text-gray-900">John Doe
                                        </div>
                                        <div class="text-sm leading-5 text-gray-500">john@example.com</div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                <div class="text-sm leading-5 text-gray-900">Software Engineer</div>
                                <div class="text-sm leading-5 text-gray-500">Web dev</div>
                            </td>

                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                <span
                                    class="inline-flex px-2 text-xs font-semibold leading-5 text-green-800 bg-green-100 rounded-lg">Active</span>
                            </td>

                            <td
                                class="px-6 py-4 text-sm leading-5 text-gray-500 whitespace-no-wrap border-b border-gray-200">
                                Owner</td>

                            <td
                                class="px-6 py-4 text-sm font-medium leading-5 text-right whitespace-no-wrap border-b border-gray-200">
                                <a href="#" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                <div class="flex items-center gap-3">
                                    <div class="flex-shrink-0 w-10 h-10">
                                        <img class="w-10 h-10 rounded-lg"
                                            src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=2&amp;w=256&amp;h=256&amp;q=80"
                                            alt="">
                                    </div>

                                    <div class="ml-4">
                                        <div class="text-sm font-medium leading-5 text-gray-900">John Doe
                                        </div>
                                        <div class="text-sm leading-5 text-gray-500">john@example.com</div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                <div class="text-sm leading-5 text-gray-900">Software Engineer</div>
                                <div class="text-sm leading-5 text-gray-500">Web dev</div>
                            </td>

                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                <span
                                    class="inline-flex px-2 text-xs font-semibold leading-5 text-green-800 bg-green-100 rounded-lg">Active</span>
                            </td>

                            <td
                                class="px-6 py-4 text-sm leading-5 text-gray-500 whitespace-no-wrap border-b border-gray-200">
                                Owner</td>

                            <td
                                class="px-6 py-4 text-sm font-medium leading-5 text-right whitespace-no-wrap border-b border-gray-200">
                                <a href="#" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                <div class="flex items-center gap-3">
                                    <div class="flex-shrink-0 w-10 h-10">
                                        <img class="w-10 h-10 rounded-lg"
                                            src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=2&amp;w=256&amp;h=256&amp;q=80"
                                            alt="">
                                    </div>

                                    <div class="ml-4">
                                        <div class="text-sm font-medium leading-5 text-gray-900">John Doe
                                        </div>
                                        <div class="text-sm leading-5 text-gray-500">john@example.com</div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                <div class="text-sm leading-5 text-gray-900">Software Engineer</div>
                                <div class="text-sm leading-5 text-gray-500">Web dev</div>
                            </td>

                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                <span
                                    class="inline-flex px-2 text-xs font-semibold leading-5 text-green-800 bg-green-100 rounded-lg">Active</span>
                            </td>

                            <td
                                class="px-6 py-4 text-sm leading-5 text-gray-500 whitespace-no-wrap border-b border-gray-200">
                                Owner</td>

                            <td
                                class="px-6 py-4 text-sm font-medium leading-5 text-right whitespace-no-wrap border-b border-gray-200">
                                <a href="#" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                <div class="flex items-center gap-3">
                                    <div class="flex-shrink-0 w-10 h-10">
                                        <img class="w-10 h-10 rounded-lg"
                                            src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=2&amp;w=256&amp;h=256&amp;q=80"
                                            alt="">
                                    </div>

                                    <div class="ml-4">
                                        <div class="text-sm font-medium leading-5 text-gray-900">John Doe
                                        </div>
                                        <div class="text-sm leading-5 text-gray-500">john@example.com</div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                <div class="text-sm leading-5 text-gray-900">Software Engineer</div>
                                <div class="text-sm leading-5 text-gray-500">Web dev</div>
                            </td>

                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                <span
                                    class="inline-flex px-2 text-xs font-semibold leading-5 text-green-800 bg-green-100 rounded-lg">Active</span>
                            </td>

                            <td
                                class="px-6 py-4 text-sm leading-5 text-gray-500 whitespace-no-wrap border-b border-gray-200">
                                Owner</td>

                            <td
                                class="px-6 py-4 text-sm font-medium leading-5 text-right whitespace-no-wrap border-b border-gray-200">
                                <a href="#" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                <div class="flex items-center gap-3">
                                    <div class="flex-shrink-0 w-10 h-10">
                                        <img class="w-10 h-10 rounded-lg"
                                            src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=2&amp;w=256&amp;h=256&amp;q=80"
                                            alt="">
                                    </div>

                                    <div class="ml-4">
                                        <div class="text-sm font-medium leading-5 text-gray-900">John Doe
                                        </div>
                                        <div class="text-sm leading-5 text-gray-500">john@example.com</div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                <div class="text-sm leading-5 text-gray-900">Software Engineer</div>
                                <div class="text-sm leading-5 text-gray-500">Web dev</div>
                            </td>

                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                <span
                                    class="inline-flex px-2 text-xs font-semibold leading-5 text-green-800 bg-green-100 rounded-lg">Active</span>
                            </td>

                            <td
                                class="px-6 py-4 text-sm leading-5 text-gray-500 whitespace-no-wrap border-b border-gray-200">
                                Owner</td>

                            <td
                                class="px-6 py-4 text-sm font-medium leading-5 text-right whitespace-no-wrap border-b border-gray-200">
                                <a href="#" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                <div class="flex items-center gap-3">
                                    <div class="flex-shrink-0 w-10 h-10">
                                        <img class="w-10 h-10 rounded-lg"
                                            src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=2&amp;w=256&amp;h=256&amp;q=80"
                                            alt="">
                                    </div>

                                    <div class="ml-4">
                                        <div class="text-sm font-medium leading-5 text-gray-900">John Doe
                                        </div>
                                        <div class="text-sm leading-5 text-gray-500">john@example.com</div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                <div class="text-sm leading-5 text-gray-900">Software Engineer</div>
                                <div class="text-sm leading-5 text-gray-500">Web dev</div>
                            </td>

                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                <span
                                    class="inline-flex px-2 text-xs font-semibold leading-5 text-green-800 bg-green-100 rounded-lg">Active</span>
                            </td>

                            <td
                                class="px-6 py-4 text-sm leading-5 text-gray-500 whitespace-no-wrap border-b border-gray-200">
                                Owner</td>

                            <td
                                class="px-6 py-4 text-sm font-medium leading-5 text-right whitespace-no-wrap border-b border-gray-200">
                                <a href="#" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div> --}}
    @push('js')
        <script src="https://code.highcharts.com/highcharts.js"></script>
        <script>
            // Bar Chart
            Highcharts.chart('container-bar', {
                chart: {
                    type: 'bar'
                },
                title: {
                    text: 'Bar Chart'
                },
                series: [{
                    name: 'Bar Chart',
                    data: {!! json_encode($barData) !!}
                }]
            });

            // Pie Chart
            Highcharts.chart('container-pie', {
                chart: {
                    type: 'pie'
                },
                title: {
                    text: 'Pie Chart'
                },
                series: [{
                    name: 'Pie Chart',
                    data: {!! json_encode($pieData) !!}
                }]
            });
        </script>
    @endpush
</div>
