<template>
  <PublicLayout>
    <Head title="Contactez-nous" />

    <!-- Hero -->
    <section style="background:#FFFFFF; padding:140px 0 80px; position:relative; overflow:hidden;">
      <div class="orb orb-1" />
      <div class="hero-grid" />
      <div style="position:absolute; left:0; top:0; bottom:0; width:3px; background:linear-gradient(to bottom, transparent, #C9A84C, transparent);" />

      <div style="max-width:1320px; margin:0 auto; padding:0 3rem; position:relative; z-index:1;">
        <div class="fade-up" style="--d:0s; display:inline-flex; align-items:center; gap:8px; background:rgba(201,168,76,0.1); border:1px solid rgba(201,168,76,0.25); border-radius:100px; padding:8px 16px; margin-bottom:24px;">
          <PhEnvelope style="width:14px; height:14px; color:#C9A84C;" />
          <span style="color:#A07828; font-size:13px; font-weight:600;">Contactez-nous</span>
        </div>
        <h1 class="fade-up" style="--d:0.1s; font-size:clamp(40px,5vw,72px); font-weight:900; color:#0D1B2A; letter-spacing:-0.03em; line-height:1.05; margin-bottom:20px;">
          Parlons de<br>
          <span style="background:linear-gradient(135deg,#A07828,#C9A84C,#E2C97E); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text;">votre projet</span>
        </h1>
        <p class="fade-up" style="--d:0.2s; font-size:18px; color:rgba(13,27,42,0.55); max-width:520px; line-height:1.75;">
          Vous avez une question, un projet de formation ? Notre équipe vous répond dans les plus brefs délais.
        </p>
      </div>
    </section>

    <!-- Contenu -->
    <section style="background:#FAF7F2; padding:80px 0 100px;">
      <div style="max-width:1320px; margin:0 auto; padding:0 3rem;">
        <div style="display:grid; grid-template-columns:1fr 1.5fr; gap:80px; align-items:start;">

          <!-- Infos de contact -->
          <div>
            <h2 style="font-size:26px; font-weight:900; color:#0D1B2A; letter-spacing:-0.02em; margin-bottom:36px;">
              Nos coordonnées
            </h2>

            <div style="display:flex; flex-direction:column; gap:16px; margin-bottom:32px;">
              <div
                v-for="info in contactInfos"
                :key="info.label"
                style="display:flex; align-items:flex-start; gap:16px; padding:22px; background:white; border-radius:20px; border:1.5px solid rgba(13,27,42,0.07); transition:all 0.25s;"
                class="info-card"
              >
                <div style="width:48px; height:48px; border-radius:14px; background:rgba(201,168,76,0.1); border:1px solid rgba(201,168,76,0.2); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                  <component :is="info.icon" style="width:22px; height:22px; color:#C9A84C;" weight="fill" />
                </div>
                <div>
                  <div style="font-size:12px; font-weight:700; color:rgba(13,27,42,0.4); letter-spacing:0.08em; text-transform:uppercase; margin-bottom:4px;">{{ info.label }}</div>
                  <a v-if="info.href" :href="info.href" style="font-size:15px; font-weight:600; color:#0D1B2A; text-decoration:none;" class="hover-gold">{{ info.value }}</a>
                  <div v-else style="font-size:15px; font-weight:600; color:#0D1B2A; line-height:1.5;">{{ info.value }}</div>
                </div>
              </div>
            </div>

            <!-- Horaires -->
            <div style="background:white; border-radius:20px; padding:28px; border:1.5px solid rgba(13,27,42,0.07);">
              <h3 style="font-size:16px; font-weight:800; color:#0D1B2A; margin-bottom:20px; display:flex; align-items:center; gap:10px;">
                <PhClock style="width:18px; height:18px; color:#C9A84C;" weight="fill" />
                Horaires d'ouverture
              </h3>
              <div style="display:flex; flex-direction:column; gap:10px;">
                <div v-for="h in horaires" :key="h.day" style="display:flex; justify-content:space-between; font-size:14px; padding:8px 0; border-bottom:1px solid rgba(13,27,42,0.05);">
                  <span style="color:rgba(13,27,42,0.55);">{{ h.day }}</span>
                  <span style="font-weight:700; color:#0D1B2A;" :style="h.hours === 'Fermé' ? 'color:rgba(13,27,42,0.35);' : ''">{{ h.hours }}</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Formulaire -->
          <div style="background:white; border-radius:28px; padding:48px; box-shadow:0 4px 40px rgba(13,27,42,0.07); border:1.5px solid rgba(201,168,76,0.12);">
            <!-- Ligne or top -->
            <div style="height:3px; background:linear-gradient(90deg, #C9A84C, #E2C97E, #C9A84C); border-radius:2px; margin-bottom:36px;" />

            <h2 style="font-size:26px; font-weight:900; color:#0D1B2A; letter-spacing:-0.02em; margin-bottom:8px;">
              Envoyez-nous un message
            </h2>
            <p style="font-size:15px; color:rgba(13,27,42,0.5); margin-bottom:36px; line-height:1.6;">
              Nous vous répondons dans les 24h ouvrées.
            </p>

            <form @submit.prevent="submit" style="display:flex; flex-direction:column; gap:20px;">

              <!-- Nom + Email -->
              <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                <div>
                  <label style="display:block; font-size:13px; font-weight:700; color:#0D1B2A; margin-bottom:8px;">
                    Nom complet <span style="color:#C9A84C;">*</span>
                  </label>
                  <input
                    v-model="form.full_name"
                    type="text"
                    placeholder="Jean Dupont"
                    required
                    class="form-input"
                    :class="{ 'form-input-error': form.errors.full_name }"
                  />
                  <p v-if="form.errors.full_name" class="form-error">{{ form.errors.full_name }}</p>
                </div>
                <div>
                  <label style="display:block; font-size:13px; font-weight:700; color:#0D1B2A; margin-bottom:8px;">
                    Email <span style="color:#C9A84C;">*</span>
                  </label>
                  <input
                    v-model="form.email"
                    type="email"
                    placeholder="jean@exemple.re"
                    required
                    class="form-input"
                    :class="{ 'form-input-error': form.errors.email }"
                  />
                  <p v-if="form.errors.email" class="form-error">{{ form.errors.email }}</p>
                </div>
              </div>

              <!-- Téléphone + Formation -->
              <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                <div>
                  <label style="display:block; font-size:13px; font-weight:700; color:#0D1B2A; margin-bottom:8px;">Téléphone</label>
                  <input v-model="form.phone" type="tel" placeholder="+262 692 00 00 00" class="form-input" />
                </div>
                <div>
                  <label style="display:block; font-size:13px; font-weight:700; color:#0D1B2A; margin-bottom:8px;">Formation intéressée</label>
                  <input v-model="form.formation_interest" type="text" placeholder="Ex: Formation Excel" class="form-input" />
                </div>
              </div>

              <!-- Objet -->
              <div>
                <label style="display:block; font-size:13px; font-weight:700; color:#0D1B2A; margin-bottom:8px;">
                  Objet <span style="color:#C9A84C;">*</span>
                </label>
                <input
                  v-model="form.subject"
                  type="text"
                  placeholder="Demande d'information sur une formation"
                  required
                  class="form-input"
                  :class="{ 'form-input-error': form.errors.subject }"
                />
                <p v-if="form.errors.subject" class="form-error">{{ form.errors.subject }}</p>
              </div>

              <!-- Message -->
              <div>
                <label style="display:block; font-size:13px; font-weight:700; color:#0D1B2A; margin-bottom:8px;">
                  Message <span style="color:#C9A84C;">*</span>
                </label>
                <textarea
                  v-model="form.message"
                  rows="5"
                  placeholder="Décrivez votre demande en quelques mots..."
                  required
                  class="form-input"
                  :class="{ 'form-input-error': form.errors.message }"
                  style="resize:vertical; min-height:120px;"
                />
                <p v-if="form.errors.message" class="form-error">{{ form.errors.message }}</p>
              </div>

              <!-- RGPD -->
              <label style="display:flex; align-items:flex-start; gap:12px; cursor:pointer;">
                <input
                  v-model="form.accept_rgpd"
                  type="checkbox"
                  required
                  style="width:18px; height:18px; margin-top:2px; accent-color:#C9A84C; flex-shrink:0;"
                />
                <span style="font-size:13px; color:rgba(13,27,42,0.55); line-height:1.6;">
                  J'accepte que mes données soient utilisées pour traiter ma demande,
                  conformément à notre <Link :href="route('politique-confidentialite')" style="color:#C9A84C; text-decoration:none; font-weight:600;">politique de confidentialité</Link>.
                </span>
              </label>
              <p v-if="form.errors.accept_rgpd" class="form-error">{{ form.errors.accept_rgpd }}</p>

              <!-- Submit -->
              <button
                type="submit"
                :disabled="form.processing"
                style="display:flex; align-items:center; justify-content:center; gap:10px; background:#C9A84C; color:#0D1B2A; font-weight:800; font-size:16px; padding:17px; border-radius:100px; border:none; cursor:pointer; font-family:inherit; transition:all 0.3s; margin-top:8px;"
                class="submit-btn"
              >
                <PhCircleNotch v-if="form.processing" style="width:18px; height:18px;" class="spin" />
                <PhPaperPlaneTilt v-else style="width:18px; height:18px;" />
                {{ form.processing ? 'Envoi en cours...' : 'Envoyer le message' }}
              </button>
            </form>
          </div>
        </div>
      </div>
    </section>
  </PublicLayout>
