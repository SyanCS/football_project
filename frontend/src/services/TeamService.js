import ApiService from '@/services/ApiService.js'

export default {
	getAll(page,perPage) {
    return ApiService.apiCall().get('teams', {
      params: {
        page: page,
        limit: perPage
      }
    })
  },
  addTeamAndPlayers(postData) {
    return ApiService.apiCall().post('teams', JSON.stringify(postData))
  },
}