import axios from 'axios';

export default {
    apiCall() {
        axios.defaults.withCredentials = true;

        let CustomHeader = {
            Accept: 'application/json',
            'Content-Type': 'application/json',
        }

        let baseURL = process.env.VUE_APP_API_BASE_URL

        let call = axios.create({
            baseURL: baseURL,
            headers: CustomHeader,
            timeout: 60 * 4 * 1000,
        })

        call.interceptors.response.use(
            response => {
                //my api answers false in a form of sending error
                if ((response.status === 200 || response.status === 201) && response.data) {
                    return Promise.resolve(response);
                } else {
                    return Promise.reject(response);
                }
            },
            error => {
                if (error.response && error.response.status) {
                    switch (error.response.status) {
                        case 401:
                        case 403:
                            AuthService.logout();
                            break;
                    }
                    return Promise.reject(error.response);
                } else {
                    return Promise.reject(error);
                }
            },
        )
        return call;
    },
}
