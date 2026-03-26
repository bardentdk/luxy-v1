<template>
  <PublicLayout>
    <Head title="La vie du centre" />

    <!-- Hero -->
    <!-- <section style="background:#FFFFFF; padding:140px 0 80px; position:relative; overflow:hidden;">
      <div class="orb orb-1" />
      <div class="hero-grid" />
      <div style="position:absolute; left:0; top:0; bottom:0; width:3px; background:linear-gradient(to bottom, transparent, #C9A84C, transparent);" />

      <div style="max-width:1320px; margin:0 auto; padding:0 3rem; position:relative; z-index:1;">
        <div class="fade-up" style="--d:0s; display:inline-flex; align-items:center; gap:8px; background:rgba(201,168,76,0.1); border:1px solid rgba(201,168,76,0.25); border-radius:100px; padding:8px 16px; margin-bottom:24px;">
          <PhNewspaper style="width:14px; height:14px; color:#C9A84C;" />
          <span style="color:#A07828; font-size:13px; font-weight:600;">Actualités & Blog</span>
        </div>
        <h1 class="fade-up" style="--d:0.1s; font-size:clamp(40px,5vw,72px); font-weight:900; color:#0D1B2A; letter-spacing:-0.03em; line-height:1.05; margin-bottom:20px;">
          La vie du centre
        </h1>
        <p class="fade-up" style="--d:0.2s; font-size:18px; color:rgba(13,27,42,0.55); max-width:520px; line-height:1.75;">
          Découvrez nos actualités, conseils et témoignages du monde de la formation professionnelle à La Réunion.
        </p>
      </div>
    </section> -->
    <!-- Hero -->
    <section style="position:relative; background:#FFFFFF; overflow:hidden; min-height:540px;">

      <!-- Orbe + grille -->
      <div class="orb orb-1" />
      <div class="hero-grid" style="z-index:3;" />
      <div style="position:absolute; left:0; top:0; bottom:0; width:3px; background:linear-gradient(to bottom, transparent, #C9A84C, transparent); z-index:2;" />

      <div style="max-width:1320px; margin:0 auto; padding:0 3rem; position:relative; z-index:1; width:100%; min-height:540px;">

        <!-- Colonne texte — positionnée normalement -->
        <div style="max-width:580px; padding:140px 0 100px; position:relative; z-index:4;">

          <div class="fade-up" style="--d:0s; display:inline-flex; align-items:center; gap:8px; background:rgba(201,168,76,0.1); border:1px solid rgba(201,168,76,0.25); border-radius:100px; padding:8px 16px; margin-bottom:28px; width:fit-content;">
            <PhNewspaper style="width:14px; height:14px; color:#C9A84C;" />
            <span style="color:#A07828; font-size:13px; font-weight:600;">Actualités & Blog</span>
          </div>

          <h1 class="fade-up" style="--d:0.1s; font-size:clamp(40px,5vw,72px); font-weight:900; color:#0D1B2A; letter-spacing:-0.03em; line-height:1.05; margin-bottom:24px;">
            La vie du centre
          </h1>

          <p class="fade-up" style="--d:0.2s; font-size:18px; color:rgba(13,27,42,0.55); max-width:460px; line-height:1.75; margin:0 0 40px;">
            Découvrez nos actualités, conseils et témoignages du monde de la formation professionnelle à La Réunion.
          </p>

          <div class="fade-up" style="--d:0.3s; display:flex; align-items:center; gap:16px; flex-wrap:wrap;">
            <div style="display:inline-flex; align-items:center; gap:8px; padding:10px 20px; background:#FAF7F2; border-radius:100px; border:1px solid rgba(13,27,42,0.08);">
              <PhNewspaper style="width:14px; height:14px; color:#C9A84C;" weight="fill" />
              <span style="font-size:13px; font-weight:700; color:rgba(13,27,42,0.65);">
                {{ articles.total ?? articles.data?.length ?? 0 }} article{{ (articles.total ?? articles.data?.length ?? 0) > 1 ? 's' : '' }} publié{{ (articles.total ?? articles.data?.length ?? 0) > 1 ? 's' : '' }}
              </span>
            </div>
          </div>
        </div>

        <!-- Image personne — positionnée en absolu à droite, ancrée en bas -->
        <div
          style="
            position:absolute;
            right:0;
            bottom:0;
            top:0;
            width:52%;
            pointer-events:none;
          "
          class="hero-image-col"
        >
          <!-- Fond arrondi crème derrière la personne -->
          <div style="
            position:absolute;
            bottom:0;
            right:8%;
            width:420px;
            height:95%;
            background:linear-gradient(180deg, rgba(201,168,76,0.07) 0%, rgba(201,168,76,0.14) 100%);
            border-radius:320px 320px 0 0;
            border:1.5px solid rgba(201,168,76,0.15);
            border-bottom:none;
            z-index:0;
          " />

          <!-- Badge étoile -->
          <div
            style="
              position:absolute;
              top:160px;
              right:0%;
              width:52px;
              height:52px;
              border-radius:14px;
              background:white;
              border:1.5px solid rgba(201,168,76,0.22);
              display:flex;
              align-items:center;
              justify-content:center;
              box-shadow:0 4px 20px rgba(13,27,42,0.08);
              z-index:5;
            "
            class="float-badge-1"
          >
            <PhStar style="width:24px; height:24px; color:#C9A84C;" weight="fill" />
          </div>

          <!-- Badge dernière actus -->
          <div
            style="
              position:absolute;
              top:190px;
              left:8%;
              background:white;
              border-radius:14px;
              border:1.5px solid rgba(13,27,42,0.07);
              padding:14px 20px;
              box-shadow:0 4px 20px rgba(13,27,42,0.08);
              z-index:5;
              white-space:nowrap;
            "
            class="float-badge-2"
          >
            <div style="font-size:10px; font-weight:700; color:rgba(13,27,42,0.35); letter-spacing:0.08em; text-transform:uppercase; margin-bottom:4px;">Dernière actus</div>
            <div style="font-size:14px; font-weight:800; color:#0D1B2A;">Rentrée 2026 🎉</div>
          </div>

          <!-- IMAGE PNG — grande, ancrée en bas -->
          <img
            v-if="heroImage"
            :src="heroImage"
            alt="Formateur Luxy Formation"
            style="
              position:absolute;
              bottom:0;
              left:50%;
              transform:translateX(-50%);
              height:105%;
              width:auto;
              max-width:none;
              object-fit:cover;
              object-position:bottom;
              display:block;
              /* filter:drop-shadow(0 12px 40px rgba(13,27,42,0.1)); */
              z-index:1;
            "
            class="grayscale"
          />

          <!-- Placeholder -->
          <div
            v-else
            style="
              position:absolute;
              bottom:0;
              left:50%;
              transform:translateX(-50%);
              width:280px;
              height:90%;
              border-radius:20px 20px 0 0;
              background:rgba(201,168,76,0.06);
              border:2px dashed rgba(201,168,76,0.25);
              border-bottom:none;
              display:flex;
              flex-direction:column;
              align-items:center;
              justify-content:center;
              gap:14px;
              z-index:2;
            "
          >
            <PhUser style="width:52px; height:52px; color:rgba(201,168,76,0.35);" weight="thin" />
            <span style="font-size:12px; font-weight:600; color:rgba(13,27,42,0.3); text-align:center; padding:0 24px; line-height:1.6;">
              Votre visuel PNG<br>ici
            </span>
          </div>
        </div>

      </div>
    </section>
    <!-- Articles -->
    <section style="background:#FAF7F2; padding:80px 0 100px;">
      <div style="max-width:1320px; margin:0 auto; padding:0 3rem;">

        <!-- Article en vedette (premier) -->
        <div v-if="articles.data?.[0]" style="margin-bottom:64px;">
          <Link
            :href="route('articles.show', articles.data[0].slug)"
            style="display:grid; grid-template-columns:1.2fr 1fr; gap:0; background:white; border-radius:28px; overflow:hidden; text-decoration:none; box-shadow:0 4px 32px rgba(13,27,42,0.08); border:1.5px solid rgba(13,27,42,0.07); transition:all 0.4s;"
            class="featured-hover"
          >
            <div style="overflow:hidden; min-height:400px; position:relative;">
              <img
                :src="articles.data[0].thumbnail_url"
                :alt="articles.data[0].title"
                style="width:100%; height:100%; object-fit:cover; transition:transform 0.7s; display:block;"
                class="featured-img"
              />
            </div>
            <div style="padding:52px 48px; display:flex; flex-direction:column; justify-content:center;">
              <div style="display:inline-flex; align-items:center; gap:8px; background:rgba(201,168,76,0.1); border:1px solid rgba(201,168,76,0.25); border-radius:100px; padding:6px 14px; margin-bottom:24px; align-self:flex-start;">
                <PhStar style="width:12px; height:12px; color:#C9A84C;" weight="fill" />
                <span style="font-size:12px; font-weight:700; color:#A07828;">Article à la une</span>
              </div>
              <h2 style="font-size:clamp(22px,2.5vw,34px); font-weight:900; color:#0D1B2A; letter-spacing:-0.02em; line-height:1.2; margin-bottom:16px;">
                {{ articles.data[0].title }}
              </h2>
              <p style="font-size:15px; color:rgba(13,27,42,0.55); line-height:1.75; margin-bottom:32px;">
                {{ articles.data[0].excerpt }}
              </p>
              <div style="display:flex; align-items:center; justify-content:space-between; padding-top:24px; border-top:1px solid rgba(13,27,42,0.07); flex-wrap:wrap; gap:12px;">
                <div style="display:flex; align-items:center; gap:10px;">
                  <img v-if="articles.data[0].author?.avatar_url" :src="articles.data[0].author.avatar_url" style="width:32px; height:32px; border-radius:50%; object-fit:cover; border:2px solid rgba(201,168,76,0.2);" />
                  <div>
                    <div style="font-size:13px; font-weight:700; color:#0D1B2A;">{{ articles.data[0].author?.full_name }}</div>
                    <div style="font-size:12px; color:rgba(13,27,42,0.4);">{{ articles.data[0].formatted_published_at }}</div>
                  </div>
                </div>
                <div style="display:flex; align-items:center; gap:6px; color:#C9A84C; font-size:14px; font-weight:700;">
                  Lire l'article
                  <PhArrowRight style="width:16px; height:16px;" />
                </div>
              </div>
            </div>
          </Link>
        </div>

        <!-- Grille articles restants -->
        <div v-if="articles.data?.length > 1" style="display:grid; grid-template-columns:repeat(3,1fr); gap:28px; margin-bottom:60px;">
          <ArticleCard
            v-for="article in articles.data.slice(1)"
            :key="article.id"
            :article="article"
          />
        </div>

        <!-- État vide -->
        <div v-if="!articles.data?.length" style="text-align:center; padding:80px 20px; background:white; border-radius:20px; border:1.5px dashed rgba(13,27,42,0.1);">
          <div style="width:80px; height:80px; border-radius:24px; background:rgba(201,168,76,0.1); border:2px dashed rgba(201,168,76,0.3); display:flex; align-items:center; justify-content:center; margin:0 auto 24px;">
            <PhNewspaper style="width:36px; height:36px; color:rgba(201,168,76,0.4);" />
          </div>
          <h3 style="font-size:20px; font-weight:700; color:#0D1B2A; margin-bottom:8px;">Aucun article pour le moment</h3>
          <p style="font-size:15px; color:rgba(13,27,42,0.5);">Revenez bientôt pour découvrir nos actualités.</p>
        </div>

        <!-- Pagination -->
        <div v-if="articles.last_page > 1" style="display:flex; justify-content:center; align-items:center; gap:8px; margin-top:20px;">
          <Link
            v-for="page in articles.last_page"
            :key="page"
            :href="route('articles.index', { page })"
            :style="page === articles.current_page
              ? 'padding:8px 16px; border-radius:100px; font-size:13px; font-weight:700; border:1.5px solid #0D1B2A; background:#0D1B2A; color:white; text-decoration:none;'
              : 'padding:8px 16px; border-radius:100px; font-size:13px; font-weight:600; border:1.5px solid rgba(13,27,42,0.12); background:white; color:rgba(13,27,42,0.6); text-decoration:none;'"
          >
            {{ page }}
          </Link>
        </div>
      </div>
    </section>
  </PublicLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import PublicLayout from '@/Layouts/PublicLayout.vue'
