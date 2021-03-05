<template>
    <dl class="grid grid-cols-1 gap-5 sm:grid-cols-4">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <dt class="text-sm font-medium text-gray-500 truncate">
                    Jobs per minute
                </dt>
                <dd class="mt-1 text-3xl font-semibold text-gray-900">
                    {{ stats.jobsPerMinute ? stats.jobsPerMinute.toLocaleString() : 0 }}
                </dd>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <dt class="text-sm font-medium text-gray-500 truncate" v-text="recentJobsPeriod" />
                <dd class="mt-1 text-3xl font-semibold text-gray-900">
                    {{ stats.recentJobs ? stats.recentJobs.toLocaleString() : 0 }}
                </dd>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <dt class="text-sm font-medium text-gray-500 truncate" v-text="failedJobsPeriod" />
                <dd class="mt-1 text-3xl font-semibold text-gray-900">
                    {{ stats.failedJobs ? stats.failedJobs.toLocaleString() : 0 }}
                </dd>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <dt class="text-sm font-medium text-gray-500 truncate">
                    Max wait time <span v-if="maxWaitQueue">({{ maxWaitQueue }})</span>
                </dt>
                <dd class="mt-1 text-3xl font-semibold text-gray-900">
                    {{ maxWaitTimeReadable }}
                </dd>
            </div>
        </div>
        <alert-modal :show="openAlertModal" @close="openAlertModal = false">
            <template #title>
                Attention
            </template>

            <template #content>
                The queue <strong>{{ maxWaitQueue }}</strong> has a max wait time of <strong>{{ maxWaitTimeReadable }}</strong>
            </template>

            <template #footer>
                <button class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150" type="button" @click="snoozeAlert">
                    Snooze 5 minutes
                </button>

                <button class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150" type="button" @click="openAlertModal = false">
                    Close
                </button>
            </template>
        </alert-modal>
    </dl>
</template>

<script>
    import values from 'lodash/values.js';
    import keys from 'lodash/keys.js';
    import { determinePeriod, readableTimeSeconds } from '@/utils.js';
    import AlertModal from '@/components/modal/AlertModal.vue';

    export default {
        components: { AlertModal },

        props: {
            thresholdMaxWaitTime: {
                type: Number,
                default: 300,
            },
        },

        data() {
            return {
                timeout: null,
                timeoutSnooze: null,
                stats: {},
                openAlertModal: false,
                alertSnoozed: false,
            };
        },

        computed: {
            maxWaitTime() {
                return values(this.stats.wait)[0];
            },

            maxWaitTimeReadable() {
                if (this.maxWaitTime) {
                    return readableTimeSeconds(this.maxWaitTime);
                }

                return '-';
            },

            maxWaitQueue() {
                if (values(this.stats.wait)[0]) {
                    return keys(this.stats.wait)[0].split(':')[1];
                }

                return '';
            },

            recentJobsPeriod() {
                return !this.stats.periods
                    ? 'Jobs past hour'
                    : `Jobs past ${determinePeriod(this.stats.periods.recentJobs)}`;
            },

            failedJobsPeriod() {
                return !this.stats.periods
                    ? 'Failed jobs past 7 days'
                    : `Failed jobs past ${determinePeriod(this.stats.periods.failedJobs)}`;
            },
        },

        watch: {
            maxWaitTime: {
                immediate: true,
                handler() {
                    this.openAlertModal = this.maxWaitTime >= this.thresholdMaxWaitTime && !this.alertSnoozed;
                },
            },
        },

        methods: {
            async fetchStats() {
                const response = await this.$axios.get(route('horizon.stats.index'));

                this.stats = response.data || {};
            },

            refreshStats() {
                this.fetchStats();

                this.timeout = setTimeout(this.refreshStats, 5000);
            },

            snoozeAlert() {
                this.openAlertModal = false;
                this.alertSnoozed = true;

                this.timeoutSnooze = setTimeout(() => { this.alertSnoozed = false }, 300000);
            },
        },

        mounted() {
            this.refreshStats();
        },

        beforeDestroy() {
            clearTimeout(this.timeout);
            clearTimeout(this.timeoutSnooze);
        },
    };
</script>
