<template>
  <v-container class="fill-height bg-grey-lighten-4" fluid>
    <v-row align="center" justify="center" class="ma-0">
      <v-col cols="12" sm="8" md="6" lg="4" xl="3">
        <v-card class="rounded-xl pa-6 mx-auto" elevation="4" max-width="450">
          <v-card-item class="text-center mb-4">
            <v-avatar size="64" color="primary" class="mb-4">
              <v-icon color="white" size="32">mdi-lock-outline</v-icon>
            </v-avatar>
            <v-card-title class="text-h5 font-weight-bold">Administración</v-card-title>
            <v-card-subtitle>Ingresa tus credenciales</v-card-subtitle>
          </v-card-item>

          <v-form @submit.prevent="handleLogin">
            <v-text-field
              v-model="form.usuario"
              label="Usuario"
              prepend-inner-icon="mdi-account"
              variant="outlined"
              class="mb-2"
              required
            ></v-text-field>

            <v-text-field
              v-model="form.password"
              label="Contraseña"
              prepend-inner-icon="mdi-key"
              type="password"
              variant="outlined"
              class="mb-4"
              required
            ></v-text-field>

            <v-btn
              block
              color="primary"
              size="large"
              class="rounded-lg mb-4"
              type="submit"
              :loading="loading"
            >
              Iniciar Sesión
            </v-btn>
          </v-form>

          <div v-if="error" class="text-error text-center text-body-2 bg-error-lighten-5 pa-2 rounded-lg">
            {{ error }}
          </div>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const loading = ref(false)
const error = ref('')

const form = ref({
  usuario: '',
  password: ''
})

const handleLogin = async () => {
  loading.value = true
  error.value = ''
  
  try {
    const response = await fetch('/api/login.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(form.value)
    })
    
    const data = await response.json()
    
    if (response.ok && data.status === 'success') {
      localStorage.setItem('admin_token', data.token)
      router.push('/admin/negocios')
    } else {
      error.value = data.message || 'Credenciales incorrectas'
    }
  } catch (err) {
    error.value = 'Error al conectar con el servidor'
  } finally {
    loading.value = false
  }
}
</script>
