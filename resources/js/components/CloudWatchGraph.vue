<template>
    <section aria-labelledby="section-alarms-title" class="px-4 py-5 sm:p-6 bg-white overflow-hidden shadow rounded-lg">
        <h2 id="section-alarms-title" class="text-lg font-medium">
            CloudWatch Graph
        </h2>
        <div>
            <line-chart :data="chartData" ref="chart" />
        </div>
    </section>
</template>

<script>
    import map from 'lodash/map.js';
    import each from 'lodash/each.js';
    import { format, differenceInSeconds } from 'date-fns';

    import LineChart from '@/components/LineChart.vue';
    import { formatDateString } from '@/utils.js';

    export default {
        components: { LineChart },

        data() {
            return {
                timeout: null,
                datas: {},
            };
        },

        computed: {
            chartData() {
                return {
                    labels: map(this.datas[Object.keys(this.datas)[0]], (data) => format(formatDateString(data.Timestamp), 'HH:mm')),
                    datasets: this.datasets,
                };
            },

            datasets() {
                return map(this.datas, (graph, label) => {
                    return {
                        label,
                        data: map(graph, (data) => Math.round(data.Average)),
                        lineTension: 0,
                        backgroundColor: 'transparent',
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#818df8',
                        borderColor: '#818df8',
                        borderWidth: 2,
                    };
                }) || [];
            },
        },

        methods: {
            async fetchMetric() {
                const response = await this.$axios.get(route('monitoring.api.cloudwatch.graph'));

                this.datas = response.data || [];

                each(this.datas, (data, graph) => {
                    this.datas[graph] = data.sort((a, b) => differenceInSeconds(formatDateString(a.Timestamp), formatDateString(b.Timestamp)));
                });
            },

            refreshMetric() {
                this.fetchMetric();

                this.timeout = setTimeout(this.refreshMetric, 300000);
            },
        },

        mounted() {
            this.refreshMetric();
        },

        beforeDestroy() {
            clearTimeout(this.timeout);
        },
    };
</script>
