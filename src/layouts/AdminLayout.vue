<template>
  <v-app>
    <v-navigation-drawer
      v-model="drawer"
      app
      color="grey-darken-4"
      theme="dark"
    >
      <v-list-item
        prepend-icon="mdi-silverware-variant"
        title="Admin Carta"
        subtitle="Administración"
        class="pa-4"
      ></v-list-item>

      <v-divider></v-divider>

      <v-list density="compact" nav>
        <v-list-item
          prepend-icon="mdi-format-list-bulleted"
          title="Gestión Negocios"
          to="/admin/negocios"
        ></v-list-item>
        <v-list-item
          prepend-icon="mdi-book-open-page-variant"
          title="Ver Carta (Pública)"
          to="/"
        ></v-list-item>
      </v-list>

      <template v-slot:append>
        <div class="pa-2">
          <v-btn block color="error" variant="tonal" prepend-icon="mdi-logout" @click="logout">
            Salir
          </v-btn>
        </div>
      </template>
    </v-navigation-drawer>

    <v-app-bar flat border-b>
      <v-app-bar-nav-icon @click="drawer = !drawer"></v-app-bar-nav-icon>
      <v-app-bar-title>Panel de Administración</v-app-bar-title>
    </v-app-bar>

    <v-main class="bg-grey-lighten-4">
      <router-view />
    </v-main>
  </v-app>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const drawer = ref(true)

const logout = () => {
  localStorage.removeItem('admin_token')
  router.push('/')
}
</script>
