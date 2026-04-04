<template>
  <CommercialLayout>
    <Head :title="contact.full_name" />

    <div style="display:flex; flex-direction:column; gap:20px; max-width:1100px;">

      <!-- Header -->
      <div style="display:flex; align-items:center; gap:16px; flex-wrap:wrap;">
        <Link :href="route('commercial.contacts.index')" style="width:36px; height:36px; border-radius:10px; border:1.5px solid rgba(13,27,42,0.12); display:flex; align-items:center; justify-content:center; color:rgba(13,27,42,0.5); text-decoration:none; transition:all 0.2s;" class="back-btn">
          <PhArrowLeft style="width:16px; height:16px;" />
        </Link>

        <div style="flex:1; display:flex; align-items:center; gap:16px; flex-wrap:wrap;">
          <div style="width:52px; height:52px; border-radius:14px; background:linear-gradient(135deg,#0D1B2A,#1A2D42); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
            <span style="font-size:18px; font-weight:900; color:#C9A84C;">{{ contact.initials }}</span>
          </div>
          <div>
            <h1 style="font-size:22px; font-weight:900; color:#0D1B2A; letter-spacing:-0.02em; margin:0 0 3px;">{{ contact.full_name }}</h1>
            <div style="display:flex; align-items:center; gap:10px; flex-wrap:wrap;">
              <span style="font-size:13px; color:rgba(13,27,42,0.5);">{{ contact.email }}</span>
              <span
                style="font-size:11px; font-weight:700; padding:3px 10px; border-radius:100px;"
                :style="`background:${contact.status_color}18; color:${contact.status_color};`"
              >
                {{ contact.status_label }}
              </span>
            </div>
          </div>
        </div>

        <div style="display:flex; gap:10px;">
          <Link
            :href="route('commercial.quotes.create', { contact_id: contact.id })"
            style="display:inline-flex; align-items:center; gap:7px; padding:10px 18px; border-radius:12px; border:1.5px solid rgba(201,168,76,0.25); background:rgba(201,168,76,0.08); color:#A07828; font-size:13px; font-weight:700; text-decoration:none; transition:all 0.2s;"
            class="quote-btn"
          >
            <PhFileText style="width:14px; height:14px;" />
            Nouveau devis
          </Link>
          <Link
            :href="route('commercial.contacts.edit', contact.id)"
            style="display:inline-flex; align-items:center; gap:7px; padding:10px 18px; border-radius:12px; border:none; background:#0D1B2A; color:white; font-size:13px; font-weight:700; text-decoration:none; transition:all 0.2s;"
            class="edit-btn"
          >
            <PhPencil style="width:14px; height:14px;" />
            Modifier
          </Link>
        </div>
      </div>

      <!-- Corps 2 colonnes -->
      <div style="display:grid; grid-template-columns:340px 1fr; gap:20px; align-items:start;">

        <!-- Colonne gauche : infos -->
        <div style="display:flex; flex-direction:column; gap:16px;">

          <!-- Infos contact -->
          <div style="background:white; border-radius:18px; padding:24px; border:1.5px solid rgba(13,27,42,0.07);">
            <h3 style="font-size:13px; font-weight:800; color:#0D1B2A; margin:0 0 18px; text-transform:uppercase; letter-spacing:0.08em;">Informations</h3>

            <div style="display:flex; flex-direction:column; gap:13px;">
              <InfoRow v-if="contact.phone"    icon="PhPhone"    label="Téléphone" :value="contact.phone" />
              <InfoRow icon="PhEnvelope" label="Email"     :value="contact.email" />
              <InfoRow v-if="contact.company"  icon="PhBuildings" label="Entreprise" :value="contact.company" />
              <InfoRow v-if="contact.job_title" icon="PhBriefcase" label="Poste"     :value="contact.job_title" />
              <InfoRow icon="PhCalendar" label="Créé le"   :value="contact.created_at" />
              <InfoRow v-if="contact.last_contacted_at" icon="PhClock" label="Dernier contact" :value="contact.last_contacted_at" />
            </div>
          </div>

          <!-- Notes -->
          <div v-if="contact.notes" style="background:white; border-radius:18px; padding:24px; border:1.5px solid rgba(13,27,42,0.07);">
            <h3 style="font-size:13px; font-weight:800; color:#0D1B2A; margin:0 0 12px; text-transform:uppercase; letter-spacing:0.08em;">Notes</h3>
            <p style="font-size:13px; color:rgba(13,27,42,0.65); line-height:1.7; margin:0; white-space:pre-line;">{{ contact.notes }}</p>
          </div>

          <!-- Deals liés -->
          <div style="background:white; border-radius:18px; padding:24px; border:1.5px solid rgba(13,27,42,0.07);">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:16px;">
              <h3 style="font-size:13px; font-weight:800; color:#0D1B2A; margin:0; text-transform:uppercase; letter-spacing:0.08em;">Deals ({{ contact.deals.length }})</h3>
            </div>
            <div v-if="!contact.deals.length" style="font-size:13px; color:rgba(13,27,42,0.3); text-align:center; padding:12px 0;">Aucun deal</div>
            <div v-else style="display:flex; flex-direction:column; gap:8px;">
              <Link
                v-for="deal in contact.deals"
                :key="deal.id"
                :href="route('commercial.deals.show', deal.id)"
                style="display:flex; align-items:center; justify-content:space-between; padding:10px 12px; border-radius:11px; background:#FAF7F2; border:1px solid rgba(13,27,42,0.06); text-decoration:none; transition:all 0.15s;"
                class="deal-row"
              >
                <div style="min-width:0;">
                  <div style="font-size:13px; font-weight:700; color:#0D1B2A; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ deal.title }}</div>
                  <div style="display:flex; align-items:center; gap:6px; margin-top:3px;">
                    <div style="width:7px; height:7px; border-radius:50%; flex-shrink:0;" :style="`background:${deal.stage?.color ?? '#6B7280'};`"></div>
                    <span style="font-size:11px; color:rgba(13,27,42,0.4);">{{ deal.stage?.name }}</span>
                  </div>
                </div>
                <span v-if="deal.amount" style="font-size:13px; font-weight:800; color:#C9A84C; flex-shrink:0; margin-left:10px;">
                  {{ formatCurrency(deal.amount) }}
                </span>
              </Link>
            </div>
          </div>

          <!-- Devis -->
          <div style="background:white; border-radius:18px; padding:24px; border:1.5px solid rgba(13,27,42,0.07);">
            <h3 style="font-size:13px; font-weight:800; color:#0D1B2A; margin:0 0 16px; text-transform:uppercase; letter-spacing:0.08em;">Devis ({{ contact.quotes.length }})</h3>
            <div v-if="!contact.quotes.length" style="font-size:13px; color:rgba(13,27,42,0.3); text-align:center; padding:12px 0;">Aucun devis</div>
            <div v-else style="display:flex; flex-direction:column; gap:8px;">
              <Link
                v-for="quote in contact.quotes"
                :key="quote.id"
                :href="route('commercial.quotes.show', quote.id)"
                style="display:flex; align-items:center; justify-content:space-between; padding:10px 12px; border-radius:11px; background:#FAF7F2; border:1px solid rgba(13,27,42,0.06); text-decoration:none; transition:all 0.15s;"
                class="deal-row"
              >
                <div>
                  <div style="font-size:13px; font-weight:700; color:#0D1B2A;">{{ quote.reference }}</div>
                  <div style="font-size:11px; color:rgba(13,27,42,0.4); margin-top:2px;">{{ quote.issued_at }}</div>
                </div>
                <div style="text-align:right;">
                  <div style="font-size:13px; font-weight:800; color:#0D1B2A;">{{ formatCurrency(quote.total) }}</div>
                  <span style="font-size:10px; font-weight:700; padding:2px 8px; border-radius:100px;" :style="`background:${quote.status_color}18; color:${quote.status_color};`">
                    {{ quote.status_label }}
                  </span>
                </div>
              </Link>
            </div>
          </div>
        </div>

        <!-- Colonne droite : activités + ajouter activité -->
        <div style="display:flex; flex-direction:column; gap:16px;">

          <!-- Ajouter activité -->
          <div style="background:white; border-radius:18px; padding:24px; border:1.5px solid rgba(13,27,42,0.07);">
            <h3 style="font-size:13px; font-weight:800; color:#0D1B2A; margin:0 0 18px; text-transform:uppercase; letter-spacing:0.08em;">Ajouter une activité</h3>

            <!-- Sélection type -->
            <div style="display:flex; gap:8px; margin-bottom:16px; flex-wrap:wrap;">
              <button
                v-for="t in activityTypes"
                :key="t.value"
                @click="activityForm.type = t.value"
                style="display:inline-flex; align-items:center; gap:6px; padding:7px 13px; border-radius:100px; font-size:12px; font-weight:700; cursor:pointer; border:1.5px solid; transition:all 0.2s; font-family:inherit;"
                :style="activityForm.type === t.value
                  ? `background:${t.color}18; color:${t.color}; border-color:${t.color}40;`
                  : 'background:white; color:rgba(13,27,42,0.5); border-color:rgba(13,27,42,0.12);'"
              >
                <component :is="t.icon" style="width:13px; height:13px;" />
                {{ t.label }}
              </button>
            </div>

            <form @submit.prevent="addActivity" style="display:flex; flex-direction:column; gap:12px;">
              <input v-model="activityForm.title" type="text" placeholder="Titre de l'activité..." required style="width:100%; padding:11px 13px; border:1.5px solid rgba(13,27,42,0.12); border-radius:11px; font-size:14px; color:#0D1B2A; outline:none; font-family:inherit; background:#FAF7F2; box-sizing:border-box; transition:border-color 0.2s;" class="f-input" />
              <textarea v-model="activityForm.body" rows="2" placeholder="Description (optionnel)..." style="width:100%; padding:11px 13px; border:1.5px solid rgba(13,27,42,0.12); border-radius:11px; font-size:14px; color:#0D1B2A; outline:none; font-family:inherit; background:#FAF7F2; box-sizing:border-box; resize:vertical; transition:border-color 0.2s;" class="f-input"></textarea>

              <div style="display:grid; grid-template-columns:1fr auto; gap:10px; align-items:end;">
                <div>
                  <label style="display:block; font-size:11px; font-weight:700; color:rgba(13,27,42,0.4); margin-bottom:5px; text-transform:uppercase; letter-spacing:0.06em;">Date planifiée</label>
                  <input v-model="activityForm.scheduled_at" type="datetime-local" style="width:100%; padding:9px 12px; border:1.5px solid rgba(13,27,42,0.12); border-radius:10px; font-size:13px; color:#0D1B2A; outline:none; font-family:inherit; background:#FAF7F2; box-sizing:border-box;" class="f-input" />
                </div>
                <button type="submit" :disabled="!activityForm.title" style="padding:10px 20px; border-radius:11px; border:none; background:#C9A84C; color:#0D1B2A; font-size:13px; font-weight:800; cursor:pointer; font-family:inherit; transition:all 0.2s; white-space:nowrap;" class="add-activity-btn">
                  Ajouter
                </button>
              </div>
            </form>
          </div>

          <!-- Timeline activités -->
          <div style="background:white; border-radius:18px; padding:24px; border:1.5px solid rgba(13,27,42,0.07);">
            <h3 style="font-size:13px; font-weight:800; color:#0D1B2A; margin:0 0 20px; text-transform:uppercase; letter-spacing:0.08em;">
              Activités ({{ contact.activities.length }})
            </h3>

            <div v-if="!contact.activities.length" style="text-align:center; padding:32px 0; color:rgba(13,27,42,0.25);">
              <PhClockCounterClockwise style="width:36px; height:36px; margin:0 auto 10px; display:block;" />
              <div style="font-size:13px;">Aucune activité enregistrée</div>
            </div>

            <div v-else style="position:relative;">
              <!-- Ligne verticale timeline -->
              <div style="position:absolute; left:17px; top:0; bottom:0; width:2px; background:rgba(13,27,42,0.06); border-radius:1px;"></div>

              <div style="display:flex; flex-direction:column; gap:16px;">
                <div
                  v-for="activity in contact.activities"
                  :key="activity.id"
                  style="display:flex; gap:16px; position:relative;"
                >
                  <!-- Icône timeline -->
                  <div
                    style="width:36px; height:36px; border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0; position:relative; z-index:1; border:2px solid white;"
                    :style="`background:${activity.type_color}18;`"
                  >
                    <PhNote      v-if="activity.type === 'note'"    style="width:14px; height:14px;" :style="`color:${activity.type_color};`" weight="fill" />
                    <PhPhone     v-else-if="activity.type === 'call'"    style="width:14px; height:14px;" :style="`color:${activity.type_color};`" weight="fill" />
                    <PhEnvelope  v-else-if="activity.type === 'email'"   style="width:14px; height:14px;" :style="`color:${activity.type_color};`" weight="fill" />
                    <PhCalendar  v-else-if="activity.type === 'meeting'" style="width:14px; height:14px;" :style="`color:${activity.type_color};`" weight="fill" />
                    <PhCheckSquare v-else style="width:14px; height:14px;" :style="`color:${activity.type_color};`" weight="fill" />
                  </div>

                  <!-- Contenu -->
                  <div style="flex:1; min-width:0; padding-bottom:16px; border-bottom:1px solid rgba(13,27,42,0.05);">
                    <div style="display:flex; align-items:flex-start; justify-content:space-between; gap:10px; margin-bottom:4px;">
                      <span style="font-size:13px; font-weight:700; color:#0D1B2A;">{{ activity.title }}</span>
                      <span :style="`color:${activity.is_done ? '#22C55E' : '#F59E0B'}; flex-shrink:0;`">
                        <PhCheckCircle v-if="activity.is_done" style="width:14px; height:14px;" weight="fill" />
                        <PhClock v-else style="width:14px; height:14px;" weight="fill" />
                      </span>
                    </div>
                    <div v-if="activity.body" style="font-size:12px; color:rgba(13,27,42,0.55); line-height:1.6; margin-bottom:6px;">{{ activity.body }}</div>
                    <div style="font-size:11px; color:rgba(13,27,42,0.35);">
                      {{ activity.user_name }} · {{ activity.created_at }}
                      <span v-if="activity.scheduled_at"> · 📅 {{ activity.scheduled_at }}</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </CommercialLayout>
