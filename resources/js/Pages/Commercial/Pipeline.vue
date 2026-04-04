<template>
  <CommercialLayout>
    <Head title="Pipeline commercial" />

    <div style="display:flex; flex-direction:column; gap:20px; height:calc(100vh - 116px);">

      <!-- Header + Actions -->
      <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px; flex-shrink:0;">
        <div>
          <h1 style="font-size:20px; font-weight:900; color:#0D1B2A; letter-spacing:-0.02em; margin:0 0 3px;">Pipeline commercial</h1>
          <p style="font-size:13px; color:rgba(13,27,42,0.45); margin:0;">
            {{ totalDeals }} opportunité{{ totalDeals > 1 ? 's' : '' }} · {{ formatCurrency(totalAmount) }} au total
          </p>
        </div>

        <button
          @click="showNewDeal = true"
          style="display:inline-flex; align-items:center; gap:8px; background:#C9A84C; color:#0D1B2A; font-weight:800; font-size:13px; padding:10px 20px; border-radius:12px; border:none; cursor:pointer; font-family:inherit; transition:all 0.2s;"
          class="new-deal-btn"
        >
          <PhPlus style="width:15px; height:15px;" />
          Nouveau deal
        </button>
      </div>

      <!-- Kanban Board -->
      <div style="display:flex; gap:16px; overflow-x:auto; flex:1; padding-bottom:8px;">
        <div
          v-for="stage in pipeline"
          :key="stage.id"
          style="min-width:280px; max-width:280px; display:flex; flex-direction:column; background:#F4F6FA; border-radius:18px; overflow:hidden;"
        >
          <!-- Header colonne -->
          <div style="padding:14px 16px; display:flex; align-items:center; justify-content:space-between; flex-shrink:0;">
            <div style="display:flex; align-items:center; gap:8px;">
              <div style="width:10px; height:10px; border-radius:50%;" :style="`background:${stage.color};`"></div>
              <span style="font-size:13px; font-weight:800; color:#0D1B2A;">{{ stage.name }}</span>
            </div>
            <div style="display:flex; align-items:center; gap:8px;">
              <span style="font-size:11px; font-weight:700; color:rgba(13,27,42,0.4); background:rgba(13,27,42,0.08); padding:2px 8px; border-radius:100px;">
                {{ stage.count }}
              </span>
            </div>
          </div>

          <!-- Montant colonne -->
          <div style="padding:0 16px 12px; font-size:13px; font-weight:800; color:#0D1B2A; flex-shrink:0;">
            {{ formatCurrency(stage.total) }}
          </div>

          <!-- Cards deals -->
          <div
            style="flex:1; overflow-y:auto; padding:0 10px 10px; display:flex; flex-direction:column; gap:8px;"
            @dragover.prevent
            @drop="onDrop($event, stage.id)"
          >
            <div
              v-for="deal in stage.deals"
              :key="deal.id"
              draggable="true"
              @dragstart="onDragStart($event, deal.id)"
              style="background:white; border-radius:14px; padding:14px; border:1.5px solid rgba(13,27,42,0.07); cursor:grab; transition:all 0.2s; user-select:none;"
              class="deal-card"
            >
              <!-- Titre deal -->
              <div style="font-size:13px; font-weight:800; color:#0D1B2A; margin-bottom:8px; line-height:1.35; overflow:hidden; text-overflow:ellipsis; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical;">
                {{ deal.title }}
              </div>

              <!-- Contact -->
              <div v-if="deal.contact" style="display:flex; align-items:center; gap:7px; margin-bottom:10px;">
                <div style="width:22px; height:22px; border-radius:6px; background:linear-gradient(135deg,#C9A84C,#E2C97E); display:flex; align-items:center; justify-content:center; font-size:9px; font-weight:900; color:#0D1B2A; flex-shrink:0;">
                  {{ deal.contact.initials }}
                </div>
                <span style="font-size:11px; color:rgba(13,27,42,0.55); overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                  {{ deal.contact.full_name }}
                </span>
              </div>

              <!-- Footer card -->
              <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:6px;">
                <span v-if="deal.amount" style="font-size:13px; font-weight:900; color:#0D1B2A;">
                  {{ formatCurrency(deal.amount) }}
                </span>
                <span v-else style="font-size:12px; color:rgba(13,27,42,0.3);">Montant non défini</span>

                <div style="display:flex; align-items:center; gap:6px;">
                  <span v-if="deal.probability" style="font-size:10px; font-weight:700; color:rgba(13,27,42,0.4);">
                    {{ deal.probability }}%
                  </span>
                  <Link
                    :href="route('commercial.deals.show', deal.id)"
                    style="width:26px; height:26px; border-radius:7px; border:1px solid rgba(13,27,42,0.1); background:white; display:flex; align-items:center; justify-content:center; text-decoration:none; color:rgba(13,27,42,0.4); transition:all 0.15s;"
                    class="deal-link-btn"
                  >
                    <PhArrowSquareOut style="width:12px; height:12px;" />
                  </Link>
                </div>
              </div>
            </div>

            <!-- Zone drop vide -->
            <div
              v-if="stage.deals.length === 0"
              style="border:2px dashed rgba(13,27,42,0.1); border-radius:12px; padding:24px; text-align:center; color:rgba(13,27,42,0.25); font-size:12px;"
            >
              Glisser un deal ici
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal nouveau deal -->
    <div
      v-if="showNewDeal"
      style="position:fixed; inset:0; background:rgba(10,22,40,0.6); z-index:1000; display:flex; align-items:center; justify-content:center; padding:20px; backdrop-filter:blur(4px);"
      @click.self="showNewDeal = false"
    >
      <div style="background:white; border-radius:24px; padding:36px; width:100%; max-width:520px; box-shadow:0 32px 80px rgba(0,0,0,0.25);">
        <h2 style="font-size:20px; font-weight:900; color:#0D1B2A; margin-bottom:24px; letter-spacing:-0.02em;">Nouveau deal</h2>

        <form @submit.prevent="createDeal" style="display:flex; flex-direction:column; gap:16px;">

          <div>
            <label class="f-label">Titre <span style="color:#C9A84C;">*</span></label>
            <input v-model="dealForm.title" type="text" placeholder="Ex: Formation Excel — Entreprise X" required class="f-input" />
          </div>

          <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
            <div>
              <label class="f-label">Contact <span style="color:#C9A84C;">*</span></label>
              <select v-model="dealForm.crm_contact_id" required class="f-input">
                <option value="">Sélectionner...</option>
                <option v-for="c in contacts" :key="c.id" :value="c.id">
                  {{ c.first_name }} {{ c.last_name }}
                </option>
              </select>
            </div>
            <div>
              <label class="f-label">Étape <span style="color:#C9A84C;">*</span></label>
              <select v-model="dealForm.deal_stage_id" required class="f-input">
                <option v-for="s in stages" :key="s.id" :value="s.id">{{ s.name }}</option>
              </select>
            </div>
          </div>

          <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
            <div>
              <label class="f-label">Montant (€)</label>
              <input v-model="dealForm.amount" type="number" step="0.01" min="0" placeholder="0.00" class="f-input" />
            </div>
            <div>
              <label class="f-label">Probabilité (%)</label>
              <input v-model="dealForm.probability" type="number" min="0" max="100" placeholder="50" class="f-input" />
            </div>
          </div>

          <div>
            <label class="f-label">Formation liée</label>
            <select v-model="dealForm.formation_id" class="f-input">
              <option value="">Aucune</option>
              <option v-for="f in formations" :key="f.id" :value="f.id">{{ f.title }}</option>
            </select>
          </div>

          <div style="display:flex; gap:12px; padding-top:8px;">
            <button type="button" @click="showNewDeal = false" style="flex:1; padding:12px; border-radius:100px; border:1.5px solid rgba(13,27,42,0.15); background:transparent; font-size:14px; font-weight:700; color:#0D1B2A; cursor:pointer; font-family:inherit;" class="cancel-btn">
              Annuler
            </button>
            <button type="submit" :disabled="submitting" style="flex:2; padding:12px; border-radius:100px; border:none; background:#C9A84C; color:#0D1B2A; font-size:14px; font-weight:800; cursor:pointer; font-family:inherit; display:flex; align-items:center; justify-content:center; gap:8px;" class="submit-btn">
              <PhCircleNotch v-if="submitting" style="width:16px; height:16px;" class="spin" />
              <PhCheck v-else style="width:16px; height:16px;" weight="bold" />
              {{ submitting ? 'Création...' : 'Créer le deal' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </CommercialLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import CommercialLayout from '@/Layouts/CommercialLayout.vue'
import {
  PhPlus, PhArrowSquareOut, PhCircleNotch, PhCheck,
} from '@phosphor-icons/vue'

const props = defineProps({
  pipeline:   Array,
  stages:     Array,
  contacts:   Array,
  formations: Array,
})

// ── Stats globales ────────────────────────────────────────
const totalDeals = computed(() => props.pipeline.reduce((s, st) => s + st.count, 0))
const totalAmount = computed(() => props.pipeline.reduce((s, st) => s + (st.total || 0), 0))

function formatCurrency(val) {
  if (!val && val !== 0) return '—'
  return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR', maximumFractionDigits: 0 }).format(val)
}

// ── Drag & Drop ───────────────────────────────────────────
let draggingDealId = null

function onDragStart(event, dealId) {
  draggingDealId = dealId
  event.dataTransfer.effectAllowed = 'move'
}

function onDrop(event, stageId) {
  if (!draggingDealId) return
  router.patch(route('commercial.pipeline.move', draggingDealId), {
    stage_id: stageId,
  }, {
    preserveScroll: true,
    preserveState:  true,
  })
  draggingDealId = null
}

// ── Nouveau deal ──────────────────────────────────────────
const showNewDeal = ref(false)
const submitting  = ref(false)
const dealForm    = ref({
  title:          '',
  crm_contact_id: '',
  deal_stage_id:  props.stages[0]?.id ?? '',
  amount:         '',
  probability:    50,
  formation_id:   '',
})

function createDeal() {
  submitting.value = true
  router.post(route('commercial.deals.store'), dealForm.value, {
    onSuccess: () => {
      showNewDeal.value  = false
      submitting.value   = false
      dealForm.value.title  = ''
      dealForm.value.amount = ''
    },
    onError: () => { submitting.value = false },
  })
}
</script>

<style scoped>
.deal-card:hover    { box-shadow:0 4px 20px rgba(13,27,42,0.1); transform:translateY(-2px); border-color:rgba(201,168,76,0.2) !important; }
.deal-card:active   { cursor:grabbing; transform:scale(0.98); }
.deal-link-btn:hover{ background:#FAF7F2 !important; color:#C9A84C !important; }
.new-deal-btn:hover { background:#E2C97E !important; transform:translateY(-1px); box-shadow:0 6px 20px rgba(201,168,76,0.3); }
.cancel-btn:hover   { background:#FAF7F2 !important; }
.submit-btn:hover:not(:disabled) { background:#E2C97E !important; }
.f-label { display:block; font-size:12px; font-weight:700; color:#0D1B2A; margin-bottom:7px; }
.f-input { width:100%; padding:11px 13px; border:1.5px solid rgba(13,27,42,0.12); border-radius:11px; font-size:14px; color:#0D1B2A; outline:none; font-family:inherit; background:#FAF7F2; transition:border-color 0.2s; box-sizing:border-box; }
.f-input:focus { border-color:rgba(201,168,76,0.6); background:white; }
.spin { animation:spin 1s linear infinite; }
@keyframes spin { to { transform:rotate(360deg); } }
</style>