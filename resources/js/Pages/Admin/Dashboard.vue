<template>
  <AdminLayout>
    <Head title="Tableau de bord" />

    <div style="display:flex; flex-direction:column; gap:24px; max-width:1400px;">

      <!-- En-tête avec date -->
      <div style="display:flex; align-items:flex-start; justify-content:space-between; flex-wrap:wrap; gap:16px;">
        <div>
          <div style="display:flex; align-items:center; gap:10px; margin-bottom:6px;">
            <div style="width:8px; height:8px; border-radius:50%; background:#22C55E; box-shadow:0 0 8px rgba(34,197,94,0.6);" />
            <span style="font-size:12px; font-weight:600; color:#22C55E; letter-spacing:0.03em;">Système opérationnel</span>
          </div>
          <h1 style="font-size:24px; font-weight:900; color:#0D1B2A; letter-spacing:-0.025em; margin:0 0 4px;">
            Bonjour, {{ $page.props.auth?.user?.first_name }} 👋
          </h1>
          <p style="font-size:13px; color:rgba(13,27,42,0.45); margin:0;">
            {{ todayLabel }} — voici un résumé de l'activité de votre site
          </p>
        </div>
        <div style="display:flex; gap:10px; flex-wrap:wrap;">
          <Link
            :href="route('admin.formations.create')"
            style="display:inline-flex; align-items:center; gap:7px; height:38px; padding:0 18px; border-radius:10px; background:#C9A84C; color:#0D1B2A; font-size:13px; font-weight:800; text-decoration:none; transition:all 0.2s; border:none; white-space:nowrap;"
            class="action-btn-gold"
          >
            <PhPlus style="width:14px; height:14px;" />
            Nouvelle formation
          </Link>
          <Link
            :href="route('admin.articles.create')"
            style="display:inline-flex; align-items:center; gap:7px; height:38px; padding:0 18px; border-radius:10px; background:white; border:1.5px solid rgba(13,27,42,0.12); color:rgba(13,27,42,0.7); font-size:13px; font-weight:700; text-decoration:none; transition:all 0.2s; white-space:nowrap;"
            class="action-btn-light"
          >
            <PhPencil style="width:14px; height:14px;" />
            Nouvel article
          </Link>
        </div>
      </div>

      <!-- Alertes si éléments en attente -->
      <div v-if="stats.unread_contacts > 0 || stats.pending_reviews > 0" style="display:flex; gap:12px; flex-wrap:wrap;">
        <Link
          v-if="stats.unread_contacts > 0"
          :href="route('admin.contacts.index')"
          style="display:flex; align-items:center; gap:10px; padding:14px 18px; background:linear-gradient(135deg,rgba(245,158,11,0.07),rgba(245,158,11,0.03)); border:1px solid rgba(245,158,11,0.2); border-radius:14px; text-decoration:none; transition:all 0.2s; flex:1; min-width:240px;"
          class="alert-card"
        >
          <div style="width:36px; height:36px; border-radius:10px; background:rgba(245,158,11,0.12); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
            <PhEnvelopeSimple style="width:17px; height:17px; color:#F59E0B;" weight="fill" />
          </div>
          <div>
            <span style="font-size:13px; font-weight:700; color:#0D1B2A; display:block; line-height:1.3;">
              {{ stats.unread_contacts }} message{{ stats.unread_contacts > 1 ? 's' : '' }} non lu{{ stats.unread_contacts > 1 ? 's' : '' }}
            </span>
            <span style="font-size:12px; color:rgba(13,27,42,0.45);">Cliquer pour consulter →</span>
          </div>
        </Link>

        <Link
          v-if="stats.pending_reviews > 0"
          :href="route('admin.reviews.index')"
          style="display:flex; align-items:center; gap:10px; padding:14px 18px; background:linear-gradient(135deg,rgba(201,168,76,0.08),rgba(201,168,76,0.03)); border:1px solid rgba(201,168,76,0.2); border-radius:14px; text-decoration:none; transition:all 0.2s; flex:1; min-width:240px;"
          class="alert-card"
        >
          <div style="width:36px; height:36px; border-radius:10px; background:rgba(201,168,76,0.12); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
            <PhStar style="width:17px; height:17px; color:#C9A84C;" weight="fill" />
          </div>
          <div>
            <span style="font-size:13px; font-weight:700; color:#0D1B2A; display:block; line-height:1.3;">
              {{ stats.pending_reviews }} avis en attente de modération
            </span>
            <span style="font-size:12px; color:rgba(13,27,42,0.45);">Modérer maintenant →</span>
          </div>
        </Link>
      </div>

      <!-- KPI Cards -->
      <div style="display:grid; grid-template-columns:repeat(4,1fr); gap:16px;">
        <div
          v-for="kpi in kpiCards"
          :key="kpi.label"
          style="background:white; border-radius:18px; padding:22px; border:1.5px solid rgba(13,27,42,0.07); position:relative; overflow:hidden; transition:all 0.25s;"
          class="kpi-card"
        >
          <!-- Fond décoratif -->
          <div style="position:absolute; top:-20px; right:-20px; width:90px; height:90px; border-radius:50%; opacity:0.08;" :style="{ background: kpi.accent }" />

          <div style="display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:18px;">
            <div
              style="width:40px; height:40px; border-radius:12px; display:flex; align-items:center; justify-content:center; flex-shrink:0;"
              :style="{ background: kpi.bg }"
            >
              <component :is="kpi.icon" style="width:19px; height:19px;" :style="{ color: kpi.accent }" weight="fill" />
            </div>
            <span
              v-if="kpi.trend !== undefined"
              style="font-size:11px; font-weight:700; padding:3px 8px; border-radius:100px;"
              :style="kpi.trend >= 0
                ? 'background:rgba(34,197,94,0.1); color:#16A34A;'
                : 'background:rgba(239,68,68,0.1); color:#DC2626;'"
            >
              {{ kpi.trend >= 0 ? '↑' : '↓' }} {{ Math.abs(kpi.trend) }}%
            </span>
          </div>

          <div style="font-size:32px; font-weight:900; color:#0D1B2A; line-height:1; letter-spacing:-0.03em; margin-bottom:5px;">
            {{ kpi.value?.toLocaleString() ?? 0 }}
          </div>
          <div style="font-size:12px; font-weight:700; color:rgba(13,27,42,0.5);">{{ kpi.label }}</div>
          <div v-if="kpi.sub" style="font-size:11px; color:rgba(13,27,42,0.3); margin-top:3px;">{{ kpi.sub }}</div>

          <!-- Barre déco bas -->
          <div style="position:absolute; bottom:0; left:0; right:0; height:2px;" :style="{ background: `linear-gradient(90deg, ${kpi.accent}40, ${kpi.accent}15)` }" />
        </div>
      </div>

      <!-- Graphique + Pages populaires -->
      <div style="display:grid; grid-template-columns:1fr 320px; gap:16px;">

        <!-- Graphique -->
        <div style="background:white; border-radius:18px; padding:28px; border:1.5px solid rgba(13,27,42,0.07);">
          <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:8px;">
            <div>
              <h2 style="font-size:15px; font-weight:800; color:#0D1B2A; margin:0 0 3px; letter-spacing:-0.01em;">
                Trafic — 30 derniers jours
              </h2>
              <p style="font-size:12px; color:rgba(13,27,42,0.4); margin:0;">Nombre de pages vues par jour</p>
            </div>
            <div style="display:flex; align-items:center; gap:6px; padding:6px 12px; background:#FAF7F2; border-radius:8px; border:1px solid rgba(201,168,76,0.15);">
              <div style="width:8px; height:8px; border-radius:50%; background:#C9A84C;" />
              <span style="font-size:11px; font-weight:600; color:rgba(13,27,42,0.5);">Pages vues</span>
            </div>
          </div>

          <!-- Total rapide -->
          <div style="display:flex; align-items:baseline; gap:8px; margin-bottom:22px; padding-bottom:18px; border-bottom:1px solid rgba(13,27,42,0.06);">
            <span style="font-size:30px; font-weight:900; color:#0D1B2A; letter-spacing:-0.03em; background:linear-gradient(135deg,#A07828,#C9A84C); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text;">
              {{ totalViewsMonth?.toLocaleString() ?? 0 }}
            </span>
            <span style="font-size:12px; color:rgba(13,27,42,0.4); font-weight:600;">vues ce mois</span>
          </div>

          <ViewsChart :data="viewsChart" />
        </div>

        <!-- Pages populaires -->
        <div style="background:white; border-radius:18px; padding:24px; border:1.5px solid rgba(13,27,42,0.07); display:flex; flex-direction:column;">
          <div style="display:flex; align-items:center; gap:8px; margin-bottom:16px; padding-bottom:16px; border-bottom:1px solid rgba(13,27,42,0.06);">
            <div style="width:28px; height:28px; border-radius:8px; background:rgba(245,158,11,0.1); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
              <PhFire style="width:14px; height:14px; color:#F59E0B;" weight="fill" />
            </div>
            <h2 style="font-size:14px; font-weight:800; color:#0D1B2A; margin:0; letter-spacing:-0.01em;">Pages populaires</h2>
          </div>

          <div style="flex:1; display:flex; flex-direction:column; gap:4px; overflow:hidden;">
            <div
              v-for="(p, i) in (topPages ?? []).slice(0, 8)"
              :key="i"
              style="display:flex; align-items:center; gap:10px; padding:8px 10px; border-radius:9px; transition:background 0.15s; cursor:default;"
              class="page-row"
            >
              <span style="width:20px; height:20px; border-radius:6px; background:#FAF7F2; border:1px solid rgba(201,168,76,0.15); display:flex; align-items:center; justify-content:center; font-size:10px; font-weight:800; color:rgba(13,27,42,0.4); flex-shrink:0;">
                {{ i + 1 }}
              </span>
              <span style="flex:1; font-size:12px; font-weight:600; color:#0D1B2A; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                {{ p.route_name ?? p.url }}
              </span>
              <span style="font-size:11px; font-weight:800; color:#A07828; flex-shrink:0; background:rgba(201,168,76,0.1); padding:2px 8px; border-radius:6px; border:1px solid rgba(201,168,76,0.15);">
                {{ p.views?.toLocaleString() }}
              </span>
            </div>

            <div v-if="!topPages?.length" style="flex:1; display:flex; align-items:center; justify-content:center;">
              <p style="font-size:12px; color:rgba(13,27,42,0.3); text-align:center; margin:0;">Aucune donnée<br>disponible</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Ligne formations + activité rapide -->
      <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">

        <!-- Formations populaires -->
        <div style="background:white; border-radius:18px; padding:24px; border:1.5px solid rgba(13,27,42,0.07);">
          <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:16px; padding-bottom:16px; border-bottom:1px solid rgba(13,27,42,0.06);">
            <div style="display:flex; align-items:center; gap:8px;">
              <div style="width:28px; height:28px; border-radius:8px; background:rgba(201,168,76,0.12); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <PhGraduationCap style="width:14px; height:14px; color:#C9A84C;" weight="fill" />
              </div>
              <h2 style="font-size:14px; font-weight:800; color:#0D1B2A; margin:0; letter-spacing:-0.01em;">Formations populaires</h2>
            </div>
            <Link
              :href="route('admin.formations.index')"
              style="font-size:12px; font-weight:700; color:#A07828; text-decoration:none; padding:5px 12px; border-radius:8px; border:1px solid rgba(201,168,76,0.25); background:rgba(201,168,76,0.06); transition:all 0.2s;"
              class="see-all"
            >
              Voir tout
            </Link>
          </div>

          <div v-if="topFormations?.length" style="display:flex; flex-direction:column; gap:8px;">
            <div
              v-for="(f, i) in topFormations"
              :key="f.id"
              style="display:flex; align-items:center; gap:12px; padding:10px 12px; border-radius:12px; background:#FAF7F2; transition:all 0.2s; cursor:default; border:1px solid transparent;"
              class="formation-row"
            >
              <span style="width:20px; text-align:center; font-size:12px; font-weight:800; color:rgba(13,27,42,0.25); flex-shrink:0;">
                {{ i + 1 }}
              </span>
              <img
                :src="f.thumbnail_url"
                :alt="f.title"
                style="width:46px; height:36px; object-fit:cover; border-radius:8px; flex-shrink:0; border:1px solid rgba(13,27,42,0.06);"
              />
              <div style="flex:1; min-width:0;">
                <div style="font-size:13px; font-weight:700; color:#0D1B2A; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; line-height:1.3;">
                  {{ f.title }}
                </div>
                <div style="font-size:11px; color:rgba(13,27,42,0.4); display:flex; align-items:center; gap:4px; margin-top:2px;">
                  <PhEye style="width:11px; height:11px;" />
                  {{ f.view_count?.toLocaleString() }} vues
                </div>
              </div>
            </div>
          </div>
          <div v-else style="padding:32px 0; text-align:center; font-size:13px; color:rgba(13,27,42,0.3);">
            Aucune formation disponible
          </div>
        </div>

        <!-- Résumé rapide -->
        <div style="display:flex; flex-direction:column; gap:16px;">

          <!-- Contacts récents -->
          <div style="background:white; border-radius:18px; padding:24px; border:1.5px solid rgba(13,27,42,0.07); flex:1;">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:16px; padding-bottom:16px; border-bottom:1px solid rgba(13,27,42,0.06);">
              <div style="display:flex; align-items:center; gap:8px;">
                <div style="width:28px; height:28px; border-radius:8px; background:rgba(245,158,11,0.1); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                  <PhEnvelopeSimple style="width:14px; height:14px; color:#F59E0B;" weight="fill" />
                </div>
                <h2 style="font-size:14px; font-weight:800; color:#0D1B2A; margin:0;">Messages</h2>
              </div>
              <Link
                :href="route('admin.contacts.index')"
                style="font-size:12px; font-weight:700; color:#A07828; text-decoration:none; padding:5px 12px; border-radius:8px; border:1px solid rgba(201,168,76,0.25); background:rgba(201,168,76,0.06); transition:all 0.2s;"
                class="see-all"
              >
                Voir tout
              </Link>
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
              <div style="padding:16px; background:#FAF7F2; border-radius:12px; text-align:center; border:1px solid rgba(13,27,42,0.06);">
                <div style="font-size:28px; font-weight:900; color:#0D1B2A; letter-spacing:-0.02em; line-height:1;">
                  {{ stats.total_contacts?.toLocaleString() ?? 0 }}
                </div>
                <div style="font-size:11px; font-weight:600; color:rgba(13,27,42,0.4); margin-top:5px;">Total reçus</div>
              </div>
              <div
                style="padding:16px; border-radius:12px; text-align:center;"
                :style="stats.unread_contacts > 0
                  ? 'background:rgba(245,158,11,0.07); border:1px solid rgba(245,158,11,0.2);'
                  : 'background:#FAF7F2; border:1px solid rgba(13,27,42,0.06);'"
              >
                <div
                  style="font-size:28px; font-weight:900; letter-spacing:-0.02em; line-height:1;"
                  :style="stats.unread_contacts > 0 ? 'color:#D97706;' : 'color:#0D1B2A;'"
                >
                  {{ stats.unread_contacts?.toLocaleString() ?? 0 }}
                </div>
                <div
                  style="font-size:11px; font-weight:600; margin-top:5px;"
                  :style="stats.unread_contacts > 0 ? 'color:#D97706;' : 'color:rgba(13,27,42,0.4);'"
                >
                  Non lus
                </div>
              </div>
            </div>
          </div>

          <!-- Avis -->
          <div style="background:white; border-radius:18px; padding:24px; border:1.5px solid rgba(13,27,42,0.07); flex:1;">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:16px; padding-bottom:16px; border-bottom:1px solid rgba(13,27,42,0.06);">
              <div style="display:flex; align-items:center; gap:8px;">
                <div style="width:28px; height:28px; border-radius:8px; background:rgba(201,168,76,0.12); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                  <PhStar style="width:14px; height:14px; color:#C9A84C;" weight="fill" />
                </div>
                <h2 style="font-size:14px; font-weight:800; color:#0D1B2A; margin:0;">Avis clients</h2>
              </div>
              <Link
                :href="route('admin.reviews.index')"
                style="font-size:12px; font-weight:700; color:#A07828; text-decoration:none; padding:5px 12px; border-radius:8px; border:1px solid rgba(201,168,76,0.25); background:rgba(201,168,76,0.06); transition:all 0.2s;"
                class="see-all"
              >
                Modérer
              </Link>
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
              <div style="padding:16px; background:#FAF7F2; border-radius:12px; text-align:center; border:1px solid rgba(13,27,42,0.06);">
                <div style="font-size:28px; font-weight:900; color:#0D1B2A; letter-spacing:-0.02em; line-height:1;">
                  {{ stats.approved_reviews?.toLocaleString() ?? 0 }}
                </div>
                <div style="font-size:11px; font-weight:600; color:rgba(13,27,42,0.4); margin-top:5px;">Approuvés</div>
              </div>
              <div
                style="padding:16px; border-radius:12px; text-align:center;"
                :style="stats.pending_reviews > 0
                  ? 'background:rgba(201,168,76,0.07); border:1px solid rgba(201,168,76,0.2);'
                  : 'background:#FAF7F2; border:1px solid rgba(13,27,42,0.06);'"
              >
                <div
                  style="font-size:28px; font-weight:900; letter-spacing:-0.02em; line-height:1;"
                  :style="stats.pending_reviews > 0 ? 'color:#A07828;' : 'color:#0D1B2A;'"
                >
                  {{ stats.pending_reviews?.toLocaleString() ?? 0 }}
                </div>
                <div
                  style="font-size:11px; font-weight:600; margin-top:5px;"
                  :style="stats.pending_reviews > 0 ? 'color:#A07828;' : 'color:rgba(13,27,42,0.4);'"
                >
                  En attente
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { computed } from 'vue'
import { Head, Link, usePage } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import ViewsChart from '@/Components/Admin/ViewsChart.vue'
import {
  PhUsers, PhGraduationCap, PhNewspaper, PhEye,
  PhEnvelopeSimple, PhStar, PhFire, PhPlus, PhPencil,
} from '@phosphor-icons/vue'

