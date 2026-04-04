<template>
  <CommercialLayout>
    <Head :title="isEdit ? 'Modifier le contact' : 'Nouveau contact'" />

    <div style="max-width:680px; display:flex; flex-direction:column; gap:20px;">

      <!-- Header -->
      <div style="display:flex; align-items:center; gap:14px;">
        <Link :href="route('commercial.contacts.index')" style="width:36px; height:36px; border-radius:10px; border:1.5px solid rgba(13,27,42,0.12); display:flex; align-items:center; justify-content:center; color:rgba(13,27,42,0.5); text-decoration:none;" class="back-btn">
          <PhArrowLeft style="width:16px; height:16px;" />
        </Link>
        <div>
          <h1 style="font-size:20px; font-weight:900; color:#0D1B2A; letter-spacing:-0.02em; margin:0 0 2px;">
            {{ isEdit ? 'Modifier le contact' : 'Nouveau contact' }}
          </h1>
          <p style="font-size:13px; color:rgba(13,27,42,0.4); margin:0;">
            {{ isEdit ? contact?.full_name : 'Ajouter un contact à votre CRM' }}
          </p>
        </div>
      </div>

      <form @submit.prevent="submit" style="display:flex; flex-direction:column; gap:20px;">

        <!-- Identité -->
        <div style="background:white; border-radius:18px; padding:28px; border:1.5px solid rgba(13,27,42,0.07);">
          <h2 style="font-size:13px; font-weight:800; color:#0D1B2A; margin:0 0 20px; text-transform:uppercase; letter-spacing:0.08em;">Identité</h2>
          <div style="display:flex; flex-direction:column; gap:16px;">
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px;">
              <div>
                <label class="f-label">Prénom <span style="color:#C9A84C;">*</span></label>
                <input v-model="form.first_name" type="text" placeholder="Jean" required class="f-input" :class="{'f-error': form.errors.first_name}" />
                <p v-if="form.errors.first_name" class="error-msg">{{ form.errors.first_name }}</p>
              </div>
              <div>
                <label class="f-label">Nom <span style="color:#C9A84C;">*</span></label>
                <input v-model="form.last_name" type="text" placeholder="Dupont" required class="f-input" :class="{'f-error': form.errors.last_name}" />
              </div>
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px;">
              <div>
                <label class="f-label">Email <span style="color:#C9A84C;">*</span></label>
                <input v-model="form.email" type="email" placeholder="jean@exemple.re" required class="f-input" :class="{'f-error': form.errors.email}" />
                <p v-if="form.errors.email" class="error-msg">{{ form.errors.email }}</p>
              </div>
              <div>
                <label class="f-label">Téléphone</label>
                <input v-model="form.phone" type="tel" placeholder="+262 692 00 00 00" class="f-input" />
              </div>
            </div>
          </div>
        </div>

        <!-- Entreprise -->
        <div style="background:white; border-radius:18px; padding:28px; border:1.5px solid rgba(13,27,42,0.07);">
          <h2 style="font-size:13px; font-weight:800; color:#0D1B2A; margin:0 0 20px; text-transform:uppercase; letter-spacing:0.08em;">Entreprise</h2>
          <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px;">
            <div>
              <label class="f-label">Nom de l'entreprise</label>
              <input v-model="form.company" type="text" placeholder="Acme Corp" class="f-input" />
            </div>
            <div>
              <label class="f-label">Poste occupé</label>
              <input v-model="form.job_title" type="text" placeholder="Directeur RH" class="f-input" />
            </div>
          </div>
        </div>

        <!-- Qualification -->
        <div style="background:white; border-radius:18px; padding:28px; border:1.5px solid rgba(13,27,42,0.07);">
          <h2 style="font-size:13px; font-weight:800; color:#0D1B2A; margin:0 0 20px; text-transform:uppercase; letter-spacing:0.08em;">Qualification</h2>
          <div>
            <label class="f-label">Statut <span style="color:#C9A84C;">*</span></label>
            <div style="display:flex; gap:10px; flex-wrap:wrap;">
              <button
                v-for="s in statuses"
                :key="s.value"
                type="button"
                @click="form.status = s.value"
                style="flex:1; min-width:100px; padding:11px; border-radius:12px; font-size:13px; font-weight:700; cursor:pointer; transition:all 0.2s; border:1.5px solid; font-family:inherit; display:flex; align-items:center; justify-content:center; gap:7px;"
                :style="form.status === s.value
                  ? `background:${s.color}15; border-color:${s.color}; color:${s.color};`
                  : 'background:white; border-color:rgba(13,27,42,0.12); color:rgba(13,27,42,0.5);'"
              >
                <div style="width:8px; height:8px; border-radius:50%;" :style="`background:${s.color};`"></div>
                {{ s.label }}
              </button>
            </div>
          </div>
        </div>

        <!-- Notes -->
        <div style="background:white; border-radius:18px; padding:28px; border:1.5px solid rgba(13,27,42,0.07);">
          <h2 style="font-size:13px; font-weight:800; color:#0D1B2A; margin:0 0 20px; text-transform:uppercase; letter-spacing:0.08em;">Notes internes</h2>
          <textarea v-model="form.notes" rows="4" placeholder="Informations utiles sur ce contact..." style="width:100%; padding:12px 14px; border:1.5px solid rgba(13,27,42,0.12); border-radius:12px; font-size:14px; color:#0D1B2A; outline:none; font-family:inherit; background:#FAF7F2; resize:vertical; box-sizing:border-box; transition:border-color 0.2s;" class="f-input-area"></textarea>
        </div>

        <!-- Actions -->
        <div style="display:flex; justify-content:flex-end; gap:12px;">
          <Link :href="route('commercial.contacts.index')" style="display:inline-flex; align-items:center; padding:12px 24px; border-radius:12px; border:1.5px solid rgba(13,27,42,0.12); background:white; font-size:14px; font-weight:700; color:rgba(13,27,42,0.6); text-decoration:none; transition:all 0.2s;" class="cancel-btn">
            Annuler
          </Link>
          <button type="submit" :disabled="form.processing" style="display:inline-flex; align-items:center; gap:8px; padding:12px 28px; border-radius:12px; border:none; background:#C9A84C; color:#0D1B2A; font-size:14px; font-weight:800; cursor:pointer; font-family:inherit; transition:all 0.2s;" class="submit-btn">
            <PhCircleNotch v-if="form.processing" style="width:16px; height:16px;" class="spin" />
            <PhFloppyDisk v-else style="width:16px; height:16px;" />
            {{ form.processing ? 'Enregistrement...' : (isEdit ? 'Mettre à jour' : 'Créer le contact') }}
          </button>
        </div>
      </form>
    </div>
  </CommercialLayout>
