<template>
  <CommercialLayout>
    <Head title="Séquences email" />

    <div style="display:flex; flex-direction:column; gap:20px;">

      <!-- Header -->
      <div style="display:flex; align-items:flex-start; justify-content:space-between; flex-wrap:wrap; gap:16px;">
        <div>
          <h1 style="font-size:20px; font-weight:900; color:#0D1B2A; letter-spacing:-0.02em; margin:0 0 3px;">Séquences email</h1>
          <p style="font-size:13px; color:rgba(13,27,42,0.45); margin:0;">Automatisez vos relances commerciales</p>
        </div>
        <button @click="showForm = true" style="display:inline-flex; align-items:center; gap:8px; background:#0D1B2A; color:white; font-weight:700; font-size:13px; padding:10px 20px; border-radius:12px; border:none; cursor:pointer; font-family:inherit; transition:all 0.2s;" class="new-btn">
          <PhPlus style="width:15px; height:15px;" />
          Nouvelle séquence
        </button>
      </div>

      <!-- Explication -->
      <div style="background:rgba(201,168,76,0.06); border:1px solid rgba(201,168,76,0.2); border-radius:14px; padding:16px 20px; display:flex; gap:12px; align-items:flex-start;">
        <PhInfo style="width:18px; height:18px; color:#C9A84C; flex-shrink:0; margin-top:1px;" weight="fill" />
        <p style="font-size:13px; color:rgba(13,27,42,0.65); line-height:1.65; margin:0;">
            Les séquences permettent d'envoyer automatiquement des emails à vos prospects selon un planning défini.
            Utilisez les variables
            <code style="background:rgba(13,27,42,0.06); padding:1px 6px; border-radius:4px; font-size:12px;" v-pre>{{contact.first_name}}</code>,
            <code style="background:rgba(13,27,42,0.06); padding:1px 6px; border-radius:4px; font-size:12px;" v-pre>{{contact.email}}</code>
            dans vos templates.
        </p>
      </div>

      <!-- Liste séquences -->
      <div v-if="sequences.length" style="display:flex; flex-direction:column; gap:12px;">
        <div
          v-for="seq in sequences"
          :key="seq.id"
          style="background:white; border-radius:18px; padding:22px 26px; border:1.5px solid rgba(13,27,42,0.07); display:flex; align-items:center; gap:20px; flex-wrap:wrap; transition:all 0.2s;"
          class="seq-row"
        >
          <!-- Icône statut -->
          <div
            style="width:44px; height:44px; border-radius:13px; display:flex; align-items:center; justify-content:center; flex-shrink:0;"
            :style="seq.is_active ? 'background:rgba(34,197,94,0.1);' : 'background:rgba(13,27,42,0.06);'"
          >
            <PhEnvelopeSimple
              style="width:20px; height:20px;"
              :style="seq.is_active ? 'color:#16A34A;' : 'color:rgba(13,27,42,0.3);'"
              weight="fill"
            />
          </div>

          <!-- Infos -->
          <div style="flex:1; min-width:0;">
            <div style="font-size:15px; font-weight:800; color:#0D1B2A; margin-bottom:5px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
              {{ seq.name }}
            </div>
            <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
              <span style="font-size:12px; color:rgba(13,27,42,0.45); display:flex; align-items:center; gap:5px;">
                <PhArrowsSplit style="width:12px; height:12px;" />
                {{ triggerLabel(seq.trigger) }}
              </span>
              <span style="font-size:12px; color:rgba(13,27,42,0.45); display:flex; align-items:center; gap:5px;">
                <PhEnvelopeSimple style="width:12px; height:12px;" />
                {{ seq.steps_count }} email{{ seq.steps_count > 1 ? 's' : '' }}
              </span>
              <span
                style="font-size:11px; font-weight:700; padding:3px 9px; border-radius:100px;"
                :style="seq.is_active ? 'background:rgba(34,197,94,0.1); color:#16A34A;' : 'background:rgba(13,27,42,0.07); color:rgba(13,27,42,0.4);'"
              >
                {{ seq.is_active ? 'Active' : 'Inactive' }}
              </span>
            </div>
          </div>

          <!-- Actions -->
          <div style="display:flex; gap:8px; flex-shrink:0;">
            <Link :href="route('commercial.sequences.show', seq.id)" style="display:inline-flex; align-items:center; gap:6px; padding:9px 16px; border-radius:10px; border:1.5px solid rgba(13,27,42,0.1); background:white; font-size:12px; font-weight:700; color:rgba(13,27,42,0.6); text-decoration:none; transition:all 0.2s;" class="icon-btn">
              <PhGear style="width:13px; height:13px;" />
              Configurer
            </Link>
          </div>
        </div>
      </div>

      <!-- Vide -->
      <div v-else style="text-align:center; padding:72px; background:white; border-radius:18px; border:2px dashed rgba(13,27,42,0.1);">
        <PhEnvelopeSimple style="width:40px; height:40px; color:rgba(13,27,42,0.15); margin:0 auto 16px; display:block;" />
        <div style="font-size:15px; font-weight:700; color:rgba(13,27,42,0.4); margin-bottom:8px;">Aucune séquence</div>
        <div style="font-size:13px; color:rgba(13,27,42,0.3); margin-bottom:28px;">Créez votre première séquence de relance email</div>
        <button @click="showForm = true" style="display:inline-flex; align-items:center; gap:8px; background:#C9A84C; color:#0D1B2A; font-weight:800; font-size:14px; padding:12px 28px; border-radius:100px; border:none; cursor:pointer; font-family:inherit;" class="new-btn">
          <PhPlus style="width:15px; height:15px;" />
          Créer une séquence
        </button>
      </div>
    </div>

    <!-- Modal création séquence -->
    <div v-if="showForm" style="position:fixed; inset:0; background:rgba(10,22,40,0.6); z-index:1000; display:flex; align-items:center; justify-content:center; padding:20px; backdrop-filter:blur(4px);" @click.self="showForm = false">
      <div style="background:white; border-radius:24px; padding:36px; width:100%; max-width:520px; box-shadow:0 32px 80px rgba(0,0,0,0.25);">
        <h2 style="font-size:18px; font-weight:900; color:#0D1B2A; margin-bottom:24px;">Nouvelle séquence</h2>

        <form @submit.prevent="createSequence" style="display:flex; flex-direction:column; gap:16px;">
          <div>
            <label class="f-label">Nom de la séquence <span style="color:#C9A84C;">*</span></label>
            <input v-model="seqForm.name" type="text" placeholder="Ex: Relance devis non répondu" required class="f-input" />
          </div>

          <div>
            <label class="f-label">Déclencheur <span style="color:#C9A84C;">*</span></label>
            <div style="display:flex; flex-direction:column; gap:8px;">
              <button
                v-for="t in triggers"
                :key="t.value"
                type="button"
                @click="seqForm.trigger = t.value"
                style="display:flex; align-items:flex-start; gap:12px; padding:12px 14px; border-radius:12px; border:1.5px solid; cursor:pointer; text-align:left; transition:all 0.2s; font-family:inherit; background:transparent;"
                :style="seqForm.trigger === t.value
                  ? 'border-color:rgba(201,168,76,0.5); background:rgba(201,168,76,0.08);'
                  : 'border-color:rgba(13,27,42,0.1);'"
              >
                <div style="width:32px; height:32px; border-radius:9px; background:rgba(13,27,42,0.06); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                  <component :is="t.icon" style="width:15px; height:15px; color:rgba(13,27,42,0.5);" />
                </div>
                <div>
                  <div style="font-size:13px; font-weight:700; color:#0D1B2A; margin-bottom:2px;">{{ t.label }}</div>
                  <div style="font-size:11px; color:rgba(13,27,42,0.45);">{{ t.desc }}</div>
                </div>
              </button>
            </div>
          </div>

          <div style="display:flex; gap:10px; padding-top:8px;">
            <button type="button" @click="showForm = false" style="flex:1; padding:12px; border-radius:100px; border:1.5px solid rgba(13,27,42,0.12); background:transparent; font-size:14px; font-weight:700; color:#0D1B2A; cursor:pointer; font-family:inherit;" class="cancel-btn">
              Annuler
            </button>
            <button type="submit" :disabled="!seqForm.name || !seqForm.trigger" style="flex:2; padding:12px; border-radius:100px; border:none; background:#C9A84C; color:#0D1B2A; font-size:14px; font-weight:800; cursor:pointer; font-family:inherit; transition:all 0.2s;" class="save-btn">
              Créer la séquence
            </button>
          </div>
        </form>
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
  PhPlus, PhEnvelopeSimple, PhInfo, PhGear,
  PhArrowsSplit, PhFunnel, PhFileText, PhClock,
} from '@phosphor-icons/vue'

