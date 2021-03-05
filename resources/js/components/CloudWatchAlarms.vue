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
        <alert-modal :show="!!metricInAlarm.AlarmName" @close="closeAlertModal">
            <template #title>
                Attention
            </template>

            <template #content>
                The alarm <strong>{{ metricInAlarm.AlarmName }}</strong> is in alarm state
            </template>

            <template #footer>
                <button class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150" type="button" @click="snoozeAlert">
                    Snooze 5 minutes
                </button>

                <button class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150" type="button" @click="closeAlertModal">
                    Close
                </button>
            </template>
        </alert-modal>
    </section>
</template>

<script>
    import AlertModal from '@/components/modal/AlertModal.vue';

    export default {
        components: { AlertModal },

        data() {
            return {
                timeout: null,
                timeoutSnooze: null,
                metricInAlarm: {},
                alertSnoozed: false,
                metricAlarms: [],
            };
        },

        watch: {
            metricAlarms: {
                immediate: true,
                deep: true,
                handler() {
                    this.metricInAlarm = this.metricAlarms.find((metric) => {
                        return metric.StateValue == 'ALARM';
                    }) || {};
                },
            },
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

            snoozeAlert() {
                this.openAlertModal = false;
                this.alertSnoozed = true;

                this.timeoutSnooze = setTimeout(this.closeAlertModal, 300000);
            },

            closeAlertModal() {
                this.metricInAlarm = {};
            },
        },

        created() {
            this.refreshMetric();
        },

        beforeDestroy() {
            clearTimeout(this.timeout);
            clearTimeout(this.timeoutSnooze);
        },
    };
</script>
