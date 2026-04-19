<template>
  <CommercialLayout>
    <Head :title="quote.reference" />

    <div style="display:flex; flex-direction:column; gap:20px; max-width:900px;">

      <!-- Header -->
      <div style="display:flex; align-items:center; gap:14px; flex-wrap:wrap;">
        <Link
          :href="route('commercial.quotes.index')"
          style="width:36px; height:36px; border-radius:10px; border:1.5px solid rgba(13,27,42,0.12); display:flex; align-items:center; justify-content:center; color:rgba(13,27,42,0.5); text-decoration:none; transition:all 0.2s;"
          class="back-btn"
        >
          <PhArrowLeft style="width:16px; height:16px;" />
        </Link>

        <div style="flex:1; min-width:0;">
          <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap; margin-bottom:4px;">
            <h1 style="font-size:20px; font-weight:900; color:#0D1B2A; letter-spacing:-0.02em; margin:0; font-family:monospace;">
              {{ quote.reference }}
            </h1>
            <span
              style="font-size:12px; font-weight:700; padding:4px 12px; border-radius:100px;"
              :style="`background:${quote.status_color}18; color:${quote.status_color};`"
            >
              {{ quote.status_label }}
            </span>
          </div>
          <div style="font-size:13px; color:rgba(13,27,42,0.45);">
            Émis le {{ formatDate(quote.issued_at) }} · Expire le {{ formatDate(quote.expires_at) }}
            <span v-if="quote.sent_at"> · Envoyé le {{ quote.sent_at }}</span>
          </div>
        </div>

        <!-- Actions -->
        <div style="display:flex; gap:10px; flex-shrink:0; flex-wrap:wrap;">
          <!-- Envoyer par email -->
          <button
            v-if="quote.status === 'draft'"
            @click="sendQuote"
            :disabled="sending"
            style="display:inline-flex; align-items:center; gap:7px; padding:10px 18px; border-radius:12px; border:none; background:#0D1B2A; color:white; font-size:13px; font-weight:700; cursor:pointer; font-family:inherit; transition:all 0.2s;"
            class="send-btn"
          >
            <PhCircleNotch v-if="sending" style="width:14px; height:14px;" class="spin" />
            <PhPaperPlaneTilt v-else style="width:14px; height:14px;" />
            {{ sending ? 'Envoi...' : 'Envoyer au client' }}
          </button>

          <!-- Télécharger PDF -->
          <a
            :href="route('commercial.quotes.pdf', quote.id)"
            target="_blank"
            style="display:inline-flex; align-items:center; gap:7px; padding:10px 18px; border-radius:12px; border:1.5px solid rgba(239,68,68,0.2); background:rgba(239,68,68,0.06); color:#DC2626; font-size:13px; font-weight:700; text-decoration:none; transition:all 0.2s;"
            class="pdf-btn"
          >
            <PhFilePdf style="width:14px; height:14px;" />
            PDF
          </a>

          <!-- Modifier -->
          <Link
            v-if="['draft', 'sent'].includes(quote.status)"
            :href="route('commercial.quotes.edit', quote.id)"
            style="display:inline-flex; align-items:center; gap:7px; padding:10px 18px; border-radius:12px; border:1.5px solid rgba(13,27,42,0.12); background:white; color:rgba(13,27,42,0.65); font-size:13px; font-weight:700; text-decoration:none; transition:all 0.2s;"
            class="edit-btn"
          >
            <PhPencil style="width:14px; height:14px;" />
            Modifier
          </Link>
        </div>
      </div>

      <!-- Changer le statut -->
      <div v-if="quote.status !== 'draft'" style="background:white; border-radius:16px; padding:16px 20px; border:1.5px solid rgba(13,27,42,0.07); display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
        <span style="font-size:12px; font-weight:700; color:rgba(13,27,42,0.4); text-transform:uppercase; letter-spacing:0.08em; flex-shrink:0;">Marquer comme :</span>
        <div style="display:flex; gap:8px; flex-wrap:wrap;">
          <button
            v-for="s in availableStatuses"
            :key="s.value"
            @click="updateStatus(s.value)"
            :disabled="quote.status === s.value"
            style="padding:7px 14px; border-radius:100px; font-size:12px; font-weight:700; cursor:pointer; border:1.5px solid; transition:all 0.2s; font-family:inherit;"
            :style="quote.status === s.value
              ? `background:${s.color}18; color:${s.color}; border-color:${s.color}40; cursor:default;`
              : 'background:white; color:rgba(13,27,42,0.5); border-color:rgba(13,27,42,0.12);'"
            class="status-btn"
          >
            {{ s.label }}
          </button>
        </div>
      </div>

      <!-- Corps -->
      <div style="display:grid; grid-template-columns:1fr 280px; gap:20px; align-items:start;">

        <!-- Gauche : lignes + totaux -->
        <div style="display:flex; flex-direction:column; gap:16px;">

          <!-- Informations client -->
          <div style="background:white; border-radius:18px; padding:24px; border:1.5px solid rgba(13,27,42,0.07);">
            <div style="display:flex; align-items:flex-start; justify-content:space-between; flex-wrap:wrap; gap:16px;">
              <div>
                <div style="font-size:10px; font-weight:700; color:rgba(13,27,42,0.35); text-transform:uppercase; letter-spacing:0.1em; margin-bottom:8px;">Destinataire</div>
                <div style="font-size:15px; font-weight:800; color:#0D1B2A; margin-bottom:3px;">{{ quote.contact.full_name }}</div>
                <div style="font-size:13px; color:rgba(13,27,42,0.55);">{{ quote.contact.email }}</div>
                <div v-if="quote.contact.company" style="font-size:13px; color:rgba(13,27,42,0.55);">{{ quote.contact.company }}</div>
                <div v-if="quote.contact.phone" style="font-size:13px; color:rgba(13,27,42,0.55);">{{ quote.contact.phone }}</div>
              </div>
              <div v-if="quote.deal" style="text-align:right;">
                <div style="font-size:10px; font-weight:700; color:rgba(13,27,42,0.35); text-transform:uppercase; letter-spacing:0.1em; margin-bottom:8px;">Deal lié</div>
                <Link :href="route('commercial.deals.show', quote.deal.id)" style="font-size:13px; font-weight:700; color:#C9A84C; text-decoration:none;">
                  {{ quote.deal.title }}
                </Link>
              </div>
            </div>
          </div>

          <!-- Lignes du devis -->
          <div style="background:white; border-radius:18px; border:1.5px solid rgba(13,27,42,0.07); overflow:hidden;">
            <div style="padding:18px 24px; border-bottom:1px solid rgba(13,27,42,0.07);">
              <h2 style="font-size:13px; font-weight:800; color:#0D1B2A; margin:0; text-transform:uppercase; letter-spacing:0.08em;">Lignes</h2>
            </div>

            <table style="width:100%; border-collapse:collapse;">
              <thead>
                <tr style="background:#FAF7F2;">
                  <th class="th">Désignation</th>
                  <th class="th" style="text-align:right;">P.U. HT</th>
                  <th class="th" style="text-align:center;">Qté</th>
                  <th class="th" style="text-align:center;">TVA</th>
                  <th class="th" style="text-align:right;">Total HT</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="item in quote.items"
                  :key="item.id"
                  style="border-top:1px solid rgba(13,27,42,0.05);"
                >
                  <td class="td">
                    <div style="font-size:13px; font-weight:700; color:#0D1B2A;">{{ item.name }}</div>
                    <div v-if="item.description" style="font-size:11px; color:rgba(13,27,42,0.45); margin-top:2px;">{{ item.description }}</div>
                    <div v-if="item.discount_percent > 0" style="font-size:11px; color:#EF4444; margin-top:2px;">Remise {{ item.discount_percent }}%</div>
                  </td>
                  <td class="td" style="text-align:right; font-size:13px; color:rgba(13,27,42,0.7);">{{ formatCurrency(item.unit_price) }}</td>
                  <td class="td" style="text-align:center; font-size:13px; color:rgba(13,27,42,0.7);">{{ item.quantity }} {{ item.unit }}</td>
                  <td class="td" style="text-align:center; font-size:13px; color:rgba(13,27,42,0.7);">{{ item.tax_rate > 0 ? item.tax_rate + '%' : '—' }}</td>
                  <td class="td" style="text-align:right; font-size:14px; font-weight:800; color:#0D1B2A;">{{ formatCurrency(item.total) }}</td>
                </tr>
              </tbody>
            </table>

            <!-- Totaux -->
            <div style="padding:20px 24px; border-top:2px solid rgba(13,27,42,0.07); background:#FAF7F2;">
              <div style="max-width:260px; margin-left:auto; display:flex; flex-direction:column; gap:8px;">
                <div style="display:flex; justify-content:space-between; font-size:13px; color:rgba(13,27,42,0.55);">
                  <span>Sous-total HT</span>
                  <span>{{ formatCurrency(quote.subtotal) }}</span>
                </div>
                <div v-if="quote.discount_percent > 0" style="display:flex; justify-content:space-between; font-size:13px; color:#EF4444;">
                  <span>Remise ({{ quote.discount_percent }}%)</span>
                  <span>— {{ formatCurrency(quote.subtotal * quote.discount_percent / 100) }}</span>
                </div>
                <div style="display:flex; justify-content:space-between; font-size:13px; color:rgba(13,27,42,0.55);">
                  <span>TVA</span>
                  <span>{{ formatCurrency(quote.tax_total) }}</span>
                </div>
                <div style="display:flex; justify-content:space-between; font-size:18px; font-weight:900; color:#0D1B2A; border-top:2px solid rgba(13,27,42,0.1); padding-top:12px; margin-top:4px;">
                  <span>Total TTC</span>
                  <span style="color:#C9A84C;">{{ formatCurrency(quote.total) }}</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Notes + CGV -->
          <div v-if="quote.notes || quote.terms" style="display:grid; gap:16px;" :style="quote.notes && quote.terms ? 'grid-template-columns:1fr 1fr;' : 'grid-template-columns:1fr;'">
            <div v-if="quote.notes" style="background:white; border-radius:18px; padding:22px; border:1.5px solid rgba(13,27,42,0.07);">
              <div style="font-size:11px; font-weight:700; color:rgba(13,27,42,0.35); text-transform:uppercase; letter-spacing:0.1em; margin-bottom:10px;">Notes</div>
              <p style="font-size:13px; color:rgba(13,27,42,0.65); line-height:1.65; margin:0; white-space:pre-line;">{{ quote.notes }}</p>
            </div>
            <div v-if="quote.terms" style="background:white; border-radius:18px; padding:22px; border:1.5px solid rgba(13,27,42,0.07);">
              <div style="font-size:11px; font-weight:700; color:rgba(13,27,42,0.35); text-transform:uppercase; letter-spacing:0.1em; margin-bottom:10px;">Conditions</div>
              <p style="font-size:13px; color:rgba(13,27,42,0.65); line-height:1.65; margin:0; white-space:pre-line;">{{ quote.terms }}</p>
            </div>
          </div>
        </div>

        <!-- Sidebar droite -->
        <div style="display:flex; flex-direction:column; gap:14px;">

          <!-- Résumé financier -->
          <div style="background:#0D1B2A; border-radius:16px; padding:20px; border-bottom:3px solid #C9A84C; text-align:center;">
            <div style="font-size:10px; color:rgba(255,255,255,0.35); text-transform:uppercase; letter-spacing:2px; margin-bottom:8px;">Total TTC</div>
            <div style="font-size:30px; font-weight:900; color:#C9A84C; letter-spacing:-0.02em; line-height:1;">
              {{ formatCurrency(quote.total) }}
            </div>
            <div style="font-size:11px; color:rgba(255,255,255,0.3); margin-top:6px;">dont {{ formatCurrency(quote.tax_total) }} de TVA</div>
          </div>

          <!-- Infos clés -->
          <div style="background:white; border-radius:16px; border:1.5px solid rgba(13,27,42,0.07); overflow:hidden;">
            <div style="background:#0D1B2A; padding:10px 16px;">
              <div style="font-size:10px; font-weight:800; color:#C9A84C; text-transform:uppercase; letter-spacing:0.12em;">Infos devis</div>
            </div>
            <div style="padding:0;">
              <div class="info-row">
                <span class="info-key">Créé par</span>
                <span class="info-val">{{ quote.created_by }}</span>
              </div>
              <div class="info-row">
                <span class="info-key">Émis le</span>
                <!-- <span class="info-val">{{ quote.issued_at }}</span> -->
                 <span class="info-val">{{ formatDate(quote.issued_at) }}</span>

              </div>
              <div class="info-row">
                <span class="info-key">Expire le</span>
                <!-- <span class="info-val">{{ quote.expires_at }}</span> -->
                 <span class="info-val">{{ formatDate(quote.expires_at) }}</span>

              </div>
              <div v-if="quote.sent_at" class="info-row">
                <span class="info-key">Envoyé le</span>
                <span class="info-val">{{ quote.sent_at }}</span>
              </div>
              <div v-if="quote.accepted_at" class="info-row">
                <span class="info-key">Accepté le</span>
                <span class="info-val" style="color:#22C55E;">{{ quote.accepted_at }}</span>
              </div>
            </div>
          </div>

          <!-- Actions rapides -->
          <div style="background:white; border-radius:16px; padding:16px; border:1.5px solid rgba(13,27,42,0.07); display:flex; flex-direction:column; gap:8px;">
            <Link
              :href="route('commercial.contacts.show', quote.contact.id)"
              style="display:flex; align-items:center; gap:10px; padding:10px 12px; border-radius:11px; background:#FAF7F2; border:1px solid rgba(13,27,42,0.06); text-decoration:none; transition:all 0.15s; font-size:13px; font-weight:600; color:#0D1B2A;"
              class="action-row"
            >
              <PhUser style="width:15px; height:15px; color:rgba(13,27,42,0.4); flex-shrink:0;" />
              Voir le contact
            </Link>
            <Link
              v-if="quote.deal"
              :href="route('commercial.deals.show', quote.deal.id)"
              style="display:flex; align-items:center; gap:10px; padding:10px 12px; border-radius:11px; background:#FAF7F2; border:1px solid rgba(13,27,42,0.06); text-decoration:none; transition:all 0.15s; font-size:13px; font-weight:600; color:#0D1B2A;"
              class="action-row"
            >
              <PhFunnel style="width:15px; height:15px; color:rgba(13,27,42,0.4); flex-shrink:0;" weight="fill" />
              Voir le deal
            </Link>
            <button
              @click="confirmDelete"
              style="display:flex; align-items:center; gap:10px; padding:10px 12px; border-radius:11px; background:rgba(239,68,68,0.05); border:1px solid rgba(239,68,68,0.15); cursor:pointer; font-family:inherit; font-size:13px; font-weight:600; color:#DC2626; transition:all 0.15s; width:100%;"
              class="delete-row"
            >
              <PhTrash style="width:15px; height:15px; flex-shrink:0;" />
              Supprimer le devis
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal suppression -->
    <div
      v-if="showDelete"
      style="position:fixed; inset:0; background:rgba(10,22,40,0.6); z-index:1000; display:flex; align-items:center; justify-content:center; padding:20px; backdrop-filter:blur(4px);"
      @click.self="showDelete = false"
    >
      <div style="background:white; border-radius:22px; padding:32px; max-width:400px; width:100%;">
        <div style="width:48px; height:48px; border-radius:13px; background:#FEE2E2; display:flex; align-items:center; justify-content:center; margin-bottom:16px;">
          <PhTrash style="width:22px; height:22px; color:#EF4444;" />
        </div>
        <h3 style="font-size:18px; font-weight:900; color:#0D1B2A; margin-bottom:8px;">Supprimer ce devis ?</h3>
        <p style="font-size:14px; color:rgba(13,27,42,0.5); margin-bottom:24px; line-height:1.6;">
          <strong>{{ quote.reference }}</strong> sera supprimé définitivement.
        </p>
        <div style="display:flex; gap:10px;">
          <button @click="showDelete = false" style="flex:1; padding:12px; border-radius:100px; border:1.5px solid rgba(13,27,42,0.12); background:transparent; font-size:14px; font-weight:700; color:#0D1B2A; cursor:pointer; font-family:inherit;" class="cancel-btn">
            Annuler
          </button>
          <button @click="deleteQuote" style="flex:1; padding:12px; border-radius:100px; border:none; background:#EF4444; color:white; font-size:14px; font-weight:700; cursor:pointer; font-family:inherit; transition:all 0.2s;" class="confirm-btn">
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
import {
  PhArrowLeft, PhPaperPlaneTilt, PhFilePdf, PhPencil,
  PhTrash, PhUser, PhFunnel, PhCircleNotch,
} from '@phosphor-icons/vue'

