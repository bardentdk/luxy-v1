<template>
  <CommercialLayout>
    <Head :title="deal.title" />

    <div style="display:flex; flex-direction:column; gap:20px; max-width:1000px;">

      <!-- Header -->
      <div style="display:flex; align-items:center; gap:14px; flex-wrap:wrap;">
        <Link
          :href="route('commercial.pipeline')"
          style="width:36px; height:36px; border-radius:10px; border:1.5px solid rgba(13,27,42,0.12); display:flex; align-items:center; justify-content:center; color:rgba(13,27,42,0.5); text-decoration:none; transition:all 0.2s;"
          class="back-btn"
        >
          <PhArrowLeft style="width:16px; height:16px;" />
        </Link>

        <div style="flex:1; min-width:0;">
          <h1 style="font-size:20px; font-weight:900; color:#0D1B2A; letter-spacing:-0.02em; margin:0 0 4px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
            {{ deal.title }}
          </h1>
          <div style="display:flex; align-items:center; gap:10px; flex-wrap:wrap;">
            <span
              style="display:inline-flex; align-items:center; gap:6px; font-size:12px; font-weight:700; padding:4px 12px; border-radius:100px;"
              :style="`background:${deal.stage.color}18; color:${deal.stage.color};`"
            >
              <div style="width:7px; height:7px; border-radius:50%;" :style="`background:${deal.stage.color};`"></div>
              {{ deal.stage.name }}
            </span>
            <span v-if="deal.amount" style="font-size:14px; font-weight:800; color:#C9A84C;">
              {{ formatCurrency(deal.amount) }}
            </span>
            <span style="font-size:12px; color:rgba(13,27,42,0.4);">{{ deal.probability }}% de probabilité</span>
          </div>
        </div>

        <div style="display:flex; gap:10px; flex-shrink:0;">
          <Link
            :href="route('commercial.quotes.create', { deal_id: deal.id })"
            style="display:inline-flex; align-items:center; gap:7px; padding:10px 18px; border-radius:12px; border:1.5px solid rgba(201,168,76,0.25); background:rgba(201,168,76,0.08); color:#A07828; font-size:13px; font-weight:700; text-decoration:none; transition:all 0.2s;"
            class="quote-btn"
          >
            <PhFileText style="width:14px; height:14px;" />
            Créer un devis
          </Link>
        </div>
      </div>

      <!-- Corps 2 colonnes -->
      <div style="display:grid; grid-template-columns:320px 1fr; gap:20px; align-items:start;">

        <!-- Colonne gauche -->
        <div style="display:flex; flex-direction:column; gap:16px;">

          <!-- Infos deal -->
          <div style="background:white; border-radius:18px; padding:24px; border:1.5px solid rgba(13,27,42,0.07);">
            <h3 style="font-size:12px; font-weight:800; color:#0D1B2A; margin:0 0 18px; text-transform:uppercase; letter-spacing:0.1em;">Détails</h3>

            <div style="display:flex; flex-direction:column; gap:13px;">
              <div v-if="deal.contact">
                <div class="info-label">Contact</div>
                <Link :href="route('commercial.contacts.show', deal.contact.id)" style="font-size:13px; font-weight:700; color:#C9A84C; text-decoration:none;">
                  {{ deal.contact.full_name }}
                </Link>
                <div style="font-size:11px; color:rgba(13,27,42,0.4);">{{ deal.contact.email }}</div>
              </div>

              <div v-if="deal.amount">
                <div class="info-label">Montant</div>
                <div style="font-size:16px; font-weight:900; color:#0D1B2A;">{{ formatCurrency(deal.amount) }}</div>
              </div>

              <div v-if="deal.expected_close_date">
                <div class="info-label">Clôture prévue</div>
                <div style="font-size:13px; font-weight:600; color:#0D1B2A;">{{ deal.expected_close_date }}</div>
              </div>

              <div v-if="deal.formation">
                <div class="info-label">Formation liée</div>
                <div style="font-size:13px; font-weight:600; color:#0D1B2A;">{{ deal.formation.title }}</div>
              </div>

              <div v-if="deal.notes">
                <div class="info-label">Notes</div>
                <div style="font-size:13px; color:rgba(13,27,42,0.65); line-height:1.65; white-space:pre-line;">{{ deal.notes }}</div>
              </div>
            </div>
          </div>

          <!-- Changer l'étape -->
          <div style="background:white; border-radius:18px; padding:24px; border:1.5px solid rgba(13,27,42,0.07);">
            <h3 style="font-size:12px; font-weight:800; color:#0D1B2A; margin:0 0 14px; text-transform:uppercase; letter-spacing:0.1em;">Déplacer le deal</h3>
            <div style="display:flex; flex-direction:column; gap:7px;">
              <button
                v-for="stage in stages"
                :key="stage.id"
                @click="moveToStage(stage)"
                :disabled="stage.id === deal.stage.id"
                style="display:flex; align-items:center; gap:10px; padding:10px 12px; border-radius:11px; border:1.5px solid; cursor:pointer; font-family:inherit; font-size:13px; font-weight:700; transition:all 0.2s; text-align:left;"
                :style="stage.id === deal.stage.id
                  ? `border-color:${stage.color}; background:${stage.color}18; color:${stage.color}; cursor:default;`
                  : 'border-color:rgba(13,27,42,0.1); background:white; color:rgba(13,27,42,0.55);'"
                class="stage-btn"
              >
                <div style="width:10px; height:10px; border-radius:50%; flex-shrink:0;" :style="`background:${stage.color};`"></div>
                {{ stage.name }}
                <span v-if="stage.id === deal.stage.id" style="margin-left:auto; font-size:10px; font-weight:700; opacity:0.6;">Actuel</span>
              </button>
            </div>
          </div>

          <!-- Devis liés -->
          <div style="background:white; border-radius:18px; padding:24px; border:1.5px solid rgba(13,27,42,0.07);">
            <h3 style="font-size:12px; font-weight:800; color:#0D1B2A; margin:0 0 14px; text-transform:uppercase; letter-spacing:0.1em;">
              Devis ({{ deal.quotes.length }})
            </h3>
            <div v-if="!deal.quotes.length" style="font-size:13px; color:rgba(13,27,42,0.3); text-align:center; padding:12px 0;">Aucun devis</div>
            <div v-else style="display:flex; flex-direction:column; gap:8px;">
              <Link
                v-for="quote in deal.quotes"
                :key="quote.id"
                :href="route('commercial.quotes.show', quote.id)"
                style="display:flex; align-items:center; justify-content:space-between; padding:10px 12px; border-radius:11px; background:#FAF7F2; border:1px solid rgba(13,27,42,0.06); text-decoration:none; transition:all 0.15s;"
                class="quote-row"
              >
                <div style="font-size:13px; font-weight:700; color:#0D1B2A; font-family:monospace;">{{ quote.reference }}</div>
                <div style="display:flex; align-items:center; gap:8px;">
                  <span style="font-size:11px; font-weight:700; padding:2px 8px; border-radius:100px;" :style="`background:${quote.status_color}18; color:${quote.status_color};`">
                    {{ quote.status_label }}
                  </span>
                </div>
              </Link>
            </div>
          </div>
        </div>

        <!-- Colonne droite : activités -->
        <div style="display:flex; flex-direction:column; gap:16px;">

          <!-- Ajouter activité -->
          <div style="background:white; border-radius:18px; padding:24px; border:1.5px solid rgba(13,27,42,0.07);">
            <h3 style="font-size:12px; font-weight:800; color:#0D1B2A; margin:0 0 18px; text-transform:uppercase; letter-spacing:0.1em;">Ajouter une activité</h3>

            <div style="display:flex; gap:8px; margin-bottom:14px; flex-wrap:wrap;">
              <button
                v-for="t in activityTypes"
                :key="t.value"
                @click="activityForm.type = t.value"
                style="display:inline-flex; align-items:center; gap:5px; padding:6px 12px; border-radius:100px; font-size:12px; font-weight:700; cursor:pointer; border:1.5px solid; transition:all 0.2s; font-family:inherit;"
                :style="activityForm.type === t.value
                  ? `background:${t.color}18; color:${t.color}; border-color:${t.color}40;`
                  : 'background:white; color:rgba(13,27,42,0.5); border-color:rgba(13,27,42,0.12);'"
              >
                <component :is="t.icon" style="width:12px; height:12px;" />
                {{ t.label }}
              </button>
            </div>

            <form @submit.prevent="addActivity" style="display:flex; flex-direction:column; gap:10px;">
              <input v-model="activityForm.title" type="text" placeholder="Titre de l'activité..." required class="f-input" />
              <textarea v-model="activityForm.body" rows="2" placeholder="Notes (optionnel)..." class="f-input" style="resize:vertical;"></textarea>
              <div style="display:flex; gap:10px; align-items:flex-end;">
                <div style="flex:1;">
                  <label class="f-label">Date planifiée</label>
                  <input v-model="activityForm.scheduled_at" type="datetime-local" class="f-input" />
                </div>
                <button type="submit" :disabled="!activityForm.title" style="padding:10px 20px; border-radius:11px; border:none; background:#C9A84C; color:#0D1B2A; font-size:13px; font-weight:800; cursor:pointer; font-family:inherit; white-space:nowrap; transition:all 0.2s;" class="add-btn">
                  Ajouter
                </button>
              </div>
            </form>
          </div>

          <!-- Timeline -->
          <div style="background:white; border-radius:18px; padding:24px; border:1.5px solid rgba(13,27,42,0.07);">
            <h3 style="font-size:12px; font-weight:800; color:#0D1B2A; margin:0 0 20px; text-transform:uppercase; letter-spacing:0.1em;">
              Historique ({{ deal.activities.length }})
            </h3>

            <div v-if="!deal.activities.length" style="text-align:center; padding:28px 0; color:rgba(13,27,42,0.25);">
              <PhClockCounterClockwise style="width:32px; height:32px; margin:0 auto 8px; display:block;" />
              <div style="font-size:13px;">Aucune activité</div>
            </div>

            <div v-else style="position:relative;">
              <div style="position:absolute; left:17px; top:0; bottom:0; width:2px; background:rgba(13,27,42,0.06); border-radius:1px;"></div>
              <div style="display:flex; flex-direction:column; gap:14px;">
                <div v-for="activity in deal.activities" :key="activity.id" style="display:flex; gap:14px;">
                  <div
                    style="width:36px; height:36px; border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0; position:relative; z-index:1; border:2px solid white;"
                    :style="`background:${activity.type_color}18;`"
                  >
                    <PhNote        v-if="activity.type === 'note'"    style="width:14px; height:14px;" :style="`color:${activity.type_color};`" weight="fill" />
                    <PhPhone       v-else-if="activity.type === 'call'"    style="width:14px; height:14px;" :style="`color:${activity.type_color};`" weight="fill" />
                    <PhEnvelope    v-else-if="activity.type === 'email'"   style="width:14px; height:14px;" :style="`color:${activity.type_color};`" weight="fill" />
                    <PhCalendar    v-else-if="activity.type === 'meeting'" style="width:14px; height:14px;" :style="`color:${activity.type_color};`" weight="fill" />
                    <PhCheckSquare v-else style="width:14px; height:14px;" :style="`color:${activity.type_color};`" weight="fill" />
                  </div>

                  <div style="flex:1; min-width:0; padding-bottom:14px; border-bottom:1px solid rgba(13,27,42,0.05);">
                    <div style="display:flex; align-items:flex-start; justify-content:space-between; gap:8px; margin-bottom:3px;">
                      <span style="font-size:13px; font-weight:700; color:#0D1B2A;">{{ activity.title }}</span>
                      <span :style="`color:${activity.is_done ? '#22C55E' : '#F59E0B'}; flex-shrink:0;`">
                        <PhCheckCircle v-if="activity.is_done" style="width:14px; height:14px;" weight="fill" />
                        <PhClock v-else style="width:14px; height:14px;" weight="fill" />
                      </span>
                    </div>
                    <div v-if="activity.body" style="font-size:12px; color:rgba(13,27,42,0.55); line-height:1.6; margin-bottom:4px;">{{ activity.body }}</div>
                    <div style="font-size:11px; color:rgba(13,27,42,0.35);">
                      {{ activity.user_name }} · {{ activity.created_at }}
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
import { ref } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import CommercialLayout from '@/Layouts/CommercialLayout.vue'
import {
  PhArrowLeft, PhFileText,
  PhNote, PhPhone, PhEnvelope, PhCalendar, PhCheckSquare,
  PhCheckCircle, PhClock, PhClockCounterClockwise,
} from '@phosphor-icons/vue'

