@section('header', __('Dashboard'))
@section('section', __('Dashboard'))

<div>

    <div>
        <div class="flex flex-col gap-4 mb-7">
            <!-- Contadores Principales -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
                <!-- Card Present -->
                <x-app.card>
                    <div class="flex flex-col items-center gap-2">
                        <!-- Contenedor del gráfico con porcentaje -->
                        <div class="chart-container">
                            <canvas id="presentChart"></canvas>
                            <div class="chart-percentage">{{ $presentPercentage }}%</div>
                        </div>

                        <!-- Etiqueta -->
                        <div class="text-lg text-green-500 font-bold">Presente</div>
                    </div>
                </x-app.card>


                <x-app.card>
                    <div class="flex flex-col items-center gap-2">
                        <!-- Contenedor del gráfico con porcentaje -->
                        <div class="chart-container">
                            <canvas id="lateChart"></canvas> <!-- ID debe coincidir con el JS -->
                            <div class="chart-percentage">{{ $latePercentage }}%</div>
                        </div>

                        <!-- Etiqueta -->
                        <div class="text-lg text-yellow-500 font-bold">TARDE</div>
                    </div>
                </x-app.card>

                <x-app.card>
                    <div class="flex flex-col items-center gap-2">
                        <div class="chart-container">
                            <canvas id="absentChart"></canvas>
                            <div class="chart-percentage">{{ $absentPercentage }}%</div>
                        </div>

                        <div class="text-lg text-red-600 font-bold">FALTA</div>
                    </div>
                </x-app.card>

                <x-app.card>
                    <div class="flex flex-col items-center gap-2">
                        <div class="chart-container">
                            <canvas id="justificationChart"></canvas>
                            <div class="chart-percentage">{{ $justificationPercentage }}%</div>
                        </div>

                        <div class="text-lg text-blue-600 font-bold">JUSTIFICACIONES</div>
                    </div>
                </x-app.card>

            </div>
        </div>

        <div class="flex flex-col gap-4 mb-7">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Responsive: una columna en pantallas pequeñas, tres en grandes -->
                <!-- Gráfico de Distribución -->
                <x-app.card class="col-span-1">
                    <div class="flex items-center justify-center doughnut-chart-container">
                        <canvas id="areaDistributionChart"></canvas>
                    </div>
                </x-app.card>

                <!-- Gráfico de Top Asistencias -->
                <x-app.card class="col-span-2">
                    <canvas id="workersChart" style="height: 400px;"></canvas>
                </x-app.card>
            </div>
        </div>


        <div class="mb-7">
            <x-app.card>
                <canvas id="attendanceTrendChart" style="height: 400px;"></canvas>
            </x-app.card>

            @push('js')
                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                <script>
                    document.addEventListener('DOMContentLoaded', () => {
                        const attendanceTrend = @json($attendanceTrend);

                        // Extraer datos para el gráfico
                        const periods = attendanceTrend.map(item => item.period);
                        const presentData = attendanceTrend.map(item => item.present);
                        const lateData = attendanceTrend.map(item => item.late);
                        const absentData = attendanceTrend.map(item => item.absent);
                        const justificationData = attendanceTrend.map(item => item.justifications);

                        const ctx = document.getElementById('attendanceTrendChart').getContext('2d');

                        new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: periods, // Periodos (e.g., meses o semanas)
                                datasets: [{
                                        label: 'Presentes',
                                        data: presentData,
                                        borderColor: 'rgba(75, 192, 192, 1)',
                                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                        fill: true,
                                        tension: 0.5, // Aumenta la curvatura
                                        pointRadius: 4, // Tamaño de los puntos
                                        pointBackgroundColor: 'rgba(75, 192, 192, 1)', // Color del punto
                                        pointHoverRadius: 6 // Tamaño del punto al pasar el cursor
                                    },
                                    {
                                        label: 'Tardanzas',
                                        data: lateData,
                                        borderColor: 'rgba(255, 206, 86, 1)',
                                        backgroundColor: 'rgba(255, 206, 86, 0.2)',
                                        fill: true,
                                        tension: 0.5, // Aumenta la curvatura
                                        pointRadius: 4,
                                        pointBackgroundColor: 'rgba(255, 206, 86, 1)',
                                        pointHoverRadius: 6
                                    },
                                    {
                                        label: 'Faltas',
                                        data: absentData,
                                        borderColor: 'rgba(255, 99, 132, 1)',
                                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                        fill: true,
                                        tension: 0.5,
                                        pointRadius: 4,
                                        pointBackgroundColor: 'rgba(255, 99, 132, 1)',
                                        pointHoverRadius: 6
                                    },
                                    {
                                        label: 'Justificaciones',
                                        data: justificationData,
                                        borderColor: 'rgba(54, 162, 235, 1)',
                                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                        fill: true,
                                        tension: 0.5,
                                        pointRadius: 4,
                                        pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                                        pointHoverRadius: 6
                                    }
                                ]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: {
                                    x: {
                                        title: {
                                            display: true,
                                            text: 'Periodo (Meses)'
                                        }
                                    },
                                    y: {
                                        beginAtZero: true,
                                        title: {
                                            display: true,
                                            text: 'Porcentaje (%)'
                                        }
                                    }
                                },
                                plugins: {
                                    tooltip: {
                                        callbacks: {
                                            label: function(tooltipItem) {
                                                return `${tooltipItem.dataset.label}: ${tooltipItem.raw}%`;
                                            }
                                        }
                                    },
                                    legend: {
                                        position: 'top'
                                    },
                                    title: {
                                        display: true,
                                        text: 'Tendencia de Asistencia a lo Largo del Tiempo'
                                    }
                                }
                            }
                        });
                    });
                </script>
            @endpush
        </div>

        <!-- Gráficos de Barras, Línea, y Circular -->
        <div class="grid md:grid-cols-2 gap-4 mb-7">
            <x-app.card>
                <div class="radar-chart-container">
                    <canvas id="attendanceRadarChart"></canvas>
                </div>
            </x-app.card>
            <x-app.card>
                <canvas id="polarAreaChart" style="height: 400px;"></canvas>
            </x-app.card>

        </div>


        {{-- <div>
            <x-app.card>
                <h2 class="text-lg font-bold mb-4">Top 5 Trabajadores por Área y Estado</h2>

                <div class="space-y-8">
                    @foreach ($workersByArea as $state => $areas)
                        <div>
                            <h3 class="text-md font-semibold text-gray-700">{{ $state }}</h3>
                            <div class="overflow-x-auto mt-2">
                                <table class="table-auto w-full border-collapse border border-gray-200">
                                    <thead class="bg-gray-500 text-white">
                                        <tr>
                                            <th class="border border-gray-300 px-4 py-2 text-left">Área</th>
                                            <th class="border border-gray-300 px-4 py-2 text-left">Trabajador</th>
                                            <th class="border border-gray-300 px-4 py-2 text-center">Cantidad</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($areas as $area => $workers)
                                            @foreach ($workers as $worker)
                                                <tr>
                                                    <td class="border border-gray-300 px-4 py-2">{{ $area }}</td>
                                                    <td class="border border-gray-300 px-4 py-2">{{ $worker['worker'] }}</td>
                                                    <td class="border border-gray-300 px-4 py-2 text-center">{{ $worker['count'] }}</td>
                                                </tr>
                                            @endforeach
                                        @empty
                                            <tr>
                                                <td colspan="3" class="border border-gray-300 px-4 py-2 text-center">
                                                    No hay datos disponibles para este estado.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endforeach
                </div>
            </x-app.card>
        </div> --}}

    </div>

    @push('js')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>
        <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-rounded-bar"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


        <script>
            document.addEventListener('DOMContentLoaded', function() {

                const createDoughnutChart = (id, percentage, color) => {
                    const ctx = document.getElementById(id).getContext('2d');
                    new Chart(ctx, {
                        type: 'doughnut',
                        data: {
                            datasets: [{
                                data: [percentage, 100 - percentage],
                                backgroundColor: [color,
                                    '#e5e5e5'
                                ], // Color principal y gris para el resto
                                borderWidth: 0,
                            }, ],
                        },
                        options: {
                            cutout: '80%', // Tamaño del agujero central
                            plugins: {
                                tooltip: {
                                    enabled: false
                                }, // Sin tooltip
                            },
                        },
                    });
                };

                createDoughnutChart('presentChart', {{ $presentPercentage }}, '#1ea32f'); // Amarillo para "Tarde"
                createDoughnutChart('lateChart', {{ $latePercentage }}, '#FFD700'); // Amarillo para "Tarde"
                createDoughnutChart('absentChart', {{ $absentPercentage }}, '#FF4500'); // Rojo para "Falta"
                createDoughnutChart('justificationChart', {{ $justificationPercentage }},
                    '#1E90FF'); // Azul para "Justificaciones"


                // Gráfico de Barras Apiladas por Área y Estado
                // Gráfico de Radar para Asistencia por Área y Estado
                // Gráfico de Radar para Asistencia por Área y Estado
                const attendanceRadarCtx = document.getElementById('attendanceRadarChart').getContext('2d');

                const attendanceRadarChart = new Chart(attendanceRadarCtx, {
                    type: 'radar',
                    data: {
                        labels: {!! json_encode(array_keys($attendanceData)) !!}, // Etiquetas de las áreas
                        datasets: [{
                                label: 'Presente',
                                data: {!! json_encode(array_column($attendanceData, 'Presente')) !!},
                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 2,
                                pointBackgroundColor: 'rgba(75, 192, 192, 1)',
                            },
                            {
                                label: 'Tarde',
                                data: {!! json_encode(array_column($attendanceData, 'Tarde')) !!},
                                backgroundColor: 'rgba(255, 206, 86, 0.2)',
                                borderColor: 'rgba(255, 206, 86, 1)',
                                borderWidth: 2,
                                pointBackgroundColor: 'rgba(255, 206, 86, 1)',
                            },
                            {
                                label: 'Falta',
                                data: {!! json_encode(array_column($attendanceData, 'Falta')) !!},
                                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                borderColor: 'rgba(255, 99, 132, 1)',
                                borderWidth: 2,
                                pointBackgroundColor: 'rgba(255, 99, 132, 1)',
                            },
                            {
                                label: 'Justificaciones',
                                data: {!! json_encode(array_column($attendanceData, 'Justificaciones')) !!},
                                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                borderColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 2,
                                pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                            },
                        ],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'right', // Posición de la leyenda en el lateral
                                labels: {
                                    boxWidth: 20, // Tamaño del recuadro de color en la leyenda
                                    font: {
                                        size: 12, // Tamaño de letra
                                    },
                                },
                            },
                            title: {
                                display: true,
                                text: 'Distribución de Asistencia por Área y Estado',
                                font: {
                                    size: 16, // Tamaño del título
                                },
                            },
                        },
                        scales: {
                            r: {
                                angleLines: {
                                    display: true, // Mostrar líneas angulares
                                },
                                suggestedMin: 0, // Valor mínimo sugerido
                                suggestedMax: Math.max(...{!! json_encode(array_column($attendanceData, 'Presente')) !!}) + 10, // Máximo dinámico
                                ticks: {
                                    beginAtZero: true, // Comenzar desde cero
                                    stepSize: 10, // Incremento en las marcas del eje
                                    font: {
                                        size: 12, // Tamaño de los números
                                    },
                                },
                                pointLabels: {
                                    font: {
                                        size: 12, // Tamaño de las etiquetas de las áreas
                                    },
                                },
                            },
                        },
                    },
                });


                // -------------
                const workersByArea = @json($workersByArea);
                const areas = Object.keys(workersByArea['Puntual'] || {});

                // Datos y nombres por estado
                const states = ['Puntual', 'Tarde', 'Falta', 'Justificaciones'];
                const colors = [{
                        bg: 'rgba(75, 192, 192, 0.6)',
                        border: 'rgba(75, 192, 192, 1)'
                    },
                    {
                        bg: 'rgba(255, 206, 86, 0.6)',
                        border: 'rgba(255, 206, 86, 1)'
                    },
                    {
                        bg: 'rgba(255, 99, 132, 0.6)',
                        border: 'rgba(255, 99, 132, 1)'
                    },
                    {
                        bg: 'rgba(54, 162, 235, 0.6)', // Color para Justificaciones
                        border: 'rgba(54, 162, 235, 1)'
                    }
                ];

                // Crear datasets para cada estado
                const datasets = states.map((state, index) => ({
                    label: state,
                    data: areas.map(area => workersByArea[state]?.[area]?.length || 0),
                    backgroundColor: colors[index].bg,
                    borderColor: colors[index].border,
                    borderWidth: 1,
                    tooltipInfo: areas.map(area =>
                        (workersByArea[state]?.[area] || []).map(worker => worker.worker).join(', ')
                    ),
                }));

                const ctx = document.getElementById('workersChart').getContext('2d');

                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: areas, // Nombres de las áreas
                        datasets // Conjunto de datos para los estados
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            x: {
                                title: {
                                    display: true,
                                    text: 'Áreas'
                                }
                            },
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Número de Trabajadores'
                                }
                            },
                        },
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    label: function(tooltipItem) {
                                        const {
                                            dataset,
                                            dataIndex
                                        } = tooltipItem;
                                        const names = dataset.tooltipInfo[dataIndex] || 'Sin datos';
                                        return `${dataset.label}: ${tooltipItem.raw} (${names})`;
                                    },
                                },
                            },
                            title: {
                                display: true,
                                text: 'Top Asistencia de Trabajadores'
                            },
                        },
                    },
                });



                // ---------

                const areaDistCtx = document.getElementById('areaDistributionChart').getContext('2d');

                // Calcular el total de trabajadores
                const labels = {!! json_encode(array_keys($areaDistribution)) !!};
                const data = {!! json_encode(array_values($areaDistribution)) !!};
                const totalWorkers = data.reduce((a, b) => a + b, 0); // Sumar los valores del dataset

                if (!labels.length || !data.length) {
                    console.error("No hay datos para el gráfico de distribución por áreas.");
                    return;
                }

                const areaDistChart = new Chart(areaDistCtx, {
                    type: 'doughnut',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: data,
                            backgroundColor: [
                                'rgba(75, 192, 192, 0.6)',
                                'rgba(255, 206, 86, 0.6)',
                                'rgba(255, 99, 132, 0.6)',
                                'rgba(54, 162, 235, 0.6)',
                                'rgba(153, 102, 255, 0.6)'
                            ],
                            hoverOffset: 10
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    font: {
                                        size: 12
                                    }
                                }
                            },
                            title: {
                                display: true,
                                text: 'Distribución de Trabajadores por Área',
                                font: {
                                    size: 16
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: (tooltipItem) => {
                                        const label = tooltipItem.label || '';
                                        const value = tooltipItem.raw || 0;
                                        const percentage = ((value / totalWorkers) * 100).toFixed(2);
                                        return `${label}: ${value} (${percentage}%)`;
                                    }
                                }
                            }
                        },
                        layout: {
                            padding: 10
                        }
                    },
                    plugins: [{
                        id: 'doughnutCenterText',
                        beforeDraw(chart) {
                            const {
                                width
                            } = chart;
                            const {
                                height
                            } = chart;
                            const ctx = chart.ctx;
                            ctx.restore();
                            const fontSize = (height / 450).toFixed(
                                2); // Ajusta el divisor para reducir el tamaño de la letra
                            ctx.font = `${fontSize}em sans-serif`;
                            ctx.textBaseline = 'middle';
                            const text = `${totalWorkers} Trabajadores`; // Texto a mostrar
                            const textX = Math.round((width - ctx.measureText(text).width) / 2);
                            const textY = height / 2;
                            ctx.fillText(text, textX, textY);
                            ctx.save();
                        }
                    }]
                });


            });
        </script>
    @endpush

    @push('js')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script> <!-- Adaptador de fechas -->

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const scatterData = {!! json_encode($scatterData) !!}; // Datos enviados desde Livewire

                // Agrupar datos por área
                const groupedData = scatterData.reduce((groups, item) => {
                    if (!groups[item.area]) {
                        groups[item.area] = [];
                    }
                    groups[item.area].push({
                        x: item.x,
                        y: item.y
                    });
                    return groups;
                }, {});

                // Crear datasets para el gráfico
                const datasets = Object.keys(groupedData).map(area => ({
                    label: area,
                    data: groupedData[area],
                    borderColor: `rgba(${Math.random() * 255}, ${Math.random() * 255}, ${Math.random() * 255}, 1)`,
                    backgroundColor: `rgba(${Math.random() * 255}, ${Math.random() * 255}, ${Math.random() * 255}, 0.5)`,
                }));

            });
        </script>
    @endpush


    <div>


        @push('js')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                // Datos dinámicos desde Livewire
                const attendanceData = @json($weeklyAttendanceData);

                // Extraer días y valores
                const days = Object.keys(attendanceData); // Lunes, Martes, etc.
                const totals = Object.values(attendanceData);

                // Colores para cada día
                const backgroundColors = [
                    'rgba(75, 192, 192, 0.6)', // Lunes
                    'rgba(255, 206, 86, 0.6)', // Martes
                    'rgba(255, 99, 132, 0.6)', // Miércoles
                    'rgba(54, 162, 235, 0.6)', // Jueves
                    'rgba(153, 102, 255, 0.6)', // Viernes
                    'rgba(255, 159, 64, 0.6)', // Sábado
                    'rgba(201, 203, 207, 0.6)'  // Domingo
                ];

                const ctx = document.getElementById('polarAreaChart').getContext('2d');

                new Chart(ctx, {
                    type: 'polarArea',
                    data: {
                        labels: days,
                        datasets: [{
                            label: 'Faltas por Día de la Semana',
                            data: totals,
                            backgroundColor: backgroundColors,
                            borderColor: backgroundColors.map(color => color.replace('0.6', '1')),
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom', // Leyenda en la parte inferior
                                labels: {
                                    font: {
                                        size: 12 // Ajustar tamaño de texto en la leyenda
                                    }
                                }
                            },
                            title: {
                                display: true,
                                text: 'Distribución de Faltas por Día de la Semana',
                                font: {
                                    size: 16
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return `${context.label}: ${context.raw} faltas`;
                                    }
                                }
                            }
                        },
                        layout: {
                            padding: {
                                top: 10,
                                bottom: 10
                            }
                        },
                        scales: {
                            r: {
                                ticks: {
                                    backdropColor: 'rgba(0, 0, 0, 0)' // Oculta el fondo detrás de los números
                                }
                            }
                        },
                        animation: {
                            animateScale: true, // Animar escala al cargar
                            animateRotate: true // Animar rotación al cargar
                        }
                    }
                });
            });
        </script>
        @endpush
    </div>

    <style>
        .chart-container-one {
            position: relative;
            width: 100%;
            /* Usar todo el ancho disponible */
            height: 0;
            /* Usar una proporción basada en el ancho */
            padding-bottom: 50%;
            /* Proporción de aspecto 2:1 */
        }

        /* Contenedor del gráfico circular */
        .chart-container {
            position: relative;
            width: 8rem;
            /* Ajusta el tamaño del círculo */
            height: 8rem;
        }

        /* Gráfico circular */
        .chart-container canvas {
            position: absolute;
            top: 0;
            left: 0;
            z-index: 1;
        }

        /* Texto del porcentaje */
        .chart-container .chart-percentage {
            position: absolute;
            top: 55%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 1.25rem;
            /* Ajusta el tamaño del texto */
            font-weight: bold;
            pointer-events: none;
            color: #333;
            /* Color del texto del porcentaje */
        }



        /* Tamaño del contenedor */
        .relative {
            position: relative;
        }

        .flex.items-center.justify-center {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .radar-chart-container {
            position: relative;
            width: 100%;
            /* Ocupa el ancho disponible */
            height: 400px;
            /* Altura inicial */
            max-width: 800px;
            /* Tamaño máximo opcional */
            margin: auto;
            /* Centrar el gráfico */
        }

        @media screen and (max-width: 768px) {
            .radar-chart-container {
                height: 300px;
                /* Reducir tamaño en pantallas más pequeñas */
            }
        }

        .doughnut-chart-container {
            position: relative;
            width: 100%;
            /* Ajuste de ancho responsivo */
            max-width: 400px;
            /* Tamaño máximo opcional */
            height: 400px;
            /* Altura fija inicial */
            margin: auto;
            /* Centrar el gráfico en su contenedor */
        }

        @media screen and (max-width: 768px) {
            .doughnut-chart-container {
                height: 300px;
                /* Reducir tamaño en pantallas más pequeñas */
            }
        }

        canvas {
            display: block;

            /* Centrar el canvas */
        }
    </style>

</div>
