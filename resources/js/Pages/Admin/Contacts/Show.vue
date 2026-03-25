<template>
  <AdminLayout>
    <Head title="Message de contact" />

    <div style="max-width:800px;">
      <div style="display:flex; align-items:center; gap:16px; margin-bottom:32px;">
        <Link :href="route('admin.contacts.index')" style="width:36px; height:36px; border-radius:10px; border:1.5px solid rgba(13,27,42,0.12); display:flex; align-items:center; justify-content:center; color:rgba(13,27,42,0.5); text-decoration:none;" class="back-btn">
          <PhArrowLeft style="width:16px; height:16px;" />
        </Link>
        <div>
          <h1 style="font-size:22px; font-weight:900; color:#0D1B2A; letter-spacing:-0.02em;">Message de contact</h1>
          <p style="font-size:13px; color:rgba(13,27,42,0.4); margin-top:2px;">Reçu le {{ new Date(contact.created_at).toLocaleDateString('fr-FR') }}</p>
        </div>
      </div>

      <div style="display:flex; flex-direction:column; gap:20px;">

        <!-- Infos expéditeur -->
        <div style="background:white; border-radius:20px; padding:28px; box-shadow:0 1px 8px rgba(13,27,42,0.06);">
          <h2 style="font-size:15px; font-weight:800; color:#0D1B2A; margin-bottom:20px;">Expéditeur</h2>
          <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
            <div>
              <div style="font-size:12px; font-weight:700; color:rgba(13,27,42,0.4); letter-spacing:0.06em; text-transform:uppercase; margin-bottom:4px;">Nom</div>
              <div style="font-size:15px; font-weight:600; color:#0D1B2A;">{{ contact.full_name }}</div>
            </div>
            <div>
              <div style="font-size:12px; font-weight:700; color:rgba(13,27,42,0.4); letter-spacing:0.06em; text-transform:uppercase; margin-bottom:4px;">Email</div>
              <a :href="`mailto:${contact.email}`" style="font-size:15px; font-weight:600; color:#C9A84C; text-decoration:none;">{{ contact.email }}</a>
            </div>
            <div v-if="contact.phone">
              <div style="font-size:12px; font-weight:700; color:rgba(13,27,42,0.4); letter-spacing:0.06em; text-transform:uppercase; margin-bottom:4px;">Téléphone</div>
              <a :href="`tel:${contact.phone}`" style="font-size:15px; font-weight:600; color:#0D1B2A; text-decoration:none;">{{ contact.phone }}</a>
            </div>
            <div v-if="contact.formation_interest">
              <div style="font-size:12px; font-weight:700; color:rgba(13,27,42,0.4); letter-spacing:0.06em; text-transform:uppercase; margin-bottom:4px;">Formation intéressée</div>
              <div style="font-size:15px; font-weight:600; color:#0D1B2A;">{{ contact.formation_interest }}</div>
            </div>
          </div>
        </div>

        <!-- Message -->
        <div style="background:white; border-radius:20px; padding:28px; box-shadow:0 1px 8px rgba(13,27,42,0.06);">
          <h2 style="font-size:15px; font-weight:800; color:#0D1B2A; margin-bottom:8px;">{{ contact.subject }}</h2>
          <p style="font-size:15px; color:rgba(13,27,42,0.7); line-height:1.8; white-space:pre-wrap;">{{ contact.message }}</p>
        </div>

        <!-- Notes admin -->
        <div style="background:white; border-radius:20px; padding:28px; box-shadow:0 1px 8px rgba(13,27,42,0.06);">
          <h2 style="font-size:15px; font-weight:800; color:#0D1B2A; margin-bottom:16px;">Notes internes</h2>
          <textarea
            v-model="notes"
            rows="4"
            placeholder="Ajouter une note interne..."
            style="width:100%; padding:12px 14px; border:1.5px solid rgba(13,27,42,0.12); border-radius:12px; font-size:14px; color:#0D1B2A; outline:none; font-family:inherit; background:#FAF7F2; resize:vertical; box-sizing:border-box;"
          />
          <button @click="saveNote" style="display:inline-flex; align-items:center; gap:8px; margin-top:12px; padding:10px 20px; border-radius:10px; border:none; background:#0D1B2A; color:white; font-size:14px; font-weight:700; cursor:pointer; font-family:inherit;" class="save-btn">
            <PhFloppyDisk style="width:16px; height:16px;" />
            Sauvegarder la note
          </button>
        </div>

        <!-- Actions -->
        <div style="display:flex; gap:12px; flex-wrap:wrap;">
          <a
            :href="`mailto:${contact.email}?subject=Re: ${contact.subject}`"
            style="display:inline-flex; align-items:center; gap:8px; padding:12px 24px; border-radius:12px; border:none; background:#C9A84C; color:#0D1B2A; font-size:14px; font-weight:700; text-decoration:none;"
            class="reply-btn"
            @click="markReplied"
          >
            <PhEnvelopeSimple style="width:16px; height:16px;" />
            Répondre par email
          </a>
          <button
            v-if="!contact.is_replied"
            @click="markReplied"
            style="display:inline-flex; align-items:center; gap:8px; padding:12px 24px; border-radius:12px; border:2px solid rgba(13,27,42,0.15); background:transparent; color:#0D1B2A; font-size:14px; font-weight:700; cursor:pointer; font-family:inherit;"
            class="mark-btn"
          >
            <PhCheck style="width:16px; height:16px;" />
            Marquer comme répondu
          </button>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { PhArrowLeft, PhFloppyDisk, PhEnvelopeSimple, PhCheck } from '@phosphor-icons/vue'

const props = defineProps({ contact: Object })

const notes = ref(props.contact.admin_notes ?? '')

function saveNote() {
  router.patch(route('admin.contacts.note', props.contact.id), { notes: notes.value })
}

function markReplied() {
  router.patch(route('admin.contacts.replied', props.contact.id))
}
</script>

<style scoped>
.back-btn:hover { background:#FAF7F2; color:#0D1B2A !important; }
.save-btn:hover { background:#1A2D42 !important; }
.reply-btn:hover { background:#E2C97E !important; transform:translateY(-1px); }
.mark-btn:hover { background:#FAF7F2; }
</style>