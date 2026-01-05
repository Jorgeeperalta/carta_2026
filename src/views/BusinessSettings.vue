<template>
  <v-container>
    <v-row>
      <v-col cols="12" md="8" offset-md="2">
        <v-card class="rounded-xl pa-4" elevation="1">
          <v-card-title class="text-h5 font-weight-bold mb-4">
            Configuración del Negocio
          </v-card-title>
          
          <v-form @submit.prevent="saveSettings">
            <v-row>
              <v-col cols="12">
                <v-text-field
                  v-model="business.nombre"
                  label="Nombre del Negocio"
                  prepend-inner-icon="mdi-store"
                  variant="outlined"
                  required
                ></v-text-field>
              </v-col>
              
              <v-col cols="12">
                <v-text-field
                  v-model="business.logo_url"
                  label="URL del Logo / Imagen Hero"
                  prepend-inner-icon="mdi-image"
                  variant="outlined"
                ></v-text-field>
              </v-col>

              <v-col cols="12">
                <v-textarea
                  v-model="business.slug"
                  label="Descripción corta"
                  prepend-inner-icon="mdi-text-box-outline"
                  variant="outlined"
                  rows="3"
                ></v-textarea>
              </v-col>
            </v-row>

            <v-divider class="my-4"></v-divider>

            <div class="d-flex justify-end">
              <v-btn
                color="primary"
                size="large"
                class="rounded-lg px-8"
                type="submit"
                :loading="saving"
              >
                Guardar Cambios
              </v-btn>
            </div>
          </v-form>
        </v-card>
      </v-col>
    </v-row>

    <v-snackbar v-model="snackbar" :color="snackbarColor" timeout="3000">
      {{ snackbarText }}
    </v-snackbar>
  </v-container>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'

const props = defineProps({
  id: {
    type: String,
    required: true
  }
})

const router = useRouter()

const business = ref({
  nombre: '',
  logo_url: '',
  slug: ''
})

const isNew = props.id === 'nuevo'
const saving = ref(false)
const snackbar = ref(false)
const snackbarText = ref('')
const snackbarColor = ref('success')

onMounted(async () => {
  if (!isNew) {
    try {
      const response = await fetch(`/api/get_menu.php?id=${props.id}`)
      const data = await response.json()
      if (data.negocio) {
        business.value = {
          id: data.negocio.id,
          nombre: data.negocio.nombre,
          logo_url: data.negocio.logo_url, 
          slug: data.negocio.slug
        }
      }
    } catch (error) {
      console.error('Error loading business:', error)
    }
  }
})

const saveSettings = async () => {
  saving.value = true
  const api = isNew ? '/api/create_negocio.php' : '/api/save_negocio.php'
  
  try {
    const response = await fetch(api, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(business.value)
    })
    
    if (response.ok) {
      const result = await response.json()
      snackbarText.value = isNew ? 'Negocio creado correctamente' : 'Configuración guardada'
      snackbarColor.value = 'success'
      
      if (isNew) {
        // Redirigir al listado después de crear
        setTimeout(() => router.push('/admin/negocios'), 1500)
      }
    } else {
      throw new Error('Error al guardar')
    }
  } catch (error) {
    snackbarText.value = 'Error al conectar con el servidor'
    snackbarColor.value = 'error'
  } finally {
    saving.value = false
    snackbar.value = true
  }
}
</script>
