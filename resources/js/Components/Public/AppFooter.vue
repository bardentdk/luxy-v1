<template>
  <footer style="background:#1C1410; color:white; position:relative; overflow:hidden;">

    <!-- Section principale -->
    <div style="max-width:1320px; margin:0 auto; padding:80px 3rem;">
      <div style="display:grid; grid-template-columns:1.4fr 1fr 1fr 1.2fr; gap:64px;">

        <!-- Brand -->
        <div>
          <div style="display:flex; align-items:center; gap:12px; margin-bottom:24px;">
            <img src="https://luxyformation.re/wp-content/uploads/2024/03/cropped-horizontal_luxy_logo-300x97.png" alt="" class="w-40">
            <!-- <div style="width:40px; height:40px; border-radius:10px; background:#C9A84C; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
              <span style="color:#0D1B2A; font-weight:900; font-size:18px;">L</span>
            </div>
            <div>
              <span style="font-weight:800; font-size:17px; display:block; line-height:1.2;">Luxy</span>
              <span style="color:#C9A84C; font-size:10px; letter-spacing:0.15em; text-transform:uppercase; display:block;">Formation</span>
            </div> -->
          </div>

          <p style="color:rgba(255,255,255,0.5); font-size:14px; line-height:1.75; margin-bottom:28px; max-width:260px;">
            Centre de formation professionnelle et soutien scolaire à La Réunion.
            Développez vos compétences avec des formateurs experts.
          </p>

          <!-- Réseaux sociaux -->
          <div style="display:flex; gap:10px;">
            <a
              v-for="social in socials"
              :key="social.name"
              :href="social.url"
              target="_blank"
              rel="noopener noreferrer"
              :aria-label="social.name"
              class="social-icon"
            >
              <component :is="social.icon" style="width:16px; height:16px;" />
            </a>
          </div>
        </div>

        <!-- Navigation -->
        <div>
          <h3 style="color:#C9A84C; font-size:11px; font-weight:700; letter-spacing:0.2em; text-transform:uppercase; margin-bottom:24px;">
            Navigation
          </h3>
          <ul style="list-style:none; padding:0; margin:0; display:flex; flex-direction:column; gap:12px;">
            <li v-for="item in navLinks" :key="item.name">
              <Link
                :href="route(item.route)"
                class="footer-link"
              >
                <PhCaretRight style="width:12px; height:12px; color:#C9A84C; opacity:0; transition:opacity 0.2s;" class="footer-caret" />
                {{ item.name }}
              </Link>
            </li>
          </ul>
        </div>

        <!-- Contact -->
        <div>
          <h3 style="color:#C9A84C; font-size:11px; font-weight:700; letter-spacing:0.2em; text-transform:uppercase; margin-bottom:24px;">
            Contact
          </h3>
          <ul style="list-style:none; padding:0; margin:0; display:flex; flex-direction:column; gap:18px;">
            <li style="display:flex; align-items:flex-start; gap:12px; color:rgba(255,255,255,0.5); font-size:14px;">
              <PhMapPin style="width:18px; height:18px; color:#C9A84C; flex-shrink:0; margin-top:2px;" weight="fill" />
              <span style="line-height:1.6;">123 Rue de la Formation,<br>97400 Saint-Denis, La Réunion</span>
            </li>
            <li style="display:flex; align-items:center; gap:12px; color:rgba(255,255,255,0.5); font-size:14px;">
              <PhPhone style="width:18px; height:18px; color:#C9A84C; flex-shrink:0;" weight="fill" />
              <a href="tel:+262262000000" style="color:inherit; text-decoration:none; transition:color 0.2s;" class="hover-white">
                +262 262 00 00 00
              </a>
            </li>
            <li style="display:flex; align-items:center; gap:12px; color:rgba(255,255,255,0.5); font-size:14px;">
              <PhEnvelopeSimple style="width:18px; height:18px; color:#C9A84C; flex-shrink:0;" weight="fill" />
              <a href="mailto:contact@luxyformation.re" style="color:inherit; text-decoration:none; transition:color 0.2s;" class="hover-white">
                contact@luxyformation.re
              </a>
            </li>
          </ul>
        </div>

        <!-- Newsletter -->
        <div>
          <h3 style="color:#C9A84C; font-size:11px; font-weight:700; letter-spacing:0.2em; text-transform:uppercase; margin-bottom:24px;">
            Newsletter
          </h3>
          <p style="color:rgba(255,255,255,0.5); font-size:14px; line-height:1.7; margin-bottom:20px;">
            Recevez nos dernières actualités et offres de formation.
          </p>
          <form @submit.prevent="subscribeNewsletter" style="display:flex; flex-direction:column; gap:12px;">
            <input
              v-model="newsletterEmail"
              type="email"
              placeholder="votre@email.re"
              required
              style="background:rgba(255,255,255,0.07); border:1px solid rgba(255,255,255,0.15); border-radius:12px; padding:12px 16px; font-size:14px; color:white; outline:none; transition:border-color 0.2s; font-family:inherit;"
              class="newsletter-input"
            />
            <button
              type="submit"
              :disabled="newsletterLoading"
              style="display:flex; align-items:center; justify-content:center; gap:8px; background:#C9A84C; color:#0D1B2A; font-weight:800; font-size:14px; padding:13px 20px; border-radius:100px; border:none; cursor:pointer; transition:all 0.3s; font-family:inherit;"
              class="newsletter-btn"
            >
              <PhPaperPlaneTilt style="width:16px; height:16px;" />
              {{ newsletterLoading ? 'Envoi en cours...' : "S'abonner" }}
            </button>
          </form>
        </div>
      </div>
    </div>

    <!-- Bas du footer -->
    <div style="border-top:1px solid rgba(255,255,255,0.06); background:#16110C;">
      <div style="max-width:1320px; margin:0 auto; padding:24px 3rem; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:16px;">
        <p style="color:rgba(255,255,255,0.3); font-size:12px; margin:0;">
          © {{ currentYear }} Luxy Coaching &amp; Formation. Tous droits réservés.
        </p>
        <nav style="display:flex; flex-wrap:wrap; align-items:center; gap:24px;">
          <Link
            v-for="page in legalPages"
            :key="page.name"
            :href="route(page.route)"
            style="color:rgba(255,255,255,0.3); font-size:12px; text-decoration:none; transition:color 0.2s;"
            class="hover-white"
          >
            {{ page.name }}
          </Link>
        </nav>
      </div>
    </div>

  </footer>
