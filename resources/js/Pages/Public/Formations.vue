<template>
  <PublicLayout>
    <Head title="Nos formations" />

    <!-- Hero -->
    <!-- <section style="background:#FFFFFF; padding:140px 0 80px; position:relative; overflow:hidden;">
      <div class="orb orb-1" />
      <div class="hero-grid" />
      <div style="position:absolute; left:0; top:0; bottom:0; width:3px; background:linear-gradient(to bottom, transparent, #C9A84C, transparent);" />

      <div style="max-width:1320px; margin:0 auto; padding:0 3rem; position:relative; z-index:1;">
        <div style="display:inline-flex; align-items:center; gap:8px; background:rgba(201,168,76,0.1); border:1px solid rgba(201,168,76,0.25); border-radius:100px; padding:8px 16px; margin-bottom:24px;">
          <PhGraduationCap style="width:14px; height:14px; color:#C9A84C;" />
          <span style="color:#A07828; font-size:13px; font-weight:600;">Catalogue de formations</span>
        </div>
        <h1 style="font-size:clamp(40px,5vw,72px); font-weight:900; color:#0D1B2A; letter-spacing:-0.03em; line-height:1.05; margin:0 0 20px;">
          Nos formations
        </h1>
        <p style="font-size:18px; color:rgba(13,27,42,0.55); max-width:520px; line-height:1.75; margin:0;">
          Choisissez votre domaine de formation et découvrez les programmes disponibles à La Réunion.
        </p>
      </div>
    </section> -->

    <!-- Hero -->
    <section style="position:relative; background:#FFFFFF; overflow:hidden; min-height:540px;">

      <!-- Orbe -->
      <div class="orb orb-1" />

      <!-- Grille — z-index:3 pour passer devant l'image -->
      <div class="hero-grid" style="z-index:3;" />

      <!-- Bande dorée gauche -->
      <div style="position:absolute; left:0; top:0; bottom:0; width:3px; background:linear-gradient(to bottom, transparent, #C9A84C, transparent); z-index:4;" />

      <div style="max-width:1320px; margin:0 auto; padding:0 3rem; position:relative; z-index:1; width:100%; min-height:540px;">

        <!-- Colonne texte — au-dessus de la grille -->
        <div style="max-width:580px; padding:140px 0 100px; position:relative; z-index:4;">

          <div style="display:inline-flex; align-items:center; gap:8px; background:rgba(201,168,76,0.1); border:1px solid rgba(201,168,76,0.25); border-radius:100px; padding:8px 16px; margin-bottom:28px; width:fit-content;">
            <PhGraduationCap style="width:14px; height:14px; color:#C9A84C;" />
            <span style="color:#A07828; font-size:13px; font-weight:600;">Catalogue de formations</span>
          </div>

          <h1 style="font-size:clamp(40px,5vw,72px); font-weight:900; color:#0D1B2A; letter-spacing:-0.03em; line-height:1.05; margin:0 0 24px;">
            Nos formations
          </h1>

          <p style="font-size:18px; color:rgba(13,27,42,0.55); max-width:460px; line-height:1.75; margin:0 0 40px;">
            Choisissez votre domaine de formation et découvrez les programmes disponibles à La Réunion.
          </p>

          <!-- Mini stats -->
          <div style="display:flex; align-items:center; gap:14px; flex-wrap:wrap;">
            <div style="display:inline-flex; align-items:center; gap:8px; padding:10px 20px; background:#FAF7F2; border-radius:100px; border:1px solid rgba(13,27,42,0.08);">
              <PhGraduationCap style="width:14px; height:14px; color:#C9A84C;" weight="fill" />
              <span style="font-size:13px; font-weight:700; color:rgba(13,27,42,0.65);">
                {{ totalFormations }} formation{{ totalFormations > 1 ? 's' : '' }} disponible{{ totalFormations > 1 ? 's' : '' }}
              </span>
            </div>
            <div style="display:inline-flex; align-items:center; gap:8px; padding:10px 20px; background:#FAF7F2; border-radius:100px; border:1px solid rgba(13,27,42,0.08);">
              <PhTag style="width:14px; height:14px; color:#C9A84C;" weight="fill" />
              <span style="font-size:13px; font-weight:700; color:rgba(13,27,42,0.65);">
                {{ categories.length }} domaine{{ categories.length > 1 ? 's' : '' }}
              </span>
            </div>
          </div>
        </div>

        <!-- Colonne image — absolue à droite, derrière la grille -->
        <div
          style="position:absolute; right:0; top:0; width:25%; pointer-events:none;"
          class="hero-image-col"
        >
          <!-- Fond arrondi crème -->
          <div style="
            position:absolute;
            bottom:0;
            right:8%;
            width:420px;
            height:95%;
            background:linear-gradient(180deg, rgba(255,255,255, 1) 0%, rgba(255,255,255, 1) 100%);
            /* background:linear-gradient(180deg, rgba(201,168,76,0.07) 0%, rgba(201,168,76,0.14) 100%); */
            border-radius:320px 320px 0 0;
            border:1.5px solid rgba(201,168,76,0.15);
            border-bottom:none;
            z-index:0;
          " />

          <!-- Badge certifiante -->
          <div
            style="
              position:absolute;
              top:170px;
              right:-20%;
              background:white;
              border-radius:14px;
              border:1.5px solid rgba(201,168,76,0.22);
              padding:12px 18px;
              box-shadow:0 4px 20px rgba(13,27,42,0.08);
              z-index:5;
              display:flex;
              align-items:center;
              gap:10px;
              white-space:nowrap;
            "
            class="float-badge-1"
          >
            <div style="width:32px; height:32px; border-radius:9px; background:rgba(201,168,76,0.12); border:1px solid rgba(201,168,76,0.22); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
              <PhCertificate style="width:17px; height:17px; color:#C9A84C;" weight="fill" />
            </div>
            <div>
              <div style="font-size:10px; font-weight:700; color:rgba(13,27,42,0.35); letter-spacing:0.07em; text-transform:uppercase; margin-bottom:2px;">Formation</div>
              <div style="font-size:13px; font-weight:800; color:#0D1B2A;">Certifiante ✓</div>
            </div>
          </div>

          <!-- Badge financement -->
          <div
            style="
              position:absolute;
              top:260px;
              left:-50%;
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
            <div style="font-size:10px; font-weight:700; color:rgba(13,27,42,0.35); letter-spacing:0.08em; text-transform:uppercase; margin-bottom:4px;">Financement</div>
            <div style="font-size:13px; font-weight:800; color:#0D1B2A;">CPF éligible 💼</div>
          </div>

          <!-- Image PNG — derrière la grille (z-index:1) -->
          <img
            v-if="heroImage"
            :src="heroImage"
            alt="Formateur Luxy Formation"
            style="
              position:relative;
              filter:grayscale();
              bottom:0;
              left:50%;
              transform:translateX(-50%);
              height:105%;
              width:auto;
              max-width:none;
              object-fit:contain;
              object-position:start;
              display:block;
              /* filter:drop-shadow(0 12px 40px rgba(13,27,42,0.1)); */
              z-index:1;
            "
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
              z-index:1;
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
    <!-- Contenu principal -->
    <section style="background:#FAF7F2; min-height:60vh; padding:60px 0 100px;">
      <div style="max-width:1320px; margin:0 auto; padding:0 3rem;">

        <!-- ─── VUE BENTO GRID — Choix du domaine ─── -->
        <Transition name="view-switch" mode="out-in">
          <div v-if="!selectedCategory" key="bento">

            <!-- Titre section -->
            <div style="margin-bottom:40px;">
              <h2 style="font-size:26px; font-weight:900; color:#0D1B2A; letter-spacing:-0.02em; margin:0 0 8px;">
                Choisissez votre domaine
              </h2>
              <p style="font-size:15px; color:rgba(13,27,42,0.5); margin:0;">
                {{ totalFormations }} formation{{ totalFormations > 1 ? 's' : '' }} disponible{{ totalFormations > 1 ? 's' : '' }} dans {{ categories.length }} domaines
              </p>
            </div>

            <!-- Bento Grid -->
            <div class="bento-grid">
              <div
                v-for="(cat, i) in categories"
                :key="cat.id"
                class="bento-card"
                :class="`bento-card--${getBentoSize(i)}`"
                @click="selectCategory(cat)"
                :style="getBentoAccent(i)"
              >
                <!-- Fond décoratif -->
                <div class="bento-bg" />

                <!-- Badge formations count -->
                <div style="position:absolute; top:20px; right:20px; background:rgba(255,255,255,0.18); backdrop-filter:blur(8px); border:1px solid rgba(255,255,255,0.25); border-radius:100px; padding:4px 12px; font-size:12px; font-weight:700; color:white; z-index:2;">
                  {{ cat.formations_count }} formation{{ cat.formations_count > 1 ? 's' : '' }}
                </div>

                <!-- Contenu -->
                <div style="position:relative; z-index:2; height:100%; display:flex; flex-direction:column; justify-content:flex-end; padding:28px;">
                  <!-- Icône -->
                  <div style="width:52px; height:52px; border-radius:14px; background:rgba(255,255,255,0.18); backdrop-filter:blur(8px); display:flex; align-items:center; justify-content:center; margin-bottom:16px; border:1px solid rgba(255,255,255,0.25);">
                    <component :is="getPhosphorIcon(cat.icon)" style="width:26px; height:26px; color:white;" weight="fill" />
                  </div>

                  <h3 style="font-size:clamp(17px,2vw,22px); font-weight:900; color:white; margin:0 0 6px; letter-spacing:-0.01em; line-height:1.2;">
                    {{ cat.name }}
                  </h3>
                  <p v-if="cat.description" style="font-size:13px; color:rgba(255,255,255,0.65); margin:0 0 16px; line-height:1.5; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;">
                    {{ cat.description }}
                  </p>

                  <div style="display:inline-flex; align-items:center; gap:8px; color:white; font-size:13px; font-weight:700; background:rgba(255,255,255,0.15); padding:8px 14px; border-radius:100px; border:1px solid rgba(255,255,255,0.25); width:fit-content; transition:all 0.3s;" class="bento-cta">
                    Voir les formations
                    <PhArrowRight style="width:14px; height:14px;" />
                  </div>
                </div>
              </div>

              <!-- Carte "Toutes les formations" -->
              <div
                v-if="categories.length > 0"
                class="bento-card bento-card--all"
                @click="selectAll"
                style="background:white; border:2px dashed rgba(13,27,42,0.12);"
              >
                <div style="height:100%; display:flex; flex-direction:column; align-items:center; justify-content:center; padding:28px; text-align:center; gap:14px; position:relative; z-index:2;">
                  <div style="width:52px; height:52px; border-radius:14px; background:#FAF7F2; display:flex; align-items:center; justify-content:center;">
                    <PhSquaresFour style="width:26px; height:26px; color:rgba(13,27,42,0.4);" weight="fill" />
                  </div>
                  <div>
                    <div style="font-size:16px; font-weight:800; color:#0D1B2A; margin-bottom:4px;">Toutes les formations</div>
                    <div style="font-size:13px; color:rgba(13,27,42,0.45);">Voir l'intégralité du catalogue</div>
                  </div>
                  <div style="display:inline-flex; align-items:center; gap:6px; font-size:13px; font-weight:700; color:rgba(13,27,42,0.6); border:1.5px solid rgba(13,27,42,0.15); padding:7px 14px; border-radius:100px; transition:all 0.2s;" class="all-cta">
                    Explorer
                    <PhArrowRight style="width:13px; height:13px;" />
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- ─── VUE FORMATIONS D'UNE CATÉGORIE ─── -->
          <div v-else key="formations">

            <!-- Navigation retour -->
            <div style="display:flex; align-items:center; gap:16px; margin-bottom:36px; flex-wrap:wrap;">
              <button
                @click="selectedCategory = null; formations = null"
                style="display:inline-flex; align-items:center; gap:8px; padding:10px 18px; border-radius:100px; border:1.5px solid rgba(13,27,42,0.15); background:white; color:rgba(13,27,42,0.7); font-size:13px; font-weight:700; cursor:pointer; font-family:inherit; transition:all 0.2s;"
                class="back-pill"
              >
                <PhArrowLeft style="width:14px; height:14px;" />
                Tous les domaines
              </button>

              <div style="display:flex; align-items:center; gap:10px;">
                <div style="width:36px; height:36px; border-radius:10px; background:linear-gradient(135deg,#C9A84C,#E2C97E); display:flex; align-items:center; justify-content:center; box-shadow:0 4px 12px rgba(201,168,76,0.3);">
                  <component :is="getPhosphorIcon(selectedCategory.icon)" style="width:18px; height:18px; color:#0D1B2A;" weight="fill" />
                </div>
                <div>
                  <span style="font-size:18px; font-weight:900; color:#0D1B2A; letter-spacing:-0.01em;">{{ selectedCategory.name }}</span>
                  <span style="font-size:13px; color:rgba(13,27,42,0.45); margin-left:10px;">{{ formations?.total ?? 0 }} formation{{ (formations?.total ?? 0) > 1 ? 's' : '' }}</span>
                </div>
              </div>
            </div>

            <!-- Filtres -->
            <div style="background:white; border-radius:16px; padding:16px 20px; display:flex; align-items:center; gap:12px; box-shadow:0 1px 8px rgba(13,27,42,0.05); border:1px solid rgba(13,27,42,0.07); margin-bottom:28px; flex-wrap:wrap;">
              <div style="position:relative; flex:1; min-width:300px;">
                <PhMagnifyingGlass style="width:16px; height:16px; color:rgba(13,27,42,0.3); position:absolute; left:12px; top:50%; transform:translateY(-50%);" />
                <input
                  v-model="localSearch"
                  type="text"
                  placeholder="Rechercher une formation..."
                  style="width:100%; padding:9px 12px 9px 38px; border:1.5px solid rgba(13,27,42,0.1); border-radius:10px; font-size:14px; color:#0D1B2A; outline:none; font-family:inherit; background:#FAF7F2; box-sizing:border-box; transition:border-color 0.2s;"
                  class="search-inp"
                  @input="applyFilters"
                />
              </div>

              <div style="display:flex; gap:8px; flex-wrap:wrap;">
                <button
                  v-for="lvl in levels"
                  :key="lvl.value"
                  @click="setLevel(lvl.value)"
                  style="padding:8px 14px; border-radius:100px; font-size:12px; font-weight:700; cursor:pointer; transition:all 0.2s; white-space:nowrap; border:1.5px solid;"
                  :style="localLevel === lvl.value
                    ? 'background:#0D1B2A; color:white; border-color:#0D1B2A;'
                    : 'background:white; color:rgba(13,27,42,0.6); border-color:rgba(13,27,42,0.12);'"
                >
                  {{ lvl.label }}
                </button>
              </div>
            </div>

            <!-- Grille formations -->
            <div v-if="formations?.data?.length" style="display:grid; grid-template-columns:repeat(2,1fr); gap:24px;">
              <FormationCard
                v-for="formation in formations.data"
                :key="formation.id"
                :formation="formation"
              />
            </div>

            <!-- État vide -->
            <div v-else-if="formations" style="text-align:center; padding:80px 20px; background:white; border-radius:20px; border:1.5px dashed rgba(13,27,42,0.1);">
              <div style="width:72px; height:72px; border-radius:20px; background:rgba(201,168,76,0.08); border:2px dashed rgba(201,168,76,0.25); display:flex; align-items:center; justify-content:center; margin:0 auto 20px;">
                <PhGraduationCap style="width:32px; height:32px; color:rgba(201,168,76,0.4);" />
              </div>
              <h3 style="font-size:18px; font-weight:800; color:#0D1B2A; margin-bottom:8px;">Aucune formation trouvée</h3>
              <p style="font-size:14px; color:rgba(13,27,42,0.45);">Essayez de modifier vos critères.</p>
            </div>

            <!-- Chargement skeleton -->
            <div v-else style="display:grid; grid-template-columns:repeat(3,1fr); gap:24px;">
              <div v-for="i in 6" :key="i" style="background:white; border-radius:20px; overflow:hidden; border:1.5px solid rgba(13,27,42,0.07);">
                <div style="height:180px;" class="skeleton-anim" />
                <div style="padding:20px; display:flex; flex-direction:column; gap:8px;">
                  <div style="height:10px; background:#EDE8DC; border-radius:5px; width:35%;" />
                  <div style="height:18px; background:#EDE8DC; border-radius:5px;" />
                  <div style="height:14px; background:#EDE8DC; border-radius:5px; width:65%;" />
                  <div style="height:40px; background:#EDE8DC; border-radius:100px; margin-top:8px;" />
                </div>
              </div>
            </div>

            <!-- Pagination -->
            <div v-if="formations?.last_page > 1" style="display:flex; justify-content:center; align-items:center; gap:8px; margin-top:48px;">
              <button
                v-for="page in formations.last_page"
                :key="page"
                @click="goToPage(page)"
                style="padding:8px 14px; border-radius:100px; font-size:13px; font-weight:700; cursor:pointer; transition:all 0.2s; border:1.5px solid;"
                :style="page === formations.current_page
                  ? 'background:#0D1B2A; color:white; border-color:#0D1B2A;'
                  : 'background:white; color:rgba(13,27,42,0.6); border-color:rgba(13,27,42,0.12);'"
              >
                {{ page }}
              </button>
            </div>
          </div>
        </Transition>
      </div>
    </section>
  </PublicLayout>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import PublicLayout from '@/Layouts/PublicLayout.vue'
