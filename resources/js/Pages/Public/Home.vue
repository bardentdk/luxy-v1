<template>
  <PublicLayout>
    <Head title="Accueil" />

    <!-- ═══════════════════════════════════════════════════
         HERO
    ═══════════════════════════════════════════════════ -->
    <section :style="{ backgroundImg: `url(${bgHeroBanner})` }" style="position:relative; min-height:100vh; display:flex; align-items:center; background:#FFFFFF; overflow:hidden;">

      <!-- Orbes décoratifs animés -->
      <div class="orb orb-1" />
      <div class="orb orb-2" />
      <div class="orb orb-3" />

      <!-- Grille de fond -->
      <div class="hero-grid" />

      <!-- Bande dorée verticale décorative -->
      <div style="position:absolute; left:0; top:0; bottom:0; width:3px; background:linear-gradient(to bottom, transparent, #C9A84C, transparent);" />

      <div style="position:relative; z-index:10; max-width:1320px; margin:0 auto; padding:120px 3rem 80px; width:100%;">
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:80px; align-items:center;">

          <!-- Colonne gauche -->
          <div>
            <!-- Badge -->
            <div class="fade-up" style="--d:0s; display:inline-flex; align-items:center; gap:10px; background:rgba(201,168,76,0.12); border:1px solid rgba(201,168,76,0.3); border-radius:100px; padding:8px 18px; margin-bottom:36px;">
              <div style="width:6px; height:6px; border-radius:50%; background:#C9A84C; box-shadow:0 0 8px rgba(201,168,76,0.6);" />
              <span style="color:#A07828; font-size:13px; font-weight:600; letter-spacing:0.05em;">Centre de formation — La Réunion</span>
            </div>

            <!-- Titre principal -->
            <h1 class="fade-up" style="--d:0.1s; font-size:clamp(48px,5.5vw,80px); font-weight:900; line-height:1.0; color:#0D1B2A; margin-bottom:28px; letter-spacing:-0.03em;">
              Formez-vous<br>
              <span style="background:linear-gradient(135deg, #A07828 0%, #C9A84C 40%, #E2C97E 70%, #C9A84C 100%); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text;">avec excellence</span>
            </h1>

            <p class="fade-up" style="--d:0.2s; font-size:18px; line-height:1.75; color:rgba(13,27,42,0.55); max-width:480px; margin-bottom:44px;">
              Développez vos compétences professionnelles et académiques avec nos formations certifiantes
              dispensées par des experts à La Réunion.
            </p>

            <!-- CTAs -->
            <div class="fade-up" style="--d:0.3s; display:flex; gap:16px; flex-wrap:wrap; margin-bottom:64px;">
              <Link :href="route('formations.index')" class="cta-primary">
                <PhGraduationCap style="width:18px; height:18px;" />
                Voir les formations
              </Link>
              <Link :href="route('contact')" class="cta-outline">
                <PhPhone style="width:18px; height:18px;" />
                Nous contacter
              </Link>
            </div>

            <!-- Mini stats -->
            <div class="fade-up" style="--d:0.4s; display:grid; grid-template-columns:repeat(3,1fr); gap:0; border-top:1px solid rgba(13,27,42,0.09); padding-top:36px;">
              <div v-for="(s, i) in heroMiniStats" :key="s.label" style="text-align:center; padding:0 20px;" :style="i > 0 ? 'border-left:1px solid rgba(13,27,42,0.09)' : ''">
                <div style="font-size:32px; font-weight:900; color:#0D1B2A; line-height:1.1; background:linear-gradient(135deg,#A07828,#C9A84C); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text;">{{ s.value }}</div>
                <div style="font-size:12px; color:rgba(13,27,42,0.4); margin-top:4px; letter-spacing:0.03em;">{{ s.label }}</div>
              </div>
            </div>
          </div>

          <!-- Colonne droite — cartes flottantes -->
          <div class="fade-up" style="--d:0.2s; position:relative; display:grid; grid-template-columns:1fr 1fr; gap:16px;">
            <div
              v-for="(card, i) in heroCards"
              :key="card.label"
              class="hero-card"
              :style="i % 2 === 1 ? 'margin-top:32px;' : ''"
            >
              <div style="width:44px; height:44px; border-radius:12px; background:rgba(201,168,76,0.12); border:1px solid rgba(201,168,76,0.22); display:flex; align-items:center; justify-content:center; margin-bottom:16px;">
                <component :is="card.icon" style="width:22px; height:22px; color:#C9A84C;" weight="bold" />
              </div>
              <div style="font-size:15px; font-weight:700; color:#0D1B2A; margin-bottom:6px;">{{ card.label }}</div>
              <div style="font-size:13px; color:rgba(13,27,42,0.45); line-height:1.5;">{{ card.desc }}</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Scroll indicator -->
      <div style="position:absolute; bottom:40px; left:50%; transform:translateX(-50%); display:flex; flex-direction:column; align-items:center; gap:8px; color:rgba(13,27,42,0.25);" class="bounce">
        <span style="font-size:11px; letter-spacing:0.2em; text-transform:uppercase;">Découvrir</span>
        <PhCaretDown style="width:18px; height:18px;" />
      </div>
    </section>

    <!-- ═══════════════════════════════════════════════════
         BANDE STATS
    ═══════════════════════════════════════════════════ -->
    <section style="background:linear-gradient(135deg, #C9A84C 0%, #B8943C 50%, #C9A84C 100%); padding:48px 0; position:relative; overflow:hidden;">
      <div style="position:absolute; inset:0; opacity:0.06; background-image:radial-gradient(circle, #000 1px, transparent 1px); background-size:24px 24px;" />
      <div style="max-width:1320px; margin:0 auto; padding:0 3rem; display:grid; grid-template-columns:repeat(4,1fr); gap:40px; position:relative; z-index:1;">
        <div v-for="s in statsData" :key="s.label" style="text-align:center;">
          <div style="font-size:clamp(36px,4vw,56px); font-weight:900; color:#0D1B2A; line-height:1; letter-spacing:-0.03em;">
            {{ s.value }}<span style="font-size:0.6em; opacity:0.7;">{{ s.suffix }}</span>
          </div>
          <div style="font-size:13px; font-weight:600; color:rgba(13,27,42,0.65); margin-top:8px; letter-spacing:0.02em;">{{ s.label }}</div>
        </div>
      </div>
    </section>

    <!-- ═══════════════════════════════════════════════════
         FORMATIONS EN VEDETTE
    ═══════════════════════════════════════════════════ -->
    <section style="background:#FAF7F2; padding:100px 0;">
      <div style="max-width:1320px; margin:0 auto; padding:0 3rem;">

        <!-- Header section -->
        <div style="display:flex; flex-direction:column; align-items:center; text-align:center; margin-bottom:72px;">
          <div class="section-badge fade-up" style="--d:0s;">
            <PhGraduationCap style="width:14px; height:14px; color:#C9A84C;" />
            <span>Nos formations</span>
          </div>
          <h2 class="fade-up" style="--d:0.1s; font-size:clamp(36px,4vw,60px); font-weight:900; color:#0D1B2A; margin:20px 0 20px; letter-spacing:-0.03em; line-height:1.1;">
            Des formations <span style="background:linear-gradient(135deg,#A07828,#C9A84C,#E2C97E); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text;">certifiantes</span>
          </h2>
          <p class="fade-up" style="--d:0.2s; font-size:18px; color:rgba(13,27,42,0.55); max-width:520px; line-height:1.7;">
            Choisissez parmi notre catalogue de formations professionnelles conçues pour répondre
            aux besoins du marché réunionnais.
          </p>
        </div>

        <!-- Cards ou état vide -->
        <div v-if="featuredFormations?.length" style="display:grid; grid-template-columns:repeat(1,1fr); gap:28px;">
          <!-- <FormationCard
            v-for="formation in featuredFormations"
            :key="formation.id"
            :formation="formation"
          />
        </div> -->
          <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:28px; margin-bottom:52px;">
            <FormationCard
              v-for="formation in featuredFormations"
              :key="formation.id"
              :formation="formation"
            />
          </div>
        </div>

        <!-- État vide premium -->
        <div v-else style="display:grid; grid-template-columns:repeat(3,1fr); gap:28px;">
          <div
            v-for="i in 3"
            :key="i"
            class="formation-placeholder fade-up"
            :style="`--d:${i*0.1}s`"
          >
            <div style="height:200px; background:linear-gradient(135deg, rgba(201,168,76,0.08), rgba(201,168,76,0.03)); border-radius:16px; margin-bottom:24px; display:flex; align-items:center; justify-content:center; border:1px dashed rgba(201,168,76,0.2);">
              <PhGraduationCap style="width:40px; height:40px; color:rgba(201,168,76,0.3);" />
            </div>
            <div style="height:12px; background:#EDE8DC; border-radius:6px; margin-bottom:12px; width:40%;" />
            <div style="height:20px; background:#EDE8DC; border-radius:6px; margin-bottom:8px; width:90%;" />
            <div style="height:20px; background:#EDE8DC; border-radius:6px; margin-bottom:8px; width:70%;" />
            <div style="height:14px; background:#EDE8DC; border-radius:6px; margin-bottom:24px; width:55%;" />
            <div style="height:44px; background:linear-gradient(135deg, rgba(201,168,76,0.15), rgba(201,168,76,0.08)); border-radius:100px; border:1px dashed rgba(201,168,76,0.3);" />
          </div>
        </div>

        <div style="text-align:center; margin-top:52px;">
          <Link :href="route('formations.index')" class="cta-secondary">
            <PhArrowRight style="width:18px; height:18px;" />
            Voir toutes les formations
          </Link>
        </div>
      </div>
    </section>

    <!-- ═══════════════════════════════════════════════════
         SECTION "POURQUOI NOUS CHOISIR"
    ═══════════════════════════════════════════════════ -->
    <section style="background:#FFFFFF; padding:120px 0; position:relative; overflow:hidden;">
      <!-- Orbe déco clair -->
      <div class="orb orb-2-light" />
      <!-- Ligne or top -->
      <div style="position:absolute; top:0; left:0; right:0; height:1px; background:linear-gradient(90deg,transparent,rgba(201,168,76,0.3),transparent);" />

      <div style="max-width:1320px; margin:0 auto; padding:0 3rem; position:relative; z-index:1;">
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:100px; align-items:center;">

          <!-- Gauche : texte -->
          <div>
            <div class="section-badge fade-up" style="--d:0s; margin-bottom:28px;">
              <PhShieldCheck style="width:14px; height:14px; color:#C9A84C;" />
              <span>Notre approche</span>
            </div>
            <h2 class="fade-up" style="--d:0.1s; font-size:clamp(32px,3.5vw,52px); font-weight:900; color:#0D1B2A; letter-spacing:-0.03em; line-height:1.1; margin-bottom:24px;">
              L'excellence au cœur<br>de chaque <span style="background:linear-gradient(135deg,#A07828,#C9A84C,#E2C97E); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text;">parcours</span>
            </h2>
            <p class="fade-up" style="--d:0.2s; font-size:17px; color:rgba(13,27,42,0.55); line-height:1.8; margin-bottom:48px;">
              Nous accompagnons chaque apprenant avec une pédagogie adaptée,
              des formateurs certifiés et un suivi personnalisé tout au long de la formation.
            </p>
            <Link :href="route('contact')" class="cta-primary fade-up" style="--d:0.3s; display:inline-flex;">
              <PhEnvelope style="width:18px; height:18px;" />
              Prendre contact
            </Link>
          </div>

          <!-- Droite : liste des avantages -->
          <div style="display:flex; flex-direction:column; gap:20px;">
            <div
              v-for="(avantage, i) in avantages"
              :key="avantage.title"
              class="avantage-card fade-up"
              :style="`--d:${i*0.1}s`"
            >
              <div style="width:48px; height:48px; border-radius:14px; background:rgba(201,168,76,0.12); border:1px solid rgba(201,168,76,0.2); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <component :is="avantage.icon" style="width:22px; height:22px; color:#C9A84C;" weight="fill" />
              </div>
              <div>
                <div style="font-size:15px; font-weight:700; color:#0D1B2A; margin-bottom:4px;">{{ avantage.title }}</div>
                <div style="font-size:13px; color:rgba(13,27,42,0.5); line-height:1.6;">{{ avantage.desc }}</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ═══════════════════════════════════════════════════
         TÉMOIGNAGES
    ═══════════════════════════════════════════════════ -->
    <section style="background:#FAF7F2; padding:120px 0; overflow:hidden;">
      <div style="max-width:1320px; margin:0 auto; padding:0 3rem;">

        <div style="display:flex; align-items:flex-end; justify-content:space-between; margin-bottom:72px; flex-wrap:wrap; gap:24px;">
          <div>
            <div class="section-badge fade-up" style="--d:0s; margin-bottom:20px;">
              <PhStar style="width:14px; height:14px; color:#C9A84C;" weight="fill" />
              <span>Témoignages</span>
            </div>
            <h2 class="fade-up" style="--d:0.1s; font-size:clamp(32px,4vw,56px); font-weight:900; color:#0D1B2A; letter-spacing:-0.03em; line-height:1.1; margin:0;">
              Ce que disent<br>
              <span style="background:linear-gradient(135deg,#A07828,#C9A84C,#E2C97E); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text;">nos apprenants</span>
            </h2>
          </div>
          <Link :href="route('reviews.index')" class="cta-secondary fade-up" style="--d:0.2s; display:inline-flex;">
            Tous les avis
            <PhArrowRight style="width:16px; height:16px;" />
          </Link>
        </div>

        <!-- Cards avis ou état vide -->
        <div v-if="featuredReviews?.length" style="display:grid; grid-template-columns:repeat(3,1fr); gap:24px;">
          <ReviewCard
            v-for="review in featuredReviews"
            :key="review.id"
            :review="review"
          />
        </div>

        <div v-else style="display:grid; grid-template-columns:repeat(3,1fr); gap:24px;">
          <div
            v-for="i in 3"
            :key="i"
            class="review-placeholder fade-up"
            :style="`--d:${i*0.1}s`"
          >
            <div style="display:flex; gap:4px; margin-bottom:20px;">
              <PhStar v-for="j in 5" :key="j" style="width:16px; height:16px; color:#C9A84C;" weight="fill" />
            </div>
            <div style="height:14px; background:#EDE8DC; border-radius:6px; margin-bottom:8px;" />
            <div style="height:14px; background:#EDE8DC; border-radius:6px; margin-bottom:8px; width:80%;" />
            <div style="height:14px; background:#EDE8DC; border-radius:6px; width:60%; margin-bottom:32px;" />
            <div style="display:flex; align-items:center; gap:12px; padding-top:20px; border-top:1px solid rgba(13,27,42,0.07);">
              <div style="width:40px; height:40px; border-radius:50%; background:#E8E4DE;" />
              <div>
                <div style="height:12px; background:#E8E4DE; border-radius:6px; width:100px; margin-bottom:6px;" />
                <div style="height:10px; background:#E8E4DE; border-radius:6px; width:70px;" />
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ═══════════════════════════════════════════════════
         ARTICLES — LA VIE DU CENTRE
    ═══════════════════════════════════════════════════ -->
    <section style="background:#ffffff; padding:120px 0;">
      <div style="max-width:1320px; margin:0 auto; padding:0 3rem;">

        <div style="display:flex; align-items:flex-end; justify-content:space-between; margin-bottom:72px; flex-wrap:wrap; gap:24px;">
          <div>
            <div class="section-badge fade-up" style="--d:0s; margin-bottom:20px;">
              <PhNewspaper style="width:14px; height:14px; color:#C9A84C;" />
              <span>Actualités</span>
            </div>
            <h2 class="fade-up" style="--d:0.1s; font-size:clamp(32px,4vw,56px); font-weight:900; color:#0D1B2A; letter-spacing:-0.03em; line-height:1.1; margin:0;">
              La vie du<br>
              <span style="background:linear-gradient(135deg,#A07828,#C9A84C,#E2C97E); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text;">centre</span>
            </h2>
          </div>
          <Link :href="route('articles.index')" class="cta-secondary fade-up" style="--d:0.2s; display:inline-flex;">
            Tous les articles
            <PhArrowRight style="width:16px; height:16px;" />
          </Link>
        </div>

        <div v-if="latestArticles?.length" style="display:grid; grid-template-columns:repeat(3,1fr); gap:28px;">
          <ArticleCard
            v-for="article in latestArticles"
            :key="article.id"
            :article="article"
          />
        </div>

        <div v-else style="display:grid; grid-template-columns:repeat(3,1fr); gap:28px;">
          <div
            v-for="i in 3"
            :key="i"
            class="article-placeholder fade-up"
            :style="`--d:${i*0.1}s`"
          >
            <div style="height:180px; background:linear-gradient(135deg,#F4F0E6,#EDE8DC); border-radius:16px; margin-bottom:20px; display:flex; align-items:center; justify-content:center;">
              <PhNewspaper style="width:32px; height:32px; color:rgba(13,27,42,0.15);" />
            </div>
            <div style="height:10px; background:#EDE8DC; border-radius:6px; margin-bottom:12px; width:30%;" />
            <div style="height:18px; background:#EDE8DC; border-radius:6px; margin-bottom:8px;" />
            <div style="height:18px; background:#EDE8DC; border-radius:6px; margin-bottom:16px; width:75%;" />
            <div style="height:12px; background:#EDE8DC; border-radius:6px; width:50%;" />
          </div>
        </div>
      </div>
    </section>

    <!-- ═══════════════════════════════════════════════════
         CTA FINAL
    ═══════════════════════════════════════════════════ -->
    <section style="background:#FAF7F2; padding:140px 0; position:relative; overflow:hidden;">
      <!-- Orbes dorés déco -->
      <div class="orb orb-1" style="opacity:0.5;" />
      <div class="orb orb-3" style="opacity:0.35;" />

      <!-- Ligne dorée décorative top -->
      <div style="position:absolute; top:0; left:0; right:0; height:3px; background:linear-gradient(90deg, transparent 0%, #C9A84C 30%, #E2C97E 50%, #C9A84C 70%, transparent 100%);" />

      <!-- Grille de fond subtile -->
      <div style="position:absolute; inset:0; background-image:radial-gradient(rgba(13,27,42,0.04) 1px, transparent 1px); background-size:44px 44px; pointer-events:none; -webkit-mask-image:radial-gradient(ellipse 80% 80% at 50% 50%, black 40%, transparent 100%); mask-image:radial-gradient(ellipse 80% 80% at 50% 50%, black 40%, transparent 100%);" />

      <div style="max-width:900px; margin:0 auto; padding:0 3rem; text-align:center; position:relative; z-index:1;">

        <div class="section-badge fade-up" style="--d:0s; margin:0 auto 36px; display:inline-flex;">
          <PhRocketLaunch style="width:14px; height:14px; color:#C9A84C;" weight="fill" />
          <span>Commencez dès aujourd'hui</span>
        </div>

        <h2 class="fade-up" style="--d:0.1s; font-size:clamp(40px,5vw,76px); font-weight:900; color:#0D1B2A; letter-spacing:-0.04em; line-height:1.0; margin-bottom:28px;">
          Prêt à démarrer<br>
          <span style="background:linear-gradient(135deg, #A07828 0%, #C9A84C 40%, #E2C97E 70%, #C9A84C 100%); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text;">votre formation ?</span>
        </h2>

        <p class="fade-up" style="--d:0.2s; font-size:18px; color:rgba(13,27,42,0.55); line-height:1.75; margin-bottom:52px; max-width:600px; margin-left:auto; margin-right:auto;">
          Contactez-nous dès aujourd'hui pour découvrir le programme
          qui correspond à vos objectifs professionnels.
        </p>

        <div class="fade-up" style="--d:0.3s; display:flex; justify-content:center; gap:16px; flex-wrap:wrap;">
          <Link :href="route('contact')" class="cta-primary">
            <PhEnvelope style="width:18px; height:18px;" />
            Nous contacter
          </Link>
          <Link :href="route('formations.index')" class="cta-secondary">
            Voir les formations
            <PhArrowRight style="width:16px; height:16px;" />
          </Link>
        </div>

        <!-- Trust indicators -->
        <div class="fade-up" style="--d:0.4s; display:flex; justify-content:center; align-items:center; gap:40px; margin-top:72px; padding-top:48px; border-top:1px solid rgba(13,27,42,0.09); flex-wrap:wrap;">
          <div v-for="t in trustItems" :key="t.label" style="display:flex; align-items:center; gap:10px; color:rgba(13,27,42,0.4);">
            <component :is="t.icon" style="width:18px; height:18px; color:#C9A84C;" weight="fill" />
            <span style="font-size:13px; font-weight:500;">{{ t.label }}</span>
          </div>
        </div>
      </div>
    </section>

  </PublicLayout>
