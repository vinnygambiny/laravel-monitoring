<template>
    <a class="flex shadow-sm rounded-md" :href="pageUrl" target="_blank">
        <div class="flex-shrink-0 flex items-center justify-center w-16 text-white text-sm font-medium rounded-l-md" :class="statusColor" />
        <div class="flex-1 flex items-center justify-between border-t border-r border-b border-gray-200 bg-white rounded-r-md truncate">
            <div class="flex-1 px-4 py-2 text-sm truncate">
                <p class="text-gray-900 font-medium hover:text-gray-600">{{ pageName }}</p>
            </div>
        </div>
    </a>
</template>

<script>
    export default {
        props: {
            statusPageId: {
                type: String,
                default: '',
                required: true,
            },
        },

        data() {
            return {
                timeout: null,
                statusData: {},
            };
        },

        computed: {
            pageStatus() {
                return this.statusData.status?.description || 'Loading...';
            },

            pageIndicator() {
                return this.statusData.status?.indicator;
            },

            pageName() {
                return this.statusData.page?.name || 'Loading...';
            },

            pageUrl() {
                return this.statusData.page?.url || '#';
            },

            statusColor() {
                if (this.pageIndicator == 'major') {
                    return 'bg-red-600';
                }

                if (this.pageIndicator == 'minor') {
                    return 'bg-yellow-600';
                }

                if (this.pageIndicator == 'none') {
                    return 'bg-green-600';
                }

                if (this.pageIndicator == 'maintenance') {
                    return 'bg-indigo-600';
                }

                return 'bg-gray-400';
            },
        },

        methods: {
            async fetchStatus() {
                const response = await this.$axios.get(`https://${this.statusPageId}.statuspage.io/api/v2/status.json`);

                this.statusData = response.data || {};
            },

            refreshStatus() {
                this.fetchStatus();

                this.timeout = setTimeout(this.refreshStatus, 300000);
            },
        },

        created() {
            this.refreshStatus();
        },

        beforeDestroy() {
            clearTimeout(this.timeout);
        },
    };
</script>
