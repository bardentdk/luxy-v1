<template>
  <CommercialLayout>
    <Head title="Opportunités" />

    <div style="display:flex; flex-direction:column; gap:20px;">

      <!-- Header -->
      <div style="display:flex; align-items:flex-start; justify-content:space-between; flex-wrap:wrap; gap:16px;">
        <div>
          <h1 style="font-size:20px; font-weight:900; color:#0D1B2A; letter-spacing:-0.02em; margin:0 0 3px;">Opportunités</h1>
          <p style="font-size:13px; color:rgba(13,27,42,0.45); margin:0;">{{ deals.total }} deal{{ deals.total > 1 ? 's' : '' }} au total</p>
        </div>
        <Link
          :href="route('commercial.pipeline')"
          style="display:inline-flex; align-items:center; gap:8px; background:#C9A84C; color:#0D1B2A; font-weight:800; font-size:13px; padding:10px 20px; border-radius:12px; text-decoration:none; transition:all 0.2s;"
          class="pipeline-btn"
        >
          <PhFunnel style="width:15px; height:15px;" weight="fill" />
          Voir le Pipeline
        </Link>
      </div>

      <!-- Tableau -->
      <div style="background:white; border-radius:18px; border:1.5px solid rgba(13,27,42,0.07); overflow:hidden; box-shadow:0 1px 6px rgba(13,27,42,0.05);">
        <table style="width:100%; border-collapse:collapse;">
          <thead>
            <tr style="background:#FAF7F2; border-bottom:1px solid rgba(13,27,42,0.07);">
              <th class="th">Titre</th>
              <th class="th">Contact</th>
              <th class="th">Étape</th>
              <th class="th">Montant</th>
              <th class="th">Probabilité</th>
              <th class="th">Créé le</th>
              <th class="th" style="text-align:right;">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="deal in deals.data"
              :key="deal.id"
              style="border-bottom:1px solid rgba(13,27,42,0.05); transition:all 0.15s;"
              class="table-row"
            >
              <!-- Titre -->
              <td class="td">
                <Link
                  :href="route('commercial.deals.show', deal.id)"
                  style="font-size:13px; font-weight:700; color:#0D1B2A; text-decoration:none; display:block; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; max-width:240px;"
                  class="deal-link"
                >
                  {{ deal.title }}
                </Link>
              </td>

              <!-- Contact -->
              <td class="td">
                <span style="font-size:13px; color:rgba(13,27,42,0.65);">{{ deal.contact_name }}</span>
              </td>

              <!-- Étape -->
              <td class="td">
                <span
                  style="display:inline-flex; align-items:center; gap:6px; font-size:12px; font-weight:700; padding:4px 11px; border-radius:100px;"
                  :style="`background:${deal.stage.color}18; color:${deal.stage.color};`"
                >
                  <div style="width:7px; height:7px; border-radius:50%;" :style="`background:${deal.stage.color};`"></div>
                  {{ deal.stage.name }}
                </span>
              </td>

              <!-- Montant -->
              <td class="td">
                <span style="font-size:14px; font-weight:800; color:#0D1B2A;">
                  {{ deal.amount ? formatCurrency(deal.amount) : '—' }}
                </span>
              </td>

              <!-- Probabilité -->
              <td class="td">
                <div style="display:flex; align-items:center; gap:8px;">
                  <div style="width:60px; height:5px; background:rgba(13,27,42,0.08); border-radius:3px; overflow:hidden;">
                    <div
                      style="height:100%; border-radius:3px; transition:width 0.4s;"
                      :style="`width:${deal.probability}%; background:${probabilityColor(deal.probability)};`"
                    ></div>
                  </div>
                  <span style="font-size:12px; font-weight:700; color:rgba(13,27,42,0.55);">{{ deal.probability }}%</span>
                </div>
              </td>

              <!-- Date -->
              <td class="td">
                <span style="font-size:12px; color:rgba(13,27,42,0.45);">{{ deal.created_at }}</span>
              </td>

              <!-- Actions -->
              <td class="td" style="text-align:right;">
                <div style="display:flex; align-items:center; justify-content:flex-end; gap:6px;">
                  <Link
                    :href="route('commercial.deals.show', deal.id)"
                    style="width:30px; height:30px; border-radius:8px; border:1.5px solid rgba(13,27,42,0.1); background:white; display:flex; align-items:center; justify-content:center; text-decoration:none; color:rgba(13,27,42,0.5); transition:all 0.15s;"
                    class="icon-btn"
                    title="Voir"
                  >
                    <PhEye style="width:13px; height:13px;" />
                  </Link>
                  <Link
                    :href="route('commercial.quotes.create', { deal_id: deal.id })"
                    style="width:30px; height:30px; border-radius:8px; border:1.5px solid rgba(201,168,76,0.25); background:rgba(201,168,76,0.08); display:flex; align-items:center; justify-content:center; text-decoration:none; color:#A07828; transition:all 0.15s;"
                    class="icon-btn-gold"
                    title="Créer un devis"
                  >
                    <PhFileText style="width:13px; height:13px;" />
                  </Link>
                  <button
                    @click="confirmDelete(deal)"
                    style="width:30px; height:30px; border-radius:8px; border:1.5px solid rgba(239,68,68,0.2); background:rgba(239,68,68,0.06); display:flex; align-items:center; justify-content:center; cursor:pointer; color:#DC2626; transition:all 0.15s;"
                    class="icon-btn-danger"
                    title="Supprimer"
                  >
                    <PhTrash style="width:13px; height:13px;" />
                  </button>
                </div>
              </td>
            </tr>

            <!-- Vide -->
            <tr v-if="deals.data.length === 0">
              <td colspan="7" style="padding:60px; text-align:center;">
                <PhFunnel style="width:40px; height:40px; color:rgba(13,27,42,0.12); margin:0 auto 14px; display:block;" weight="fill" />
                <div style="font-size:15px; font-weight:700; color:rgba(13,27,42,0.35); margin-bottom:8px;">Aucun deal</div>
                <div style="font-size:13px; color:rgba(13,27,42,0.3); margin-bottom:24px;">Ajoutez des opportunités depuis le pipeline</div>
                <Link
                  :href="route('commercial.pipeline')"
                  style="display:inline-flex; align-items:center; gap:8px; background:#C9A84C; color:#0D1B2A; font-weight:800; font-size:13px; padding:11px 24px; border-radius:100px; text-decoration:none;"
                >
                  <PhFunnel style="width:14px; height:14px;" weight="fill" />
                  Ouvrir le Pipeline
                </Link>
              </td>
            </tr>
          </tbody>
        </table>

        <!-- Pagination -->
        <div v-if="deals.last_page > 1" style="padding:16px 20px; border-top:1px solid rgba(13,27,42,0.06); display:flex; align-items:center; justify-content:space-between;">
          <span style="font-size:13px; color:rgba(13,27,42,0.4);">{{ deals.from }}–{{ deals.to }} sur {{ deals.total }}</span>
          <div style="display:flex; gap:6px;">
            <Link
              v-for="page in deals.last_page"
              :key="page"
              :href="`?page=${page}`"
              style="width:32px; height:32px; border-radius:8px; border:1.5px solid; display:flex; align-items:center; justify-content:center; font-size:13px; font-weight:700; text-decoration:none; transition:all 0.15s;"
              :style="page === deals.current_page
                ? 'background:#0D1B2A; color:white; border-color:#0D1B2A;'
                : 'background:white; color:rgba(13,27,42,0.5); border-color:rgba(13,27,42,0.12);'"
            >
              {{ page }}
            </Link>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal suppression -->
    <div
      v-if="toDelete"
      style="position:fixed; inset:0; background:rgba(10,22,40,0.6); z-index:1000; display:flex; align-items:center; justify-content:center; padding:20px; backdrop-filter:blur(4px);"
      @click.self="toDelete = null"
    >
      <div style="background:white; border-radius:22px; padding:32px; max-width:420px; width:100%; box-shadow:0 32px 80px rgba(0,0,0,0.2);">
        <div style="width:48px; height:48px; border-radius:13px; background:#FEE2E2; display:flex; align-items:center; justify-content:center; margin-bottom:16px;">
          <PhTrash style="width:22px; height:22px; color:#EF4444;" />
        </div>
        <h3 style="font-size:18px; font-weight:900; color:#0D1B2A; margin-bottom:8px;">Supprimer ce deal ?</h3>
        <p style="font-size:14px; color:rgba(13,27,42,0.5); margin-bottom:24px; line-height:1.6;">
          <strong>{{ toDelete?.title }}</strong> sera supprimé définitivement.
        </p>
        <div style="display:flex; gap:10px;">
          <button
            @click="toDelete = null"
            style="flex:1; padding:12px; border-radius:100px; border:1.5px solid rgba(13,27,42,0.12); background:transparent; font-size:14px; font-weight:700; color:#0D1B2A; cursor:pointer; font-family:inherit;"
            class="cancel-btn"
          >
            Annuler
          </button>
          <button
            @click="deleteDeal"
            style="flex:1; padding:12px; border-radius:100px; border:none; background:#EF4444; color:white; font-size:14px; font-weight:700; cursor:pointer; font-family:inherit; transition:all 0.2s;"
            class="confirm-btn"
          >
            Supprimer
          </button>
        </div>
      </div>
    </div>
  </CommercialLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import CommercialLayout from '@/Layouts/CommercialLayout.vue'