</template>

<script setup>
import { computed } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import PublicLayout from '@/Layouts/PublicLayout.vue'
import FormationCard from '@/Components/Public/FormationCard.vue'
import ReviewCard from '@/Components/Public/ReviewCard.vue'
import ArticleCard from '@/Components/Public/ArticleCard.vue'
import {
  PhGraduationCap, PhPhone, PhEnvelope, PhCaretDown, PhArrowRight,
  PhStar, PhNewspaper, PhShieldCheck, PhRocketLaunch,
  PhCertificate, PhBriefcase, PhChalkboardTeacher, PhUsers,
  PhCheckCircle, PhLockSimple, PhHeadset, PhMedal,
} from '@phosphor-icons/vue'
const bgHeroBanner = "https://f.hellowork.com/helloworkplace/2019/02/formation-professionnelle.jpg/uuu";
const props = defineProps({
  featuredFormations: Array,
  featuredReviews:    Array,
  latestArticles:     Array,
  stats:              Object,
})

const heroMiniStats = computed(() => [
  { value: (props.stats?.formations ?? 0) + '+', label: 'Formations' },
  { value: (props.stats?.reviews ?? 0) + '+',    label: 'Avis clients' },
  { value: (props.stats?.years ?? 8) + ' ans',   label: "D'expertise" },
])

