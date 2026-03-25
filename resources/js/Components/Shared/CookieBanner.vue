<template>
  <Transition name="cookie-slide">
    <div
      v-if="visible"
      style="position:fixed; bottom:0; left:0; right:0; z-index:9000; padding:0 0 32px; pointer-events:none;"
    >
      <div style="max-width:1320px; margin:0 auto; padding:0 3rem;">
        <div
          style="
            background:white;
            border-radius:28px 28px 0 0;
            border:1.5px solid rgba(13,27,42,0.08);
            border-bottom:none;
            box-shadow:0 -8px 60px rgba(13,27,42,0.12), 0 -1px 0 rgba(201,168,76,0.15);
            overflow:hidden;
            pointer-events:all;
            position:relative;
          "
        >
          <!-- Ligne dorée top -->
          <div style="height:3px; background:linear-gradient(90deg, transparent 0%, #C9A84C 25%, #E2C97E 50%, #C9A84C 75%, transparent 100%);" />

          <!-- Contenu principal -->
          <div style="padding:40px 48px 44px;">
            <div style="display:grid; grid-template-columns:auto 1fr auto; gap:40px; align-items:center;">

              <!-- Icône décorative -->
              <div style="flex-shrink:0;">
                <div style="
                  width:64px;
                  height:64px;
                  border-radius:18px;
                  background:rgba(201,168,76,0.1);
                  border:1.5px solid rgba(201,168,76,0.22);
                  display:flex;
                  align-items:center;
                  justify-content:center;
                ">
                  <PhCookie style="width:30px; height:30px; color:#C9A84C;" weight="fill" />
                </div>
              </div>

              <!-- Texte -->
              <div>
                <h3 style="
                  font-size:18px;
                  font-weight:900;
                  color:#0D1B2A;
                  letter-spacing:-0.02em;
                  margin:0 0 10px;
                  line-height:1.2;
                ">
                  Nous utilisons des cookies
                </h3>
                <p style="
                  font-size:14px;
                  color:rgba(13,27,42,0.55);
                  line-height:1.75;
                  margin:0;
                  max-width:640px;
                ">
                  Luxy Formation utilise des cookies pour améliorer votre expérience de navigation,
                  analyser notre trafic et personnaliser notre contenu.
                  En continuant à utiliser notre site, vous acceptez notre utilisation des cookies.
                  Consultez notre
                  <Link
                    :href="route('politique-cookies')"
                    style="color:#C9A84C; font-weight:700; text-decoration:none; border-bottom:1px solid rgba(201,168,76,0.3); transition:border-color 0.2s;"
                    class="cookie-link"
                  >
                    politique des cookies
                  </Link>
                  pour en savoir plus.
                </p>
              </div>

              <!-- Actions -->
              <div style="display:flex; flex-direction:column; gap:12px; flex-shrink:0;">
                <button
                  @click="acceptAll"
                  style="
                    display:inline-flex;
                    align-items:center;
                    justify-content:center;
                    gap:8px;
                    background:#C9A84C;
                    color:#0D1B2A;
                    font-weight:800;
                    font-size:14px;
                    padding:13px 28px;
                    border-radius:100px;
                    border:none;
                    cursor:pointer;
                    font-family:inherit;
                    white-space:nowrap;
                    transition:all 0.3s cubic-bezier(0.16,1,0.3,1);
                    min-width:200px;
                  "
                  class="btn-accept"
                >
                  <PhCheckCircle style="width:16px; height:16px;" weight="fill" />
                  Tout accepter
                </button>

                <button
                  @click="acceptNecessary"
                  style="
                    display:inline-flex;
                    align-items:center;
                    justify-content:center;
                    gap:8px;
                    background:transparent;
                    color:rgba(13,27,42,0.6);
                    font-weight:700;
                    font-size:13px;
                    padding:11px 28px;
                    border-radius:100px;
                    border:1.5px solid rgba(13,27,42,0.15);
                    cursor:pointer;
                    font-family:inherit;
                    white-space:nowrap;
                    transition:all 0.3s cubic-bezier(0.16,1,0.3,1);
                    min-width:200px;
                  "
                  class="btn-refuse"
                >
                  Cookies nécessaires uniquement
                </button>
              </div>
            </div>

            <!-- Séparateur + options avancées -->
            <div style="margin-top:32px; padding-top:24px; border-top:1px solid rgba(13,27,42,0.07); display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:16px;">

              <!-- Toggles cookies -->
              <div style="display:flex; align-items:center; gap:24px; flex-wrap:wrap;">
                <div
                  v-for="cookie in cookieTypes"
                  :key="cookie.key"
                  style="display:flex; align-items:center; gap:10px;"
                >
                  <!-- Toggle -->
                  <button
                    @click="!cookie.required && toggleCookie(cookie.key)"
                    style="
                      position:relative;
                      width:44px;
                      height:24px;
                      border-radius:100px;
                      border:none;
                      cursor:pointer;
                      transition:background 0.25s;
                      flex-shrink:0;
                      padding:0;
                    "
                    :style="(cookie.required || preferences[cookie.key])
                      ? 'background:#C9A84C; cursor:' + (cookie.required ? 'default' : 'pointer')
                      : 'background:rgba(13,27,42,0.15);'"
                    :disabled="cookie.required"
                  >
                    <span
                      style="
                        position:absolute;
                        top:3px;
                        width:18px;
                        height:18px;
                        border-radius:50%;
                        background:white;
                        box-shadow:0 1px 4px rgba(0,0,0,0.2);
                        transition:left 0.25s cubic-bezier(0.16,1,0.3,1);
                      "
                      :style="(cookie.required || preferences[cookie.key]) ? 'left:23px;' : 'left:3px;'"
                    />
                  </button>

                  <div>
                    <div style="font-size:13px; font-weight:700; color:#0D1B2A; line-height:1.2;">
                      {{ cookie.label }}
                    </div>
                    <div style="font-size:11px; color:rgba(13,27,42,0.4); margin-top:1px;">
                      {{ cookie.required ? 'Toujours actif' : (preferences[cookie.key] ? 'Activé' : 'Désactivé') }}
                    </div>
                  </div>
                </div>
              </div>

              <!-- Bouton paramètres avancés + fermer -->
              <div style="display:flex; align-items:center; gap:12px;">
                <button
                  @click="savePreferences"
                  style="
                    display:inline-flex;
                    align-items:center;
                    gap:7px;
                    background:transparent;
                    color:#A07828;
                    font-size:13px;
                    font-weight:700;
                    padding:9px 18px;
                    border-radius:100px;
                    border:1.5px solid rgba(201,168,76,0.3);
                    cursor:pointer;
                    font-family:inherit;
                    transition:all 0.25s;
                    white-space:nowrap;
                  "
                  class="btn-save"
                >
                  <PhFloppyDisk style="width:14px; height:14px;" />
                  Enregistrer mes choix
                </button>

                <button
                  @click="acceptNecessary"
                  style="
                    width:36px;
                    height:36px;
                    border-radius:50%;
                    border:1.5px solid rgba(13,27,42,0.1);
                    background:white;
                    color:rgba(13,27,42,0.4);
                    display:flex;
                    align-items:center;
                    justify-content:center;
                    cursor:pointer;
                    transition:all 0.2s;
                    flex-shrink:0;
                  "
                  class="btn-close"
                  title="Fermer"
                >
                  <PhX style="width:16px; height:16px;" />
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </Transition>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import { Link } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import {
  PhCookie, PhCheckCircle, PhX, PhFloppyDisk,
} from '@phosphor-icons/vue'