const props = defineProps({
  deal:   Object,
  stages: Array,
})

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
    deal_id:         props.deal.id,
    crm_contact_id:  props.deal.contact?.id,
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

function moveToStage(stage) {
  router.patch(route('commercial.pipeline.move', props.deal.id), {
    stage_id: stage.id,
  }, { preserveScroll: true })
}

function formatCurrency(val) {
  return new Intl.NumberFormat('fr-FR', {
    style: 'currency', currency: 'EUR', maximumFractionDigits: 0,
  }).format(val)
}
</script>

<style scoped>
.back-btn:hover  { background:#FAF7F2 !important; color:#0D1B2A !important; }
.quote-btn:hover { background:rgba(201,168,76,0.18) !important; }
.quote-row:hover { background:rgba(201,168,76,0.06) !important; border-color:rgba(201,168,76,0.2) !important; }
.stage-btn:hover:not(:disabled) { background:#FAF7F2 !important; border-color:rgba(13,27,42,0.25) !important; color:#0D1B2A !important; }
.add-btn:hover:not(:disabled)   { background:#E2C97E !important; }
.f-label { display:block; font-size:11px; font-weight:700; color:rgba(13,27,42,0.4); margin-bottom:5px; text-transform:uppercase; letter-spacing:0.06em; }
.f-input { width:100%; padding:10px 12px; border:1.5px solid rgba(13,27,42,0.12); border-radius:10px; font-size:13px; color:#0D1B2A; outline:none; font-family:inherit; background:#FAF7F2; box-sizing:border-box; transition:border-color 0.2s; }
.f-input:focus { border-color:rgba(201,168,76,0.5) !important; background:white !important; }
.info-label { font-size:10px; font-weight:700; color:rgba(13,27,42,0.35); text-transform:uppercase; letter-spacing:0.08em; margin-bottom:4px; }
</style>