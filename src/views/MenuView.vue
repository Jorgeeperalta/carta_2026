<template>
  <v-app>
    <!-- APP BAR -->
    <v-app-bar
      flat
      color="white"
      class="border-b"
      :height="$vuetify.display.smAndDown ? 56 : 70"
    >
      <v-container
        fluid
        class="d-flex align-center pa-0 px-3 fill-height"
        style="max-width: 900px"
      >
        <v-avatar size="36" color="grey-lighten-4">
          <v-icon color="primary">mdi-silverware-variant</v-icon>
        </v-avatar>

        <v-app-bar-title
          class="font-weight-bold ml-2"
          :class="$vuetify.display.smAndDown ? 'text-subtitle-1' : 'text-h6'"
        >
          {{ negocio.nombre }}
        </v-app-bar-title>

        <v-spacer />

        <v-btn icon variant="text" to="/admin/negocio">
          <v-icon>mdi-cog-outline</v-icon>
        </v-btn>
      </v-container>
    </v-app-bar>

    <v-main class="bg-grey-lighten-4 main-content">
      <!-- HERO (Full Width) -->
      <v-img
        :src="negocio.logo_url"
        :height="$vuetify.display.smAndDown ? 200 : 300"
        cover
        class="rounded-b-xl"
      >
        <div class="fill-height bg-gradient-to-t from-black-50 to-transparent pa-6 d-flex align-end">
          <v-container fluid class="pa-0" style="max-width: 900px">
            <div class="text-white">
              <div class="font-weight-black" :class="$vuetify.display.smAndDown ? 'text-h4' : 'text-h2'">
                {{ negocio.nombre }}
              </div>
              <div class="text-subtitle-1 opacity-80 mt-1">
                {{ negocio.slug }}
              </div>
            </div>
          </v-container>
        </div>
      </v-img>

      <v-container fluid class="pa-0" style="max-width: 900px">

        <!-- CATEGORIAS -->
        <v-sheet class="sticky-top bg-grey-lighten-4 py-2" elevation="0">
          <v-slide-group center-active>
            <v-chip
              v-for="cat in categorias"
              :key="cat"
              class="mx-2"
              size="small"
              variant="flat"
              :color="categoriaSeleccionada === cat ? 'primary' : undefined"
              @click="categoriaSeleccionada = cat"
            >
              {{ cat }}
            </v-chip>
          </v-slide-group>
        </v-sheet>

        <!-- PRODUCTOS -->
        <v-row class="mt-2 px-2">
          <v-col
            v-for="item in productosFiltrados"
            :key="item.id"
            cols="12"
            sm="6"
            md="4"
          >
            <v-card class="rounded-xl mb-2" elevation="1" variant="flat">
              <div class="d-flex">
                <div class="pa-3 d-flex flex-column flex-grow-1">
                  <div class="text-body-1 font-weight-bold mb-1">{{ item.nombre }}</div>
                  <div class="text-caption text-grey-darken-1 line-clamp-2 mb-2">{{ item.slug }}</div>
                  <v-spacer />
                  <div class="text-subtitle-1 font-weight-black text-primary">${{ item.precio }}</div>
                </div>
                <v-avatar class="ma-2 rounded-lg" :size="$vuetify.display.smAndDown ? 70 : 90">
                  <v-img :src="item.imagen_url" cover />
                </v-avatar>
              </div>
            </v-card>
          </v-col>
        </v-row>
      </v-container>
    </v-main>

    <!-- BOTTOM NAV -->
    <v-bottom-navigation grow color="primary" elevation="6" height="64" class="custom-bottom-nav">
      <div class="d-flex w-100 justify-center" style="max-width: 900px; margin: 0 auto">
        <v-btn value="menu">
          <v-icon>mdi-book-open-variant</v-icon>
          <span>Carta</span>
        </v-btn>
        <v-btn value="favs">
          <v-icon>mdi-heart-outline</v-icon>
          <span>Favoritos</span>
        </v-btn>
        <v-btn value="info">
          <v-icon>mdi-information-outline</v-icon>
          <span>Info</span>
        </v-btn>
      </div>
    </v-bottom-navigation>
  </v-app>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'

const props = defineProps({
  id: {
    type: String,
    required: true
  }
})

const negocio = ref({ nombre: 'Cargando...' })
const items = ref([])
const categoriaSeleccionada = ref('Todos')

const categorias = computed(() => {
  const cats = items.value.map(i => i.categoria)
  return ['Todos', ...new Set(cats)]
})

const productosFiltrados = computed(() => {
  if (categoriaSeleccionada.value === 'Todos') return items.value
  return items.value.filter(i => i.categoria === categoriaSeleccionada.value)
})

onMounted(async () => {
  try {
    const response = await fetch(`/api/get_menu.php?id=${props.id}`)
    const data = await response.json()
    negocio.value = data.negocio
    items.value = data.items
  } catch (error) {
    console.error('Error fetching menu:', error)
    negocio.value = { nombre: 'Burger Queen' }
    items.value = [
      { id: 1, nombre: 'Doble con Queso', slug: 'Doble carne, doble cheddar y salsa especial.', precio: 850, categoria: 'Hamburguesas', imagen_url: 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?q=80&w=1899' },
      { id: 2, nombre: 'Burger Veggie', slug: 'Medallón de lentejas, lechuga y tomate.', precio: 750, categoria: 'Hamburguesas', imagen_url: 'https://images.unsplash.com/photo-1550547660-d9450f859349?q=80&w=1965' },
    ]
  }
})
</script>

<style scoped>
.main-content { padding-bottom: 96px; }
.sticky-top { position: sticky; top: 70px; z-index: 10; }
.line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
.bg-gradient-to-t { background: linear-gradient(to top, rgba(0,0,0,0.8), transparent); }
.custom-bottom-nav { padding-bottom: env(safe-area-inset-bottom); }
@media (min-width: 900px) { .custom-bottom-nav { border-radius: 20px 20px 0 0; } }
</style>
