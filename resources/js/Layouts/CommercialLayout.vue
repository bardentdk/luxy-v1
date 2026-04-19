<template>
  <div class="crm-root">

    <!-- ── OVERLAY mobile (ferme la sidebar) ── -->
    <div
      v-if="mobileOpen"
      class="mobile-overlay"
      @click="mobileOpen = false"
    ></div>

    <!-- ── SIDEBAR ── -->
    <aside
      class="sidebar"
      :class="{
        'sidebar-collapsed': !sidebarOpen,
        'sidebar-mobile-open': mobileOpen,
      }"
    >
      <!-- Logo -->
      <div class="sidebar-logo">
        <div class="logo-badge">L</div>
        <div v-if="sidebarOpen || mobileOpen" class="logo-texts">
          <div class="logo-name">Luxy CRM</div>
          <div class="logo-sub">Espace commercial</div>
        </div>
      </div>

      <!-- Nav -->
      <nav class="sidebar-nav">
        <div
          v-for="section in menuSections"
          :key="section.title"
          class="nav-section"
        >
          <div
            v-if="(sidebarOpen || mobileOpen) && section.title"
            class="nav-section-label"
          >
            {{ section.title }}
          </div>

          <NavItem
            v-for="item in section.items"
            :key="item.route"
            :item="item"
            :collapsed="!sidebarOpen && !mobileOpen"
            @click="mobileOpen = false"
          />
        </div>
      </nav>

      <!-- Footer sidebar -->
      <div class="sidebar-footer">
        <Link
          v-if="isAdmin"
          :href="route('admin.dashboard')"
          class="sidebar-footer-link"
          @click="mobileOpen = false"
        >
          <div class="sidebar-icon-wrap">
            <PhShieldCheck style="width:16px; height:16px;" />
          </div>
          <span v-if="sidebarOpen || mobileOpen" class="sidebar-footer-label">Admin</span>
        </Link>

        <!-- Toggle — desktop uniquement -->
        <button
          class="sidebar-toggle-btn desktop-only"
          @click="sidebarOpen = !sidebarOpen"
        >
          <div class="sidebar-icon-wrap">
            <PhSidebarSimple style="width:16px; height:16px;" />
          </div>
          <span v-if="sidebarOpen" class="sidebar-footer-label">Réduire</span>
        </button>
      </div>
    </aside>

    <!-- ── CONTENU PRINCIPAL ── -->
    <div
      class="main-content"
      :class="{ 'main-collapsed': !sidebarOpen }"
    >
      <!-- Topbar -->
      <header class="topbar">
        <!-- Burger mobile -->
        <button
          class="burger mobile-only"
          @click="mobileOpen = !mobileOpen"
        >
          <PhList style="width:20px; height:20px; color:#0D1B2A;" />
        </button>

        <!-- Titre page -->
        <div class="topbar-title-block">
          <div class="topbar-title">{{ currentPageTitle }}</div>
          <div class="topbar-date">{{ now }}</div>
        </div>

        <!-- Actions topbar -->
        <div class="topbar-actions">

          <!-- Leads en attente -->
          <Link
            v-if="$page.props.pendingLeads > 0"
            :href="route('commercial.contacts.index')"
            class="leads-pill"
          >
            <div class="leads-dot"></div>
            <span class="leads-label">
              {{ $page.props.pendingLeads }} lead{{ $page.props.pendingLeads > 1 ? 's' : '' }}
            </span>
          </Link>

          <!-- Flash -->
          <Transition name="flash">
            <div
              v-if="flashMessage"
              class="flash-msg"
              :class="flashSuccess ? 'flash-success' : 'flash-error'"
            >
              <PhCheckCircle v-if="flashSuccess" style="width:13px; height:13px;" weight="fill" />
              <PhWarning v-else style="width:13px; height:13px;" weight="fill" />
              <span class="flash-text">{{ flashMessage }}</span>
            </div>
          </Transition>

          <!-- User dropdown -->
          <div class="user-menu-wrap" v-click-outside="() => userMenuOpen = false">
            <button class="user-btn" @click="userMenuOpen = !userMenuOpen">
              <img
                v-if="$page.props.auth?.user?.avatar_url"
                :src="$page.props.auth.user.avatar_url"
                class="user-avatar"
              />
              <div v-else class="user-avatar-fallback">
                {{ $page.props.auth?.user?.initials }}
              </div>
              <span class="user-name desktop-only">{{ $page.props.auth?.user?.first_name }}</span>
              <PhCaretDown style="width:12px; height:12px; color:rgba(13,27,42,0.4);" class="desktop-only" />
            </button>

            <Transition name="dropdown">
              <div v-if="userMenuOpen" class="user-dropdown">
                <Link :href="route('admin.profile.edit')" class="dropdown-item" @click="userMenuOpen = false">
                  <PhUser style="width:14px; height:14px;" />
                  Mon profil
                </Link>
                <div class="dropdown-sep"></div>
                <form @submit.prevent="logout">
                  <button type="submit" class="dropdown-item dropdown-item-danger">
                    <PhSignOut style="width:14px; height:14px;" />
                    Déconnexion
                  </button>
                </form>
              </div>
            </Transition>
          </div>
        </div>
      </header>

      <!-- Page -->
      <main class="page-main">
        <slot />
      </main>
    </div>
  </div>