const props = defineProps({ quote: Object })

const sending    = ref(false)
const showDelete = ref(false)

const availableStatuses = [
  { value: 'sent',     label: 'Envoyé',   color: '#F59E0B' },
  { value: 'accepted', label: 'Accepté',  color: '#22C55E' },
  { value: 'refused',  label: 'Refusé',   color: '#EF4444' },
  { value: 'expired',  label: 'Expiré',   color: '#9CA3AF' },
]
function formatDate(dateStr) {
  if (!dateStr) return '—'
  const d = new Date(dateStr)
  return d.toLocaleDateString('fr-FR', { day: 'numeric', month: 'long', year: 'numeric' })
}
function formatCurrency(val) {
  return new Intl.NumberFormat('fr-FR', {
    style: 'currency', currency: 'EUR', maximumFractionDigits: 2,
  }).format(val || 0)
}

function sendQuote() {
  sending.value = true
  router.post(route('commercial.quotes.send', props.quote.id), {}, {
    onFinish: () => { sending.value = false },
    preserveScroll: true,
  })
}

function updateStatus(status) {
  router.patch(route('commercial.quotes.status', props.quote.id), { status }, { preserveScroll: true })
}

function confirmDelete() { showDelete.value = true }

function deleteQuote() {
  router.delete(route('commercial.quotes.destroy', props.quote.id))
}
</script>