import FormationCard from '@/Components/Public/FormationCard.vue'
import {
  PhGraduationCap, PhArrowRight, PhArrowLeft,
  PhMagnifyingGlass, PhSquaresFour, PhTag,
  PhBriefcase, PhBooks, PhMonitor, PhUsers,
  PhTranslate, PhCertificate, PhChalkboardTeacher,
  PhCode, PhTrendUp, PhGlobe, PhUser,
} from '@phosphor-icons/vue'

const heroImage = "https://img.freepik.com/photos-premium/sympathique-bel-homme-beau-jeune-homme-croisant-bras-debout-fond-blanc_590464-47211.jpg?semt=ais_hybrid&w=740&q=80";

const props = defineProps({
  categories:       Array,
  formations:       Object,
  selectedCategory: Object,
  filters:          Object,
  // heroImage:        { type: String, default: null },
})

const selectedCategory = ref(props.selectedCategory ?? null)
const formations       = ref(props.formations ?? null)
const localSearch      = ref(props.filters?.search ?? '')
const localLevel       = ref(props.filters?.level  ?? '')

const levels = [
  { value: '',             label: 'Tous niveaux' },
  { value: 'debutant',     label: 'Débutant' },
  { value: 'intermediaire',label: 'Intermédiaire' },
  { value: 'avance',       label: 'Avancé' },
]