const props = defineProps({
  stats:         Object,
  topPages:      Array,
  viewsChart:    Object,
  topFormations: Array,
})

const page = usePage()

const todayLabel = computed(() => {
  return new Date().toLocaleDateString('fr-FR', {
    weekday: 'long', day: 'numeric', month: 'long', year: 'numeric',
  })
})

const totalViewsMonth = computed(() => {
  if (!props.viewsChart) return 0
  return Object.values(props.viewsChart).reduce((a, b) => a + b, 0)
})

const kpiCards = computed(() => [
  {
    label:  'Utilisateurs',
    value:  props.stats?.total_users,
    sub:    `${props.stats?.active_users ?? 0} actifs`,
    icon:   PhUsers,
    accent: '#C9A84C',
    bg:     'rgba(201,168,76,0.1)',
    trend:  undefined,
  },
  {
    label:  'Formations publiées',
    value:  props.stats?.published_formations,
    sub:    `${props.stats?.total_formations ?? 0} au total`,
    icon:   PhGraduationCap,
    accent: '#0D1B2A',
    bg:     'rgba(13,27,42,0.07)',
    trend:  undefined,
  },
  {
    label:  'Articles publiés',
    value:  props.stats?.published_articles,
    sub:    `${props.stats?.total_articles ?? 0} au total`,
    icon:   PhNewspaper,
    accent: '#A07828',
    bg:     'rgba(201,168,76,0.08)',
    trend:  undefined,
  },
  {
    label:  "Vues aujourd'hui",
    value:  props.stats?.views_today,
    sub:    `${props.stats?.views_this_month?.toLocaleString() ?? 0} ce mois`,
    icon:   PhEye,
    accent: '#22C55E',
    bg:     'rgba(34,197,94,0.1)',
    trend:  undefined,
  },
])
</script>

<style scoped>
.kpi-card:hover        { box-shadow:0 6px 24px rgba(13,27,42,0.08); transform:translateY(-2px); border-color:rgba(201,168,76,0.2) !important; }
.page-row:hover        { background:#FAF7F2 !important; }
.formation-row:hover   { background:white !important; border-color:rgba(201,168,76,0.2) !important; box-shadow:0 2px 12px rgba(13,27,42,0.06); }
.alert-card:hover      { transform:translateY(-1px); box-shadow:0 4px 16px rgba(13,27,42,0.08); }
.action-btn-gold:hover { background:#E2C97E !important; transform:translateY(-1px); box-shadow:0 6px 20px rgba(201,168,76,0.35); }
.action-btn-light:hover{ background:#FAF7F2 !important; border-color:rgba(201,168,76,0.2) !important; color:#0D1B2A !important; }
.see-all:hover         { background:rgba(201,168,76,0.12) !important; border-color:rgba(201,168,76,0.35) !important; }
</style>