</template>

<script setup>
// ── Même script que la version précédente, on ajoute juste mobileOpen ──
import { ref, computed, onMounted, onBeforeUnmount, watch, h, defineComponent } from 'vue'
import { Link, usePage, router } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import {
  PhGauge, PhFunnel, PhUsers, PhFileText, PhPackage,
  PhEnvelopeSimple, PhChartBar, PhShieldCheck,
  PhSidebarSimple, PhCaretDown, PhCheckCircle,
  PhWarning, PhUser, PhSignOut, PhList,
} from '@phosphor-icons/vue'

const NavItem = defineComponent({
  name: 'NavItem',
  props: {
    item:      { type: Object,  required: true },
    collapsed: { type: Boolean, default: false },
  },
  emits: ['click'],
  setup(props, { emit }) {
    const page     = usePage()
    const isActive = computed(() => {
      try {
        const url = new URL(route(props.item.route))
        return window.location.pathname.startsWith(url.pathname)
      } catch { return false }
    })

    return () => h(Link, {
      href: route(props.item.route),
      onClick: () => emit('click'),
      style: [
        'display:flex; align-items:center; gap:10px; padding:10px 8px; border-radius:10px;',
        'text-decoration:none; transition:all 0.2s; overflow:hidden; white-space:nowrap; margin-bottom:2px;',
        isActive.value
          ? 'background:rgba(201,168,76,0.15); color:#C9A84C; border-left:3px solid #C9A84C; padding-left:5px;'
          : 'color:rgba(255,255,255,0.5); border-left:3px solid transparent;',
      ].join(''),
    }, () => [
      h('div', {
        style: [
          'width:32px; height:32px; border-radius:9px; display:flex; align-items:center; justify-content:center; flex-shrink:0;',
          isActive.value ? 'background:rgba(201,168,76,0.2);' : 'background:rgba(255,255,255,0.06);',
        ].join(''),
      }, [h(props.item.icon, { style: 'width:16px; height:16px;' })]),
      !props.collapsed
        ? h('div', { style: 'flex:1; min-width:0;' }, [
            h('div', { style: 'font-size:13px; font-weight:700; overflow:hidden; text-overflow:ellipsis;' }, props.item.name),
          ])
        : null,
    ])
  },
})

const page         = usePage()
const sidebarOpen  = ref(true)
const mobileOpen   = ref(false)
const userMenuOpen = ref(false)

const isAdmin = computed(() => {
  const role = page.props.auth?.user?.role_slug
  return role === 'admin' || role === 'super-admin'
})

const now = ref('')
let clockInterval = null

