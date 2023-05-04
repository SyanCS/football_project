<template>
    <v-container fluid>
      <v-row>
        <v-col cols="12" sm="8" md="6" lg="4" offset-sm="2" offset-md="3" offset-lg="4">
          <v-card>
            <v-card-title class="headline">Player Market</v-card-title>
            <v-card-text>
              <v-autocomplete
                v-model="selectedTeam"
                :items="teams"
                item-value="id"
                item-text="name"
                label="Seller"
              ></v-autocomplete>
  
              <v-autocomplete
                v-model="selectedPlayer"
                :items="players"
                item-text="name"
                item-value="id"
                label="Player"
                :disabled="!selectedTeam"
              ></v-autocomplete>
  
              <v-autocomplete
                v-model="selectedBuyerTeam"
                :items="buyerTeams"
                item-text="name"
                label="Buyer"
                :disabled="!selectedPlayer"
                return-object
              ></v-autocomplete>
  
              <v-text-field
                v-model="transferAmount"
                label="Transfer amount"
                type="number"
                :disabled="!selectedBuyerTeam"
              ></v-text-field>
  
              <v-btn
                color="primary"
                @click="transferPlayer"
                :disabled="!transferAmount || transferAmount <= 0"
              >
                Transfer Player
              </v-btn>
            </v-card-text>
          </v-card>
          <v-btn color="secondary" class="ml-2 mt-5" outlined @click="$router.push('/')">Back to Menu</v-btn>
        </v-col>
      </v-row>
    </v-container>
  </template>
  
  
  <script>
  import TeamService from '@/services/TeamService';
  import PlayerService from '@/services/PlayerService';
  import TransferService from '@/services/TransferService';
  
  export default {
    data() {
      return {
        teams: [],
        players: [],
        selectedTeam: null,
        selectedPlayer: null,
        buyerTeams: [],
        selectedBuyerTeam: null,
        transferAmount: 0,
      };
    },
    watch: {
      selectedTeam(newValue) {
        if (newValue) {
          this.buyerTeams = this.teams.filter(team => team.id !== newValue);
          this.getPlayers(newValue);
        }
      },
    },
    methods: {
      getTeams() {
        TeamService.getAll()
          .then(response => {
            this.teams = response.data.teams;
          })
          .catch(error => {
            this.$toast.error("Error fetching teams.", "", { position: "topRight" });
            console.error(error);
          });
      },
      getPlayers(teamId) {
        PlayerService.getByTeam(teamId)
          .then(response => {
            this.players = response.data.players;
          })
          .catch(error => {
            this.$toast.error("Error fetching players.", "", { position: "topRight" });
            console.error(error);
          });
      },
      transferPlayer() {
        if (this.selectedBuyerTeam.moneyBalance < this.transferAmount) {
          this.$toast.error("Insufficient funds for transfer.", "", { position: "topRight" });
          return;
        } 

        const postData = {
            sellerId: this.selectedTeam,
            playerId: this.selectedPlayer,
            buyerId: this.selectedBuyerTeam.id,
            transferAmount: this.transferAmount
        };

        TransferService.createTransfer(postData)
        .then(response => {
            this.$toast.success("Transfer successful.", "", { position: "topRight" });
            this.selectedTeam = null;
            this.selectedPlayer = null;
            this.transferAmount = 0;
            this.selectedBuyerTeam = null;
        })
        .catch(error => {
            this.$toast.error("Error transferring player.", "", { position: "topRight" });
            console.error(error);
        });
      },
    },
    created() {
      this.getTeams();
    },
  };
  </script>
  