</template>

<script setup>
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import {
  PhCaretRight,
  PhMapPin,
  PhPhone,
  PhEnvelopeSimple,
  PhPaperPlaneTilt,
  PhFacebookLogo,
  PhInstagramLogo,
  PhLinkedinLogo,
} from '@phosphor-icons/vue'

const currentYear      = new Date().getFullYear()
const newsletterEmail  = ref('')
const newsletterLoading = ref(false)

const socials = [
  { name: 'Facebook',  url: '#', icon: PhFacebookLogo },
  { name: 'Instagram', url: '#', icon: PhInstagramLogo },
  { name: 'LinkedIn',  url: '#', icon: PhLinkedinLogo },
]

const navLinks = [
  { name: 'Accueil',          route: 'home' },
  { name: 'La vie du centre', route: 'articles.index' },
  { name: 'Nos formations',   route: 'formations.index' },
  { name: 'Vos avis',         route: 'reviews.index' },
  { name: 'Contact',          route: 'contact' },
]

const legalPages = [
  { name: 'Mentions légales', route: 'mentions-legales' },
  { name: 'CGV',              route: 'cgv' },
  { name: 'CGU',              route: 'cgu' },
  { name: 'Cookies',          route: 'politique-cookies' },
  { name: 'Confidentialité',  route: 'politique-confidentialite' },
]

async function subscribeNewsletter() {
  newsletterLoading.value = true
  await new Promise(r => setTimeout(r, 1000))
  newsletterEmail.value   = ''
  newsletterLoading.value = false
  alert('Merci pour votre inscription !')
}
</script>

<style scoped>
.social-icon {
  width: 36px;
  height: 36px;
  border-radius: 10px;
  background: rgba(255,255,255,0.08);
  border: 1px solid rgba(255,255,255,0.1);
  display: flex;
  align-items: center;
  justify-content: center;
  text-decoration: none;
  color: rgba(255,255,255,0.6);
  transition: all 0.3s ease;
}
.social-icon:hover {
  background: #C9A84C;
  border-color: #C9A84C;
  color: #0D1B2A;
  transform: translateY(-2px);
}

.footer-link {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  color: rgba(255,255,255,0.5);
  font-size: 14px;
  text-decoration: none;
  transition: all 0.2s ease;
}
.footer-link:hover {
  color: white;
  transform: translateX(4px);
}
.footer-link:hover .footer-caret {
  opacity: 1 !important;
}

.hover-white:hover {
  color: white !important;
}

.newsletter-input:focus {
  border-color: rgba(201,168,76,0.5) !important;
}
.newsletter-btn:hover:not(:disabled) {
  background: #E2C97E;
  transform: translateY(-2px);
  box-shadow: 0 8px 24px rgba(201,168,76,0.3);
}
.newsletter-btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

@media (max-width: 1024px) {
  footer > div:first-child > div > div:first-child {
    grid-template-columns: 1fr 1fr !important;
  }
}
@media (max-width: 640px) {
  footer > div:first-child > div > div:first-child {
    grid-template-columns: 1fr !important;
  }
}
</style>