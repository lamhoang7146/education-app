<script setup>
import {ref, onMounted, watch} from 'vue';
import Chart from 'chart.js/auto';
import Container from "./../Components/Container.vue";
import {Listbox, ListboxButton, ListboxOption, ListboxOptions} from "@headlessui/vue";
import {router} from "@inertiajs/vue3";
import {route} from "ziggy-js";
const params = route().params;
const props = defineProps({
    chartData: Object,
    categories:Object,
    topCourses: Object,
    selectedCategoryId: [Number, String, null]
});

let selectedCategory = ref(props.categories.find(item=> item.id === parseInt(params.category_courses_id)));
const categoryCoursesParams = ref([{id:null,name:'Select category'},...props.categories]);

watch(selectedCategory, (newCategory) => {
    router.get(route('analytics'), {
        category_courses_id: newCategory ? newCategory.id : null,
    },{
        preserveScroll: true,
    })
})
const chartRef = ref(null);
let barChart = null;

const pieChartRef = ref(null);
let pieChart = null;

const labels = [
    'January', 'February', 'March', 'April', 'May', 'June',
    'July', 'August', 'September', 'October', 'November', 'December'
];

const data = {
    labels: labels,
    datasets: [{
        label: ['Monthly Revenue (VND)'],
        data: props.chartData.data,
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
const config = {
    type: 'bar',
    data: data,
    options: {
        responsive: true,
        maintainAspectRatio: false,
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
                text: 'Monthly Revenue Analysis ' + new Date().getFullYear(),
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
                    },
                    // Định dạng hiển thị số VND
                    callback: function(value) {
                        return value.toLocaleString('vi-VN') + ' đ';
                    }
                }
            }
        }
    }
};

const pieData = {
    labels: props.chartData.courseDistribution.labels,
    datasets: [{
        label: 'Course Distribution',
        data: props.chartData.courseDistribution.data,
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
                text: 'Course Distribution by Category  ',
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
                <div class="flex justify-between items-center">
                    <h1 class="text-lg font-medium">Best selling courses</h1>
                        <Listbox v-model="selectedCategory">
                            <div class="relative">
                                <ListboxButton class="border-[1px] border-gray-200 rounded-md py-2 px-4 text-left min-w-56 text-sm">
                                    {{ selectedCategory?.name || 'Select category' }}
                                </ListboxButton>
                                <transition name="list">
                                    <ListboxOptions
                                        class="absolute top-[120%] bg-behind dark:dark-bg-behind rounded-md p-2 box-shadow-copy w-full z-30">
                                        <ListboxOption
                                            class="cursor-pointer p-2 text-sm hover:hover-selected dark:hover:dark-hover-selected transition rounded-md"
                                            v-for="item in categoryCoursesParams"
                                            :key="item.id"
                                            :value="item">
                                            {{ item.name }}
                                        </ListboxOption>
                                    </ListboxOptions>
                                </transition>
                            </div>
                        </Listbox>
                </div>

                <div>
                    <div class="px-2 py-4 grid grid-cols-[.8fr_3fr_.5fr]">
                        <div>Rank</div>
                        <div>Courses</div>
                        <div>Enrollment</div>
                    </div>
                    <div>
                        <div v-for="top in topCourses" class="border-t-[.5px] border-gray-200 py-4 px-2 grid grid-cols-[.8fr_3fr_.5fr] last:pb-0 text-sm">
                            <div>{{top.id}}</div>
                            <div class="line-clamp-1">{{top.title}}</div>
                            <div class="text-center">{{top.enrollment_count}}</div>
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
 .list-enter-from, .list-leave-to {
     transform: translateY(-10px);
     opacity: 0;
 }

.list-enter-to, .list-leave-from {
    transform: translateY(0px);
    opacity: 1;
}

.list-enter-active, .list-leave-active {
    transition: .2s ease-in-out;
}
</style>