const iconMap = {
  PhBriefcase:        PhBriefcase,
  PhBooks:            PhBooks,
  PhMonitor:          PhMonitor,
  PhUsers:            PhUsers,
  PhTranslate:        PhTranslate,
  PhCertificate:      PhCertificate,
  PhChalkboardTeacher:PhChalkboardTeacher,
  PhCode:             PhCode,
  PhTrendUp:          PhTrendUp,
  PhGlobe:            PhGlobe,
  PhGraduationCap:    PhGraduationCap,
}

function getPhosphorIcon(iconName) {
  return iconMap[iconName] ?? PhGraduationCap
}

const totalFormations = computed(() =>
  props.categories.reduce((sum, c) => sum + (c.formations_count ?? 0), 0)
)

// Bento garde ses dégradés riches — c'est une zone graphique intentionnellement colorée
const bentoColors = [
  'linear-gradient(135deg, #0D1B2A 0%, #1A2D42 100%)',
  'linear-gradient(135deg, #C9A84C 0%, #B8943C 100%)',
  'linear-gradient(135deg, #1A3A5C 0%, #0D2440 100%)',
  'linear-gradient(135deg, #2D4A3E 0%, #1A3028 100%)',
  'linear-gradient(135deg, #3D2B1F 0%, #5C3D2E 100%)',
  'linear-gradient(135deg, #2B1F3D 0%, #1A1228 100%)',
]

