<template>
    <default>
        <div class="space-y-6 max-w-3xl mx-auto px-4 sm:px-6 lg:max-w-7xl lg:px-8">
            <div v-if="statusPages.length">
                <ul class="grid grid-cols-1 gap-5 sm:gap-6 sm:grid-cols-3 lg:grid-cols-5">
                    <li class="col-span-1" :key="statuspage" v-for="statuspage in statusPages">
                        <status-badge :status-page-id="statuspage" />
                    </li>
                </ul>
            </div>

            <div v-if="hasHorizon">
                <horizon-stats />
            </div>

            <div v-if="hasAws">
                <div class="grid grid-cols-1 gap-4 items-start lg:grid-cols-3 lg:gap-8">
                    <!-- Left column -->
                    <div class="grid grid-cols-1 gap-4 lg:col-span-2 h-full">
                        <cloud-watch-alarms />
                    </div>
                    <div class="grid grid-cols-1 gap-4 h-full">
                        <cloud-watch-graph />
                    </div>
                </div>
            </div>

            <!-- Main 3 column grid -->
            <div class="grid grid-cols-1 gap-4 items-start lg:grid-cols-3 lg:gap-8" v-if="hasHorizon">
                <!-- Left column -->
                <div class="grid grid-cols-1 gap-4 lg:col-span-2">
                    <section aria-labelledby="section-1-title">
                        <div class="rounded-lg bg-white overflow-hidden shadow">
                            <div class="p-6">
                                <h2 id="section-1-title" class="text-lg font-medium">
                                    <a :href="route('horizon.index', 'failed')" target="_blank">
                                        Failed Jobs
                                    </a>
                                </h2>
                                <div class="flow-root">
                                    <failed-jobs />
                                </div>
                            </div>
                        </div>
                    </section>
                </div>

                <!-- Right column -->
                <div class="grid grid-cols-1 gap-4">
                    <section aria-labelledby="section-2-title">
                        <div class="rounded-lg bg-white overflow-hidden shadow">
                            <div class="p-6">
                                <h2 id="section-2-title" class="text-lg font-medium">
                                    Current Workload
                                </h2>
                                <div class="flow-root">
                                    <workloads />
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </default>
</template>

<script>
    import Default from '../layouts/default.vue';
    import StatusBadge from '../components/StatusBadge.vue';
    import HorizonStats from '../components/HorizonStats.vue';
    import FailedJobs from '../components/FailedJobs.vue';
    import Workloads from '../components/Workloads.vue';
    import CloudWatchAlarms from '../components/CloudWatchAlarms.vue';
    import CloudWatchGraph from '../components/CloudWatchGraph.vue';

    export default {
        components: {
            FailedJobs, HorizonStats, StatusBadge, Default, Workloads, CloudWatchAlarms, CloudWatchGraph,
        },

        props: {
            hasHorizon: {
                type: Boolean,
                default: false,
            },
            hasAws: {
                type: Boolean,
                default: false,
            },
            statusPages: {
                type: Array,
                default: () => [],
            },
        },
    };
</script>