const statsData = computed(() => [
  { value: props.stats?.students ?? 320, suffix: '+',    label: 'Apprenants formés' },
  { value: props.stats?.formations ?? 0, suffix: '',     label: 'Formations disponibles' },
  { value: props.stats?.reviews ?? 0,    suffix: '+',    label: 'Avis positifs' },
  { value: props.stats?.years ?? 8,      suffix: ' ans', label: "D'expertise" },
])

const heroCards = [
  { icon: PhCertificate,       label: 'Certifications',     desc: 'Formations certifiantes reconnues' },
  { icon: PhChalkboardTeacher, label: 'Formateurs experts', desc: 'Professionnels du secteur' },
  { icon: PhBriefcase,         label: 'Pro & Scolaire',     desc: 'Tous niveaux et profils' },
  { icon: PhUsers,             label: 'Suivi personnalisé', desc: 'Accompagnement individuel' },
]

const avantages = [
  { icon: PhMedal,       title: 'Formation d\'excellence',  desc: 'Des programmes conçus par des experts certifiés avec des méthodes pédagogiques éprouvées.' },
  { icon: PhHeadset,     title: 'Accompagnement continu',   desc: 'Un suivi personnalisé tout au long de votre parcours pour maximiser vos chances de succès.' },
  { icon: PhCertificate, title: 'Certifications reconnues', desc: 'Obtenez des certificats valorisés sur le marché du travail réunionnais et national.' },
  { icon: PhCheckCircle, title: 'Résultats garantis',       desc: 'Nous nous engageons sur des objectifs pédagogiques clairs et mesurables.' },
]

