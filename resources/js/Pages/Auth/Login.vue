<template>
  <div style="min-height:100vh; background:#FAF7F2; display:flex; align-items:center; justify-content:center; padding:24px; position:relative; overflow:hidden;">

    <!-- Fond décoratif — orbes dorés -->
    <div style="position:absolute; inset:0; pointer-events:none; overflow:hidden;">
      <div style="position:absolute; top:-150px; right:-150px; width:600px; height:600px; border-radius:50%; background:radial-gradient(circle, rgba(201,168,76,0.12) 0%, transparent 65%); filter:blur(60px);" />
      <div style="position:absolute; bottom:-150px; left:-150px; width:500px; height:500px; border-radius:50%; background:radial-gradient(circle, rgba(201,168,76,0.08) 0%, transparent 65%); filter:blur(60px);" />
      <div style="position:absolute; top:40%; left:50%; transform:translate(-50%,-50%); width:800px; height:400px; border-radius:50%; background:radial-gradient(circle, rgba(201,168,76,0.05) 0%, transparent 65%); filter:blur(80px);" />
    </div>

    <!-- Grille de fond subtile -->
    <div style="position:absolute; inset:0; pointer-events:none; background-image:radial-gradient(rgba(13,27,42,0.045) 1px, transparent 1px); background-size:40px 40px; -webkit-mask-image:radial-gradient(ellipse 80% 80% at 50% 50%, black 40%, transparent 100%); mask-image:radial-gradient(ellipse 80% 80% at 50% 50%, black 40%, transparent 100%);" />

    <div style="position:relative; z-index:10; width:100%; max-width:460px;">

      <!-- Logo -->
      <div style="text-align:center; margin-bottom:40px;">
        <Link :href="route('home')" style="display:inline-flex; flex-direction:column; align-items:center; gap:14px; text-decoration:none;">
          <div style="position:relative;">
            <div style="width:68px; height:68px; border-radius:20px; background:linear-gradient(135deg,#C9A84C,#E2C97E); display:flex; align-items:center; justify-content:center; box-shadow:0 8px 32px rgba(201,168,76,0.4); position:relative; z-index:1;">
              <span style="color:#0D1B2A; font-weight:900; font-size:30px; line-height:1;">L</span>
            </div>
            <div style="position:absolute; inset:-6px; border-radius:26px; background:rgba(201,168,76,0.15); filter:blur(12px); z-index:0;" />
          </div>
          <div>
            <span style="color:#0D1B2A; font-weight:900; font-size:20px; display:block; letter-spacing:-0.02em; line-height:1.2;">Luxy Coaching & Formation</span>
            <span style="color:#A07828; font-size:11px; letter-spacing:0.2em; text-transform:uppercase; display:block; margin-top:4px; font-weight:700;">Espace administrateur</span>
          </div>
        </Link>
      </div>

      <!-- Carte de connexion -->
      <div style="background:white; border:1.5px solid rgba(201,168,76,0.18); border-radius:28px; padding:44px; box-shadow:0 8px 60px rgba(13,27,42,0.1), 0 2px 12px rgba(201,168,76,0.08); position:relative; overflow:hidden;">

        <!-- Ligne or top -->
        <div style="position:absolute; top:0; left:0; right:0; height:3px; background:linear-gradient(90deg, transparent 0%, #C9A84C 25%, #E2C97E 50%, #C9A84C 75%, transparent 100%);" />

        <h1 style="font-size:24px; font-weight:900; color:#0D1B2A; margin:0 0 6px; letter-spacing:-0.025em;">
          Connexion
        </h1>
        <p style="font-size:14px; color:rgba(13,27,42,0.5); margin:0 0 36px; line-height:1.6;">
          Accédez à votre espace d'administration.
        </p>

        <!-- Message d'erreur global -->
        <div v-if="form.errors.email && !form.errors.email.includes('@')" style="display:flex; align-items:center; gap:10px; padding:14px 16px; background:rgba(239,68,68,0.06); border:1px solid rgba(239,68,68,0.2); border-radius:12px; margin-bottom:24px;">
          <PhWarningCircle style="width:17px; height:17px; color:#EF4444; flex-shrink:0;" weight="fill" />
          <span style="font-size:13px; color:#DC2626; font-weight:600;">Email ou mot de passe incorrect.</span>
        </div>

        <form @submit.prevent="submit" style="display:flex; flex-direction:column; gap:22px;">

          <!-- Email -->
          <div>
            <label style="display:block; font-size:13px; font-weight:700; color:#0D1B2A; margin-bottom:9px; letter-spacing:0.01em;">
              Adresse email
            </label>
            <div style="position:relative;">
              <PhEnvelopeSimple style="position:absolute; left:14px; top:50%; transform:translateY(-50%); width:18px; height:18px; pointer-events:none;" :style="form.errors.email ? 'color:#EF4444;' : 'color:rgba(13,27,42,0.3);'" />
              <input
                v-model="form.email"
                type="email"
                autocomplete="email"
                required
                placeholder="votre@email.re"
                style="width:100%; padding:13px 14px 13px 44px; border-radius:14px; font-size:14px; color:#0D1B2A; outline:none; font-family:inherit; background:#FAF7F2; box-sizing:border-box; transition:all 0.2s;"
                :style="form.errors.email
                  ? 'border:1.5px solid #EF4444;'
                  : 'border:1.5px solid rgba(13,27,42,0.12);'"
                class="login-input"
              />
            </div>
            <p v-if="form.errors.email" style="font-size:12px; color:#EF4444; margin-top:6px; display:flex; align-items:center; gap:4px;">
              {{ form.errors.email }}
            </p>
          </div>

          <!-- Mot de passe -->
          <div>
            <label style="display:block; font-size:13px; font-weight:700; color:#0D1B2A; margin-bottom:9px; letter-spacing:0.01em;">
              Mot de passe
            </label>
            <div style="position:relative;">
              <PhLock style="position:absolute; left:14px; top:50%; transform:translateY(-50%); width:18px; height:18px; pointer-events:none;" :style="form.errors.password ? 'color:#EF4444;' : 'color:rgba(13,27,42,0.3);'" />
              <input
                v-model="form.password"
                :type="showPassword ? 'text' : 'password'"
                autocomplete="current-password"
                required
                placeholder="••••••••"
                style="width:100%; padding:13px 48px 13px 44px; border-radius:14px; font-size:14px; color:#0D1B2A; outline:none; font-family:inherit; background:#FAF7F2; box-sizing:border-box; transition:all 0.2s;"
                :style="form.errors.password
                  ? 'border:1.5px solid #EF4444;'
                  : 'border:1.5px solid rgba(13,27,42,0.12);'"
                class="login-input"
              />
              <button
                type="button"
                @click="showPassword = !showPassword"
                style="position:absolute; right:14px; top:50%; transform:translateY(-50%); background:none; border:none; cursor:pointer; color:rgba(13,27,42,0.3); padding:0; display:flex; align-items:center; justify-content:center; transition:color 0.2s;"
                class="eye-btn"
              >
                <PhEye v-if="showPassword" style="width:18px; height:18px;" />
                <PhEyeSlash v-else style="width:18px; height:18px;" />
              </button>
            </div>
            <p v-if="form.errors.password" style="font-size:12px; color:#EF4444; margin-top:6px;">
              {{ form.errors.password }}
            </p>
          </div>

          <!-- Se souvenir de moi -->
          <label style="display:flex; align-items:center; gap:12px; cursor:pointer; user-select:none;">
            <div
              @click="form.remember = !form.remember"
              style="width:20px; height:20px; border-radius:6px; border:2px solid; display:flex; align-items:center; justify-content:center; flex-shrink:0; transition:all 0.2s; cursor:pointer;"
              :style="form.remember
                ? 'background:#C9A84C; border-color:#C9A84C;'
                : 'background:white; border-color:rgba(13,27,42,0.2);'"
            >
              <PhCheck v-if="form.remember" style="width:12px; height:12px; color:#0D1B2A;" weight="bold" />
            </div>
            <input v-model="form.remember" type="checkbox" style="display:none;" />
            <span style="font-size:14px; color:rgba(13,27,42,0.6); font-weight:500;">Se souvenir de moi</span>
          </label>

          <!-- Bouton submit -->
          <button
            type="submit"
            :disabled="form.processing"
            style="display:flex; align-items:center; justify-content:center; gap:10px; width:100%; padding:15px; border-radius:14px; border:none; background:linear-gradient(135deg,#C9A84C,#B8943C); color:#0D1B2A; font-size:15px; font-weight:800; cursor:pointer; font-family:inherit; transition:all 0.3s cubic-bezier(0.16,1,0.3,1); margin-top:4px; letter-spacing:0.01em; position:relative; overflow:hidden;"
            class="submit-btn"
          >
            <span style="position:absolute; inset:0; background:linear-gradient(135deg, rgba(255,255,255,0.15), transparent); pointer-events:none;" />
            <PhCircleNotch v-if="form.processing" style="width:19px; height:19px;" class="spin" />
            <PhSignIn v-else style="width:19px; height:19px;" />
            {{ form.processing ? 'Connexion en cours...' : 'Se connecter' }}
          </button>
        </form>
      </div>

      <!-- Retour au site -->
      <div style="text-align:center; margin-top:28px;">
        <Link
          :href="route('home')"
          style="display:inline-flex; align-items:center; gap:8px; color:rgba(13,27,42,0.35); font-size:13px; font-weight:600; text-decoration:none; padding:10px 20px; border-radius:100px; border:1.5px solid rgba(13,27,42,0.1); background:white; transition:all 0.2s;"
          class="back-link"
        >
          <PhArrowLeft style="width:15px; height:15px;" />
          Retour au site
        </Link>
      </div>

      <!-- Footer discret -->
      <p style="text-align:center; margin-top:20px; font-size:11px; color:rgba(13,27,42,0.25); letter-spacing:0.03em;">
        © {{ year }} Luxy Coaching & Formation
      </p>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import {
  PhEnvelopeSimple, PhLock, PhEye, PhEyeSlash,
  PhSignIn, PhCircleNotch, PhArrowLeft,
  PhCheck, PhWarningCircle,
} from '@phosphor-icons/vue'