function getBentoAccent(i) {
  return `background:${bentoColors[i % bentoColors.length]};`
}

function getBentoSize(i) {
  if (i === 0) return 'large'
  if (i === 1) return 'wide'
  return 'normal'
}

function selectCategory(cat) {
  selectedCategory.value = cat
  formations.value = null
  localSearch.value = ''
  localLevel.value  = ''
  router.get(route('formations.index'), { categorie: cat.slug }, {
    preserveState: true, preserveScroll: false, only: ['formations', 'selectedCategory'],
    onSuccess: (page) => { formations.value = page.props.formations },
  })
}

function selectAll() {
  selectedCategory.value = { name: 'Toutes les formations', icon: 'PhSquaresFour', slug: null }
  formations.value = null
  router.get(route('formations.index'), {}, {
    preserveState: true, only: ['formations'],
    onSuccess: (page) => { formations.value = page.props.formations },
  })
}

function setLevel(val) { localLevel.value = val; applyFilters() }

function applyFilters() {
  router.get(route('formations.index'), {
    categorie: selectedCategory.value?.slug ?? undefined,
    search:    localSearch.value || undefined,
    level:     localLevel.value  || undefined,
  }, {
    preserveState: true, replace: true, only: ['formations'],
    onSuccess: (page) => { formations.value = page.props.formations },
  })
}