const trustItems = [
  { icon: PhShieldCheck, label: 'Organisme certifié Qualiopi' },
  { icon: PhUsers,       label: '320+ apprenants formés' },
  { icon: PhMedal,       label: 'Formateurs experts' },
  { icon: PhHeadset,     label: 'Support 7j/7' },
]
</script>

<style scoped>
/* ── Orbes animés ───────────────────────────── */
.orb {
  position: absolute;
  border-radius: 50%;
  filter: blur(100px);
  pointer-events: none;
}
.orb-1 {
  width: 600px; height: 600px;
  background: radial-gradient(circle, rgba(201,168,76,0.18) 0%, transparent 70%);
  top: -200px; right: -100px;
  animation: float1 8s ease-in-out infinite;
}
/* orb-2 sur fond clair : or très doux au lieu de bleu nuit */
.orb-2 {
  width: 500px; height: 500px;
  background: radial-gradient(circle, rgba(201,168,76,0.10) 0%, transparent 70%);
  bottom: -150px; left: -100px;
  animation: float2 10s ease-in-out infinite;
}
/* orb-2 variante section "pourquoi nous" */
.orb-2-light {
  position: absolute;
  width: 500px; height: 500px;
  border-radius: 50%;
  filter: blur(100px);
  pointer-events: none;
  background: radial-gradient(circle, rgba(201,168,76,0.12) 0%, transparent 70%);
  bottom: -150px; right: -100px;
  animation: float2 10s ease-in-out infinite;
}
.orb-3 {
  width: 300px; height: 300px;
  background: radial-gradient(circle, rgba(201,168,76,0.10) 0%, transparent 70%);
  top: 50%; left: 40%;
  animation: float1 12s ease-in-out infinite reverse;
}

