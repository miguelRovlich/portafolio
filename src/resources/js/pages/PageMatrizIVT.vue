<template>
    <v-container fluid>
     
<div class="text-h4 transition-swing text-center">Matriz IVT</div>


  <v-container class="grey lighten-5">
     <v-row no-gutters>
      <template v-for="n in 1">
        <v-col :key="n">
          <v-card
            class="pa-2"
            outlined
            tile
            
          >
          <div class="text-h6 transition-swing text-center">  Mes de Pagos </div>
          </v-card>
        </v-col>
        <v-responsive
          v-if="n === 2"
          :key="`width-${n}`"
          width="100%"
        ></v-responsive>
      </template>
    </v-row>

    <v-row no-gutters>
      <template v-for="n in 4">
        <v-col :key="n">
          <v-card
            class="pa-2"
            outlined
            tile
          >
            Column
          </v-card>
        </v-col>
        <v-responsive
          v-if="n === 2"
          :key="`width-${n}`"
          width="100%"
        ></v-responsive>
      </template>
    </v-row>
 <v-row no-gutters>
      <template v-for="n in 4">
        <v-col :key="n">
          <v-card
            class="pa-2"
            outlined
            tile
          >
            Column
          </v-card>
        </v-col>
        <v-responsive
          v-if="n === 2"
          :key="`width-${n}`"
          width="100%"
        ></v-responsive>
      </template>
    </v-row>



  </v-container>

     </v-container>
</template>