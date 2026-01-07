<template>
  <v-container>
    <v-row class="mb-4 align-center">
      <v-col>
        <v-btn
          variant="text"
          prepend-icon="mdi-arrow-left"
          to="/admin/negocios"
          class="mb-2"
        >
          Volver al listado
        </v-btn>
        <h1 class="text-h4 font-weight-bold">Gestionar Productos</h1>
        <p class="text-subtitle-1 text-grey-darken-1">{{ businessName }}</p>
      </v-col>
      <v-col class="text-right">
        <v-btn
          color="secondary"
          prepend-icon="mdi-folder-plus"
          variant="outlined"
          class="rounded-lg mr-2"
          @click="categoryDialog = true"
        >
          Nueva Categoría
        </v-btn>
        <v-btn
          color="primary"
          prepend-icon="mdi-plus"
          size="large"
          class="rounded-lg"
          @click="openProductDialog()"
        >
          Nuevo Producto
        </v-btn>
      </v-col>
    </v-row>

    <!-- Agrupación por categorías -->
    <div v-for="cat in categoriasConProductos" :key="cat.id" class="mb-8">
      <h2 class="text-h5 font-weight-bold mb-3 d-flex align-center">
        {{ cat.nombre }}
        <v-divider class="ml-4"></v-divider>
      </h2>
      <v-row>
        <v-col v-for="prod in cat.productos" :key="prod.id" cols="12" sm="6" md="4">
          <v-card class="rounded-xl overflow-hidden" elevation="1">
            <v-img
              :src="prod.imagen_url || 'https://via.placeholder.com/300x200?text=Sin+Imagen'"
              height="150"
              contain
              class="bg-grey-lighten-4"
            ></v-img>
            <v-card-item>
              <div class="d-flex justify-space-between align-center">
                <div class="text-h6 font-weight-bold">{{ prod.nombre }}</div>
                <div class="text-primary font-weight-bold">${{ prod.precio }}</div>
              </div>
              <div class="text-body-2 text-grey-darken-1 line-clamp-2 mt-1">
                {{ prod.descripcion }}
              </div>
            </v-card-item>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn icon="mdi-pencil" variant="text" size="small" color="primary" @click="openProductDialog(prod)"></v-btn>
              <v-btn icon="mdi-delete" variant="text" size="small" color="error" @click="confirmDelete(prod)"></v-btn>
            </v-card-actions>
          </v-card>
        </v-col>
      </v-row>
    </div>

    <v-row v-if="categoriasConProductos.length === 0">
      <v-col cols="12" class="text-center py-12">
        <v-icon size="64" color="grey-lighten-1">mdi-food-off</v-icon>
        <div class="text-h6 text-grey-darken-1 mt-4">No hay productos registrados.</div>
      </v-col>
    </v-row>

    <!-- Diálogo para Producto -->
    <v-dialog v-model="productDialog" max-width="600">
      <v-card class="rounded-xl pa-2">
        <v-card-title class="text-h5 font-weight-bold">
          {{ editedProduct.id ? 'Editar Producto' : 'Nuevo Producto' }}
        </v-card-title>
        <v-card-text>
          <v-form ref="form" v-model="valid">
            <v-row>
              <v-col cols="12" md="6">
                <v-text-field
                  v-model="editedProduct.nombre"
                  label="Nombre del Producto"
                  variant="outlined"
                  required
                ></v-text-field>
              </v-col>
              <v-col cols="12" md="6">
                <v-text-field
                  v-model="editedProduct.precio"
                  label="Precio"
                  type="number"
                  prefix="$"
                  variant="outlined"
                  required
                ></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-select
                  v-model="editedProduct.categoria_id"
                  :items="listaCategorias"
                  item-title="nombre"
                  item-value="id"
                  label="Categoría"
                  variant="outlined"
                  required
                ></v-select>
              </v-col>
              <v-col cols="12">
                <v-textarea
                  v-model="editedProduct.descripcion"
                  label="Descripción"
                  variant="outlined"
                  rows="2"
                ></v-textarea>
              </v-col>
              <v-col cols="12">
                <v-text-field
                  v-model="editedProduct.imagen_url"
                  label="URL de la Imagen"
                  prepend-inner-icon="mdi-image"
                  variant="outlined"
                ></v-text-field>
              </v-col>
            </v-row>
          </v-form>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn variant="text" @click="productDialog = false">Cancelar</v-btn>
          <v-btn color="primary" variant="flat" class="px-6 rounded-lg" @click="saveProduct" :loading="saving">
            Guardar
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <!-- Diálogo Nueva Categoría (Simple) -->
    <v-dialog v-model="categoryDialog" max-width="400">
      <v-card class="rounded-xl pa-2">
        <v-card-title class="font-weight-bold">Nueva Categoría</v-card-title>
        <v-card-text>
          <v-text-field
            v-model="newCategoryName"
            label="Nombre de la categoría"
            variant="outlined"
            placeholder="Ej: Hamburguesas, Bebidas..."
            hide-details
          ></v-text-field>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn variant="text" @click="categoryDialog = false">Cancelar</v-btn>
          <v-btn color="secondary" variant="flat" @click="saveCategory" :loading="savingCategory">
            Crear
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <!-- Diálogo Confirmar Eliminación -->
    <v-dialog v-model="deleteDialog" max-width="400">
      <v-card class="rounded-xl pa-2">
        <v-card-title class="text-h6">¿Eliminar producto?</v-card-title>
        <v-card-text>
          Esta acción eliminará a <b>{{ editedProduct.nombre }}</b> permanentemente.
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn variant="text" @click="deleteDialog = false">Cancelar</v-btn>
          <v-btn color="error" variant="flat" @click="deleteItem" :loading="deleting">Eliminar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-snackbar v-model="snackbar" :color="snackbarColor">
      {{ snackbarText }}
    </v-snackbar>
  </v-container>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'