@keyframes float1 {
  0%, 100% { transform: translateY(0) scale(1); }
  50%       { transform: translateY(-30px) scale(1.05); }
}
@keyframes float2 {
  0%, 100% { transform: translateY(0) scale(1); }
  50%       { transform: translateY(20px) scale(0.95); }
}

/* ── Grille hero ─────────────────────────────── */
.hero-grid {
  position: absolute; inset: 0; pointer-events: none;
  background-image:
    linear-gradient(rgba(13,27,42,0.04) 1px, transparent 1px),
    linear-gradient(90deg, rgba(13,27,42,0.04) 1px, transparent 1px);
  background-size: 80px 80px;
  -webkit-mask-image: radial-gradient(ellipse at center, black 40%, transparent 80%);
  mask-image: radial-gradient(ellipse at center, black 40%, transparent 80%);
}

/* ── Animations d'entrée ─────────────────────── */
.fade-up {
  animation: fadeUp 0.7s cubic-bezier(0.16, 1, 0.3, 1) both;
  animation-delay: var(--d, 0s);
}
@keyframes fadeUp {
  from { opacity: 0; transform: translateY(24px); }
  to   { opacity: 1; transform: translateY(0); }
}

/* ── Bounce scroll indicator ─────────────────── */
.bounce {
  animation: bounce 2s ease-in-out infinite;
}
@keyframes bounce {
  0%, 100% { transform: translateX(-50%) translateY(0); }
  50%       { transform: translateX(-50%) translateY(8px); }
}