import ArticleCard from '@/Components/Public/ArticleCard.vue'
import { PhNewspaper, PhStar, PhArrowRight } from '@phosphor-icons/vue'

defineProps({ articles: Object })


const heroImage = "https://img.freepik.com/psd-gratuit/portrait-studio-jeune-adolescent_23-2150162526.jpg?semt=ais_hybrid&w=740&q=80";
</script>

<style scoped>
.orb { position:absolute; border-radius:50%; filter:blur(100px); pointer-events:none; }
.orb-1 { width:700px; height:700px; background:radial-gradient(circle,rgba(201,168,76,0.13) 0%,transparent 70%); top:-250px; right:-150px; animation:floatOrb 10s ease-in-out infinite; }
@keyframes floatOrb { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-24px)} }

.hero-grid { position:absolute; inset:0; pointer-events:none; background-image:linear-gradient(rgba(13,27,42,0.04) 1px,transparent 1px),linear-gradient(90deg,rgba(13,27,42,0.04) 1px,transparent 1px); background-size:80px 80px; -webkit-mask-image:radial-gradient(ellipse at center,black 40%,transparent 80%); mask-image:radial-gradient(ellipse at center,black 40%,transparent 80%); }

.fade-up { animation:fadeUp 0.7s cubic-bezier(0.16,1,0.3,1) both; animation-delay:var(--d,0s); }
@keyframes fadeUp { from{opacity:0;transform:translateY(24px)} to{opacity:1;transform:translateY(0)} }

/* ── Badges flottants ── */
.float-badge-1 { animation:floatA 6s ease-in-out infinite; }
.float-badge-2 { animation:floatB 8s ease-in-out infinite 1.5s; }
@keyframes floatA { 0%,100%{transform:translateY(0) rotate(0deg)} 50%{transform:translateY(-8px) rotate(2deg)} }
@keyframes floatB { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-7px)} }

/* ── Article vedette ── */
.featured-hover:hover { box-shadow:0 16px 64px rgba(13,27,42,0.12) !important; transform:translateY(-4px); border-color:rgba(201,168,76,0.2) !important; }
.featured-hover:hover .featured-img { transform:scale(1.04); }

/* ── Responsive ── */
@media (max-width:1100px) {
  .hero-image-col { display:none !important; }
}
</style>