const showPassword = ref(false)
const year         = new Date().getFullYear()

const form = useForm({
  email:    '',
  password: '',
  remember: false,
})

function submit() {
  form.post(route('auth.login.post'), {
    onFinish: () => form.reset('password'),
  })
}
</script>

<style scoped>
/* ── Inputs ── */
.login-input:focus {
  border-color: rgba(201,168,76,0.55) !important;
  background: white !important;
  box-shadow: 0 0 0 3px rgba(201,168,76,0.1) !important;
}

/* ── Bouton submit ── */
.submit-btn:hover:not(:disabled) {
  background: linear-gradient(135deg, #E2C97E, #C9A84C) !important;
  transform: translateY(-2px);
  box-shadow: 0 8px 28px rgba(201,168,76,0.45);
}
.submit-btn:active:not(:disabled) {
  transform: translateY(0);
}
.submit-btn:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}

/* ── Toggle password ── */
.eye-btn:hover { color: rgba(13,27,42,0.6) !important; }

/* ── Retour au site ── */
.back-link:hover {
  background: #FAF7F2 !important;
  color: #0D1B2A !important;
  border-color: rgba(201,168,76,0.2) !important;
}

/* ── Spin ── */
.spin { animation: spin 1s linear infinite; }
@keyframes spin { to { transform: rotate(360deg); } }
</style>