// PhArrowsSplit n'existe pas dans Phosphor — on réutilise PhFunnel
const props = defineProps({ sequences: Array })

const showForm = ref(false)
const seqForm  = reactive({ name: '', trigger: '', is_active: true })

const triggers = [
  { value: 'deal_created', label: 'Deal créé',           desc: 'Quand une opportunité est ajoutée au pipeline',    icon: PhFunnel },
  { value: 'quote_sent',   label: 'Devis envoyé',         desc: 'Quand un devis est envoyé au contact',            icon: PhFileText },
  { value: 'no_activity',  label: 'Inactivité',           desc: 'Quand aucune activité depuis X jours',            icon: PhClock },
]

function triggerLabel(val) {
  return triggers.find(t => t.value === val)?.label ?? val
}

function createSequence() {
  router.post(route('commercial.sequences.store'), { ...seqForm }, {
    onSuccess: (page) => {
      showForm.value = false
      // Rediriger vers la fiche de la séquence créée
    },
  })
}
</script>

<style scoped>
.seq-row:hover  { box-shadow:0 4px 20px rgba(13,27,42,0.07); }
.icon-btn:hover { background:#FAF7F2 !important; color:#0D1B2A !important; }
.new-btn:hover  { background:#1A2D42 !important; transform:translateY(-1px); }
.cancel-btn:hover { background:#FAF7F2 !important; }
.save-btn:hover:not(:disabled) { background:#E2C97E !important; }
.f-label { display:block; font-size:12px; font-weight:700; color:#0D1B2A; margin-bottom:7px; }
.f-input { width:100%; padding:11px 13px; border:1.5px solid rgba(13,27,42,0.12); border-radius:11px; font-size:14px; color:#0D1B2A; outline:none; font-family:inherit; background:#FAF7F2; transition:border-color 0.2s; box-sizing:border-box; }
.f-input:focus { border-color:rgba(201,168,76,0.6) !important; background:white !important; }
</style>