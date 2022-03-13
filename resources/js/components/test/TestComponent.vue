<template>
    <div class="doctor-page" style="width: 300px; height: 300px;">
        <apexchart type="donut" width="500" height="500" :options="chartOptions" :series="series"></apexchart>
    </div>
</template>

<script>
    export default {
        props:[],

        data: function() {
            return {
                chartOptions: {
                    chart: {
                    type: 'donut',
                    },
                    responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 200
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                    }]
                },
                series: [44, 55, 41, 17, 15],
            }
        },

    }
</script>