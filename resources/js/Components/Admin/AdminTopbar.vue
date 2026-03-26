<template>
  <header style="
    position:sticky; top:0; z-index:30;
    background:white;
    border-bottom:1.5px solid rgba(13,27,42,0.07);
    padding:0 28px;
    height:60px;
    display:flex; align-items:center; justify-content:space-between; gap:16px;
    box-shadow:0 1px 12px rgba(13,27,42,0.06);
    flex-shrink:0;
  ">
    <!-- Gauche : burger mobile + breadcrumb -->
    <div style="display:flex; align-items:center; gap:14px; min-width:0;">

      <!-- Burger mobile -->
      <button
        class="lg:hidden burger-btn"
        @click="$emit('open-mobile')"
        style="width:36px; height:36px; border-radius:10px; border:1.5px solid rgba(13,27,42,0.1); background:#FAF7F2; display:flex; align-items:center; justify-content:center; cursor:pointer; transition:all 0.2s; flex-shrink:0;"
        
      >
        <PhList style="width:18px; height:18px; color:rgba(13,27,42,0.6);" />
      </button>

      <!-- Breadcrumb dynamique -->
      <div style="display:flex; align-items:center; gap:6px; min-width:0; overflow:hidden;">
        <div style="display:flex; align-items:center; gap:6px; font-size:13px; color:rgba(13,27,42,0.4); flex-wrap:nowrap; white-space:nowrap; overflow:hidden;">
          <span style="font-weight:600;">Administration</span>
          <PhCaretRight style="width:12px; height:12px; flex-shrink:0;" />
          <span style="font-weight:800; color:#0D1B2A; overflow:hidden; text-overflow:ellipsis; max-width:200px;">{{ currentPageLabel }}</span>
        </div>
      </div>
    </div>

    <!-- Droite : actions -->
    <div style="display:flex; align-items:center; gap:10px; flex-shrink:0;">

      <!-- Voir le site -->
      <Link
        :href="route('home')"
        target="_blank"
        style="display:inline-flex; align-items:center; gap:7px; height:34px; padding:0 14px; border-radius:9px; border:1.5px solid rgba(13,27,42,0.1); background:#FAF7F2; color:rgba(13,27,42,0.6); font-size:12px; font-weight:700; text-decoration:none; transition:all 0.2s; white-space:nowrap;"
        class="site-btn"
      >
        <PhArrowSquareOut style="width:14px; height:14px;" />
        Voir le site
      </Link>

      <!-- Notifications -->
      <div style="position:relative;">
        <button
          @click="notifOpen = !notifOpen"
          style="width:36px; height:36px; border-radius:10px; border:1.5px solid rgba(13,27,42,0.1); background:white; display:flex; align-items:center; justify-content:center; cursor:pointer; transition:all 0.2s; position:relative;"
          class="notif-btn"
        >
          <PhBell style="width:17px; height:17px; color:rgba(13,27,42,0.5);" weight="fill" />
          <!-- Badge -->
          <span
            v-if="totalBadge > 0"
            style="position:absolute; top:5px; right:5px; width:8px; height:8px; border-radius:50%; background:#C9A84C; border:1.5px solid white;"
          />
        </button>

        <!-- Dropdown notifications -->
        <Transition name="dropdown">
          <div
            v-if="notifOpen"
            style="position:absolute; top:calc(100% + 8px); right:0; width:300px; background:white; border:1.5px solid rgba(13,27,42,0.08); border-radius:16px; box-shadow:0 8px 40px rgba(13,27,42,0.12); overflow:hidden; z-index:100;"
          >
            <div style="padding:14px 18px; border-bottom:1px solid rgba(13,27,42,0.06); display:flex; align-items:center; justify-content:space-between;">
              <span style="font-size:13px; font-weight:800; color:#0D1B2A;">Notifications</span>
              <span v-if="totalBadge > 0" style="font-size:11px; font-weight:700; background:rgba(201,168,76,0.12); border:1px solid rgba(201,168,76,0.2); color:#A07828; padding:2px 8px; border-radius:100px;">{{ totalBadge }}</span>
            </div>
            <div style="padding:8px;">
              <!-- Contacts non lus -->
              <Link
                v-if="unreadContacts > 0"
                :href="route('admin.contacts.index')"
                @click="notifOpen = false"
                style="display:flex; align-items:center; gap:12px; padding:12px; border-radius:10px; text-decoration:none; transition:background 0.15s; margin-bottom:4px;"
                class="notif-item"
              >
                <div style="width:34px; height:34px; border-radius:9px; background:rgba(245,158,11,0.1); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                  <PhEnvelopeSimple style="width:16px; height:16px; color:#F59E0B;" weight="fill" />
                </div>
                <div style="flex:1; min-width:0;">
                  <div style="font-size:13px; font-weight:700; color:#0D1B2A; line-height:1.3;">{{ unreadContacts }} message{{ unreadContacts > 1 ? 's' : '' }} non lu{{ unreadContacts > 1 ? 's' : '' }}</div>
                  <div style="font-size:11px; color:rgba(13,27,42,0.45); margin-top:2px;">Cliquer pour consulter</div>
                </div>
                <PhCaretRight style="width:13px; height:13px; color:rgba(13,27,42,0.3); flex-shrink:0;" />
              </Link>

              <!-- Avis en attente -->
              <Link
                v-if="pendingReviews > 0"
                :href="route('admin.reviews.index')"
                @click="notifOpen = false"
                style="display:flex; align-items:center; gap:12px; padding:12px; border-radius:10px; text-decoration:none; transition:background 0.15s;"
                class="notif-item"
              >
                <div style="width:34px; height:34px; border-radius:9px; background:rgba(201,168,76,0.1); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                  <PhStar style="width:16px; height:16px; color:#C9A84C;" weight="fill" />
                </div>
                <div style="flex:1; min-width:0;">
                  <div style="font-size:13px; font-weight:700; color:#0D1B2A; line-height:1.3;">{{ pendingReviews }} avis à modérer</div>
                  <div style="font-size:11px; color:rgba(13,27,42,0.45); margin-top:2px;">Cliquer pour modérer</div>
                </div>
                <PhCaretRight style="width:13px; height:13px; color:rgba(13,27,42,0.3); flex-shrink:0;" />
              </Link>

              <!-- Aucune notif -->
              <div v-if="totalBadge === 0" style="padding:20px; text-align:center;">
                <PhBellSlash style="width:28px; height:28px; color:rgba(13,27,42,0.2); margin:0 auto 8px; display:block;" />
                <p style="font-size:13px; color:rgba(13,27,42,0.35); margin:0;">Aucune notification</p>
              </div>
            </div>
          </div>
        </Transition>
      </div>

      <!-- Avatar + menu user -->
      <div style="position:relative;">
        <button
          @click="userOpen = !userOpen"
          style="display:flex; align-items:center; gap:8px; height:36px; padding:0 12px; border-radius:10px; border:1.5px solid rgba(13,27,42,0.1); background:white; cursor:pointer; transition:all 0.2s;"
          class="user-btn"
        >
          <img
            v-if="user?.avatar_url"
            :src="user.avatar_url"
            :alt="user?.full_name"
            style="width:24px; height:24px; border-radius:7px; object-fit:cover; border:1.5px solid rgba(201,168,76,0.25);"
          />
          <div v-else style="width:24px; height:24px; border-radius:7px; background:rgba(201,168,76,0.12); border:1.5px solid rgba(201,168,76,0.25); display:flex; align-items:center; justify-content:center;">
            <PhUser style="width:12px; height:12px; color:#C9A84C;" weight="fill" />
          </div>
          <span style="font-size:13px; font-weight:700; color:#0D1B2A; white-space:nowrap; max-width:120px; overflow:hidden; text-overflow:ellipsis;">{{ user?.first_name }}</span>
          <PhCaretDown style="width:13px; height:13px; color:rgba(13,27,42,0.4); transition:transform 0.2s;" :style="userOpen ? 'transform:rotate(180deg);' : ''" />
        </button>

        <!-- Dropdown user -->
        <Transition name="dropdown">
          <div
            v-if="userOpen"
            style="position:absolute; top:calc(100% + 8px); right:0; width:220px; background:white; border:1.5px solid rgba(13,27,42,0.08); border-radius:16px; box-shadow:0 8px 40px rgba(13,27,42,0.12); overflow:hidden; z-index:100;"
          >
            <!-- Info user -->
            <div style="padding:14px 16px; border-bottom:1px solid rgba(13,27,42,0.06); background:#FAF7F2;">
              <div style="font-size:13px; font-weight:800; color:#0D1B2A; line-height:1.3; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ user?.full_name }}</div>
              <div style="font-size:11px; color:rgba(13,27,42,0.45); margin-top:2px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ user?.email }}</div>
              <span style="display:inline-block; margin-top:6px; font-size:10px; font-weight:700; background:rgba(201,168,76,0.15); border:1px solid rgba(201,168,76,0.25); color:#A07828; padding:2px 8px; border-radius:100px; letter-spacing:0.04em;">
                {{ user?.role?.name ?? 'Admin' }}
              </span>
            </div>

            <div style="padding:8px;">
              <Link
                :href="route('admin.profile.edit')"
                @click="userOpen = false"
                style="display:flex; align-items:center; gap:10px; padding:10px 12px; border-radius:9px; text-decoration:none; transition:background 0.15s; font-size:13px; font-weight:600; color:rgba(13,27,42,0.7);"
                class="user-menu-item"
              >
                <PhUser style="width:15px; height:15px; color:rgba(13,27,42,0.4);" weight="fill" />
                Mon profil
              </Link>

              <Link
                :href="route('admin.dashboard')"
                @click="userOpen = false"
                style="display:flex; align-items:center; gap:10px; padding:10px 12px; border-radius:9px; text-decoration:none; transition:background 0.15s; font-size:13px; font-weight:600; color:rgba(13,27,42,0.7);"
                class="user-menu-item"
              >
                <PhGauge style="width:15px; height:15px; color:rgba(13,27,42,0.4);" weight="fill" />
                Dashboard
              </Link>

              <div style="height:1px; background:rgba(13,27,42,0.06); margin:6px 0;" />

              <Link
                :href="route('auth.logout')"
                method="post"
                as="button"
                style="display:flex; align-items:center; gap:10px; padding:10px 12px; border-radius:9px; text-decoration:none; transition:background 0.15s; font-size:13px; font-weight:600; color:#EF4444; width:100%; border:none; background:transparent; cursor:pointer; font-family:inherit; text-align:left;"
                class="logout-item"
              >
                <PhSignOut style="width:15px; height:15px;" />
                Se déconnecter
              </Link>
            </div>
          </div>
        </Transition>
      </div>
    </div>

    <!-- Clic extérieur pour fermer dropdowns -->
    <div v-if="notifOpen || userOpen" style="position:fixed; inset:0; z-index:99;" @click="notifOpen = false; userOpen = false;" />
  </header>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import {
  PhList, PhCaretRight, PhCaretDown, PhBell, PhBellSlash,
  PhEnvelopeSimple, PhStar, PhArrowSquareOut,
  PhUser, PhGauge, PhSignOut,
} from '@phosphor-icons/vue'

