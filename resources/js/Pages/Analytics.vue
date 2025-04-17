<script setup>
import { ref, onMounted } from 'vue';
import Chart from 'chart.js/auto';
import Container from "./../Components/Container.vue";

const chartRef = ref(null);
const pieChartRef = ref(null);
let barChart = null;
let pieChart = null;

const labels = [
    'January', 'February', 'March', 'April', 'May', 'June',
    'July', 'August', 'September', 'October', 'November', 'December'
];

const data = {
    labels: labels,
    datasets: [{
        label: ['My First Dataset'],
        data: [65, 59, 80, 81, 56, 55, 40, 21, 34, 78, 88, 60],
        backgroundColor: [
            'rgba(255, 99, 132, 0.2)',
            'rgba(255, 159, 64, 0.2)',
            'rgba(255, 205, 86, 0.2)',
            'rgba(75, 192, 192, 0.2)',
            'rgba(54, 162, 235, 0.2)',
            'rgba(153, 102, 255, 0.2)',
            'rgba(201, 203, 207, 0.2)',
            'rgba(100, 149, 237, 0.2)',
            'rgba(255, 140, 0, 0.2)',
            'rgba(220, 20, 60, 0.2)',
            'rgba(0, 206, 209, 0.2)',
            'rgba(124, 252, 0, 0.2)'
        ],
        borderColor: [
            'rgb(255, 99, 132)',
            'rgb(255, 159, 64)',
            'rgb(255, 205, 86)',
            'rgb(75, 192, 192)',
            'rgb(54, 162, 235)',
            'rgb(153, 102, 255)',
            'rgb(201, 203, 207)',
            'rgb(100, 149, 237)',
            'rgb(255, 140, 0)',
            'rgb(220, 20, 60)',
            'rgb(0, 206, 209)',
            'rgb(124, 252, 0)'
        ],
        borderWidth: 1
    }]
};

// Dữ liệu cho biểu đồ pie
const pieData = {
    labels: ['Course A', 'Course B', 'Course C', 'Course D', 'Course E'],
    datasets: [{
        label: 'Course Distribution',
        data: [30, 25, 15, 20, 10],
        backgroundColor: [
            'rgba(255, 99, 132, 0.7)',
            'rgba(54, 162, 235, 0.7)',
            'rgba(255, 205, 86, 0.7)',
            'rgba(75, 192, 192, 0.7)',
            'rgba(153, 102, 255, 0.7)'
        ],
        borderColor: [
            'rgb(255, 99, 132)',
            'rgb(54, 162, 235)',
            'rgb(255, 205, 86)',
            'rgb(75, 192, 192)',
            'rgb(153, 102, 255)'
        ],
        borderWidth: 1
    }]
};

const config = {
    type: 'bar',
    data: data,
    options: {
        responsive: true,
        maintainAspectRatio: false, // Cho phép canvas co giãn tự do trong container
        layout: {
            padding: {
                top: 20,
                right: 20,
                bottom: 20,
                left: 20
            }
        },
        plugins: {
            legend: {
                position: 'top',
                labels: {
                    font: {
                        size: 14
                    }
                }
            },
            title: {
                display: true,
                text: 'Monthly Data Analysis',
                font: {
                    size: 20
                },
                padding: {
                    top: 10,
                    bottom: 30
                }
            }
        },
        scales: {
            x: {
                ticks: {
                    font: {
                        size: 12
                    }
                }
            },
            y: {
                beginAtZero: true,
                ticks: {
                    font: {
                        size: 12
                    }
                }
            }
        }
    }
};

const pieConfig = {
    type: 'pie',
    data: pieData,
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    font: {
                        size: 14
                    },
                },
            },
            title: {
                display: true,
                text: 'Course Distribution',
                font: {
                    size: 18
                },
                padding: {
                    top: 10,
                    bottom: 20
                }
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        const label = context.label || '';
                        const value = context.raw || 0;
                        const total = context.dataset.data.reduce((acc, val) => acc + val, 0);
                        const percentage = Math.round((value / total) * 100);
                        return `${label}: ${value} (${percentage}%)`;
                    }
                }
            }
        }
    }
};

onMounted(() => {
    // Khởi tạo biểu đồ bar
    const ctx = chartRef.value.getContext('2d');
    barChart = new Chart(ctx, config);

    // Khởi tạo biểu đồ pie
    const pieCtx = pieChartRef.value.getContext('2d');
    pieChart = new Chart(pieCtx, pieConfig);

    // Dọn dẹp khi component bị hủy
    return () => {
        if (barChart) {
            barChart.destroy();
        }
        if (pieChart) {
            pieChart.destroy();
        }
    };
});
</script>

<template>
        <Container class="w-full max-h-[70vh] relative">
            <canvas class="w-full h-full" ref="chartRef"></canvas>
        </Container>
    <div class="grid grid-cols-2 gap-x-5 mt-5 mb-5">
        <Container class="max-h-[53vh]">
            <canvas class="w-full h-full" ref="pieChartRef"></canvas>
        </Container>
        <Container>
            <div>
                <h1 class="text-lg font-medium">Top Scoring Users</h1>
                <div>

                </div>
                <div>
                    <div class="px-2 py-4 grid grid-cols-[.8fr_3fr_.8fr]">
                        <div>Rank</div>
                        <div>Student</div>
                        <div>Score</div>
                    </div>
                    <div>
                        <div class="border-t-[.5px] border-gray-200 py-4 px-2 grid grid-cols-[.8fr_3fr_.8fr] last:pb-0">
                            <div>1</div>
                            <div>Nguyễn Lâm Hoàng</div>
                            <div>98%</div>
                        </div>
                        <div class="border-t-[.5px] border-gray-200 py-4 px-2 grid grid-cols-[.8fr_3fr_.8fr] last:pb-0">
                            <div>1</div>
                            <div>Nguyễn Lâm Hoàng</div>
                            <div>98%</div>
                        </div>
                        <div class="border-t-[.5px] border-gray-200 py-4 px-2 grid grid-cols-[.8fr_3fr_.8fr] last:pb-0">
                            <div>1</div>
                            <div>Nguyễn Lâm Hoàng</div>
                            <div>98%</div>
                        </div>
                        <div class="border-t-[.5px] border-gray-200 py-4 px-2 grid grid-cols-[.8fr_3fr_.8fr] last:pb-0">
                            <div>1</div>
                            <div>Nguyễn Lâm Hoàng</div>
                            <div>98%</div>
                        </div>
                        <div class="border-t-[.5px] border-gray-200 py-4 px-2 grid grid-cols-[.8fr_3fr_.8fr] last:pb-0">
                            <div>1</div>
                            <div>Nguyễn Lâm Hoàng</div>
                            <div>98%</div>
                        </div>

                    </div>
                </div>
            </div>
        </Container>
    </div>
</template>

<style scoped>
canvas {
    display: block;
    width: 100% !important;
    height: 100% !important;
}
</style>
