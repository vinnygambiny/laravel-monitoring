<script type="text/ecmascript-6">
    import each from 'lodash/each.js';
    import Chart from 'chart.js';

    export default {
        props: {
            data: {
                type: Object,
                default: () => {},
            },
        },

        data() {
            return {
                context: null,
                chart: null,
            };
        },

        watch: {
            data: {
                immediate: true,
                deep: true,
                handler() {
                    if (this.chart) {
                        this.chart.data = this.data;
                        this.chart.update();
                    }
                },
            },
        },

        mounted() {
            this.context = this.$refs.canvas.getContext('2d');

            this.chart = new Chart(this.context, {
                type: 'line',
                options: {
                    legend: {
                        display: false,
                    },
                    animation: {
                        duration: 0,
                    },
                    scales: {
                        yAxes: [
                            {
                                ticks: {
                                    beginAtZero: true,
                                },
                                gridLines: {
                                    display: true,
                                },
                                beforeBuildTicks(scale) {
                                    const datasetMax = [];

                                    each(scale.chart.data.datasets, (dataset) => {
                                        datasetMax.push(...dataset.data);
                                    });

                                    const max = Math.max(...datasetMax);
                                    scale.max = parseFloat(max) + parseFloat(max * 0.25);
                                },
                            },
                        ],
                        xAxes: [
                            {
                                gridLines: {
                                    display: true,
                                },
                                afterTickToLabelConversion(data) {
                                    const xLabels = data.ticks;

                                    xLabels.forEach((labels, i) => {
                                        if (i % 6 != 0 && (i + 1) != xLabels.length) {
                                            xLabels[i] = '';
                                        }
                                    });
                                },
                            },
                        ],
                    },
                },
                data: this.data,
            });
        },

        beforeDestroy() {
            this.chart.destroy();
        },
    };
</script>

<template>
    <div style="position: relative;">
        <canvas ref="canvas" />
    </div>
</template>