defineProps({ collapsed: Boolean })
defineEmits(['open-mobile'])

const page    = usePage()
const user    = computed(() => page.props.auth?.user)
const notifOpen = ref(false)
const userOpen  = ref(false)

const pendingReviews = computed(() => page.props.stats?.pending_reviews ?? 0)
const unreadContacts = computed(() => page.props.stats?.unread_contacts ?? 0)
const totalBadge     = computed(() => pendingReviews.value + unreadContacts.value)

// Labels de page pour le breadcrumb
const routeLabels = {
  'admin.dashboard':                  'Tableau de bord',
  'admin.formations.index':           'Formations',
  'admin.formations.create':          'Nouvelle formation',
  'admin.formations.edit':            'Modifier la formation',
  'admin.formations.categories.index': 'Catégories',
  'admin.articles.index':             'Articles',
  'admin.articles.create':            'Nouvel article',
  'admin.articles.edit':              'Modifier l\'article',
  'admin.reviews.index':              'Avis',
  'admin.contacts.index':             'Messages',
  'admin.users.index':                'Utilisateurs',
  'admin.roles.index':                'Rôles',
  'admin.logs.index':                 'Logs',
  'admin.profile.edit':               'Mon profil',
}

const currentPageLabel = computed(() => {
  const name = page.component?.replace('Admin/', 'admin.').toLowerCase()
  // Cherche par URL
  const url = page.url
  for (const [key, label] of Object.entries(routeLabels)) {
    try {
      const r = route(key)
      if (url.startsWith(new URL(r, window.location.origin).pathname)) return label
    } catch {}
  }
  return 'Administration'
})
</script>

<style scoped>
.burger-btn:hover  { background:white !important; border-color:rgba(201,168,76,0.2) !important; }
.site-btn:hover    { background:white !important; border-color:rgba(201,168,76,0.2) !important; color:#0D1B2A !important; }
.notif-btn:hover   { background:#FAF7F2 !important; border-color:rgba(201,168,76,0.2) !important; }
.user-btn:hover    { background:#FAF7F2 !important; border-color:rgba(201,168,76,0.2) !important; }
.notif-item:hover  { background:#FAF7F2 !important; }
.user-menu-item:hover { background:#FAF7F2 !important; color:#0D1B2A !important; }
.logout-item:hover { background:rgba(239,68,68,0.05) !important; }

.dropdown-enter-active { transition:all 0.2s cubic-bezier(0.16,1,0.3,1); }
.dropdown-leave-active { transition:all 0.15s ease; }
.dropdown-enter-from, .dropdown-leave-to { opacity:0; transform:translateY(-6px) scale(0.98); }
</style>