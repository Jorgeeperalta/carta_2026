<template>
  <v-container>
    <v-row class="mb-4 align-center">
      <v-col>
        <h1 class="text-h4 font-weight-bold">Listado de Negocios</h1>
      </v-col>
      <v-col class="text-right">
        <v-btn
          color="primary"
          prepend-icon="mdi-plus"
          size="large"
          class="rounded-lg"
          to="/admin/negocio/nuevo"
        >
          Nuevo Negocio
        </v-btn>
      </v-col>
    </v-row>

    <v-card class="rounded-xl overflow-hidden" elevation="1">
      <v-table hover>
        <thead>
          <tr class="bg-grey-lighten-4">
            <th class="font-weight-bold">ID</th>
            <th class="font-weight-bold">Nombre</th>
            <th class="font-weight-bold">Descripción</th>
            <th class="font-weight-bold text-center">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="n in negocios" :key="n.id">
            <td>{{ n.id }}</td>
            <td class="font-weight-bold">{{ n.nombre }}</td>
            <td class="text-grey-darken-1">{{ n.slug }}</td>
            <td class="text-center">
              <v-btn
                icon="mdi-pencil"
                variant="text"
                color="primary"
                :to="'/admin/negocio/' + n.id"
              ></v-btn>
              <v-btn
                icon="mdi-delete"
                variant="text"
                color="error"
                @click="confirmDelete(n)"
              ></v-btn>
              <v-btn
                icon="mdi-eye"
                variant="text"
                color="info"
                :to="'/' + n.id"
                target="_blank"
              ></v-btn>
            </td>
          </tr>
          <tr v-if="negocios.length === 0">
            <td colspan="4" class="text-center py-8 text-grey">
              No hay negocios registrados.
            </td>
          </tr>
        </tbody>
      </v-table>
    </v-card>

    <!-- Dialogo de confirmación -->
    <v-dialog v-model="deleteDialog" max-width="400">
      <v-card class="rounded-xl pa-2">
        <v-card-title class="text-h6">¿Eliminar negocio?</v-card-title>
        <v-card-text>
          Esta acción no se puede deshacer. Se eliminarán los datos de <b>{{ selectedNegocio?.nombre }}</b>.
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn variant="text" @click="deleteDialog = false">Cancelar</v-btn>
          <v-btn color="error" variant="flat" @click="deleteItem" :loading="deleting">Eliminar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-container>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const negocios = ref([])
const deleteDialog = ref(false)
const selectedNegocio = ref(null)
const deleting = ref(false)

const loadNegocios = async () => {
  try {
    const response = await fetch('/api/get_negocios.php')
    negocios.value = await response.json()
  } catch (error) {
    console.error('Error cargando negocios:', error)
  }
}

const confirmDelete = (negocio) => {
  selectedNegocio.value = negocio
  deleteDialog.value = true
}

const deleteItem = async () => {
  deleting.value = true
  try {
    const response = await fetch('/api/delete_negocio.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ id: selectedNegocio.value.id })
    })
    
    if (response.ok) {
      await loadNegocios()
      deleteDialog.value = false
    }
  } catch (error) {
    console.error('Error eliminando:', error)
  } finally {
    deleting.value = false
  }
}

onMounted(loadNegocios)
</script>
