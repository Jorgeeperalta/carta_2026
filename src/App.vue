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
  :style="{
    maxWidth: $vuetify.display.smAndDown ? '100%' : '900px'
  }"
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

        <v-btn icon variant="text">
          <v-icon>mdi-magnify</v-icon>
        </v-btn>
      </v-container>
    </v-app-bar>

    <!-- MAIN -->
   <v-main class="bg-grey-lighten-4 main-content">

     <v-container
  fluid
  class="pa-0"
  :style="{
    maxWidth: $vuetify.display.smAndDown ? '100%' : '900px'
  }"
>


        <!-- HERO -->
        <v-row no-gutters>
          <v-col cols="12">
            <v-img
              src="https://images.unsplash.com/photo-1514362545857-3bc16c4c7d1b?q=80&w=2070&auto=format&fit=crop"
              :height="$vuetify.display.smAndDown ? 160 : 220"
              cover
              class="rounded-b-xl"
            >
              <div
                class="fill-height bg-gradient-to-t from-black-50 to-transparent pa-4 d-flex align-end"
              >
                <div class="text-white">
                  <div class="font-weight-black"
                       :class="$vuetify.display.smAndDown ? 'text-h6' : 'text-h4'">
                    {{ negocio.nombre }}
                  </div>

                  <div
                    v-if="!$vuetify.display.smAndDown"
                    class="text-subtitle-1 opacity-80"
                  >
                    Explora nuestras delicias artesanales
                  </div>
                </div>
              </div>
            </v-img>
          </v-col>
        </v-row>

        <!-- CATEGORIAS -->
        <v-sheet
          class="sticky-top bg-grey-lighten-4 py-2"
          elevation="0"
        >
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
        <v-row class="mt-2">
          <v-col
            v-for="item in productosFiltrados"
            :key="item.id"
            cols="12"
            sm="6"
            md="4"
          >
            <v-card
              class="rounded-xl mb-2"
              elevation="1"
              variant="flat"
            >
              <div class="d-flex">
                <div class="pa-3 d-flex flex-column flex-grow-1">
                  <div class="text-body-1 font-weight-bold mb-1">
                    {{ item.nombre }}
                  </div>

                  <div class="text-caption text-grey-darken-1 line-clamp-2 mb-2">
                    {{ item.descripcion }}
                  </div>

                  <v-spacer />

                  <div class="text-subtitle-1 font-weight-black text-primary">
                    ${{ item.precio }}
                  </div>
                </div>

                <v-avatar
                  class="ma-2 rounded-lg"
                  :size="$vuetify.display.smAndDown ? 70 : 90"
                >
                  <v-img :src="item.imagen_url" cover />
                </v-avatar>
              </div>
            </v-card>
          </v-col>
        </v-row>

      </v-container>
    </v-main>

    <!-- BOTTOM NAV -->
  <v-bottom-navigation
  grow
  color="primary"
  elevation="6"
  height="64"
  class="custom-bottom-nav"
>

     <div
  class="d-flex w-100 justify-center"
  :style="{
    maxWidth: $vuetify.display.smAndDown ? '100%' : '900px',
    margin: '0 auto'
  }"
>

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
    const response = await fetch('/api/get_menu.php?id=1')
    if (!response.ok) throw new Error('Error en la red')
    const data = await response.json()
    negocio.value = data.negocio
    items.value = data.items
  } catch (error) {
    console.error('Error fetching menu:', error)
    negocio.value = { nombre: 'Burger Queen' }
    items.value = [
      { id: 1, nombre: 'Doble con Queso', descripcion: 'Doble carne, doble cheddar y salsa especial.', precio: 850, categoria: 'Hamburguesas', imagen_url: 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?q=80&w=1899' },
      { id: 2, nombre: 'Burger Veggie', descripcion: 'Medallón de lentejas, lechuga y tomate.', precio: 750, categoria: 'Hamburguesas', imagen_url: 'https://images.unsplash.com/photo-1550547660-d9450f859349?q=80&w=1965' },
      { id: 3, nombre: 'Papas Cheddar', descripcion: 'Papas fritas con baño de cheddar y bacon.', precio: 600, categoria: 'Papas Fritas', imagen_url: 'https://images.unsplash.com/photo-1573225342350-16731dd9bf3d?q=80&w=1962' },
      { id: 4, nombre: 'Coca Cola 500ml', descripcion: 'Bebida refrescante.', precio: 300, categoria: 'Bebidas', imagen_url: 'https://images.unsplash.com/photo-1622483767028-3f66f32aef97?q=80&w=2070' }
    ]
  }
})
</script>

<style scoped>
  .main-content {
  padding-bottom: 96px; /* altura bottom nav + aire */
}

@supports (padding-bottom: env(safe-area-inset-bottom)) {
  .main-content {
    padding-bottom: calc(96px + env(safe-area-inset-bottom));
  }
}

.sticky-top {
  position: sticky;
  top: 70px; /* Debajo del app-bar */
  z-index: 10;
}

.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.bg-gradient-to-t {
  background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
}

.custom-bottom-nav {
  padding-bottom: env(safe-area-inset-bottom);
}


@media (min-width: 900px) {
  .custom-bottom-nav {
    border-radius: 20px 20px 0 0;
  }
}

/* Animations */
.list-enter-active,
.list-leave-active {
  transition: all 0.4s ease;
}
.list-enter-from,
.list-leave-to {
  opacity: 0;
  transform: translateY(20px);
}
</style>