import { PhFunnel, PhEye, PhFileText, PhTrash } from '@phosphor-icons/vue'

const props = defineProps({
  deals: Object,
})

const toDelete = ref(null)

function formatCurrency(val) {
  return new Intl.NumberFormat('fr-FR', {
    style: 'currency', currency: 'EUR', maximumFractionDigits: 0,
  }).format(val)
}

function probabilityColor(prob) {
  if (prob >= 75) return '#22C55E'
  if (prob >= 50) return '#C9A84C'
  if (prob >= 25) return '#F59E0B'
  return '#EF4444'
}

function confirmDelete(deal) {
  toDelete.value = deal
}

function deleteDeal() {
  router.delete(route('commercial.deals.destroy', toDelete.value.id), {
    onFinish: () => { toDelete.value = null },
  })
}
</script>

<style scoped>
.th           { padding:12px 16px; text-align:left; font-size:11px; font-weight:700; color:rgba(13,27,42,0.4); text-transform:uppercase; letter-spacing:0.08em; }
.td           { padding:14px 16px; }
.table-row:hover   { background:#FAFAF8 !important; }
.deal-link:hover   { color:#C9A84C !important; }
.icon-btn:hover    { background:#FAF7F2 !important; color:#0D1B2A !important; }
.icon-btn-gold:hover   { background:rgba(201,168,76,0.18) !important; }
.icon-btn-danger:hover { background:rgba(239,68,68,0.12) !important; }
.pipeline-btn:hover    { background:#E2C97E !important; transform:translateY(-1px); }
.cancel-btn:hover  { background:#FAF7F2 !important; }
.confirm-btn:hover { background:#DC2626 !important; }
</style>