</template>

<script setup>
import { computed } from 'vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import CommercialLayout from '@/Layouts/CommercialLayout.vue'
import { PhArrowLeft, PhCircleNotch, PhFloppyDisk } from '@phosphor-icons/vue'

const props  = defineProps({ contact: Object })
const isEdit = computed(() => !!props.contact?.id)

const statuses = [
  { value: 'lead',     label: 'Lead',     color: '#6366F1' },
  { value: 'prospect', label: 'Prospect', color: '#F59E0B' },
  { value: 'client',   label: 'Client',   color: '#22C55E' },
  { value: 'lost',     label: 'Perdu',    color: '#EF4444' },
]

const form = useForm({
  first_name: props.contact?.first_name ?? '',
  last_name:  props.contact?.last_name  ?? '',
  email:      props.contact?.email      ?? '',
  phone:      props.contact?.phone      ?? '',
  company:    props.contact?.company    ?? '',
  job_title:  props.contact?.job_title  ?? '',
  status:     props.contact?.status     ?? 'lead',
  notes:      props.contact?.notes      ?? '',
})

function submit() {
  if (isEdit.value) {
    form.put(route('commercial.contacts.update', props.contact.id))
  } else {
    form.post(route('commercial.contacts.store'))
  }
}
</script>

<style scoped>
.back-btn:hover   { background:#FAF7F2 !important; color:#0D1B2A !important; }
.cancel-btn:hover { background:#FAF7F2 !important; }
.submit-btn:hover:not(:disabled) { background:#E2C97E !important; transform:translateY(-1px); }
.f-label { display:block; font-size:12px; font-weight:700; color:#0D1B2A; margin-bottom:7px; }
.f-input { width:100%; padding:11px 13px; border:1.5px solid rgba(13,27,42,0.12); border-radius:11px; font-size:14px; color:#0D1B2A; outline:none; font-family:inherit; background:#FAF7F2; transition:border-color 0.2s; box-sizing:border-box; }
.f-input:focus, .f-input-area:focus { border-color:rgba(201,168,76,0.6) !important; background:white !important; outline:none; }
.f-error { border-color:#EF4444 !important; }
.error-msg { font-size:11px; color:#EF4444; margin-top:5px; }
.spin { animation:spin 1s linear infinite; }
@keyframes spin { to { transform:rotate(360deg); } }
</style>