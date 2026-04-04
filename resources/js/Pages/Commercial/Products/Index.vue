<template>
  <CommercialLayout>
    <Head title="Catalogue produits" />

    <div style="display:flex; flex-direction:column; gap:20px;">

      <!-- Header -->
      <div style="display:flex; align-items:flex-start; justify-content:space-between; flex-wrap:wrap; gap:16px;">
        <div>
          <h1 style="font-size:20px; font-weight:900; color:#0D1B2A; letter-spacing:-0.02em; margin:0 0 3px;">Catalogue produits</h1>
          <p style="font-size:13px; color:rgba(13,27,42,0.45); margin:0;">Produits et formations utilisés dans vos devis</p>
        </div>
        <div style="display:flex; gap:10px;">
          <button @click="syncFormations" :disabled="syncing" style="display:inline-flex; align-items:center; gap:8px; padding:10px 18px; border-radius:12px; border:1.5px solid rgba(201,168,76,0.25); background:rgba(201,168,76,0.08); color:#A07828; font-size:13px; font-weight:700; cursor:pointer; font-family:inherit; transition:all 0.2s;" class="sync-btn">
            <PhArrowsClockwise style="width:14px; height:14px;" :class="syncing ? 'spin' : ''" />
            Sync formations
          </button>
          <button @click="showForm = true; editingProduct = null; resetForm()" style="display:inline-flex; align-items:center; gap:8px; background:#0D1B2A; color:white; font-weight:700; font-size:13px; padding:10px 20px; border-radius:12px; border:none; cursor:pointer; font-family:inherit; transition:all 0.2s;" class="new-btn">
            <PhPlus style="width:15px; height:15px;" />
            Nouveau produit
          </button>
        </div>
      </div>

      <!-- Grille produits -->
      <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:16px;">
        <div
          v-for="product in products.data"
          :key="product.id"
          style="background:white; border-radius:18px; padding:22px; border:1.5px solid rgba(13,27,42,0.07); transition:all 0.2s; position:relative;"
          class="product-card"
          :style="!product.is_active ? 'opacity:0.55;' : ''"
        >
          <!-- Badge formation -->
          <div v-if="product.formation" style="position:absolute; top:16px; right:16px; background:rgba(201,168,76,0.1); border:1px solid rgba(201,168,76,0.22); color:#A07828; font-size:9px; font-weight:700; padding:3px 9px; border-radius:100px; text-transform:uppercase; letter-spacing:0.08em;">
            Formation
          </div>

          <!-- Icône -->
          <div style="width:44px; height:44px; border-radius:13px; background:rgba(13,27,42,0.06); display:flex; align-items:center; justify-content:center; margin-bottom:14px;">
            <PhGraduationCap v-if="product.formation" style="width:22px; height:22px; color:#C9A84C;" weight="fill" />
            <PhPackage v-else style="width:22px; height:22px; color:rgba(13,27,42,0.4);" weight="fill" />
          </div>

          <div style="font-size:14px; font-weight:800; color:#0D1B2A; margin-bottom:5px; line-height:1.35;">{{ product.name }}</div>
          <div v-if="product.reference" style="font-size:11px; color:rgba(13,27,42,0.35); margin-bottom:12px; font-family:monospace;">{{ product.reference }}</div>

          <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:16px;">
            <div>
              <span style="font-size:20px; font-weight:900; color:#0D1B2A; letter-spacing:-0.02em;">{{ formatCurrency(product.unit_price) }}</span>
              <span style="font-size:12px; color:rgba(13,27,42,0.4); margin-left:4px;">/ {{ product.unit }}</span>
            </div>
            <span v-if="product.tax_rate > 0" style="font-size:11px; color:rgba(13,27,42,0.4); background:#FAF7F2; padding:3px 9px; border-radius:6px;">TVA {{ product.tax_rate }}%</span>
          </div>

          <!-- Actions -->
          <div style="display:flex; gap:8px;">
            <button @click="openEdit(product)" style="flex:1; padding:8px; border-radius:10px; border:1.5px solid rgba(13,27,42,0.1); background:white; font-size:12px; font-weight:700; color:rgba(13,27,42,0.6); cursor:pointer; font-family:inherit; display:flex; align-items:center; justify-content:center; gap:6px; transition:all 0.2s;" class="edit-btn">
              <PhPencil style="width:13px; height:13px;" /> Modifier
            </button>
            <button @click="confirmDelete(product)" style="width:36px; height:36px; border-radius:10px; border:1.5px solid rgba(239,68,68,0.2); background:rgba(239,68,68,0.06); display:flex; align-items:center; justify-content:center; cursor:pointer; color:#DC2626; transition:all 0.2s;" class="del-btn">
              <PhTrash style="width:13px; height:13px;" />
            </button>
          </div>
        </div>

        <!-- Vide -->
        <div v-if="products.data.length === 0" style="grid-column:span 3; text-align:center; padding:60px; background:white; border-radius:18px; border:2px dashed rgba(13,27,42,0.1); color:rgba(13,27,42,0.3);">
          <PhPackage style="width:40px; height:40px; margin:0 auto 14px; display:block;" />
          <div style="font-size:15px; font-weight:700; margin-bottom:6px;">Aucun produit</div>
          <div style="font-size:13px;">Créez des produits ou synchronisez vos formations</div>
        </div>
      </div>
    </div>

    <!-- Modal form produit -->
    <div v-if="showForm" style="position:fixed; inset:0; background:rgba(10,22,40,0.6); z-index:1000; display:flex; align-items:center; justify-content:center; padding:20px; backdrop-filter:blur(4px);" @click.self="showForm = false">
      <div style="background:white; border-radius:24px; padding:36px; width:100%; max-width:500px; box-shadow:0 32px 80px rgba(0,0,0,0.25);">
        <h2 style="font-size:18px; font-weight:900; color:#0D1B2A; margin-bottom:24px;">{{ editingProduct ? 'Modifier le produit' : 'Nouveau produit' }}</h2>

        <form @submit.prevent="saveProduct" style="display:flex; flex-direction:column; gap:16px;">
          <div>
            <label class="f-label">Nom <span style="color:#C9A84C;">*</span></label>
            <input v-model="productForm.name" type="text" required class="f-input" placeholder="Ex: Formation Excel avancé" />
          </div>
          <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
            <div>
              <label class="f-label">Référence</label>
              <input v-model="productForm.reference" type="text" class="f-input" placeholder="REF-001" />
            </div>
            <div>
              <label class="f-label">Unité</label>
              <select v-model="productForm.unit" class="f-input">
                <option value="formation">Formation</option>
                <option value="heure">Heure</option>
                <option value="jour">Jour</option>
                <option value="personne">Personne</option>
                <option value="unité">Unité</option>
              </select>
            </div>
          </div>
          <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
            <div>
              <label class="f-label">Prix unitaire (€) <span style="color:#C9A84C;">*</span></label>
              <input v-model.number="productForm.unit_price" type="number" step="0.01" min="0" required class="f-input" />
            </div>
            <div>
              <label class="f-label">TVA (%)</label>
              <input v-model.number="productForm.tax_rate" type="number" step="0.1" min="0" max="100" class="f-input" />
            </div>
          </div>
          <div>
            <label class="f-label">Description</label>
            <textarea v-model="productForm.description" rows="2" class="f-input" style="resize:vertical;" placeholder="Description du produit..."></textarea>
          </div>

          <div style="display:flex; gap:10px; padding-top:8px;">
            <button type="button" @click="showForm = false" style="flex:1; padding:12px; border-radius:100px; border:1.5px solid rgba(13,27,42,0.12); background:transparent; font-size:14px; font-weight:700; color:#0D1B2A; cursor:pointer; font-family:inherit;" class="cancel-btn">
              Annuler
            </button>
            <button type="submit" :disabled="saving" style="flex:2; padding:12px; border-radius:100px; border:none; background:#C9A84C; color:#0D1B2A; font-size:14px; font-weight:800; cursor:pointer; font-family:inherit; display:flex; align-items:center; justify-content:center; gap:8px;" class="save-btn">
              <PhCircleNotch v-if="saving" style="width:15px; height:15px;" class="spin" />
              <PhFloppyDisk v-else style="width:15px; height:15px;" />
              {{ saving ? 'Enregistrement...' : 'Enregistrer' }}
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Modal suppression -->
    <div v-if="toDelete" style="position:fixed; inset:0; background:rgba(10,22,40,0.6); z-index:1000; display:flex; align-items:center; justify-content:center; padding:20px;" @click.self="toDelete = null">
      <div style="background:white; border-radius:22px; padding:32px; max-width:400px; width:100%;">
        <div style="width:48px; height:48px; border-radius:13px; background:#FEE2E2; display:flex; align-items:center; justify-content:center; margin-bottom:16px;">
          <PhTrash style="width:22px; height:22px; color:#EF4444;" />
        </div>
        <h3 style="font-size:18px; font-weight:900; color:#0D1B2A; margin-bottom:8px;">Supprimer ce produit ?</h3>
        <p style="font-size:14px; color:rgba(13,27,42,0.5); margin-bottom:24px; line-height:1.6;">
          <strong>{{ toDelete.name }}</strong> sera supprimé définitivement.
        </p>
        <div style="display:flex; gap:10px;">
          <button @click="toDelete = null" style="flex:1; padding:12px; border-radius:100px; border:1.5px solid rgba(13,27,42,0.12); background:transparent; font-size:14px; font-weight:700; color:#0D1B2A; cursor:pointer; font-family:inherit;" class="cancel-btn">
            Annuler
          </button>
          <button @click="deleteProduct" style="flex:1; padding:12px; border-radius:100px; border:none; background:#EF4444; color:white; font-size:14px; font-weight:700; cursor:pointer; font-family:inherit; transition:all 0.2s;" class="confirm-del-btn">
            Supprimer
          </button>
        </div>
      </div>
    </div>
  </CommercialLayout>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { Head, router } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import CommercialLayout from '@/Layouts/CommercialLayout.vue'