const props = defineProps({
  id: {
    type: String,
    required: true
  }
})

const businessName = ref('')
const categoriasConProductos = ref([])
const listaCategorias = ref([])

// Dialogs
const productDialog = ref(false)
const categoryDialog = ref(false)
const deleteDialog = ref(false)

// Forms
const valid = ref(false)
const saving = ref(false)
const deleting = ref(false)
const savingCategory = ref(false)
const newCategoryName = ref('')
const editedProduct = ref({
  id: null,
  nombre: '',
  precio: '',
  descripcion: '',
  imagen_url: '',
  categoria_id: null
})

// Notification
const snackbar = ref(false)
const snackbarText = ref('')
const snackbarColor = ref('success')

const loadData = async () => {
  try {
    // Cargar menú (negocio + productos agrupados)
    const menuResp = await fetch(`/api/get_menu.php?id=${props.id}`)
    const menuData = await menuResp.json()
    businessName.value = menuData.negocio?.nombre || 'Negocio'
    
    const menuGroups = []
    const categoriesMap = {}
    
    menuData.items.forEach(item => {
      if (!categoriesMap[item.categoria]) {
        categoriesMap[item.categoria] = {
          nombre: item.categoria,
          productos: []
        }
        menuGroups.push(categoriesMap[item.categoria])
      }
      categoriesMap[item.categoria].productos.push(item)
    })
    categoriasConProductos.value = menuGroups

    // Cargar todas las categorías para el select
    const catResp = await fetch(`/api/get_categorias.php?negocio_id=${props.id}`)
    listaCategorias.value = await catResp.json()
    
  } catch (error) {
    console.error('Error cargando data:', error)
  }
}

const openProductDialog = (prod = null) => {
  if (prod) {
    editedProduct.value = { ...prod }
  } else {
    editedProduct.value = {
      id: null,
      nombre: '',
      precio: '',
      descripcion: '',
      imagen_url: '',
      categoria_id: listaCategorias.value.length > 0 ? listaCategorias.value[0].id : null
    }
  }
  productDialog.value = true
}

const saveProduct = async () => {
  if (!editedProduct.value.categoria_id) {
    showSnackbar('Debes seleccionar o crear una categoría primero', 'warning')
    return
  }
  
  saving.value = true
  try {
    const response = await fetch('/api/save_producto.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(editedProduct.value)
    })
    
    if (response.ok) {
      showSnackbar('Producto guardado correctamente')
      productDialog.value = false
      await loadData()
    }
  } catch (error) {
    showSnackbar('Error al guardar el producto', 'error')
  } finally {
    saving.value = false
  }
}

const saveCategory = async () => {
  if (!newCategoryName.value) return
  
  savingCategory.value = true
  try {
    // Reutilizo save_negocio logic o creo uno nuevo? 
    // Por simplicidad crearé api/save_categoria.php
    const response = await fetch('/api/save_categoria.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ 
        negocio_id: props.id, 
        nombre: newCategoryName.value 
      })
    })
    
    if (response.ok) {
      showSnackbar('Categoría creada')
      categoryDialog.value = false
      newCategoryName.value = ''
      await loadData()
    }
  } catch (error) {
    showSnackbar('Error al crear categoría', 'error')
  } finally {
    savingCategory.value = false
  }
}

const confirmDelete = (prod) => {
  editedProduct.value = prod
  deleteDialog.value = true
}

const deleteItem = async () => {
  deleting.value = true
  try {
    const response = await fetch('/api/delete_producto.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ id: editedProduct.value.id })
    })
    
    if (response.ok) {
      showSnackbar('Producto eliminado')
      deleteDialog.value = false
      await loadData()
    }
  } catch (error) {
    showSnackbar('Error al eliminar', 'error')
  } finally {
    deleting.value = false
  }
}

const showSnackbar = (text, color = 'success') => {
  snackbarText.value = text
  snackbarColor.value = color
  snackbar.value = true
}

onMounted(loadData)
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
