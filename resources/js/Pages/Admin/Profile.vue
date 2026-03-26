<template>
  <AdminLayout>
    <Head title="Mon profil" />

    <div style="max-width:700px; display:flex; flex-direction:column; gap:24px;">

      <!-- Header -->
      <div>
        <h1 style="font-size:24px; font-weight:900; color:#0D1B2A; letter-spacing:-0.02em; margin:0 0 4px;">
          Mon profil
        </h1>
        <p style="font-size:14px; color:rgba(13,27,42,0.45); margin:0;">
          Gérez vos informations personnelles et votre mot de passe
        </p>
      </div>

      <!-- Avatar -->
      <div style="background:white; border-radius:20px; padding:32px; border:1.5px solid rgba(13,27,42,0.07); position:relative; overflow:hidden;">
        <!-- Ligne or top -->
        <div style="position:absolute; top:0; left:0; right:0; height:2px; background:linear-gradient(90deg, transparent, #C9A84C, #E2C97E, #C9A84C, transparent);" />

        <h2 style="font-size:15px; font-weight:800; color:#0D1B2A; margin-bottom:24px; display:flex; align-items:center; gap:8px;">
          <div style="width:26px; height:26px; border-radius:7px; background:rgba(201,168,76,0.1); border:1px solid rgba(201,168,76,0.2); display:flex; align-items:center; justify-content:center;">
            <PhCamera style="width:13px; height:13px; color:#C9A84C;" />
          </div>
          Photo de profil
        </h2>

        <div style="display:flex; align-items:center; gap:28px;">
          <div style="position:relative; flex-shrink:0;">
            <img
              :src="avatarPreview ?? user.avatar_url"
              :alt="user.full_name"
              style="width:100px; height:100px; border-radius:50%; object-fit:cover; border:3px solid rgba(201,168,76,0.3); box-shadow:0 4px 20px rgba(201,168,76,0.2);"
            />
            <button
              @click="$refs.avatarInput.click()"
              style="position:absolute; bottom:2px; right:2px; width:30px; height:30px; border-radius:50%; background:#C9A84C; border:2.5px solid white; display:flex; align-items:center; justify-content:center; cursor:pointer; transition:all 0.2s; box-shadow:0 2px 8px rgba(201,168,76,0.4);"
              class="avatar-edit-btn"
            >
              <PhCamera style="width:13px; height:13px; color:#0D1B2A;" />
            </button>
            <input ref="avatarInput" type="file" accept="image/*" style="display:none;" @change="handleAvatarChange" />
          </div>

          <div style="flex:1; min-width:0;">
            <div style="font-size:20px; font-weight:900; color:#0D1B2A; margin-bottom:3px; letter-spacing:-0.01em;">{{ user.full_name }}</div>
            <div style="font-size:13px; color:rgba(13,27,42,0.45); margin-bottom:14px;">{{ user.email }}</div>
            <button
              @click="$refs.avatarInput.click()"
              style="display:inline-flex; align-items:center; gap:7px; padding:9px 16px; border-radius:10px; border:1.5px solid rgba(13,27,42,0.12); background:#FAF7F2; font-size:13px; font-weight:600; color:rgba(13,27,42,0.65); cursor:pointer; font-family:inherit; transition:all 0.2s;"
              class="change-avatar-btn"
            >
              <PhUpload style="width:14px; height:14px;" />
              Changer la photo
            </button>
          </div>
        </div>
      </div>

      <!-- Informations personnelles -->
      <div style="background:white; border-radius:20px; padding:32px; border:1.5px solid rgba(13,27,42,0.07);">
        <h2 style="font-size:15px; font-weight:800; color:#0D1B2A; margin-bottom:24px; display:flex; align-items:center; gap:8px;">
          <div style="width:26px; height:26px; border-radius:7px; background:rgba(201,168,76,0.1); border:1px solid rgba(201,168,76,0.2); display:flex; align-items:center; justify-content:center;">
            <PhUser style="width:13px; height:13px; color:#C9A84C;" />
          </div>
          Informations personnelles
        </h2>

        <form @submit.prevent="submit" style="display:flex; flex-direction:column; gap:20px;">

          <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
            <div>
              <label class="form-label">Prénom <span style="color:#C9A84C;">*</span></label>
              <input v-model="form.first_name" type="text" required class="form-input" :class="{ error: form.errors.first_name }" />
              <p v-if="form.errors.first_name" class="form-error">{{ form.errors.first_name }}</p>
            </div>
            <div>
              <label class="form-label">Nom <span style="color:#C9A84C;">*</span></label>
              <input v-model="form.last_name" type="text" required class="form-input" :class="{ error: form.errors.last_name }" />
              <p v-if="form.errors.last_name" class="form-error">{{ form.errors.last_name }}</p>
            </div>
          </div>

          <div>
            <label class="form-label">Email <span style="color:#C9A84C;">*</span></label>
            <input v-model="form.email" type="email" required class="form-input" :class="{ error: form.errors.email }" />
            <p v-if="form.errors.email" class="form-error">{{ form.errors.email }}</p>
          </div>

          <div>
            <label class="form-label">Téléphone</label>
            <input v-model="form.phone" type="tel" placeholder="+262 692 00 00 00" class="form-input" />
          </div>

          <!-- Mot de passe -->
          <div style="padding:24px; border-radius:14px; background:#FAF7F2; border:1.5px solid rgba(201,168,76,0.15);">
            <h3 style="font-size:14px; font-weight:800; color:#0D1B2A; margin:0 0 18px; display:flex; align-items:center; gap:7px;">
              <PhLock style="width:15px; height:15px; color:#C9A84C;" weight="fill" />
              Changer le mot de passe
            </h3>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
              <div>
                <label class="form-label">Nouveau mot de passe</label>
                <input v-model="form.password" type="password" placeholder="Laisser vide pour ne pas changer" class="form-input" :class="{ error: form.errors.password }" />
                <p v-if="form.errors.password" class="form-error">{{ form.errors.password }}</p>
              </div>
              <div>
                <label class="form-label">Confirmer le mot de passe</label>
                <input v-model="form.password_confirmation" type="password" placeholder="Confirmer le nouveau mot de passe" class="form-input" />
              </div>
            </div>
          </div>

          <!-- Actions -->
          <div style="display:flex; align-items:center; justify-content:space-between; padding-top:8px; border-top:1px solid rgba(13,27,42,0.07);">
            <p style="font-size:12px; color:rgba(13,27,42,0.35); margin:0; display:flex; align-items:center; gap:5px;">
              <PhShieldCheck style="width:13px; height:13px; color:rgba(201,168,76,0.5);" weight="fill" />
              Vos données sont sécurisées
            </p>
            <button
              type="submit"
              :disabled="form.processing"
              style="display:inline-flex; align-items:center; gap:8px; padding:12px 28px; border-radius:12px; border:none; background:#C9A84C; color:#0D1B2A; font-size:14px; font-weight:800; cursor:pointer; font-family:inherit; transition:all 0.25s; white-space:nowrap;"
              class="submit-btn"
            >
              <PhCircleNotch v-if="form.processing" style="width:16px; height:16px;" class="spin" />
              <PhFloppyDisk v-else style="width:16px; height:16px;" />
              {{ form.processing ? 'Enregistrement...' : 'Sauvegarder' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Head, useForm } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import {
  PhCamera, PhUpload, PhCircleNotch, PhFloppyDisk,
  PhUser, PhLock, PhShieldCheck,
} from '@phosphor-icons/vue'

const props = defineProps({ user: Object })

const avatarPreview = ref(null)

const form = useForm({
  first_name:            props.user.first_name ?? '',
  last_name:             props.user.last_name  ?? '',
  email:                 props.user.email      ?? '',
  phone:                 props.user.phone      ?? '',
  password:              '',
  password_confirmation: '',
  avatar:                null,
})

function handleAvatarChange(e) {
  const file = e.target.files[0]
  if (!file) return
  form.avatar         = file
  avatarPreview.value = URL.createObjectURL(file)
}

function submit() {
  form.put(route('admin.profile.update'), { forceFormData: true })
}
</script>

<style scoped>
.form-label    { display:block; font-size:13px; font-weight:700; color:#0D1B2A; margin-bottom:8px; }
.form-input    { width:100%; padding:12px 14px; border:1.5px solid rgba(13,27,42,0.12); border-radius:12px; font-size:14px; color:#0D1B2A; outline:none; font-family:inherit; background:#FAF7F2; transition:all 0.2s; box-sizing:border-box; }
.form-input:focus { border-color:rgba(201,168,76,0.5); background:white; box-shadow:0 0 0 3px rgba(201,168,76,0.1); }
.form-input.error { border-color:#EF4444 !important; }
.form-error    { font-size:12px; color:#EF4444; margin-top:5px; }
.submit-btn:hover:not(:disabled) { background:#E2C97E !important; transform:translateY(-1px); box-shadow:0 6px 20px rgba(201,168,76,0.35); }
.submit-btn:disabled { opacity:0.6; cursor:not-allowed; }
.change-avatar-btn:hover { background:white !important; border-color:rgba(201,168,76,0.25) !important; color:#0D1B2A !important; }
.avatar-edit-btn:hover { background:#E2C97E !important; transform:scale(1.1); }
.spin { animation:spin 1s linear infinite; }
@keyframes spin { to { transform:rotate(360deg); } }
</style>