function updateClock() {
  now.value = new Date().toLocaleDateString('fr-FR', {
    weekday: 'long', day: 'numeric', month: 'long', year: 'numeric',
  })
}

onMounted(() => {
  updateClock()
  clockInterval = setInterval(updateClock, 60000)
  // Desktop : sidebar ouverte par défaut, mobile : fermée
  if (window.innerWidth < 768) sidebarOpen.value = false
})

onBeforeUnmount(() => clearInterval(clockInterval))

// Fermer la sidebar mobile au resize
onMounted(() => {
  window.addEventListener('resize', () => {
    if (window.innerWidth >= 768) mobileOpen.value = false
  })
})

const currentPageTitle = computed(() => {
  const path = window.location.pathname
  const map = {
    '/commercial':          'Tableau de bord',
    '/commercial/pipeline': 'Pipeline',
    '/commercial/contacts': 'Contacts CRM',
    '/commercial/deals':    'Opportunités',
    '/commercial/devis':    'Devis',
    '/commercial/produits': 'Produits',
    '/commercial/sequences':'Séquences email',
  }
  for (const [prefix, label] of Object.entries(map)) {
    if (path === prefix || path.startsWith(prefix + '/') || path.startsWith(prefix + '?')) {
      return label
    }
  }
  return 'Espace commercial'
})

const flashMessage = ref('')
const flashSuccess = ref(true)
let flashTimeout   = null

watch(() => page.props.flash, (flash) => {
  if (flash?.success) {
    flashMessage.value = flash.success
    flashSuccess.value = true
  } else if (flash?.error) {
    flashMessage.value = flash.error
    flashSuccess.value = false
  } else { return }
  clearTimeout(flashTimeout)
  flashTimeout = setTimeout(() => { flashMessage.value = '' }, 4000)
}, { immediate: true, deep: true })

const menuSections = [
  {
    title: '',
    items: [
      { name: 'Tableau de bord', route: 'commercial.dashboard', icon: PhGauge },
      { name: 'Pipeline',        route: 'commercial.pipeline',  icon: PhFunnel },
    ],
  },
  {
    title: 'Relations',
    items: [
      { name: 'Contacts CRM', route: 'commercial.contacts.index', icon: PhUsers },
      { name: 'Opportunités', route: 'commercial.deals.index',    icon: PhChartBar },
    ],
  },
  {
    title: 'Commercial',
    items: [
      { name: 'Devis',    route: 'commercial.quotes.index',   icon: PhFileText },
      { name: 'Produits', route: 'commercial.products.index', icon: PhPackage },
    ],
  },
  {
    title: 'Automatisation',
    items: [
      { name: 'Séquences email', route: 'commercial.sequences.index', icon: PhEnvelopeSimple },
    ],
  },
]

function logout() { router.post(route('auth.logout')) }

const vClickOutside = {
  mounted(el, binding) {
    el._clickOutside = (e) => { if (!el.contains(e.target)) binding.value(e) }
    document.addEventListener('click', el._clickOutside)
  },
  unmounted(el) { document.removeEventListener('click', el._clickOutside) },
}
</script>

<style scoped>
/* ══════════════════════════════════════════
   STRUCTURE
══════════════════════════════════════════ */
.crm-root {
  min-height: 100vh;
  background: #F4F6FA;
  display: flex;
}

/* ══════════════════════════════════════════
   SIDEBAR
══════════════════════════════════════════ */
.sidebar {
  width: 260px;
  background: #0D1B2A;
  min-height: 100vh;
  position: fixed;
  left: 0; top: 0; bottom: 0;
  z-index: 100;
  transition: width 0.3s cubic-bezier(0.16,1,0.3,1), transform 0.3s cubic-bezier(0.16,1,0.3,1);
  display: flex;
  flex-direction: column;
  overflow: hidden;
}
.sidebar-collapsed { width: 72px; }