</template>

<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import PublicLayout from '@/Layouts/PublicLayout.vue'
import { PhEnvelope, PhMapPin, PhPhone, PhClock, PhPaperPlaneTilt, PhCircleNotch } from '@phosphor-icons/vue'

const form = useForm({
  full_name:          '',
  email:              '',
  phone:              '',
  subject:            '',
  message:            '',
  formation_interest: '',
  accept_rgpd:        false,
})

function submit() {
  form.post(route('contact.store'), {
    onSuccess: () => form.reset(),
  })
}

const contactInfos = [
  { icon: PhMapPin,  label: 'Adresse',   value: '123 Rue de la Formation, 97400 Saint-Denis, La Réunion', href: null },
  { icon: PhPhone,   label: 'Téléphone', value: '+262 262 00 00 00', href: 'tel:+262262000000' },
  { icon: PhEnvelope,label: 'Email',     value: 'contact@luxyformation.re', href: 'mailto:contact@luxyformation.re' },
]

const horaires = [
  { day: 'Lundi — Vendredi', hours: '08h00 — 17h00' },
  { day: 'Samedi',           hours: '08h30 — 12h00' },
  { day: 'Dimanche',         hours: 'Fermé' },
]
</script>

<style scoped>
.orb { position:absolute; border-radius:50%; filter:blur(100px); pointer-events:none; }
.orb-1 { width:600px; height:600px; background:radial-gradient(circle,rgba(201,168,76,0.12) 0%,transparent 70%); top:-200px; right:-100px; animation:floatOrb 10s ease-in-out infinite; }
@keyframes floatOrb { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-20px)} }
.hero-grid { position:absolute; inset:0; pointer-events:none; background-image:linear-gradient(rgba(13,27,42,0.04) 1px,transparent 1px),linear-gradient(90deg,rgba(13,27,42,0.04) 1px,transparent 1px); background-size:80px 80px; -webkit-mask-image:radial-gradient(ellipse at center,black 40%,transparent 80%); mask-image:radial-gradient(ellipse at center,black 40%,transparent 80%); }
.fade-up { animation:fadeUp 0.7s cubic-bezier(0.16,1,0.3,1) both; animation-delay:var(--d,0s); }
@keyframes fadeUp { from{opacity:0;transform:translateY(24px)} to{opacity:1;transform:translateY(0)} }
.hover-gold:hover { color:#C9A84C !important; }
.info-card:hover { box-shadow:0 4px 20px rgba(13,27,42,0.07); transform:translateY(-2px); border-color:rgba(201,168,76,0.2) !important; }
.form-input { width:100%; padding:13px 16px; border:1.5px solid rgba(13,27,42,0.12); border-radius:14px; font-size:15px; color:#0D1B2A; outline:none; font-family:inherit; background:#FAF7F2; transition:border-color 0.2s; box-sizing:border-box; }
.form-input:focus { border-color:#C9A84C; background:white; }
.form-input-error { border-color:#EF4444 !important; }
.form-error { font-size:12px; color:#EF4444; margin-top:5px; }
.submit-btn:hover:not(:disabled) { background:#E2C97E; transform:translateY(-2px); box-shadow:0 8px 32px rgba(201,168,76,0.35); }
.submit-btn:disabled { opacity:0.6; cursor:not-allowed; }
.spin { animation:spin 1s linear infinite; }
@keyframes spin { to { transform:rotate(360deg); } }
</style>