/* ── Hero cards ──────────────────────────────── */
.hero-card {
  background: rgba(13,27,42,0.03);
  border: 1px solid rgba(13,27,42,0.08);
  border-radius: 20px;
  padding: 28px;
  transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
}
.hero-card:hover {
  background: rgba(201,168,76,0.07);
  border-color: rgba(201,168,76,0.25);
  transform: translateY(-4px);
  box-shadow: 0 8px 32px rgba(201,168,76,0.15);
}

/* ── Badges de section ───────────────────────── */
.section-badge {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: rgba(201,168,76,0.1);
  border: 1px solid rgba(201,168,76,0.25);
  border-radius: 100px;
  padding: 8px 16px;
  font-size: 13px;
  font-weight: 600;
  color: #A07828;
  letter-spacing: 0.02em;
}

/* ── CTAs ────────────────────────────────────── */
.cta-primary {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  background: #C9A84C;
  color: #0D1B2A;
  font-weight: 800;
  font-size: 15px;
  padding: 14px 28px;
  border-radius: 100px;
  text-decoration: none;
  transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
  white-space: nowrap;
  letter-spacing: 0.01em;
  cursor: pointer;
  border: none;
}
.cta-primary:hover {
  background: #E2C97E;
  transform: translateY(-2px);
  box-shadow: 0 8px 32px rgba(201,168,76,0.4);
}