function goToPage(page) {
  router.get(route('formations.index'), {
    categorie: selectedCategory.value?.slug ?? undefined,
    search:    localSearch.value || undefined,
    level:     localLevel.value  || undefined, page,
  }, {
    preserveState: true, only: ['formations'],
    onSuccess: (p) => { formations.value = p.props.formations },
  })
}

watch(() => props.formations,       (v) => { if (v) formations.value = v })
watch(() => props.selectedCategory, (v) => { if (v) selectedCategory.value = v })
</script>

<style scoped>
.orb { position:absolute; border-radius:50%; filter:blur(100px); pointer-events:none; }
.orb-1 { width:600px; height:600px; background:radial-gradient(circle,rgba(201,168,76,0.12) 0%,transparent 70%); top:-200px; right:-100px; animation:floatOrb 10s ease-in-out infinite; }
@keyframes floatOrb { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-24px)} }
.hero-grid { position:absolute; inset:0; pointer-events:none; background-image:linear-gradient(rgba(13,27,42,0.04) 1px,transparent 1px),linear-gradient(90deg,rgba(13,27,42,0.04) 1px,transparent 1px); background-size:80px 80px; -webkit-mask-image:radial-gradient(ellipse at center,black 40%,transparent 80%); mask-image:radial-gradient(ellipse at center,black 40%,transparent 80%); }

