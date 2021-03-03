<template>
    <ul class="divide-y divide-gray-200">
        <li class="py-4" :key="job.id" v-for="job in failedJobs">
            <div class="flex space-x-3">
                <div class="flex-1 space-y-1">
                    <div class="flex items-center justify-between">
                        <a class="text-sm font-medium" :href="`/horizon/failed/${job.id}`" target="_blank">{{ jobName(job) }}</a>
                        <p class="text-xs text-gray-500">
                            {{ failedAt(job) }}
                        </p>
                    </div>
                    <p class="text-xs text-gray-500">
                        Queue: {{ job.queue }} | Attempts: {{ job.payload.attempts }}
                    </p>
                </div>
            </div>
        </li>
    </ul>
</template>

<script>

    import { jobBaseName, readableTimestamp } from '@/utils.js';

    export default {
        data() {
            return {
                timeout: null,
                failedJobs: [],
            };
        },

        methods: {
            async fetchFailedJobs() {
                const response = await this.$axios.get(route('horizon.failed-jobs.index'));

                this.failedJobs = response.data?.jobs || {};
            },

            refreshFailedJobs() {
                this.fetchFailedJobs();

                this.timeout = setTimeout(this.refreshFailedJobs, 50000);
            },

            failedAt(job) {
                return readableTimestamp(job.failed_at);
            },

            jobName(job) {
                return jobBaseName(job.name);
            },
        },

        mounted() {
            this.refreshFailedJobs();
        },

        beforeDestroy() {
            clearTimeout(this.timeout);
        },
    };
</script>