/* Sur fond clair : outline navy */
.cta-outline {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  background: transparent;
  color: #0D1B2A;
  font-weight: 700;
  font-size: 15px;
  padding: 14px 28px;
  border-radius: 100px;
  text-decoration: none;
  border: 1.5px solid rgba(13,27,42,0.2);
  transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
  white-space: nowrap;
}
.cta-outline:hover {
  background: #0D1B2A;
  color: white;
  border-color: #0D1B2A;
  transform: translateY(-2px);
}

.cta-secondary {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  background: transparent;
  color: #0D1B2A;
  font-weight: 700;
  font-size: 15px;
  padding: 12px 24px;
  border-radius: 100px;
  text-decoration: none;
  border: 2px solid rgba(13,27,42,0.18);
  transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
  white-space: nowrap;
}
.cta-secondary:hover {
  background: #0D1B2A;
  color: white;
  border-color: #0D1B2A;
  transform: translateY(-2px);
}

/* Pas de cta-ghost sur fond sombre dans la V1 claire — on le garde navy */
.cta-ghost {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  background: transparent;
  color: rgba(13,27,42,0.65);
  font-weight: 700;
  font-size: 15px;
  padding: 14px 28px;
  border-radius: 100px;
  text-decoration: none;
  border: 1.5px solid rgba(13,27,42,0.15);
  transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
  white-space: nowrap;
}
.cta-ghost:hover {
  background: #0D1B2A;
  color: white;
  border-color: #0D1B2A;
  transform: translateY(-2px);
}

/* ── Avantage cards sur fond blanc ───────────── */
.avantage-card {
  display: flex;
  align-items: flex-start;
  gap: 20px;
  padding: 24px;
  background: #FAF7F2;
  border: 1px solid rgba(13,27,42,0.07);
  border-radius: 16px;
  transition: all 0.3s ease;
}
.avantage-card:hover {
  background: white;
  border-color: rgba(201,168,76,0.25);
  transform: translateX(6px);
  box-shadow: 0 4px 20px rgba(13,27,42,0.07);
}

/* ── Placeholders skeletons ──────────────────── */
.formation-placeholder,
.review-placeholder,
.article-placeholder {
  background: white;
  border: 1px solid rgba(13,27,42,0.07);
  border-radius: 20px;
  padding: 28px;
}

/* ── Mobile responsive ───────────────────────── */
@media (max-width: 1024px) {
  .hero-right-grid { display: none !important; }
}
</style>