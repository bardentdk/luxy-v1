<template>
  <CommercialLayout>
    <Head :title="isEdit ? 'Modifier le devis' : 'Nouveau devis'" />

    <div style="max-width:900px; display:flex; flex-direction:column; gap:20px;">

      <!-- Header -->
      <div style="display:flex; align-items:center; gap:14px;">
        <Link :href="route('commercial.quotes.index')" style="width:36px; height:36px; border-radius:10px; border:1.5px solid rgba(13,27,42,0.12); display:flex; align-items:center; justify-content:center; color:rgba(13,27,42,0.5); text-decoration:none;" class="back-btn">
          <PhArrowLeft style="width:16px; height:16px;" />
        </Link>
        <div>
          <h1 style="font-size:20px; font-weight:900; color:#0D1B2A; letter-spacing:-0.02em; margin:0 0 2px;">
            {{ isEdit ? `Modifier ${quote?.reference}` : 'Nouveau devis' }}
          </h1>
        </div>
      </div>

      <form @submit.prevent="submit" style="display:flex; flex-direction:column; gap:20px;">

        <!-- Section contact + dates -->
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">

          <!-- Contact -->
          <div style="background:white; border-radius:18px; padding:24px; border:1.5px solid rgba(13,27,42,0.07);">
            <h2 class="sec-title">Contact</h2>
            <div style="display:flex; flex-direction:column; gap:14px;">
              <div>
                <label class="f-label">Contact <span style="color:#C9A84C;">*</span></label>
                <select v-model="form.crm_contact_id" required class="f-input" :disabled="isEdit">
                  <option value="">Sélectionner un contact...</option>
                  <option v-for="c in contacts" :key="c.id" :value="c.id">
                    {{ c.first_name }} {{ c.last_name }} {{ c.company ? `(${c.company})` : '' }}
                  </option>
                </select>
              </div>
              <div>
                <label class="f-label">Deal lié</label>
                <select v-model="form.deal_id" class="f-input">
                  <option value="">Aucun</option>
                  <option v-for="d in filteredDeals" :key="d.id" :value="d.id">{{ d.title }}</option>
                </select>
              </div>
            </div>
          </div>

          <!-- Dates + remise -->
          <div style="background:white; border-radius:18px; padding:24px; border:1.5px solid rgba(13,27,42,0.07);">
            <h2 class="sec-title">Paramètres</h2>
            <div style="display:flex; flex-direction:column; gap:14px;">
              <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                <div>
                  <label class="f-label">Date d'émission <span style="color:#C9A84C;">*</span></label>
                  <input v-model="form.issued_at" type="date" required class="f-input" />
                </div>
                <div>
                  <label class="f-label">Date d'expiration <span style="color:#C9A84C;">*</span></label>
                  <input v-model="form.expires_at" type="date" required class="f-input" />
                </div>
              </div>
              <div>
                <label class="f-label">Remise globale (%)</label>
                <div style="position:relative;">
                  <input v-model="form.discount_percent" type="number" step="0.1" min="0" max="100" placeholder="0" class="f-input" style="padding-right:36px;" />
                  <span style="position:absolute; right:12px; top:50%; transform:translateY(-50%); font-size:14px; font-weight:700; color:rgba(13,27,42,0.4);">%</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Lignes de devis -->
        <div style="background:white; border-radius:18px; padding:24px; border:1.5px solid rgba(13,27,42,0.07);">
          <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:20px;">
            <h2 class="sec-title" style="margin:0;">Lignes du devis</h2>
            <div style="display:flex; gap:8px;">
              <button
                v-if="products.length"
                type="button"
                @click="showProductPicker = true"
                style="display:inline-flex; align-items:center; gap:6px; padding:8px 14px; border-radius:10px; border:1.5px solid rgba(201,168,76,0.25); background:rgba(201,168,76,0.08); color:#A07828; font-size:12px; font-weight:700; cursor:pointer; font-family:inherit; transition:all 0.2s;"
                class="add-product-btn"
              >
                <PhPackage style="width:13px; height:13px;" />
                Ajouter depuis le catalogue
              </button>
              <button
                type="button"
                @click="addLine()"
                style="display:inline-flex; align-items:center; gap:6px; padding:8px 14px; border-radius:10px; border:1.5px solid rgba(13,27,42,0.12); background:white; color:rgba(13,27,42,0.6); font-size:12px; font-weight:700; cursor:pointer; font-family:inherit; transition:all 0.2s;"
                class="add-line-btn"
              >
                <PhPlus style="width:13px; height:13px;" />
                Ligne manuelle
              </button>
            </div>
          </div>

          <!-- Tableau lignes -->
          <div style="display:flex; flex-direction:column; gap:8px; margin-bottom:20px;">
            <div style="display:grid; grid-template-columns:3fr 1fr 1fr 1fr 1fr auto; gap:8px; padding:8px 12px;">
              <span class="col-head">Désignation</span>
              <span class="col-head">Prix unit.</span>
              <span class="col-head">Qté</span>
              <span class="col-head">TVA (%)</span>
              <span class="col-head">Total HT</span>
              <span></span>
            </div>

            <div
              v-for="(item, i) in form.items"
              :key="i"
              style="display:grid; grid-template-columns:3fr 1fr 1fr 1fr 1fr auto; gap:8px; padding:10px 12px; background:#FAF7F2; border-radius:12px; border:1px solid rgba(13,27,42,0.06); align-items:center;"
            >
              <div>
                <input v-model="item.name" type="text" placeholder="Nom du produit/service..." class="line-input" @input="recalcLine(i)" />
                <input v-model="item.description" type="text" placeholder="Description..." class="line-input" style="margin-top:5px; font-size:11px; color:rgba(13,27,42,0.5);" />
              </div>
              <input v-model.number="item.unit_price" type="number" step="0.01" min="0" placeholder="0.00" class="line-input" @input="recalcLine(i)" />
              <input v-model.number="item.quantity" type="number" step="0.01" min="0.01" placeholder="1" class="line-input" @input="recalcLine(i)" />
              <input v-model.number="item.tax_rate" type="number" step="0.1" min="0" max="100" placeholder="0" class="line-input" @input="recalcLine(i)" />
              <span style="font-size:14px; font-weight:800; color:#0D1B2A; text-align:right;">{{ formatCurrency(item.total || 0) }}</span>
              <button type="button" @click="removeLine(i)" style="width:28px; height:28px; border-radius:8px; border:1px solid rgba(239,68,68,0.2); background:rgba(239,68,68,0.06); display:flex; align-items:center; justify-content:center; cursor:pointer; color:#DC2626; flex-shrink:0;" class="remove-btn">
                <PhMinus style="width:12px; height:12px;" />
              </button>
            </div>

            <div v-if="form.items.length === 0" style="text-align:center; padding:32px; border:2px dashed rgba(13,27,42,0.1); border-radius:12px; color:rgba(13,27,42,0.3);">
              <PhPlus style="width:24px; height:24px; margin:0 auto 8px; display:block;" />
              <div style="font-size:13px;">Ajoutez des lignes à votre devis</div>
            </div>
          </div>

          <!-- Totaux -->
          <div style="border-top:1px solid rgba(13,27,42,0.07); padding-top:16px;">
            <div style="display:flex; flex-direction:column; gap:8px; max-width:280px; margin-left:auto;">
              <div style="display:flex; justify-content:space-between; font-size:13px; color:rgba(13,27,42,0.55);">
                <span>Sous-total HT</span>
                <span>{{ formatCurrency(computedSubtotal) }}</span>
              </div>
              <div v-if="form.discount_percent > 0" style="display:flex; justify-content:space-between; font-size:13px; color:#EF4444;">
                <span>Remise ({{ form.discount_percent }}%)</span>
                <span>- {{ formatCurrency(computedSubtotal * form.discount_percent / 100) }}</span>
              </div>
              <div style="display:flex; justify-content:space-between; font-size:13px; color:rgba(13,27,42,0.55);">
                <span>TVA</span>
                <span>{{ formatCurrency(computedTax) }}</span>
              </div>
              <div style="display:flex; justify-content:space-between; font-size:17px; font-weight:900; color:#0D1B2A; border-top:2px solid rgba(13,27,42,0.1); padding-top:10px; margin-top:4px;">
                <span>Total TTC</span>
                <span style="color:#C9A84C;">{{ formatCurrency(computedTotal) }}</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Notes + CGV -->
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
          <div style="background:white; border-radius:18px; padding:24px; border:1.5px solid rgba(13,27,42,0.07);">
            <label class="f-label">Notes au client</label>
            <textarea v-model="form.notes" rows="4" placeholder="Informations complémentaires pour le client..." class="f-textarea"></textarea>
          </div>
          <div style="background:white; border-radius:18px; padding:24px; border:1.5px solid rgba(13,27,42,0.07);">
            <label class="f-label">Conditions générales</label>
            <textarea v-model="form.terms" rows="4" placeholder="Conditions de paiement, délais, garanties..." class="f-textarea"></textarea>
          </div>
        </div>

        <!-- Actions -->
        <div style="display:flex; justify-content:flex-end; gap:12px; padding-bottom:40px;">
          <Link :href="route('commercial.quotes.index')" style="display:inline-flex; align-items:center; padding:12px 24px; border-radius:12px; border:1.5px solid rgba(13,27,42,0.12); background:white; font-size:14px; font-weight:700; color:rgba(13,27,42,0.6); text-decoration:none;" class="cancel-btn">
            Annuler
          </Link>
          <button type="submit" :disabled="form.processing || form.items.length === 0" style="display:inline-flex; align-items:center; gap:8px; padding:12px 28px; border-radius:12px; border:none; background:#C9A84C; color:#0D1B2A; font-size:14px; font-weight:800; cursor:pointer; font-family:inherit; transition:all 0.2s;" class="submit-btn">
            <PhCircleNotch v-if="form.processing" style="width:16px; height:16px;" class="spin" />
            <PhFloppyDisk v-else style="width:16px; height:16px;" />
            {{ form.processing ? 'Enregistrement...' : (isEdit ? 'Mettre à jour' : 'Créer le devis') }}
          </button>
        </div>
      </form>

      <!-- Modal catalogue produits -->
      <div v-if="showProductPicker" style="position:fixed; inset:0; background:rgba(10,22,40,0.55); z-index:1000; display:flex; align-items:center; justify-content:center; padding:20px; backdrop-filter:blur(4px);" @click.self="showProductPicker = false">
        <div style="background:white; border-radius:22px; padding:28px; width:100%; max-width:480px; max-height:80vh; overflow-y:auto; box-shadow:0 24px 60px rgba(0,0,0,0.2);">
          <h3 style="font-size:16px; font-weight:900; color:#0D1B2A; margin-bottom:18px;">Choisir un produit</h3>
          <div style="display:flex; flex-direction:column; gap:8px;">
            <button
              v-for="product in products"
              :key="product.id"
              type="button"
              @click="addFromProduct(product)"
              style="display:flex; align-items:center; justify-content:space-between; padding:14px 16px; border-radius:12px; border:1.5px solid rgba(13,27,42,0.08); background:white; cursor:pointer; text-align:left; transition:all 0.2s; font-family:inherit;"
              class="product-row"
            >
              <div>
                <div style="font-size:13px; font-weight:700; color:#0D1B2A;">{{ product.name }}</div>
                <div style="font-size:11px; color:rgba(13,27,42,0.4); margin-top:2px;">{{ product.reference || 'Sans référence' }} · par {{ product.unit }}</div>
              </div>
              <span style="font-size:14px; font-weight:800; color:#C9A84C; flex-shrink:0; margin-left:12px;">{{ formatCurrency(product.unit_price) }}</span>
            </button>
          </div>
        </div>
      </div>
    </div>
  </CommercialLayout>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import CommercialLayout from '@/Layouts/CommercialLayout.vue'
