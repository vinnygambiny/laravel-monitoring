import Vue from 'vue';
import Axios from 'axios';

const axios = Axios.create({
    headers: {
        common: {
            'Accept': 'application/json, text/plain, */*',
            'X-Requested-With': 'XMLHttpRequest',
        },
    },
});

const VueAxios = {
    install(VueInstance, axios) {
        VueInstance.axios = axios;
        VueInstance.prototype.$axios = axios;
    },
};

Vue.use(VueAxios, axios);
