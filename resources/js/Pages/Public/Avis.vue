<template>
  <PublicLayout>
    <Head title="Vos avis" />

    <!-- Hero -->
    <!-- <section style="background:#FFFFFF; padding:140px 0 80px; position:relative; overflow:hidden;">
      <div class="orb orb-1" />
      <div class="hero-grid" />
      <div style="position:absolute; left:0; top:0; bottom:0; width:3px; background:linear-gradient(to bottom, transparent, #C9A84C, transparent);" />

      <div style="max-width:1320px; margin:0 auto; padding:0 3rem; position:relative; z-index:1; text-align:center;">
        <div class="fade-up" style="--d:0s; display:inline-flex; align-items:center; gap:8px; background:rgba(201,168,76,0.1); border:1px solid rgba(201,168,76,0.25); border-radius:100px; padding:8px 16px; margin-bottom:24px;">
          <PhStar style="width:14px; height:14px; color:#C9A84C;" weight="fill" />
          <span style="color:#A07828; font-size:13px; font-weight:600;">Témoignages</span>
        </div>
        <h1 class="fade-up" style="--d:0.1s; font-size:clamp(40px,5vw,72px); font-weight:900; color:#0D1B2A; letter-spacing:-0.03em; line-height:1.05; margin-bottom:20px;">
          Ce que disent<br>
          <span style="background:linear-gradient(135deg,#A07828,#C9A84C,#E2C97E); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text;">nos apprenants</span>
        </h1>

        <div class="fade-up" style="--d:0.2s; display:inline-flex; align-items:center; gap:24px; background:white; border:1.5px solid rgba(201,168,76,0.2); border-radius:24px; padding:28px 40px; margin-top:20px; flex-wrap:wrap; justify-content:center; box-shadow:0 4px 32px rgba(13,27,42,0.07);">
          <div style="text-align:center;">
            <div style="font-size:52px; font-weight:900; color:#0D1B2A; line-height:1; background:linear-gradient(135deg,#A07828,#C9A84C); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text;">{{ stats.average }}</div>
            <div style="display:flex; gap:4px; justify-content:center; margin-top:8px;">
              <PhStar v-for="i in 5" :key="i" style="width:18px; height:18px;" :style="i <= Math.round(stats.average) ? 'color:#C9A84C;' : 'color:rgba(13,27,42,0.1);'" weight="fill" />
            </div>
          </div>
          <div style="width:1px; height:60px; background:rgba(13,27,42,0.08);" />
          <div style="text-align:center;">
            <div style="font-size:28px; font-weight:900; color:#0D1B2A; margin-bottom:4px;">{{ stats.total }}</div>
            <div style="font-size:14px; color:rgba(13,27,42,0.45); font-weight:600;">avis vérifiés</div>
          </div>
          <div style="width:1px; height:60px; background:rgba(13,27,42,0.08);" />
          <div style="display:flex; flex-direction:column; gap:6px;">
            <div v-for="(count, rating) in ratingBreakdown" :key="rating" style="display:flex; align-items:center; gap:10px;">
              <span style="font-size:12px; color:rgba(13,27,42,0.45); width:12px; text-align:right; font-weight:600;">{{ 5 - rating }}</span>
              <PhStar style="width:12px; height:12px; color:#C9A84C;" weight="fill" />
              <div style="width:100px; height:4px; background:rgba(13,27,42,0.08); border-radius:2px; overflow:hidden;">
                <div :style="`width:${stats.total > 0 ? (count / stats.total * 100) : 0}%; height:100%; background:#C9A84C; border-radius:2px;`" />
              </div>
              <span style="font-size:12px; color:rgba(13,27,42,0.35); font-weight:600;">{{ count }}</span>
            </div>
          </div>
        </div>
      </div>
    </section> -->
    <!-- Hero -->
    <section style="position:relative; background:#FFFFFF; overflow:hidden; min-height:580px;">
      <div class="orb orb-1" />
      <div class="hero-grid" style="z-index:3;" />
      <div style="position:absolute; left:0; top:0; bottom:0; width:3px; background:linear-gradient(to bottom, transparent, #C9A84C, transparent); z-index:4;" />

      <!-- IMAGE GAUCHE — ancrée en bas, grande -->
      <div
        style="position:absolute; left:0; bottom:0; width:520px; z-index:1; pointer-events:none;"
        class="hero-img-left"
      >
        <img
          v-if="heroImageLeft"
          :src="heroImageLeft"
          alt="Apprenant Luxy Formation"
          style="width:100%; height:auto; object-fit:contain; object-position:bottom; display:block; filter:grayscale()"
        />
        <div v-else style="width:100%; height:480px; border-radius:20px; background:rgba(201,168,76,0.06); border:2px dashed rgba(201,168,76,0.2); display:flex; flex-direction:column; align-items:center; justify-content:center; gap:12px;">
          <PhUser style="width:48px; height:48px; color:rgba(201,168,76,0.3);" weight="thin" />
          <span style="font-size:12px; font-weight:600; color:rgba(13,27,42,0.3);">Image gauche</span>
        </div>
      </div>

      <!-- IMAGE DROITE — ancrée en bas, grande -->
      <div
        style="position:absolute; right:0; bottom:0; width:520px; z-index:1; pointer-events:none;"
        class="hero-img-right"
      >
        <img
          v-if="heroImageRight"
          :src="heroImageRight"
          alt="Apprenant Luxy Formation"
          style="width:100%; height:auto; object-fit:contain; object-position:bottom; display:block; filter:grayscale()"
        />
        <div v-else style="width:100%; height:480px; border-radius:20px; background:rgba(201,168,76,0.06); border:2px dashed rgba(201,168,76,0.2); display:flex; flex-direction:column; align-items:center; justify-content:center; gap:12px;">
          <PhUser style="width:48px; height:48px; color:rgba(201,168,76,0.3);" weight="thin" />
          <span style="font-size:12px; font-weight:600; color:rgba(13,27,42,0.3);">Image droite</span>
        </div>
      </div>

      <!-- CONTENU CENTRÉ — au-dessus des images -->
      <div style="max-width:1320px; margin:0 auto; padding:0 3rem; position:relative; z-index:4; min-height:580px; display:flex; flex-direction:column; align-items:center; justify-content:center; padding-top:80px; padding-bottom:60px;">

        <div class="fade-up" style="--d:0s; display:inline-flex; align-items:center; gap:8px; background:rgba(201,168,76,0.1); border:1px solid rgba(201,168,76,0.25); border-radius:100px; padding:8px 16px; margin-bottom:28px;">
          <PhStar style="width:14px; height:14px; color:#C9A84C;" weight="fill" />
          <span style="color:#A07828; font-size:13px; font-weight:600;">Témoignages</span>
        </div>

        <h1 class="fade-up" style="--d:0.1s; font-size:clamp(40px,5.5vw,76px); font-weight:900; color:#0D1B2A; letter-spacing:-0.03em; line-height:1.05; margin-bottom:48px; text-align:center;">
          Ce que disent<br>
          <span style="background:linear-gradient(135deg,#A07828,#C9A84C,#E2C97E); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text;">nos apprenants</span>
        </h1>

        <!-- Score global -->
        <div class="fade-up" style="--d:0.2s; display:inline-flex; align-items:center; gap:24px; background:white; border:1.5px solid rgba(201,168,76,0.2); border-radius:24px; padding:28px 40px; flex-wrap:wrap; justify-content:center; box-shadow:0 4px 32px rgba(13,27,42,0.07);">
          <div style="text-align:center;">
            <div style="font-size:52px; font-weight:900; line-height:1; background:linear-gradient(135deg,#A07828,#C9A84C); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text;">{{ stats.average }}</div>
            <div style="display:flex; gap:4px; justify-content:center; margin-top:8px;">
              <PhStar v-for="i in 5" :key="i" style="width:18px; height:18px;" :style="i <= Math.round(stats.average) ? 'color:#C9A84C;' : 'color:rgba(13,27,42,0.1);'" weight="fill" />
            </div>
          </div>
          <div style="width:1px; height:60px; background:rgba(13,27,42,0.08);" />
          <div style="text-align:center;">
            <div style="font-size:28px; font-weight:900; color:#0D1B2A; margin-bottom:4px;">{{ stats.total }}</div>
            <div style="font-size:14px; color:rgba(13,27,42,0.45); font-weight:600;">avis vérifiés</div>
          </div>
          <div style="width:1px; height:60px; background:rgba(13,27,42,0.08);" />
          <div style="display:flex; flex-direction:column; gap:7px;">
            <div v-for="(count, rating) in ratingBreakdown" :key="rating" style="display:flex; align-items:center; gap:10px;">
              <span style="font-size:12px; color:rgba(13,27,42,0.45); width:12px; text-align:right; font-weight:600;">{{ 5 - rating }}</span>
              <PhStar style="width:12px; height:12px; color:#C9A84C;" weight="fill" />
              <div style="width:100px; height:4px; background:rgba(13,27,42,0.08); border-radius:2px; overflow:hidden;">
                <div :style="`width:${stats.total > 0 ? (count / stats.total * 100) : 0}%; height:100%; background:#C9A84C; border-radius:2px;`" />
              </div>
              <span style="font-size:12px; color:rgba(13,27,42,0.35); font-weight:600;">{{ count }}</span>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- Grille avis -->
    <section style="background:#FAF7F2; padding:80px 0 100px;">
      <div style="max-width:1320px; margin:0 auto; padding:0 3rem;">

        <div v-if="reviews.data?.length" style="display:grid; grid-template-columns:repeat(3,1fr); gap:24px; margin-bottom:60px;">
          <ReviewCard
            v-for="review in reviews.data"
            :key="review.id"
            :review="review"
          />
        </div>

        <div v-else style="text-align:center; padding:80px 20px; background:white; border-radius:20px; border:1.5px dashed rgba(13,27,42,0.1);">
          <div style="width:80px; height:80px; border-radius:24px; background:rgba(201,168,76,0.1); border:2px dashed rgba(201,168,76,0.3); display:flex; align-items:center; justify-content:center; margin:0 auto 24px;">
            <PhStar style="width:36px; height:36px; color:rgba(201,168,76,0.4);" weight="fill" />
          </div>
          <h3 style="font-size:20px; font-weight:700; color:#0D1B2A; margin-bottom:8px;">Aucun avis pour le moment</h3>
          <p style="font-size:15px; color:rgba(13,27,42,0.5);">Soyez le premier à partager votre expérience.</p>
        </div>

        <!-- Pagination -->
        <div v-if="reviews.last_page > 1" style="display:flex; justify-content:center; gap:8px; margin-top:48px;">
          <Link
            v-for="page in reviews.last_page"
            :key="page"
            :href="route('reviews.index', { page })"
            :style="page === reviews.current_page
              ? 'padding:8px 16px; border-radius:100px; font-size:13px; font-weight:700; border:1.5px solid #0D1B2A; background:#0D1B2A; color:white; text-decoration:none;'
              : 'padding:8px 16px; border-radius:100px; font-size:13px; font-weight:600; border:1.5px solid rgba(13,27,42,0.12); background:white; color:rgba(13,27,42,0.6); text-decoration:none;'"
          >
            {{ page }}
          </Link>
        </div>
      </div>
    </section>

    <!-- CTA laisser un avis -->
    <section style="background:white; padding:100px 0; text-align:center; border-top:1px solid rgba(13,27,42,0.06);">
      <div style="max-width:600px; margin:0 auto; padding:0 3rem;">
        <div style="display:inline-flex; align-items:center; gap:8px; background:rgba(201,168,76,0.1); border:1px solid rgba(201,168,76,0.25); border-radius:100px; padding:7px 16px; margin-bottom:28px;">
          <PhStar style="width:13px; height:13px; color:#C9A84C;" weight="fill" />
          <span style="font-size:13px; font-weight:600; color:#A07828;">Votre avis compte</span>
        </div>
        <h2 style="font-size:clamp(28px,3.5vw,44px); font-weight:900; color:#0D1B2A; letter-spacing:-0.03em; margin-bottom:16px;">
          Vous avez suivi une formation chez nous ?
        </h2>
        <p style="font-size:16px; color:rgba(13,27,42,0.55); line-height:1.75; margin-bottom:36px;">
          Partagez votre expérience et aidez d'autres apprenants à faire leur choix.
        </p>
        <Link :href="route('contact')" style="display:inline-flex; align-items:center; gap:10px; background:#C9A84C; color:#0D1B2A; font-weight:800; font-size:15px; padding:15px 32px; border-radius:100px; text-decoration:none;" class="cta-hover">
          <PhStar style="width:18px; height:18px;" weight="fill" />
          Laisser un avis
        </Link>
      </div>
    </section>
  </PublicLayout>