import { PhArrowLeft, PhPlus, PhMinus, PhPackage, PhCircleNotch, PhFloppyDisk } from '@phosphor-icons/vue'

const props = defineProps({
  quote:             Object,
  contacts:          Array,
  deals:             Array,
  products:          Array,
  preselect_contact: [String, Number],
  preselect_deal:    [String, Number],
})

const isEdit = computed(() => !!props.quote?.id)
const showProductPicker = ref(false)

const defaultItem = () => ({ product_id: null, name: '', description: '', unit_price: 0, quantity: 1, unit: 'formation', tax_rate: 0, discount_percent: 0, total: 0 })

const form = useForm({
  crm_contact_id:   props.quote?.crm_contact_id ?? props.preselect_contact ?? '',
  deal_id:          props.quote?.deal_id         ?? props.preselect_deal    ?? '',
  issued_at:        props.quote?.issued_at       ?? new Date().toISOString().split('T')[0],
  expires_at:       props.quote?.expires_at      ?? new Date(Date.now() + 30 * 86400000).toISOString().split('T')[0],
  discount_percent: props.quote?.discount_percent ?? 0,
  notes:            props.quote?.notes            ?? '',
  terms:            props.quote?.terms            ?? 'Paiement à réception de facture. Devis valable 30 jours.',
  items:            props.quote?.items?.map(i => ({ ...i })) ?? [defaultItem()],
})

