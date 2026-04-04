<template>
  <CommercialLayout>
    <Head title="Devis" />

    <div style="display:flex; flex-direction:column; gap:20px;">

      <!-- Header -->
      <div style="display:flex; align-items:flex-start; justify-content:space-between; flex-wrap:wrap; gap:16px;">
        <div>
          <h1 style="font-size:20px; font-weight:900; color:#0D1B2A; letter-spacing:-0.02em; margin:0 0 3px;">Devis</h1>
          <p style="font-size:13px; color:rgba(13,27,42,0.45); margin:0;">{{ quotes.total }} devis au total</p>
        </div>
        <Link :href="route('commercial.quotes.create')" style="display:inline-flex; align-items:center; gap:8px; background:#C9A84C; color:#0D1B2A; font-weight:800; font-size:13px; padding:10px 20px; border-radius:12px; text-decoration:none; transition:all 0.2s;" class="new-btn">
          <PhPlus style="width:15px; height:15px;" />
          Nouveau devis
        </Link>
      </div>

      <!-- Filtres statut -->
      <div style="display:flex; gap:8px; flex-wrap:wrap;">
        <button
          v-for="s in statusFilters"
          :key="s.value"
          @click="filterByStatus(s.value)"
          style="display:inline-flex; align-items:center; gap:7px; padding:8px 16px; border-radius:100px; font-size:12px; font-weight:700; cursor:pointer; border:1.5px solid; transition:all 0.2s; font-family:inherit;"
          :style="localStatus === s.value
            ? `background:${s.color}18; color:${s.color}; border-color:${s.color}40;`
            : 'background:white; color:rgba(13,27,42,0.5); border-color:rgba(13,27,42,0.12);'"
        >
          <div v-if="s.value !== ''" style="width:7px; height:7px; border-radius:50%;" :style="`background:${s.color};`"></div>
          {{ s.label }}
        </button>
      </div>

      <!-- Tableau -->
      <div style="background:white; border-radius:18px; border:1.5px solid rgba(13,27,42,0.07); overflow:hidden;">
        <table style="width:100%; border-collapse:collapse;">
          <thead>
            <tr style="background:#FAF7F2; border-bottom:1px solid rgba(13,27,42,0.07);">
              <th class="th">Référence</th>
              <th class="th">Contact</th>
              <th class="th">Montant</th>
              <th class="th">Statut</th>
              <th class="th">Émis le</th>
              <th class="th">Expire le</th>
              <th class="th" style="text-align:right;">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="quote in quotes.data" :key="quote.id" style="border-bottom:1px solid rgba(13,27,42,0.05); transition:all 0.15s;" class="table-row">

              <td class="td">
                <Link :href="route('commercial.quotes.show', quote.id)" style="font-size:13px; font-weight:800; color:#0D1B2A; text-decoration:none; font-family:monospace;" class="ref-link">
                  {{ quote.reference }}
                </Link>
              </td>

              <td class="td">
                <span style="font-size:13px; color:rgba(13,27,42,0.7);">{{ quote.contact_name }}</span>
              </td>

              <td class="td">
                <span style="font-size:14px; font-weight:800; color:#0D1B2A;">{{ formatCurrency(quote.total) }}</span>
              </td>

              <td class="td">
                <span style="font-size:11px; font-weight:700; padding:4px 11px; border-radius:100px;" :style="`background:${quote.status_color}18; color:${quote.status_color};`">
                  {{ quote.status_label }}
                </span>
              </td>

              <td class="td"><span style="font-size:13px; color:rgba(13,27,42,0.55);">{{ quote.issued_at }}</span></td>
              <td class="td"><span style="font-size:13px; color:rgba(13,27,42,0.55);">{{ quote.expires_at }}</span></td>

              <td class="td" style="text-align:right;">
                <div style="display:flex; align-items:center; justify-content:flex-end; gap:6px;">
                  <Link :href="route('commercial.quotes.show', quote.id)" style="width:30px; height:30px; border-radius:8px; border:1.5px solid rgba(13,27,42,0.1); background:white; display:flex; align-items:center; justify-content:center; text-decoration:none; color:rgba(13,27,42,0.5); transition:all 0.15s;" class="icon-btn">
                    <PhEye style="width:13px; height:13px;" />
                  </Link>
                  <Link :href="route('commercial.quotes.edit', quote.id)" style="width:30px; height:30px; border-radius:8px; border:1.5px solid rgba(13,27,42,0.1); background:white; display:flex; align-items:center; justify-content:center; text-decoration:none; color:rgba(13,27,42,0.5); transition:all 0.15s;" class="icon-btn">
                    <PhPencil style="width:13px; height:13px;" />
                  </Link>
                  <a :href="route('commercial.quotes.pdf', quote.id)" target="_blank" style="width:30px; height:30px; border-radius:8px; border:1.5px solid rgba(239,68,68,0.2); background:rgba(239,68,68,0.06); display:flex; align-items:center; justify-content:center; text-decoration:none; color:#DC2626; transition:all 0.15s;" class="icon-btn-red">
                    <PhFilePdf style="width:13px; height:13px;" />
                  </a>
                </div>
              </td>
            </tr>

            <tr v-if="quotes.data.length === 0">
              <td colspan="7" style="padding:60px; text-align:center; color:rgba(13,27,42,0.3);">
                <PhFileText style="width:36px; height:36px; margin:0 auto 12px; display:block;" />
                <div style="font-size:14px; font-weight:700; margin-bottom:4px;">Aucun devis</div>
                <div style="font-size:13px;">Créez votre premier devis</div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </CommercialLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import CommercialLayout from '@/Layouts/CommercialLayout.vue'
import { PhPlus, PhEye, PhPencil, PhFilePdf, PhFileText } from '@phosphor-icons/vue'

const props = defineProps({ quotes: Object, filters: Object })

const localStatus = ref(props.filters?.status ?? '')

const statusFilters = [
  { value: '',         label: 'Tous',       color: '#0D1B2A' },
  { value: 'draft',    label: 'Brouillon',  color: '#6B7280' },
  { value: 'sent',     label: 'Envoyé',     color: '#F59E0B' },
  { value: 'accepted', label: 'Accepté',    color: '#22C55E' },
  { value: 'refused',  label: 'Refusé',     color: '#EF4444' },
  { value: 'expired',  label: 'Expiré',     color: '#9CA3AF' },
]

function filterByStatus(val) {
  localStatus.value = val
  router.get(route('commercial.quotes.index'), { status: val || undefined }, { preserveState: true, replace: true })
}

function formatCurrency(val) {
  return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR', maximumFractionDigits: 0 }).format(val || 0)
}
</script>

<style scoped>
.th { padding:12px 16px; text-align:left; font-size:11px; font-weight:700; color:rgba(13,27,42,0.4); text-transform:uppercase; letter-spacing:0.08em; }
.td { padding:14px 16px; }
.table-row:hover  { background:#FAFAF8 !important; }
.ref-link:hover   { color:#C9A84C !important; }
.icon-btn:hover   { background:#FAF7F2 !important; color:#0D1B2A !important; }
.icon-btn-red:hover { background:rgba(239,68,68,0.1) !important; }
.new-btn:hover    { background:#E2C97E !important; transform:translateY(-1px); }
</style>