import ApiService from '@/services/ApiService.js'

export default {
	createTransfer(postData) {
        return ApiService.apiCall().post('transfers',  JSON.stringify(postData))
    },
}