</template>

<script setup>
import { ref, h, defineComponent } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import CommercialLayout from '@/Layouts/CommercialLayout.vue'
import {
  PhArrowLeft, PhPencil, PhFileText,
  PhPhone, PhEnvelope, PhBuildings, PhBriefcase,
  PhCalendar, PhClock, PhNote, PhCheckSquare,
  PhCheckCircle, PhClockCounterClockwise,
} from '@phosphor-icons/vue'

// InfoRow via h()
const InfoRow = defineComponent({
  name: 'InfoRow',
  props: { icon: String, label: String, value: String },
  setup(props) {
    return () => h('div', { style: 'display:flex; align-items:flex-start; gap:10px;' }, [
      h('div', {
        style: 'width:30px; height:30px; border-radius:8px; background:#FAF7F2; border:1px solid rgba(13,27,42,0.07); display:flex; align-items:center; justify-content:center; flex-shrink:0;',
      }, [
        h(PhClock, { style: 'width:13px; height:13px; color:rgba(13,27,42,0.4);' }),
      ]),
      h('div', {}, [
        h('div', { style: 'font-size:10px; font-weight:700; color:rgba(13,27,42,0.35); text-transform:uppercase; letter-spacing:0.08em; margin-bottom:2px;' }, props.label),
        h('div', { style: 'font-size:13px; color:#0D1B2A; font-weight:600;' }, props.value),
      ]),
    ])
  },
})