const STORAGE_KEY = 'luxy_cookie_consent'

const visible = ref(false)

const preferences = reactive({
  analytics:   false,
  marketing:   false,
  preferences: false,
})

const cookieTypes = [
  {
    key:      'necessary',
    label:    'Nécessaires',
    required: true,
  },
  {
    key:      'analytics',
    label:    'Analytiques',
    required: false,
  },
  {
    key:      'marketing',
    label:    'Marketing',
    required: false,
  },
  {
    key:      'preferences',
    label:    'Préférences',
    required: false,
  },
]

onMounted(() => {
  const stored = localStorage.getItem(STORAGE_KEY)
  if (!stored) {
    // Léger délai pour une apparition non intempestive
    setTimeout(() => { visible.value = true }, 1200)
  }
})

function toggleCookie(key) {
  preferences[key] = !preferences[key]
}

function acceptAll() {
  preferences.analytics   = true
  preferences.marketing   = true
  preferences.preferences = true
  save({ all: true })
}

function acceptNecessary() {
  preferences.analytics   = false
  preferences.marketing   = false
  preferences.preferences = false
  save({ necessary: true })
}

function savePreferences() {
  save({ ...preferences })
}

function save(data) {
  localStorage.setItem(STORAGE_KEY, JSON.stringify({
    accepted_at: new Date().toISOString(),
    ...data,
  }))
  visible.value = false
}
</script>

<style scoped>
/* ── Transition ── */
.cookie-slide-enter-active {
  transition: all 0.6s cubic-bezier(0.16, 1, 0.3, 1);
}
.cookie-slide-leave-active {
  transition: all 0.4s cubic-bezier(0.4, 0, 1, 1);
}
.cookie-slide-enter-from,
.cookie-slide-leave-to {
  transform: translateY(100%);
  opacity: 0;
}

/* ── Boutons ── */
.btn-accept:hover {
  background: #E2C97E !important;
  transform: translateY(-2px);
  box-shadow: 0 8px 28px rgba(201,168,76,0.4);
}

.btn-refuse:hover {
  background: #FAF7F2 !important;
  border-color: rgba(13,27,42,0.25) !important;
  color: #0D1B2A !important;
}

.btn-save:hover {
  background: rgba(201,168,76,0.08) !important;
  border-color: rgba(201,168,76,0.45) !important;
}

.btn-close:hover {
  background: #FAF7F2 !important;
  color: #0D1B2A !important;
  border-color: rgba(13,27,42,0.2) !important;
}

.cookie-link:hover {
  border-color: #C9A84C !important;
}

/* ── Responsive ── */
@media (max-width: 1024px) {
  .cookie-grid {
    grid-template-columns: 1fr !important;
  }
}

@media (max-width: 768px) {
  .cookie-inner {
    padding: 32px 24px 36px !important;
  }
}
</style>