/* ── Bento Grid ───────── */
.bento-grid { display:grid; grid-template-columns:repeat(3,1fr); grid-auto-rows:200px; gap:16px; }
.bento-card { border-radius:20px; position:relative; overflow:hidden; cursor:pointer; transition:all 0.4s cubic-bezier(0.16,1,0.3,1); border:1px solid rgba(255,255,255,0.1); }
.bento-card:hover { transform:translateY(-4px) scale(1.01); box-shadow:0 20px 60px rgba(13,27,42,0.25); }
.bento-card:hover .bento-cta { background:rgba(255,255,255,0.22) !important; gap:12px !important; }
.bento-card:hover .bento-bg { opacity:1 !important; transform:scale(1.1); }
.bento-card--large { grid-column:span 2; grid-row:span 2; }
.bento-card--wide  { grid-column:span 1; grid-row:span 2; }
.bento-card--normal{ grid-column:span 1; grid-row:span 1; }
.bento-card--all   { grid-column:span 1; grid-row:span 1; }
.bento-bg { position:absolute; inset:0; background:rgba(255,255,255,0.04); opacity:0; transition:all 0.4s ease; }
.bento-card--all:hover { border-color:rgba(13,27,42,0.2) !important; }
.bento-card--all:hover .all-cta { background:#0D1B2A !important; color:white !important; border-color:#0D1B2A !important; }

/* ── Transitions ─────── */
.view-switch-enter-active { transition:all 0.4s cubic-bezier(0.16,1,0.3,1); }
.view-switch-leave-active { transition:all 0.25s ease; }
.view-switch-enter-from   { opacity:0; transform:translateY(16px); }
.view-switch-leave-to     { opacity:0; transform:translateY(-8px); }

.back-pill:hover { background:#FAF7F2 !important; border-color:rgba(201,168,76,0.2) !important; }
.search-inp:focus { border-color:#C9A84C !important; background:white !important; }

/* ── Skeleton ─────────── */
@keyframes skeleton { 0%{background-position:200% 0} 100%{background-position:-200% 0} }
.skeleton-anim { background:linear-gradient(90deg,#EDE8DC 25%,#E4DED2 50%,#EDE8DC 75%); background-size:200% 100%; animation:skeleton 1.5s ease-in-out infinite; }

@media (max-width:1024px) {
  .bento-grid { grid-template-columns:repeat(2,1fr); }
  .bento-card--large { grid-column:span 2; grid-row:span 1; }
  .bento-card--wide  { grid-column:span 1; grid-row:span 1; }
  .hero-image-col { display:none !important; }

}
@media (max-width:640px) {
  .bento-grid { grid-template-columns:1fr; grid-auto-rows:160px; }
  .bento-card--large, .bento-card--wide { grid-column:span 1; grid-row:span 1; }
  .hero-image-col { display:none !important; }

}
/* ── Badges flottants ── */

.float-badge-1 { animation:floatA 6s ease-in-out infinite; }
.float-badge-2 { animation:floatB 8s ease-in-out infinite 1.5s; }
@keyframes floatA { 0%,100%{transform:translateY(0) rotate(0deg)} 50%{transform:translateY(-8px) rotate(2deg)} }
@keyframes floatB { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-7px)} }

/* ── Masquer sur mobile ── */

@media (max-width:1100px) {
  .hero-image-col { display:none !important; }
}
</style>