<template>
<v-container fluid>
  <v-text-field label="Name" v-model="ingredient.name"></v-text-field>
  <v-row>
    <v-col cols="12" sm="6">
        <p style="text-align:center">Coverage Area (sq/ft) :
            <vue-numeric-input v-model="ingredient.coverage" :value="0" :min="0" controls-type="updown"></vue-numeric-input>
        </p>
    </v-col>
    <v-col cols="12" sm="6">
      <p style="text-align:center">Purchase Price :
          <vue-numeric-input v-model="ingredient.purchaseprice" :value="0" :min="0" controls-type="updown"></vue-numeric-input>
      </p>
    </v-col>
  </v-row>
  <v-select
    v-model="ingredientColors"
    :items="colorNames"
    label="Color"
    multiple
    chips
    persistent-hint
  ></v-select>
  <v-select
    v-model="ingredientPatterns"
    :items="patternNames"
    label="Pattern"
    multiple
    chips
    persistent-hint
  ></v-select>
  <v-row style="align:center">
    <v-spacer></v-spacer>
    <v-btn color="green" dark class="mx-2 my-2" @click="saveIngredient">Save</v-btn>
    <v-btn color="error" dark class="mx-2 my-2" v-if="ingredientid != 'new'" @click="deleteIngredient">Delete</v-btn>
    <v-btn class="mx-2 my-2" @click="$router.push({name:'ingredients'})">Cancel</v-btn>
    <v-spacer></v-spacer>
  </v-row>
</v-container>
</template>

<script>
import axios from 'axios'
import { api } from '~/config'
import VueNumericInput from 'vue-numeric-input'

export default {

components: {
    VueNumericInput,
},

created() {
    this.ingredientid = this.$route.params.ingredientid
    this.getColors()
    let data = {'title': 'Edit Ingredient'}
    this.$store.dispatch('title/setTitle', data)
},

data: () => ({
    ingredient: {},
    colors: [],
    colorNames: [],
    patterns: [],
    patternNames: [],
    ingredientColors: [],
    ingredientPatterns: []
}),

methods: {
  getColors(){
    axios.get(api.path('colors'))
        .then(res => {
            this.colors = res.data
            this.colors.map( item => this.colorNames.push(item['name']) )
            this.getPatterns()
        })
        .catch(err => {
            this.$toast.error(err.response.data.errors)
        })
  },
  getPatterns(){
    axios.get(api.path('patterns'))
        .then(res => {
          this.patterns = res.data
          this.patterns.map( item => this.patternNames.push(item['name']) )
          if(this.ingredientid != 'new') this.getIngredient(this.ingredientid)
          else {
              this.ingredient.color = []
              this.ingredient.pattern = []
          }
        })
        .catch(err => {
            this.$toast.error(err.response.data.errors)
        })
  },
  getIngredient(ingredientid) {
      axios.get(api.path('ingredient') +"/"+ ingredientid)
          .then(res => {
            this.ingredient  = res.data
            this.ingredientColors = [...this.ingredient.colors.map( item => (item.name) )]
            this.ingredientPatterns = [...this.ingredient.patterns.map( item => (item.name) )]
          })
          .catch(err => {
              this.$toast.error(err.response.data.errors)
          })
          .then(() => {
              this.loading = false
      })
    },
  saveIngredient(){
    this.ingredient.colors = this.ingredientColors.map((item_color, ind, arr) => {
      const index = this.colors.findIndex(item => item['name'] == item_color)
      return this.colors[index]['id']
    })
    this.ingredient.patterns = this.ingredientPatterns.map((item_pattern, ind, arr) => {
        const index = this.patterns.findIndex(item => item['name'] == item_pattern)
        return this.patterns[index]['id']
    })

      if(!this.ingredient['name']) {
        this.$toast.error("Input Ingredient Name!")
        return
      }
      if(!this.ingredient['coverage']) {
        this.$toast.error("Input Coverage Area!")
        return
      }
      if(!this.ingredient['purchaseprice']) {
        this.$toast.error("Input Purchase Price!")
        return
      }

      if(this.ingredientid == 'new')
      {
        axios.post(api.path('ingredient'), this.ingredient)
            .then(res => {
                this.$toast.success('Saved successfully!')
                this.$router.push({name:'ingredients'})
            })
            .catch(err => {
                this.$toast.error("Ingredient data error!")
            })
      }
      else
      {
        this.$router.push({name:'ingredients'})
        axios.put(api.path('ingredient') +'/'+ this.ingredient['id'], this.ingredient)
            .then(res => {
                this.$toast.success('Updated successfully!')
            })
            .catch(err => {
                this.$toast.error("Ingredient data error!")
            })
      }
    },
    deleteIngredient(){
        axios.delete(api.path('ingredient') +'/'+ this.ingredient['id'])
            .then(res => {
                    this.$toast.success('Deleted successfully!')
                    this.$router.push({name:'ingredients'})
            })
            .catch(err => {
                this.$toast.error("Ingredient data error!")
        })
    },
}

}
</script>