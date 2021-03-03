<template>
    <section aria-labelledby="section-alarms-title" class="px-4 py-5 sm:p-6 bg-white overflow-hidden shadow rounded-lg" v-if="metricAlarms.length">
        <h2 id="section-alarms-title" class="text-lg font-medium">
            CloudWatch Alarms
        </h2>
        <div class="-ml-2 space-y-2">
            <span class="ml-2 inline-flex items-center px-3 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800" :key="metricAlarm.AlarmName" v-for="metricAlarm in metricAlarms">
                <svg
                    class="-ml-1 mr-1.5 h-4 w-4"
                    :class="getColor(metricAlarm)"
                    fill="currentColor"
                    viewBox="0 0 8 8"
                >
                    <circle cx="4" cy="4" r="3" />
                </svg>
                {{ metricAlarm.AlarmName }}
            </span>
        </div>
    </section>
</template>

<script>
    export default {
        data() {
            return {
                timeout: null,
                metricAlarms: [],
            };
        },

        computed: {

        },

        methods: {
            async fetchMetric() {
                const response = await this.$axios.get(route('monitoring.api.cloudwatch.alarms'));

                this.metricAlarms = response.data || [];
            },

            refreshMetric() {
                this.fetchMetric();

                this.timeout = setTimeout(this.refreshMetric, 60000);
            },

            getColor(metric) {
                if (metric.StateValue == 'OK') {
                    return 'text-green-600';
                }

                if (metric.StateValue == 'ALARM') {
                    return 'text-red-600';
                }

                return 'text-gray-400';
            },
        },

        created() {
            this.refreshMetric();
        },

        beforeDestroy() {
            clearTimeout(this.timeout);
        },
    };
</script>