const filteredDeals = computed(() =>
  props.deals.filter(d => !form.crm_contact_id || d.crm_contact_id == form.crm_contact_id)
)

// ── Calculs ───────────────────────────────────────────────
function recalcLine(i) {
  const item = form.items[i]
  const base     = (item.unit_price || 0) * (item.quantity || 1)
  const discount = base * ((item.discount_percent || 0) / 100)
  item.total = Math.round((base - discount) * 100) / 100
}

const computedSubtotal = computed(() => {
  const raw = form.items.reduce((s, i) => s + (i.total || 0), 0)
  return Math.round(raw * 100) / 100
})

const computedTax = computed(() => {
  return Math.round(form.items.reduce((s, i) => {
    return s + (i.total || 0) * ((i.tax_rate || 0) / 100)
  }, 0) * 100) / 100
})

const computedTotal = computed(() => {
  const sub      = computedSubtotal.value
  const discount = sub * ((form.discount_percent || 0) / 100)
  return Math.round((sub - discount + computedTax.value) * 100) / 100
})

// ── Lignes ────────────────────────────────────────────────
function addLine() {
  form.items.push(defaultItem())
}

function removeLine(i) {
  form.items.splice(i, 1)
}

function addFromProduct(product) {
  form.items.push({
    product_id:       product.id,
    name:             product.name,
    description:      '',
    unit_price:       parseFloat(product.unit_price),
    quantity:         1,
    unit:             product.unit,
    tax_rate:         parseFloat(product.tax_rate),
    discount_percent: 0,
    total:            parseFloat(product.unit_price),
  })
  showProductPicker.value = false
}