<style scoped>
.th { padding:11px 16px; text-align:left; font-size:10px; font-weight:700; color:rgba(13,27,42,0.4); text-transform:uppercase; letter-spacing:0.08em; }
.td { padding:13px 16px; vertical-align:top; }
.info-row { display:flex; justify-content:space-between; align-items:center; padding:10px 16px; border-bottom:1px solid rgba(13,27,42,0.05); }
.info-key { font-size:11px; color:rgba(13,27,42,0.4); font-weight:600; text-transform:uppercase; letter-spacing:0.06em; }
.info-val { font-size:12px; font-weight:700; color:#0D1B2A; text-align:right; }
.back-btn:hover    { background:#FAF7F2 !important; color:#0D1B2A !important; }
.send-btn:hover:not(:disabled) { background:#1A2D42 !important; transform:translateY(-1px); }
.pdf-btn:hover     { background:rgba(239,68,68,0.1) !important; }
.edit-btn:hover    { background:#FAF7F2 !important; }
.status-btn:hover:not(:disabled) { background:#FAF7F2 !important; border-color:rgba(13,27,42,0.25) !important; color:#0D1B2A !important; }
.action-row:hover  { background:rgba(201,168,76,0.06) !important; border-color:rgba(201,168,76,0.2) !important; }
.delete-row:hover  { background:rgba(239,68,68,0.1) !important; }
.cancel-btn:hover  { background:#FAF7F2 !important; }
.confirm-btn:hover { background:#DC2626 !important; }
.spin { animation:spin 1s linear infinite; }
@keyframes spin { to { transform:rotate(360deg); } }
</style>