import ApiService from '@/services/ApiService.js'

export default {
    getByTeam(teamId) {
        return ApiService.apiCall().get(`teams/${teamId}/players`)
    },

}