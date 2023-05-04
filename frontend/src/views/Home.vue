<template>
    <v-container>
        <v-row class="text-center">
        <v-col cols="12" sm="6">
            <v-card
            class="mx-auto"
            hover
            @click="$router.push('/teams')"
            style="cursor: pointer; padding: 48px; transition: 0.3s;"
            :elevation="2"
            @mouseover.native="elevation = 10"
            @mouseleave.native="elevation = 2"
            >
            <v-icon large color="primary">mdi-account-group-outline</v-icon>
            <h3>Teams</h3>
            </v-card>
        </v-col>
        <v-col cols="12" sm="6">
            <v-card
            class="mx-auto"
            hover
            @click="$router.push('/transfers')"
            style="cursor: pointer; padding: 48px; transition: 0.3s;"
            :elevation="2"
            @mouseover.native="elevation = 10"
            @mouseleave.native="elevation = 2"
            >
            <v-icon large color="primary">mdi-cash</v-icon>
            <h3>Player Market</h3>
            </v-card>
        </v-col>
        </v-row>
    </v-container>
</template>

<script>
    export default {
        name: "HomePage",
        data() {
            return {
            elevation: 2,
            };
        },
    };
</script>