import { PhPlus, PhPencil, PhTrash, PhPackage, PhGraduationCap, PhArrowsClockwise, PhCircleNotch, PhFloppyDisk } from '@phosphor-icons/vue'

const props = defineProps({ products: Object })

const showForm      = ref(false)
const editingProduct = ref(null)
const toDelete      = ref(null)
const saving        = ref(false)
const syncing       = ref(false)

const productForm = reactive({ name: '', reference: '', unit_price: 0, unit: 'formation', tax_rate: 0, description: '' })

function resetForm() {
  Object.assign(productForm, { name: '', reference: '', unit_price: 0, unit: 'formation', tax_rate: 0, description: '' })
}

function openEdit(product) {
  editingProduct.value = product
  Object.assign(productForm, { name: product.name, reference: product.reference ?? '', unit_price: product.unit_price, unit: product.unit, tax_rate: product.tax_rate, description: product.description ?? '' })
  showForm.value = true
}

function saveProduct() {
  saving.value = true
  const url    = editingProduct.value ? route('commercial.products.update', editingProduct.value.id) : route('commercial.products.store')
  const method = editingProduct.value ? 'put' : 'post'
  router[method](url, { ...productForm }, {
    onSuccess: () => { showForm.value = false; saving.value = false },
    onError: () => { saving.value = false },
  })
}

