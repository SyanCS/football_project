<template>
  <v-container>
    <v-row>
      <v-col cols="12">
        <h1>Teams</h1>
        <v-data-table
          :headers="headers"
          :items="teams"
          :items-per-page="perPage"
          :page.sync="page"
          :server-items-length="totalTeams"
          @update:items-per-page="updateItemsPerPage"
          class="elevation-1"
        >
          <template v-slot:item.players="{ item }">
            <PlayerListModal :players="item.players" />
          </template>
          <template v-slot:item.moneyBalance="{ item }">
            {{ formatMoneyBalance(item.moneyBalance) }}
          </template>
        </v-data-table>
        <v-btn
          fab
          color="primary"
          :class="{ 'elevation-12': page === 1 }"
          fixed
          bottom
          right
          class="mb-15 mr-10"
          @click="$router.push('/teams/add')"
        >
          <v-icon>mdi-plus</v-icon>
        </v-btn>
      </v-col>
    </v-row>
    <v-btn color="secondary" class="ml-2 mt-5" outlined @click="$router.push('/')">Back to Menu</v-btn>
  </v-container>
</template>
<script>
import TeamService from "@/services/TeamService.js";
import PlayerListModal from "@/components/PlayerListModal.vue";

export default {
  name: 'Teams',
  components: {
    PlayerListModal
  },

  data() {
    return {
      headers: [
        { text: 'Team Name', value: 'name' },
        { text: 'Country', value: 'country' },
        { text: 'Money Balance', value: 'moneyBalance', sortable: false, valueFormatter: this.formatMoneyBalance },
        { text: 'Players', value: 'players' }
      ],
      teams: [],
      totalTeams: 0,
      page: 1,
      perPage: 10,
      loading: false
    }
  },

  mounted() {
    this.getTeams()
  },

  watch: {
    page() {
      this.getTeams();
    },
    perPage() {
      this.getTeams();
    },
  },

  methods: {
    formatMoneyBalance(value) {
      if (!value) return ''
      const formatter = new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
        minimumFractionDigits: 2,
      })
      return formatter.format(value)
    },

    updateItemsPerPage(value) {
      this.perPage = value;
      this.getTeams();
    },

    getTeams() {
      this.loading = true
      TeamService
      .getAll(this.page,this.perPage)
      .then(response => {
        this.teams = response.data.teams
        this.totalTeams = response.data.total
      })
      .catch(error => {
        this.$toast.error("Error fetching teams.", "", { position: "topRight"});
        console.error(error)
      })
      .finally(() => {
        this.loading = false
      })
    }
  }
}
</script>

