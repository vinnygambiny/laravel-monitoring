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
                    {{ maxWaitTime }}
                </dd>
            </div>
        </div>
    </dl>
</template>

<script>
    import values from 'lodash/values.js';
    import keys from 'lodash/keys.js';
    import { determinePeriod, readableTimeSeconds } from '@/utils.js';

    export default {
        data() {
            return {
                timeout: null,
                stats: {},
            };
        },

        computed: {
            maxWaitTime() {
                if (values(this.stats.wait)[0]) {
                    return readableTimeSeconds(values(this.stats.wait)[0]);
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

        methods: {
            async fetchStats() {
                const response = await this.$axios.get(route('horizon.stats.index'));

                this.stats = response.data || {};
            },

            refreshStats() {
                this.fetchStats();

                this.timeout = setTimeout(this.refreshStats, 5000);
            },
        },

        mounted() {
            this.refreshStats();
        },

        beforeDestroy() {
            clearTimeout(this.timeout);
        },
    };
</script>