function confirmDelete(product) { toDelete.value = product }

function deleteProduct() {
  router.delete(route('commercial.products.destroy', toDelete.value.id), {
    onFinish: () => { toDelete.value = null },
  })
}

function syncFormations() {
  syncing.value = true
  router.post(route('commercial.products.sync'), {}, { onFinish: () => { syncing.value = false } })
}

function formatCurrency(val) {
  return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR', maximumFractionDigits: 2 }).format(val || 0)
}
</script>

<style scoped>
.product-card:hover { box-shadow:0 4px 24px rgba(13,27,42,0.08); transform:translateY(-2px); border-color:rgba(201,168,76,0.18) !important; }
.edit-btn:hover     { background:#FAF7F2 !important; color:#0D1B2A !important; }
.del-btn:hover      { background:rgba(239,68,68,0.12) !important; }
.sync-btn:hover     { background:rgba(201,168,76,0.15) !important; }
.new-btn:hover      { background:#1A2D42 !important; }
.cancel-btn:hover   { background:#FAF7F2 !important; }
.save-btn:hover:not(:disabled) { background:#E2C97E !important; }
.confirm-del-btn:hover { background:#DC2626 !important; }
.f-label  { display:block; font-size:12px; font-weight:700; color:#0D1B2A; margin-bottom:7px; }
.f-input  { width:100%; padding:11px 13px; border:1.5px solid rgba(13,27,42,0.12); border-radius:11px; font-size:14px; color:#0D1B2A; outline:none; font-family:inherit; background:#FAF7F2; transition:border-color 0.2s; box-sizing:border-box; }
.f-input:focus { border-color:rgba(201,168,76,0.6) !important; background:white !important; }
.spin { animation:spin 1s linear infinite; }
@keyframes spin { to { transform:rotate(360deg); } }
</style>