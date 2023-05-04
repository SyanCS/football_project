<template>
    <v-container>
      <v-row>
        <v-col cols="12" sm="10" md="8" offset-sm="1" offset-md="2">
          <v-card>
            <v-card-title>
              <h1 class="headline">Add Team</h1>
            </v-card-title>
  
            <v-card-text>
              <v-form v-model="valid" ref="form">
                <v-text-field v-model="team.name" label="Team Name" :rules="nameRules" required></v-text-field>
                <v-text-field v-model="team.country" label="Country" :rules="countryRules" required></v-text-field>
                <v-text-field v-model="team.moneyBalance" label="Money Balance" :rules="moneyBalanceRules" required></v-text-field>
  
                <v-card class="players-card">
                  <v-card-title>
                    <h3 class="headline">Players</h3>
                    <v-btn @click="addPlayer" fab small outlined color="white" class="ml-3">
                      <v-icon color="primary">mdi-plus</v-icon>
                    </v-btn>
                  </v-card-title>
  
                  <v-card-text>
                    <v-col v-for="(player, index) in team.players" :key="index" class="player-box">
                      <v-row>
                        <v-col>
                          <v-text-field v-model="player.name" label="Player Name" :rules="playerNameRules" required></v-text-field>
                        </v-col>
  
                        <v-col>
                          <v-text-field v-model="player.surname" label="Player Surname" :rules="playerSurnameRules" required></v-text-field>
                        </v-col>
  
                        <v-col>
                          <v-btn @click="removePlayer(index)" fab small outlined color="white">
                            <v-icon color="error">mdi-minus</v-icon>
                          </v-btn>
                        </v-col>
                      </v-row>
                    </v-col>
                  </v-card-text>
                </v-card>
                <v-btn color="primary" class="mt-5" @click="saveTeam">Save</v-btn>
                <v-btn color="secondary" class="ml-2 mt-5" outlined @click="$router.push('/teams')">Back to Teams List</v-btn>
              </v-form>
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>
    </v-container>
  </template>
  
  <script>
  import TeamService from "@/services/TeamService.js";
  
  export default {
    name: "AddTeam",
  
    data: () => ({
      valid: false,
      team: {
        name: "",
        country: "",
        moneyBalance: 0,
        players: [],
      },
      nameRules: [(v) => !!v || "Team name is required"],
      countryRules: [(v) => !!v || "Country is required"],
      moneyBalanceRules: [
        (v) => /^\d+(\.\d{1,2})?$/.test(v) || "Money balance is invalid",
      ],
      playerNameRules: [(v) => !!v || "Player name is required"],
      playerSurnameRules: [(v) => !!v || "Player surname is required"],
    }),
  
    methods: {
      addPlayer() {
        this.team.players.push({ name: "", surname: "" });
      },
  
      removePlayer(index) {
        this.team.players.splice(index, 1);
      },

    saveTeam() {
        TeamService.addTeamAndPlayers(this.team)
        .then(() => {
            this.$toast.success("Team created!", "", { position: "topRight"});
            this.$router.push({ name: "teams" });
        })
        .catch((error) => {
            this.$toast.error("Error creating team.", "", { position: "topRight"});
            console.error(error);
        });
    },
  },
};
</script>
