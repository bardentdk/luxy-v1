<template>
  <CommercialLayout>
    <Head title="Tableau de bord commercial" />

    <div style="display:flex; flex-direction:column; gap:24px;">

      <!-- ── KPIs ── -->
      <div style="display:grid; grid-template-columns:repeat(4,1fr); gap:16px;">

        <!-- CA Gagné -->
        <div style="background:white; border-radius:18px; padding:22px; border:1.5px solid rgba(13,27,42,0.07); position:relative; overflow:hidden;">
          <div style="position:absolute; top:0; left:0; right:0; height:3px; background:linear-gradient(90deg,#22C55E,#16A34A);"></div>
          <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:14px;">
            <div style="width:38px; height:38px; border-radius:11px; background:rgba(34,197,94,0.1); display:flex; align-items:center; justify-content:center;">
              <PhCurrencyEur style="width:18px; height:18px; color:#16A34A;" weight="fill" />
            </div>
            <span style="font-size:11px; font-weight:700; color:#16A34A; background:rgba(34,197,94,0.1); padding:3px 9px; border-radius:100px;">Gagné</span>
          </div>
          <div style="font-size:28px; font-weight:900; color:#0D1B2A; letter-spacing:-0.03em; line-height:1; margin-bottom:4px;">
            {{ formatCurrency(kpis.revenue_won) }}
          </div>
          <div style="font-size:12px; color:rgba(13,27,42,0.45);">Chiffre d'affaires</div>
        </div>

        <!-- Pipeline -->
        <div style="background:white; border-radius:18px; padding:22px; border:1.5px solid rgba(13,27,42,0.07); position:relative; overflow:hidden;">
          <div style="position:absolute; top:0; left:0; right:0; height:3px; background:linear-gradient(90deg,#C9A84C,#E2C97E);"></div>
          <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:14px;">
            <div style="width:38px; height:38px; border-radius:11px; background:rgba(201,168,76,0.1); display:flex; align-items:center; justify-content:center;">
              <PhFunnel style="width:18px; height:18px; color:#C9A84C;" weight="fill" />
            </div>
            <span style="font-size:11px; font-weight:700; color:#A07828; background:rgba(201,168,76,0.1); padding:3px 9px; border-radius:100px;">Pipeline</span>
          </div>
          <div style="font-size:28px; font-weight:900; color:#0D1B2A; letter-spacing:-0.03em; line-height:1; margin-bottom:4px;">
            {{ formatCurrency(kpis.revenue_pipeline) }}
          </div>
          <div style="font-size:12px; color:rgba(13,27,42,0.45);">{{ kpis.deals_in_progress }} deals en cours</div>
        </div>

        <!-- Contacts -->
        <div style="background:white; border-radius:18px; padding:22px; border:1.5px solid rgba(13,27,42,0.07); position:relative; overflow:hidden;">
          <div style="position:absolute; top:0; left:0; right:0; height:3px; background:linear-gradient(90deg,#6366F1,#818CF8);"></div>
          <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:14px;">
            <div style="width:38px; height:38px; border-radius:11px; background:rgba(99,102,241,0.1); display:flex; align-items:center; justify-content:center;">
              <PhUsers style="width:18px; height:18px; color:#6366F1;" weight="fill" />
            </div>
            <span style="font-size:11px; font-weight:700; color:#4F46E5; background:rgba(99,102,241,0.1); padding:3px 9px; border-radius:100px;">+{{ kpis.new_leads_month }} ce mois</span>
          </div>
          <div style="font-size:28px; font-weight:900; color:#0D1B2A; letter-spacing:-0.03em; line-height:1; margin-bottom:4px;">
            {{ kpis.total_contacts }}
          </div>
          <div style="font-size:12px; color:rgba(13,27,42,0.45);">Contacts CRM</div>
        </div>

        <!-- Taux de conversion -->
        <div style="background:white; border-radius:18px; padding:22px; border:1.5px solid rgba(13,27,42,0.07); position:relative; overflow:hidden;">
          <div style="position:absolute; top:0; left:0; right:0; height:3px; background:linear-gradient(90deg,#3B82F6,#60A5FA);"></div>
          <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:14px;">
            <div style="width:38px; height:38px; border-radius:11px; background:rgba(59,130,246,0.1); display:flex; align-items:center; justify-content:center;">
              <PhChartLineUp style="width:18px; height:18px; color:#3B82F6;" weight="fill" />
            </div>
            <span style="font-size:11px; font-weight:700; color:#2563EB; background:rgba(59,130,246,0.1); padding:3px 9px; border-radius:100px;">{{ kpis.deals_won }} gagnés</span>
          </div>
          <div style="font-size:28px; font-weight:900; color:#0D1B2A; letter-spacing:-0.03em; line-height:1; margin-bottom:4px;">
            {{ kpis.conversion_rate }}%
          </div>
          <div style="font-size:12px; color:rgba(13,27,42,0.45);">Taux de conversion</div>
        </div>
      </div>

      <!-- ── Ligne 2 : Pipeline + Activités ── -->
      <div style="display:grid; grid-template-columns:1.4fr 1fr; gap:16px;">

        <!-- Pipeline résumé -->
        <div style="background:white; border-radius:18px; padding:24px; border:1.5px solid rgba(13,27,42,0.07);">
          <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:20px;">
            <h2 style="font-size:15px; font-weight:800; color:#0D1B2A; margin:0; letter-spacing:-0.01em;">Résumé pipeline</h2>
            <Link :href="route('commercial.pipeline')" style="font-size:12px; font-weight:700; color:#C9A84C; text-decoration:none; display:flex; align-items:center; gap:5px;" class="view-link">
              Voir le Kanban <PhArrowRight style="width:13px; height:13px;" />
            </Link>
          </div>

          <div style="display:flex; flex-direction:column; gap:10px;">
            <div v-for="stage in pipeline" :key="stage.id">
              <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:5px;">
                <div style="display:flex; align-items:center; gap:8px;">
                  <div style="width:8px; height:8px; border-radius:50%;" :style="`background:${stage.color};`"></div>
                  <span style="font-size:13px; font-weight:600; color:#0D1B2A;">{{ stage.name }}</span>
                  <span style="font-size:11px; color:rgba(13,27,42,0.4);">{{ stage.deals_count }} deal{{ stage.deals_count > 1 ? 's' : '' }}</span>
                </div>
                <span style="font-size:13px; font-weight:700; color:#0D1B2A;">{{ formatCurrency(stage.deals_amount) }}</span>
              </div>
              <div style="height:5px; background:rgba(13,27,42,0.06); border-radius:3px; overflow:hidden;">
                <div
                  style="height:100%; border-radius:3px; transition:width 0.6s cubic-bezier(0.16,1,0.3,1);"
                  :style="`width:${stageWidth(stage)}%; background:${stage.color};`"
                ></div>
              </div>
            </div>
          </div>
        </div>

        <!-- Activités à faire -->
        <div style="background:white; border-radius:18px; padding:24px; border:1.5px solid rgba(13,27,42,0.07);">
          <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:20px;">
            <h2 style="font-size:15px; font-weight:800; color:#0D1B2A; margin:0;">Activités à faire</h2>
            <span style="font-size:11px; font-weight:700; background:rgba(239,68,68,0.1); color:#DC2626; padding:3px 9px; border-radius:100px;" v-if="todoActivities.length">
              {{ todoActivities.length }}
            </span>
          </div>

          <div v-if="todoActivities.length === 0" style="text-align:center; padding:32px 0; color:rgba(13,27,42,0.3);">
            <PhCheckCircle style="width:32px; height:32px; margin:0 auto 8px; display:block; color:rgba(34,197,94,0.4);" weight="fill" />
            <div style="font-size:13px;">Aucune activité en attente</div>
          </div>

          <div v-else style="display:flex; flex-direction:column; gap:8px;">
            <div
              v-for="activity in todoActivities"
              :key="activity.id"
              style="display:flex; align-items:flex-start; gap:10px; padding:10px; border-radius:11px; background:#FAF7F2; border:1px solid rgba(13,27,42,0.06);"
            >
              <div style="width:30px; height:30px; border-radius:8px; display:flex; align-items:center; justify-content:center; flex-shrink:0; background:rgba(201,168,76,0.12);">
                <PhCalendar style="width:14px; height:14px; color:#C9A84C;" weight="fill" />
              </div>
              <div style="flex:1; min-width:0;">
                <div style="font-size:12px; font-weight:700; color:#0D1B2A; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ activity.title }}</div>
                <div style="font-size:11px; color:rgba(13,27,42,0.4);">{{ activity.contact_name }} · {{ activity.scheduled_at }}</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- ── Ligne 3 : Contacts récents + Devis récents ── -->
      <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">

        <!-- Derniers contacts -->
        <div style="background:white; border-radius:18px; padding:24px; border:1.5px solid rgba(13,27,42,0.07);">
          <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:20px;">
            <h2 style="font-size:15px; font-weight:800; color:#0D1B2A; margin:0;">Derniers contacts</h2>
            <Link :href="route('commercial.contacts.index')" style="font-size:12px; font-weight:700; color:#C9A84C; text-decoration:none;" class="view-link">
              Voir tout →
            </Link>
          </div>

          <div style="display:flex; flex-direction:column; gap:10px;">
            <div
              v-for="contact in recentContacts"
              :key="contact.id"
              style="display:flex; align-items:center; gap:12px; padding:10px; border-radius:12px; transition:all 0.2s;"
              class="list-row"
            >
              <div
                style="width:36px; height:36px; border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0; font-size:13px; font-weight:900; color:white; background:linear-gradient(135deg,#0D1B2A,#1A2D42);"
              >
                {{ contact.initials }}
              </div>
              <div style="flex:1; min-width:0;">
                <Link :href="route('commercial.contacts.show', contact.id)" style="font-size:13px; font-weight:700; color:#0D1B2A; text-decoration:none; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; display:block;" class="name-link">
                  {{ contact.full_name }}
                </Link>
                <div style="font-size:11px; color:rgba(13,27,42,0.4);">{{ contact.email }}</div>
              </div>
              <span
                style="font-size:10px; font-weight:700; padding:3px 9px; border-radius:100px; flex-shrink:0;"
                :style="`background:${contact.status_color}18; color:${contact.status_color};`"
              >
                {{ contact.status_label }}
              </span>
            </div>
          </div>
        </div>

        <!-- Devis récents -->
        <div style="background:white; border-radius:18px; padding:24px; border:1.5px solid rgba(13,27,42,0.07);">
          <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:20px;">
            <h2 style="font-size:15px; font-weight:800; color:#0D1B2A; margin:0;">Devis récents</h2>
            <Link :href="route('commercial.quotes.index')" style="font-size:12px; font-weight:700; color:#C9A84C; text-decoration:none;" class="view-link">
              Voir tout →
            </Link>
          </div>

          <div style="display:flex; flex-direction:column; gap:10px;">
            <div
              v-for="quote in recentQuotes"
              :key="quote.id"
              style="display:flex; align-items:center; gap:12px; padding:10px; border-radius:12px; transition:all 0.2s;"
              class="list-row"
            >
              <div style="width:36px; height:36px; border-radius:10px; background:rgba(201,168,76,0.1); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <PhFileText style="width:16px; height:16px; color:#C9A84C;" weight="fill" />
              </div>
              <div style="flex:1; min-width:0;">
                <Link :href="route('commercial.quotes.show', quote.id)" style="font-size:13px; font-weight:700; color:#0D1B2A; text-decoration:none; display:block; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;" class="name-link">
                  {{ quote.reference }}
                </Link>
                <div style="font-size:11px; color:rgba(13,27,42,0.4);">{{ quote.contact_name }} · {{ quote.issued_at }}</div>
              </div>
              <div style="text-align:right; flex-shrink:0;">
                <div style="font-size:13px; font-weight:800; color:#0D1B2A;">{{ formatCurrency(quote.total) }}</div>
                <span
                  style="font-size:10px; font-weight:700; padding:2px 8px; border-radius:100px;"
                  :style="`background:${quote.status_color}18; color:${quote.status_color};`"
                >
                  {{ quote.status_label }}
                </span>
              </div>
            </div>
          </div>

          <!-- CTA nouveau devis -->
          <Link
            :href="route('commercial.quotes.create')"
            style="display:flex; align-items:center; justify-content:center; gap:8px; margin-top:16px; padding:11px; border-radius:12px; border:2px dashed rgba(201,168,76,0.25); background:rgba(201,168,76,0.04); color:#A07828; font-size:13px; font-weight:700; text-decoration:none; transition:all 0.2s;"
            class="add-quote-btn"
          >
            <PhPlus style="width:15px; height:15px;" />
            Nouveau devis
          </Link>
        </div>
      </div>
    </div>
  </CommercialLayout>