</template>

<script setup>
import { computed } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import PublicLayout from '@/Layouts/PublicLayout.vue'
import ReviewCard from '@/Components/Public/ReviewCard.vue'
import { PhStar, PhUser } from '@phosphor-icons/vue'

const props = defineProps({
  reviews: Object,
  stats:   Object,
})
const heroImageLeft = "https://png.pngtree.com/png-vector/20241116/ourmid/pngtree-smiling-man-giving-thumbs-up-png-image_14421150.png";
const heroImageRight = "https://img.freepik.com/photos-gratuite/brunette-homme-fond-blanc-isole_1368-4404.jpg?semt=ais_hybrid&w=740&q=80";

const ratingBreakdown = computed(() => [
  props.stats.five  ?? 0,
  props.stats.four  ?? 0,
  props.stats.three ?? 0,
])

</script>

<style scoped>
.orb { position:absolute; border-radius:50%; filter:blur(100px); pointer-events:none; }
.orb-1 { width:600px; height:600px; background:radial-gradient(circle,rgba(201,168,76,0.13) 0%,transparent 70%); top:-200px; right:-100px; animation:floatOrb 10s ease-in-out infinite; }
@keyframes floatOrb { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-24px)} }
.hero-grid { position:absolute; inset:0; pointer-events:none; background-image:linear-gradient(rgba(13,27,42,0.04) 1px,transparent 1px),linear-gradient(90deg,rgba(13,27,42,0.04) 1px,transparent 1px); background-size:80px 80px; -webkit-mask-image:radial-gradient(ellipse at center,black 40%,transparent 80%); mask-image:radial-gradient(ellipse at center,black 40%,transparent 80%); }
.fade-up { animation:fadeUp 0.7s cubic-bezier(0.16,1,0.3,1) both; animation-delay:var(--d,0s); }
@keyframes fadeUp { from{opacity:0;transform:translateY(24px)} to{opacity:1;transform:translateY(0)} }
.cta-hover:hover { background:#E2C97E !important; transform:translateY(-2px); box-shadow:0 8px 32px rgba(201,168,76,0.3); }

/* ── Images héro ── */
.hero-img-left  { animation:floatA 7s ease-in-out infinite; }
.hero-img-right { animation:floatB 8s ease-in-out infinite 1s; }
@keyframes floatA { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-10px)} }
@keyframes floatB { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-8px)} }

/* ── Masquer sur mobile ── */
@media (max-width:1100px) {
  .hero-img-left, .hero-img-right { display:none !important; }
}
</style>