function formatCurrency(val) {
  return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR', maximumFractionDigits: 2 }).format(val || 0)
}

function submit() {
  const data = { ...form.data(), items: form.items }
  if (isEdit.value) {
    form.transform(() => data).put(route('commercial.quotes.update', props.quote.id))
  } else {
    form.transform(() => data).post(route('commercial.quotes.store'))
  }
}
</script>

<style scoped>
.sec-title { font-size:13px; font-weight:800; color:#0D1B2A; margin:0 0 18px; text-transform:uppercase; letter-spacing:0.08em; }
.col-head  { font-size:10px; font-weight:700; color:rgba(13,27,42,0.4); text-transform:uppercase; letter-spacing:0.08em; }
.f-label   { display:block; font-size:12px; font-weight:700; color:#0D1B2A; margin-bottom:7px; }
.f-input   { width:100%; padding:11px 13px; border:1.5px solid rgba(13,27,42,0.12); border-radius:11px; font-size:14px; color:#0D1B2A; outline:none; font-family:inherit; background:#FAF7F2; transition:border-color 0.2s; box-sizing:border-box; }
.f-input:focus  { border-color:rgba(201,168,76,0.6) !important; background:white !important; }
.f-textarea { width:100%; padding:11px 13px; border:1.5px solid rgba(13,27,42,0.12); border-radius:11px; font-size:13px; color:#0D1B2A; outline:none; font-family:inherit; background:#FAF7F2; resize:vertical; box-sizing:border-box; transition:border-color 0.2s; }
.f-textarea:focus { border-color:rgba(201,168,76,0.6) !important; background:white !important; outline:none; }
.line-input { width:100%; padding:8px 10px; border:1px solid rgba(13,27,42,0.1); border-radius:8px; font-size:13px; color:#0D1B2A; outline:none; font-family:inherit; background:white; box-sizing:border-box; }
.line-input:focus { border-color:rgba(201,168,76,0.5) !important; outline:none; }
.back-btn:hover    { background:#FAF7F2 !important; color:#0D1B2A !important; }
.cancel-btn:hover  { background:#FAF7F2 !important; }
.submit-btn:hover:not(:disabled) { background:#E2C97E !important; transform:translateY(-1px); }
.submit-btn:disabled { opacity:0.5; cursor:not-allowed; }
.add-product-btn:hover { background:rgba(201,168,76,0.15) !important; }
.add-line-btn:hover    { background:#FAF7F2 !important; }
.remove-btn:hover      { background:rgba(239,68,68,0.12) !important; }
.product-row:hover     { background:#FAF7F2 !important; border-color:rgba(201,168,76,0.25) !important; }
.spin { animation:spin 1s linear infinite; }
@keyframes spin { to { transform:rotate(360deg); } }
</style>