const props = defineProps({ contact: Object })

const activityTypes = [
  { value: 'note',    label: 'Note',    color: '#6366F1', icon: PhNote },
  { value: 'call',    label: 'Appel',   color: '#22C55E', icon: PhPhone },
  { value: 'email',   label: 'Email',   color: '#C9A84C', icon: PhEnvelope },
  { value: 'meeting', label: 'RDV',     color: '#3B82F6', icon: PhCalendar },
  { value: 'task',    label: 'Tâche',   color: '#F59E0B', icon: PhCheckSquare },
]

const activityForm = ref({ type: 'note', title: '', body: '', scheduled_at: '' })

function addActivity() {
  router.post(route('commercial.activities.store'), {
    crm_contact_id: props.contact.id,
    ...activityForm.value,
  }, {
    onSuccess: () => {
      activityForm.value.title        = ''
      activityForm.value.body         = ''
      activityForm.value.scheduled_at = ''
    },
    preserveScroll: true,
  })
}

function formatCurrency(val) {
  if (!val) return '—'
  return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR', maximumFractionDigits: 0 }).format(val)
}
</script>

<style scoped>
.back-btn:hover     { background:#FAF7F2 !important; color:#0D1B2A !important; }
.edit-btn:hover     { background:#1A2D42 !important; }
.quote-btn:hover    { background:rgba(201,168,76,0.18) !important; }
.deal-row:hover     { background:rgba(201,168,76,0.06) !important; border-color:rgba(201,168,76,0.2) !important; }
.f-input:focus      { border-color:rgba(201,168,76,0.5) !important; background:white !important; }
.add-activity-btn:hover:not(:disabled) { background:#E2C97E !important; }
</style>