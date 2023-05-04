import ApiService from '@/services/ApiService.js'

export default {
  get(id) {
    return ApiService.apiCall().get(`companies/${id}`)
  },
	getAll() {
    return ApiService.apiCall().get(`companies/`)
  },
  post(postData) {
    return ApiService.apiCall().post(`/companies/`, JSON.stringify(postData));
  },
}