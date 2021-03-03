<template>
    <ul class="divide-y divide-gray-200">
        <li class="py-4" :key="workload.name" v-for="workload in workloads">
            <div class="flex space-x-3">
                <div class="flex-1 space-y-1">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-medium">
                            {{ workload.name }}
                        </h3>
                        <p class="text-xs text-gray-500">
                            {{ formatTime(workload.wait) }}
                        </p>
                    </div>
                    <p class="text-xs text-gray-500">
                        Processes: {{ workload.processes }} | Jobs: {{ workload.length }}
                    </p>
                </div>
            </div>
        </li>
    </ul>
</template>

<script>
    import { readableTimeSeconds } from '@/utils.js';

    export default {
        data() {
            return {
                timeout: null,
                workloads: [],
            };
        },

        methods: {
            async fetchWorkloads() {
                const response = await this.$axios.get(route('horizon.workload.index'));

                this.workloads = response.data || [];
            },

            refreshWorkloads() {
                this.fetchWorkloads();

                this.timeout = setTimeout(this.refreshWorkloads, 5000);
            },

            formatTime(wait) {
                return wait ? readableTimeSeconds(wait) : 'A Few Seconds';
            },
        },

        mounted() {
            this.refreshWorkloads();
        },

        beforeDestroy() {
            clearTimeout(this.timeout);
        },
    };
</script>
