<template>
    <div class="-ml-2 space-y-2">
        <span class="ml-2 flex items-center px-3 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800" :key="healthCheck.name" v-for="healthCheck in healthChecksOrdered">
            <svg
                class="-ml-1 mr-1.5 h-4 w-4"
                :class="getColor(healthCheck)"
                fill="currentColor"
                viewBox="0 0 8 8"
            >
                <circle cx="4" cy="4" r="3" />
            </svg>
            {{ healthCheck.name }} - {{ toDistance(healthCheck.last_ping) }}
        </span>
    </div>
</template>

<script>
    import orderBy from 'lodash/orderBy.js';
    import { readableTimeDistance } from '@/utils.js';

    export default {
        data() {
            return {
                timeout: null,
                healthChecks: [],
            };
        },

        computed: {
            healthChecksOrdered() {
                return orderBy(this.healthChecks, ['status', 'last_ping', 'name'], ['asc', 'desc', 'asc']);
            },
        },

        methods: {
            async fetchHealthChecks() {
                const response = await this.$axios.get(route('monitoring.api.healthchecks'));

                this.healthChecks = response.data.checks || [];
            },

            refreshHealthChecks() {
                this.fetchHealthChecks();

                this.timeout = setTimeout(this.refreshHealthChecks, 5000);
            },

            toDistance(date) {
                return readableTimeDistance(date);
            },

            getColor(check) {
                if (check.status == 'up') {
                    return 'text-green-600';
                }

                if (check.status == 'down') {
                    return 'text-red-600';
                }

                return 'text-gray-400';
            },
        },

        mounted() {
            this.refreshHealthChecks();
        },

        beforeDestroy() {
            clearTimeout(this.timeout);
        },
    };
</script>
