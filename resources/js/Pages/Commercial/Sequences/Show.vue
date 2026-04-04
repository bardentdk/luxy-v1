<template>
  <CommercialLayout>
    <Head :title="sequence.name" />

    <div style="display:flex; flex-direction:column; gap:20px; max-width:900px;">

      <!-- Header -->
      <div style="display:flex; align-items:center; gap:14px; flex-wrap:wrap;">
        <Link :href="route('commercial.sequences.index')" style="width:36px; height:36px; border-radius:10px; border:1.5px solid rgba(13,27,42,0.12); display:flex; align-items:center; justify-content:center; color:rgba(13,27,42,0.5); text-decoration:none;" class="back-btn">
          <PhArrowLeft style="width:16px; height:16px;" />
        </Link>
        <div style="flex:1;">
          <h1 style="font-size:20px; font-weight:900; color:#0D1B2A; letter-spacing:-0.02em; margin:0 0 3px;">{{ sequence.name }}</h1>
          <div style="display:flex; align-items:center; gap:10px;">
            <span style="font-size:12px; color:rgba(13,27,42,0.4);">{{ sequence.steps.length }} étape{{ sequence.steps.length > 1 ? 's' : '' }}</span>
            <span
              style="font-size:11px; font-weight:700; padding:3px 9px; border-radius:100px;"
              :style="sequence.is_active ? 'background:rgba(34,197,94,0.1); color:#16A34A;' : 'background:rgba(13,27,42,0.07); color:rgba(13,27,42,0.4);'"
            >
              {{ sequence.is_active ? 'Active' : 'Inactive' }}
            </span>
          </div>
        </div>

        <!-- Déclencher manuellement -->
        <button @click="showTrigger = true" style="display:inline-flex; align-items:center; gap:7px; padding:10px 18px; border-radius:12px; border:1.5px solid rgba(201,168,76,0.25); background:rgba(201,168,76,0.08); color:#A07828; font-size:13px; font-weight:700; cursor:pointer; font-family:inherit; transition:all 0.2s;" class="trigger-btn">
          <PhPaperPlaneTilt style="width:14px; height:14px;" />
          Déclencher
        </button>
      </div>

      <!-- Timeline des étapes -->
      <div style="background:white; border-radius:18px; padding:28px; border:1.5px solid rgba(13,27,42,0.07);">
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:24px;">
          <h2 style="font-size:14px; font-weight:800; color:#0D1B2A; margin:0; text-transform:uppercase; letter-spacing:0.08em;">Étapes de la séquence</h2>
          <button @click="openNewStep" style="display:inline-flex; align-items:center; gap:6px; padding:8px 14px; border-radius:10px; border:1.5px solid rgba(13,27,42,0.12); background:white; font-size:12px; font-weight:700; color:rgba(13,27,42,0.6); cursor:pointer; font-family:inherit; transition:all 0.2s;" class="add-step-btn">
            <PhPlus style="width:13px; height:13px;" />
            Ajouter une étape
          </button>
        </div>

        <div v-if="sequence.steps.length === 0" style="text-align:center; padding:40px; border:2px dashed rgba(13,27,42,0.08); border-radius:14px; color:rgba(13,27,42,0.3);">
          <PhEnvelopeSimple style="width:36px; height:36px; margin:0 auto 12px; display:block;" />
          <div style="font-size:13px; font-weight:600; margin-bottom:4px;">Aucune étape</div>
          <div style="font-size:12px;">Ajoutez des emails à envoyer automatiquement</div>
        </div>

        <!-- Steps timeline -->
        <div v-else style="position:relative;">
          <div v-for="(step, i) in sequence.steps" :key="step.id" style="display:flex; gap:20px; margin-bottom:20px;">

            <!-- Indicateur timeline -->
            <div style="display:flex; flex-direction:column; align-items:center; flex-shrink:0; width:40px;">
              <div style="width:40px; height:40px; border-radius:12px; background:rgba(201,168,76,0.1); border:2px solid rgba(201,168,76,0.25); display:flex; align-items:center; justify-content:center; flex-shrink:0; font-size:14px; font-weight:900; color:#C9A84C;">
                J+{{ step.delay_days }}
              </div>
              <div v-if="i < sequence.steps.length - 1" style="width:2px; flex:1; min-height:24px; background:linear-gradient(to bottom, rgba(201,168,76,0.3), rgba(201,168,76,0.1)); margin-top:6px;"></div>
            </div>

            <!-- Contenu étape -->
            <div style="flex:1; background:#FAF7F2; border-radius:16px; padding:18px 20px; border:1px solid rgba(13,27,42,0.07); min-width:0;">
              <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:10px; flex-wrap:wrap; gap:8px;">
                <div>
                  <div style="font-size:13px; font-weight:800; color:#0D1B2A; margin-bottom:3px;">{{ step.subject }}</div>
                  <div style="display:flex; gap:8px; align-items:center;">
                    <span style="font-size:11px; color:rgba(13,27,42,0.45);">
                      Envoi {{ step.delay_days === 0 ? 'immédiat' : `J+${step.delay_days}` }}
                    </span>
                    <span
                      style="font-size:10px; font-weight:700; padding:2px 8px; border-radius:100px;"
                      :style="step.is_active ? 'background:rgba(34,197,94,0.1); color:#16A34A;' : 'background:rgba(13,27,42,0.07); color:rgba(13,27,42,0.4);'"
                    >
                      {{ step.is_active ? 'Actif' : 'Inactif' }}
                    </span>
                    <span style="font-size:11px; color:rgba(13,27,42,0.35);">{{ step.logs_count }} envoi{{ step.logs_count > 1 ? 's' : '' }}</span>
                  </div>
                </div>
                <div style="display:flex; gap:6px;">
                  <button @click="openEditStep(step)" style="width:30px; height:30px; border-radius:8px; border:1px solid rgba(13,27,42,0.1); background:white; display:flex; align-items:center; justify-content:center; cursor:pointer; color:rgba(13,27,42,0.5); transition:all 0.15s;" class="icon-btn">
                    <PhPencil style="width:12px; height:12px;" />
                  </button>
                  <button @click="deleteStep(step)" style="width:30px; height:30px; border-radius:8px; border:1px solid rgba(239,68,68,0.2); background:rgba(239,68,68,0.06); display:flex; align-items:center; justify-content:center; cursor:pointer; color:#DC2626; transition:all 0.15s;" class="del-btn">
                    <PhTrash style="width:12px; height:12px;" />
                  </button>
                </div>
              </div>

              <!-- Preview email -->
              <div style="background:white; border-radius:10px; padding:12px 14px; border:1px solid rgba(13,27,42,0.07); font-size:12px; color:rgba(13,27,42,0.55); line-height:1.65; max-height:80px; overflow:hidden; position:relative;">
                <div v-html="step.body_html.substring(0, 200) + '...'" style="pointer-events:none;"></div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Panel déclencher sur un contact -->
      <div v-if="showTrigger" style="position:fixed; inset:0; background:rgba(10,22,40,0.6); z-index:1000; display:flex; align-items:center; justify-content:center; padding:20px; backdrop-filter:blur(4px);" @click.self="showTrigger = false">
        <div style="background:white; border-radius:22px; padding:32px; width:100%; max-width:480px;">
          <h3 style="font-size:17px; font-weight:900; color:#0D1B2A; margin-bottom:18px;">Déclencher la séquence</h3>
          <form @submit.prevent="triggerSequence" style="display:flex; flex-direction:column; gap:14px;">
            <div>
              <label class="f-label">Sélectionner un contact <span style="color:#C9A84C;">*</span></label>
              <select v-model="triggerContactId" required class="f-input">
                <option value="">Choisir un contact...</option>
                <option v-for="c in contacts" :key="c.id" :value="c.id">{{ c.first_name }} {{ c.last_name }} ({{ c.email }})</option>
              </select>
            </div>
            <div style="display:flex; gap:10px;">
              <button type="button" @click="showTrigger = false" style="flex:1; padding:11px; border-radius:100px; border:1.5px solid rgba(13,27,42,0.12); background:transparent; font-size:14px; font-weight:700; color:#0D1B2A; cursor:pointer; font-family:inherit;" class="cancel-btn">
                Annuler
              </button>
              <button type="submit" :disabled="!triggerContactId" style="flex:2; padding:11px; border-radius:100px; border:none; background:#C9A84C; color:#0D1B2A; font-size:14px; font-weight:800; cursor:pointer; font-family:inherit; display:flex; align-items:center; justify-content:center; gap:7px;" class="save-btn">
                <PhPaperPlaneTilt style="width:15px; height:15px;" />
                Déclencher
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Modal étape -->
      <div v-if="showStepForm" style="position:fixed; inset:0; background:rgba(10,22,40,0.6); z-index:1000; display:flex; align-items:center; justify-content:center; padding:20px; backdrop-filter:blur(4px);" @click.self="showStepForm = false">
        <div style="background:white; border-radius:24px; padding:36px; width:100%; max-width:620px; max-height:90vh; overflow-y:auto; box-shadow:0 32px 80px rgba(0,0,0,0.25);">
          <h2 style="font-size:17px; font-weight:900; color:#0D1B2A; margin-bottom:22px;">
            {{ editingStep ? 'Modifier l\'étape' : 'Nouvelle étape' }}
          </h2>

          <form @submit.prevent="saveStep" style="display:flex; flex-direction:column; gap:16px;">
            <div>
              <label class="f-label">Délai d'envoi <span style="color:#C9A84C;">*</span></label>
              <div style="display:flex; align-items:center; gap:10px;">
                <input v-model.number="stepForm.delay_days" type="number" min="0" required class="f-input" style="max-width:100px;" />
                <span style="font-size:13px; color:rgba(13,27,42,0.5);">jour(s) après le déclencheur</span>
              </div>
              <p style="font-size:11px; color:rgba(13,27,42,0.4); margin-top:5px;">0 = envoi immédiat, 1 = J+1, 3 = J+3...</p>
            </div>

            <div>
              <label class="f-label">Objet de l'email <span style="color:#C9A84C;">*</span></label>
              <input v-model="stepForm.subject" type="text" required class="f-input" placeholder="Ex: Suite à notre échange..." />
            </div>

            <div>
              <label class="f-label">Corps de l'email <span style="color:#C9A84C;">*</span></label>
              <div style="margin-bottom:8px; display:flex; gap:6px; flex-wrap:wrap;">
                <button
                  v-for="v in variables"
                  :key="v"
                  type="button"
                  @click="insertVariable(v)"
                  style="font-size:10px; font-weight:700; padding:3px 9px; border-radius:6px; border:1px solid rgba(201,168,76,0.3); background:rgba(201,168,76,0.08); color:#A07828; cursor:pointer; font-family:monospace;"
                >
                  {{ v }}
                </button>
              </div>
              <textarea
                ref="bodyRef"
                v-model="stepForm.body_html"
                rows="8"
                required
                class="f-input"
                style="resize:vertical; font-family:monospace; font-size:12px;"
                placeholder="<p>Bonjour {{contact.first_name}},</p>&#10;<p>Suite à notre échange...</p>"
              ></textarea>
            </div>

            <div style="display:flex; gap:10px; padding-top:6px;">
              <button type="button" @click="showStepForm = false" style="flex:1; padding:12px; border-radius:100px; border:1.5px solid rgba(13,27,42,0.12); background:transparent; font-size:14px; font-weight:700; color:#0D1B2A; cursor:pointer; font-family:inherit;" class="cancel-btn">
                Annuler
              </button>
              <button type="submit" style="flex:2; padding:12px; border-radius:100px; border:none; background:#C9A84C; color:#0D1B2A; font-size:14px; font-weight:800; cursor:pointer; font-family:inherit; display:flex; align-items:center; justify-content:center; gap:8px;" class="save-btn">
                <PhFloppyDisk style="width:15px; height:15px;" />
                {{ editingStep ? 'Mettre à jour' : 'Ajouter l\'étape' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </CommercialLayout>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import CommercialLayout from '@/Layouts/CommercialLayout.vue'
import {
  PhArrowLeft, PhPlus, PhPencil, PhTrash,
  PhEnvelopeSimple, PhPaperPlaneTilt, PhFloppyDisk,
} from '@phosphor-icons/vue'

const props = defineProps({ sequence: Object, contacts: Array })

const showTrigger    = ref(false)
const showStepForm   = ref(false)
const editingStep    = ref(null)
const triggerContactId = ref('')
const bodyRef        = ref(null)

const stepForm = reactive({ delay_days: 1, subject: '', body_html: '', is_active: true })

const variables = ['{{contact.first_name}}', '{{contact.last_name}}', '{{contact.email}}', '{{contact.company}}']

function openNewStep() {
  editingStep.value      = null
  stepForm.delay_days    = 1
  stepForm.subject       = ''
  stepForm.body_html     = ''
  showStepForm.value     = true
}

function openEditStep(step) {
  editingStep.value   = step
  stepForm.delay_days = step.delay_days
  stepForm.subject    = step.subject
  stepForm.body_html  = step.body_html
  showStepForm.value  = true
}

function insertVariable(v) {
  const el  = bodyRef.value
  if (!el) { stepForm.body_html += v; return }
  const s   = el.selectionStart
  const e   = el.selectionEnd
  stepForm.body_html = stepForm.body_html.substring(0, s) + v + stepForm.body_html.substring(e)
}

function saveStep() {
  const url    = editingStep.value
    ? route('commercial.sequences.steps.update', [props.sequence.id, editingStep.value.id])
    : route('commercial.sequences.steps.store', props.sequence.id)
  const method = editingStep.value ? 'put' : 'post'
  router[method](url, { ...stepForm }, {
    onSuccess: () => { showStepForm.value = false },
    preserveScroll: true,
  })
}

function deleteStep(step) {
  if (!confirm(`Supprimer l'étape "${step.subject}" ?`)) return
  router.delete(route('commercial.sequences.steps.destroy', [props.sequence.id, step.id]), { preserveScroll: true })
}

function triggerSequence() {
  router.post(route('commercial.sequences.trigger', [props.sequence.id, triggerContactId.value]), {}, {
    onSuccess: () => { showTrigger.value = false; triggerContactId.value = '' },
  })
}
</script>

<style scoped>
.back-btn:hover    { background:#FAF7F2 !important; color:#0D1B2A !important; }
.trigger-btn:hover { background:rgba(201,168,76,0.15) !important; }
.add-step-btn:hover{ background:#FAF7F2 !important; }
.icon-btn:hover    { background:#FAF7F2 !important; color:#0D1B2A !important; }
.del-btn:hover     { background:rgba(239,68,68,0.12) !important; }
.cancel-btn:hover  { background:#FAF7F2 !important; }
.save-btn:hover    { background:#E2C97E !important; }
.f-label { display:block; font-size:12px; font-weight:700; color:#0D1B2A; margin-bottom:7px; }
.f-input { width:100%; padding:11px 13px; border:1.5px solid rgba(13,27,42,0.12); border-radius:11px; font-size:14px; color:#0D1B2A; outline:none; font-family:inherit; background:#FAF7F2; transition:border-color 0.2s; box-sizing:border-box; }
.f-input:focus { border-color:rgba(201,168,76,0.6) !important; background:white !important; }
</style>