.sidebar-logo {
  padding: 20px 16px;
  border-bottom: 1px solid rgba(255,255,255,0.07);
  flex-shrink: 0;
  display: flex;
  align-items: center;
  gap: 12px;
  overflow: hidden;
}
.logo-badge {
  width: 40px; height: 40px;
  border-radius: 11px;
  background: linear-gradient(135deg,#C9A84C,#E2C97E);
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
  font-size: 18px; font-weight: 900; color: #0D1B2A;
}
.logo-texts { overflow: hidden; white-space: nowrap; }
.logo-name  { font-size: 14px; font-weight: 800; color: white; line-height: 1.2; }
.logo-sub   { font-size: 10px; color: #C9A84C; letter-spacing: 1.5px; text-transform: uppercase; }

.sidebar-nav {
  flex: 1; overflow-y: auto; overflow-x: hidden; padding: 12px 8px;
}
.nav-section { margin-bottom: 24px; }
.nav-section-label {
  font-size: 9px; font-weight: 700; color: rgba(255,255,255,0.25);
  letter-spacing: 0.15em; text-transform: uppercase;
  padding: 0 8px; margin-bottom: 6px;
}

.sidebar-footer {
  padding: 12px 8px;
  border-top: 1px solid rgba(255,255,255,0.07);
  flex-shrink: 0;
}
.sidebar-footer-link {
  display: flex; align-items: center; gap: 10px;
  padding: 10px 8px; border-radius: 10px;
  text-decoration: none; color: rgba(255,255,255,0.4);
  transition: all 0.2s; overflow: hidden; white-space: nowrap;
  margin-bottom: 4px;
}
.sidebar-footer-link:hover { background: rgba(255,255,255,0.06); color: rgba(255,255,255,0.7); }
.sidebar-footer-label { font-size: 13px; font-weight: 600; }

.sidebar-toggle-btn {
  display: flex; align-items: center; gap: 10px;
  padding: 10px 8px; border-radius: 10px;
  background: transparent; border: none;
  color: rgba(255,255,255,0.3); cursor: pointer;
  width: 100%; transition: all 0.2s;
  overflow: hidden; white-space: nowrap; font-family: inherit;
}
.sidebar-toggle-btn:hover { background: rgba(255,255,255,0.06); color: rgba(255,255,255,0.6); }

.sidebar-icon-wrap {
  width: 32px; height: 32px; border-radius: 9px;
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0; background: rgba(255,255,255,0.06);
}

/* ══════════════════════════════════════════
   MAIN CONTENT
══════════════════════════════════════════ */
.main-content {
  flex: 1;
  margin-left: 260px;
  transition: margin-left 0.3s cubic-bezier(0.16,1,0.3,1);
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}
.main-collapsed { margin-left: 72px; }

/* ══════════════════════════════════════════
   TOPBAR
══════════════════════════════════════════ */
.topbar {
  background: white;
  border-bottom: 1px solid rgba(13,27,42,0.07);
  padding: 0 24px;
  height: 60px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 12px;
  position: sticky; top: 0; z-index: 50;
  flex-shrink: 0;
}

.burger {
  background: transparent; border: none; cursor: pointer; padding: 6px;
  border-radius: 8px; display: flex; align-items: center; justify-content: center;
  flex-shrink: 0; transition: background 0.15s;
}
.burger:hover { background: #FAF7F2; }

.topbar-title-block { flex: 1; min-width: 0; }
.topbar-title { font-size: 15px; font-weight: 800; color: #0D1B2A; letter-spacing: -0.01em; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.topbar-date  { font-size: 11px; color: rgba(13,27,42,0.4); }

.topbar-actions { display: flex; align-items: center; gap: 10px; flex-shrink: 0; }

.leads-pill {
  display: flex; align-items: center; gap: 7px;
  padding: 6px 12px; border-radius: 100px;
  background: rgba(201,168,76,0.1); border: 1px solid rgba(201,168,76,0.25);
  text-decoration: none; transition: all 0.2s;
}
.leads-pill:hover { background: rgba(201,168,76,0.18); }
.leads-dot  { width: 7px; height: 7px; border-radius: 50%; background: #C9A84C; animation: pulse 2s infinite; }
.leads-label { font-size: 12px; font-weight: 700; color: #A07828; }

.flash-msg {
  display: flex; align-items: center; gap: 7px;
  padding: 7px 14px; border-radius: 100px; font-size: 12px; font-weight: 700;
}
.flash-success { background: rgba(34,197,94,0.1); color: #16A34A; border: 1px solid rgba(34,197,94,0.2); }
.flash-error   { background: rgba(239,68,68,0.1); color: #DC2626; border: 1px solid rgba(239,68,68,0.2); }
.flash-text    { display: none; }

/* ══════════════════════════════════════════
   USER MENU
══════════════════════════════════════════ */
.user-menu-wrap { position: relative; }
.user-btn {
  display: flex; align-items: center; gap: 9px;
  padding: 6px 12px 6px 6px; border-radius: 100px;
  border: 1.5px solid rgba(13,27,42,0.1); background: white;
  cursor: pointer; transition: all 0.2s;
}
.user-btn:hover { border-color: rgba(201,168,76,0.3); background: #FAF7F2; }
.user-avatar {
  width: 28px; height: 28px; border-radius: 50%; object-fit: cover;
}
.user-avatar-fallback {
  width: 28px; height: 28px; border-radius: 50%;
  background: linear-gradient(135deg,#C9A84C,#E2C97E);
  display: flex; align-items: center; justify-content: center;
  font-size: 11px; font-weight: 800; color: #0D1B2A;
}
.user-name { font-size: 13px; font-weight: 600; color: #0D1B2A; max-width: 100px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }

.user-dropdown {
  position: absolute; right: 0; top: calc(100% + 8px);
  background: white; border-radius: 14px; border: 1.5px solid rgba(13,27,42,0.08);
  box-shadow: 0 8px 32px rgba(13,27,42,0.12); padding: 8px; min-width: 180px; z-index: 200;
}
.dropdown-item {
  display: flex; align-items: center; gap: 10px;
  padding: 9px 12px; border-radius: 9px;
  text-decoration: none; color: rgba(13,27,42,0.7);
  font-size: 13px; font-weight: 600; transition: all 0.15s;
  background: transparent; border: none; cursor: pointer; width: 100%; font-family: inherit;
}
.dropdown-item:hover { background: #FAF7F2; color: #0D1B2A; }
.dropdown-item-danger { color: rgba(239,68,68,0.8); }
.dropdown-item-danger:hover { background: rgba(239,68,68,0.07); }
.dropdown-sep { height: 1px; background: rgba(13,27,42,0.06); margin: 4px 0; }

/* ══════════════════════════════════════════
   PAGE MAIN
══════════════════════════════════════════ */
.page-main { flex: 1; padding: 24px; }

/* ══════════════════════════════════════════
   MOBILE OVERLAY
══════════════════════════════════════════ */
.mobile-overlay {
  position: fixed; inset: 0; background: rgba(10,22,40,0.5);
  z-index: 99; backdrop-filter: blur(2px);
}

/* ══════════════════════════════════════════
   ANIMATIONS
══════════════════════════════════════════ */
.flash-enter-active, .flash-leave-active { transition: all 0.35s cubic-bezier(0.16,1,0.3,1); }
.flash-enter-from, .flash-leave-to { opacity: 0; transform: translateY(-6px) scale(0.97); }
.dropdown-enter-active, .dropdown-leave-active { transition: all 0.2s cubic-bezier(0.16,1,0.3,1); }
.dropdown-enter-from, .dropdown-leave-to { opacity: 0; transform: translateY(-6px) scale(0.97); }

@keyframes pulse { 0%,100%{opacity:1} 50%{opacity:0.4} }

/* ══════════════════════════════════════════
   RESPONSIVE
══════════════════════════════════════════ */
.desktop-only { display: flex; }
.mobile-only  { display: none; }

@media (max-width: 767px) {

  /* Sidebar : hors écran par défaut, slide depuis la gauche */
  .sidebar {
    width: 260px !important;
    transform: translateX(-100%);
    box-shadow: none;
  }
  .sidebar-mobile-open {
    transform: translateX(0) !important;
    box-shadow: 8px 0 32px rgba(0,0,0,0.25);
  }

  /* Logo toujours visible dans la sidebar mobile */
  .logo-texts { display: block !important; }

  /* Main : pas de margin-left sur mobile */
  .main-content, .main-collapsed {
    margin-left: 0 !important;
  }

  /* Topbar */
  .topbar { padding: 0 16px; gap: 8px; }
  .topbar-date { display: none; }

  /* Masquer le texte flash sur mobile, garder l'icône */
  .flash-text { display: none; }
  .flash-msg  { padding: 7px 10px; }

  /* Leads pill : juste le point sur mobile */
  .leads-label { display: none; }
  .leads-pill  { padding: 6px 9px; }

  /* Page main */
  .page-main { padding: 16px; }

  /* Toggle */
  .desktop-only { display: none !important; }
  .mobile-only  { display: flex !important; }
}

@media (min-width: 768px) {
  .burger { display: none !important; }
}

@media (max-width: 1024px) and (min-width: 768px) {
  /* Tablette : sidebar réduite par défaut */
  .sidebar { width: 72px; }
  .main-content { margin-left: 72px; }
  .main-collapsed { margin-left: 72px; }
  .topbar-date { display: none; }
  .page-main { padding: 20px; }
}
</style>

<!-- <script setup>
import { ref, computed, onMounted, onBeforeUnmount, watch, h, defineComponent } from 'vue'
import { Link, usePage, router } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import {
  PhGauge, PhFunnel, PhUsers, PhFileText, PhPackage,
  PhEnvelopeSimple, PhChartBar, PhShieldCheck,
  PhSidebarSimple, PhCaretDown, PhCheckCircle,
  PhWarning, PhUser, PhSignOut,
} from '@phosphor-icons/vue'

// ── NavItem via h() ──────────────────────────────────────
const NavItem = defineComponent({
  name: 'NavItem',
  props: {
    item:      { type: Object,  required: true },
    collapsed: { type: Boolean, default: false },
  },
  setup(props) {
    const page    = usePage()
    const isActive = computed(() => {
      try {
        const url = new URL(route(props.item.route))
        return window.location.pathname.startsWith(url.pathname)
      } catch { return false }
    })

    return () => h(Link, {
      href: route(props.item.route),
      style: [
        'display:flex; align-items:center; gap:10px; padding:10px 8px; border-radius:10px;',
        'text-decoration:none; transition:all 0.2s; overflow:hidden; white-space:nowrap; margin-bottom:2px;',
        isActive.value
          ? 'background:rgba(201,168,76,0.15); color:#C9A84C; border-left:3px solid #C9A84C; padding-left:5px;'
          : 'color:rgba(255,255,255,0.5); border-left:3px solid transparent;',
      ].join(''),
    }, () => [
      h('div', {
        style: [
          'width:32px; height:32px; border-radius:9px; display:flex; align-items:center; justify-content:center; flex-shrink:0;',
          isActive.value
            ? 'background:rgba(201,168,76,0.2);'
            : 'background:rgba(255,255,255,0.06);',
        ].join(''),
      }, [
        h(props.item.icon, { style: 'width:16px; height:16px;' })
      ]),
      !props.collapsed
        ? h('div', { style: 'flex:1; min-width:0;' }, [
            h('div', { style: 'font-size:13px; font-weight:700; overflow:hidden; text-overflow:ellipsis;' }, props.item.name),
            props.item.desc
              ? h('div', { style: 'font-size:10px; color:rgba(255,255,255,0.3); margin-top:1px;' }, props.item.desc)
              : null,
          ])
        : null,
      !props.collapsed && props.item.badge
        ? h('div', {
            style: 'font-size:10px; font-weight:800; padding:2px 7px; border-radius:100px; background:rgba(201,168,76,0.25); color:#C9A84C;',
          }, props.item.badge)
        : null,
    ])
  },
})

// ── Page ────────────────────────────────────────────────────
const page         = usePage()
const sidebarOpen  = ref(true)
const userMenuOpen = ref(false)

const isAdmin = computed(() => {
  const role = page.props.auth?.user?.role_slug
  return role === 'admin' || role === 'super-admin'
})

// ── Date/heure ──────────────────────────────────────────────
const now = ref('')
let clockInterval = null

function updateClock() {
  const d = new Date()
  now.value = d.toLocaleDateString('fr-FR', {
    weekday: 'long', day: 'numeric', month: 'long', year: 'numeric',
  })
}

onMounted(() => {
  updateClock()
  clockInterval = setInterval(updateClock, 60000)
})

onBeforeUnmount(() => clearInterval(clockInterval))

// ── Titre page courant ──────────────────────────────────────
const pageTitles = {
  'commercial.dashboard':     'Tableau de bord',
  'commercial.pipeline':      'Pipeline commercial',
  'commercial.contacts.index':'Contacts CRM',
  'commercial.contacts.show': 'Fiche contact',
  'commercial.contacts.create':'Nouveau contact',
  'commercial.deals.index':   'Opportunités',
  'commercial.deals.show':    'Fiche deal',
  'commercial.quotes.index':  'Devis',
  'commercial.quotes.create': 'Nouveau devis',
  'commercial.quotes.show':   'Fiche devis',
  'commercial.products.index':'Catalogue produits',
  'commercial.sequences.index':'Séquences email',
  'commercial.sequences.show': 'Séquence email',
}

const currentPageTitle = computed(() => {
  const name = page.component?.replace('Commercial/', 'commercial.').replace('/', '.').toLowerCase()
  return pageTitles[page.url] ?? 'Espace commercial'
})

// ── Flash ────────────────────────────────────────────────────
const flashMessage = ref('')
const flashSuccess = ref(true)
let flashTimeout = null

watch(() => page.props.flash, (flash) => {
  if (flash?.success) {
    flashMessage.value = flash.success
    flashSuccess.value = true
    clearTimeout(flashTimeout)
    flashTimeout = setTimeout(() => { flashMessage.value = '' }, 4000)
  } else if (flash?.error) {
    flashMessage.value = flash.error
    flashSuccess.value = false
    clearTimeout(flashTimeout)
    flashTimeout = setTimeout(() => { flashMessage.value = '' }, 5000)
  }
}, { immediate: true, deep: true })

// ── Menu ────────────────────────────────────────────────────
const menuSections = [
  {
    title: '',
    items: [
      { name: 'Tableau de bord', desc: 'Vue globale', route: 'commercial.dashboard', icon: PhGauge },
      { name: 'Pipeline',        desc: 'Kanban deals',  route: 'commercial.pipeline',  icon: PhFunnel },
    ],
  },
  {
    title: 'Relations',
    items: [
      { name: 'Contacts CRM', route: 'commercial.contacts.index', icon: PhUsers },
      { name: 'Opportunités', route: 'commercial.deals.index',    icon: PhChartBar },
    ],
  },
  {
    title: 'Commercial',
    items: [
      { name: 'Devis',    route: 'commercial.quotes.index',   icon: PhFileText },
      { name: 'Produits', route: 'commercial.products.index', icon: PhPackage },
    ],
  },
  {
    title: 'Automatisation',
    items: [
      { name: 'Séquences email', route: 'commercial.sequences.index', icon: PhEnvelopeSimple },
    ],
  },
]

// ── Déconnexion ─────────────────────────────────────────────
function logout() {
  router.post(route('auth.logout'))
}

// ── Click outside directive ──────────────────────────────────
const vClickOutside = {
  mounted(el, binding) {
    el._clickOutside = (e) => {
      if (!el.contains(e.target)) binding.value(e)
    }
    document.addEventListener('click', el._clickOutside)
  },
  unmounted(el) {
    document.removeEventListener('click', el._clickOutside)
  },
}
</script> -->