</template>

<script setup>
import { computed } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import CommercialLayout from '@/Layouts/CommercialLayout.vue'
import {
  PhCurrencyEur, PhFunnel, PhUsers, PhChartLineUp,
  PhArrowRight, PhCheckCircle, PhCalendar, PhFileText, PhPlus,
} from '@phosphor-icons/vue'

const props = defineProps({
  kpis:           Object,
  pipeline:       Array,
  todoActivities: Array,
  recentContacts: Array,
  recentQuotes:   Array,
})

function formatCurrency(val) {
  if (!val && val !== 0) return '—'
  return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR', maximumFractionDigits: 0 }).format(val)
}

const totalPipelineAmount = computed(() =>
  props.pipeline.reduce((s, st) => s + (st.deals_amount || 0), 0)
)

function stageWidth(stage) {
  if (!totalPipelineAmount.value) return 0
  return Math.round((stage.deals_amount / totalPipelineAmount.value) * 100)
}
</script>

<style scoped>
.view-link:hover { opacity:0.75; }
.list-row:hover  { background:#FAF7F2 !important; }
.name-link:hover { color:#C9A84C !important; }
.add-quote-btn:hover { background:rgba(201,168,76,0.1) !important; border-color:rgba(201,168,76,0.4) !important; }
</style>