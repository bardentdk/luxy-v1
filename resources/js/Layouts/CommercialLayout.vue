<template>
  <div style="min-height:100vh; background:#F4F6FA; display:flex;">

    <!-- ── SIDEBAR ── -->
    <aside
      :style="`
        width:${sidebarOpen ? '260px' : '72px'};
        background:#0D1B2A;
        min-height:100vh;
        position:fixed;
        left:0; top:0; bottom:0;
        z-index:100;
        transition:width 0.3s cubic-bezier(0.16,1,0.3,1);
        display:flex;
        flex-direction:column;
        overflow:hidden;
      `"
    >
      <!-- Logo -->
      <div style="padding:20px 16px; border-bottom:1px solid rgba(255,255,255,0.07); flex-shrink:0;">
        <div style="display:flex; align-items:center; gap:12px; overflow:hidden;">
          <img src="https://luxyformation.re/wp-content/uploads/2024/03/cropped-horizontal_luxy_logo-300x97.png" alt="" class="w-40">
          <!-- <div style="width:40px; height:40px; border-radius:11px; background:linear-gradient(135deg,#C9A84C,#E2C97E); display:flex; align-items:center; justify-content:center; flex-shrink:0;"> -->
            <!-- <span style="font-size:18px; font-weight:900; color:#0D1B2A;">L</span> -->
          <!-- </div> -->
          <div v-if="sidebarOpen" style="overflow:hidden; white-space:nowrap;">
            <!-- <div style="font-size:14px; font-weight:800; color:white; line-height:1.2;">Luxy CRM</div>
            <div style="font-size:10px; color:#C9A84C; letter-spacing:1.5px; text-transform:uppercase;">Espace commercial</div> -->
          </div>
        </div>
      </div>

      <!-- Nav -->
      <nav style="flex:1; overflow-y:auto; overflow-x:hidden; padding:12px 8px;">
        <div
          v-for="section in menuSections"
          :key="section.title"
          style="margin-bottom:24px;"
        >
          <div
            v-if="sidebarOpen && section.title"
            style="font-size:9px; font-weight:700; color:rgba(255,255,255,0.25); letter-spacing:0.15em; text-transform:uppercase; padding:0 8px; margin-bottom:6px;"
          >
            {{ section.title }}
          </div>

          <NavItem
            v-for="item in section.items"
            :key="item.route"
            :item="item"
            :collapsed="!sidebarOpen"
          />
        </div>
      </nav>

      <!-- Footer sidebar -->
      <div style="padding:12px 8px; border-top:1px solid rgba(255,255,255,0.07); flex-shrink:0;">
        <!-- Lien retour admin -->
        <Link
          v-if="$page.props.auth?.user && isAdmin"
          :href="route('admin.dashboard')"
          style="display:flex; align-items:center; gap:10px; padding:10px 8px; border-radius:10px; text-decoration:none; color:rgba(255,255,255,0.4); transition:all 0.2s; overflow:hidden; white-space:nowrap;"
          class="sidebar-link"
        >
          <div style="width:32px; height:32px; border-radius:9px; display:flex; align-items:center; justify-content:center; flex-shrink:0; background:rgba(255,255,255,0.06);">
            <PhShieldCheck style="width:16px; height:16px;" />
          </div>
          <span v-if="sidebarOpen" style="font-size:13px; font-weight:600;">Admin</span>
        </Link>

        <!-- Toggle sidebar -->
        <button
          @click="sidebarOpen = !sidebarOpen"
          style="display:flex; align-items:center; gap:10px; padding:10px 8px; border-radius:10px; background:transparent; border:none; color:rgba(255,255,255,0.3); cursor:pointer; width:100%; transition:all 0.2s; overflow:hidden; white-space:nowrap; margin-top:4px;"
          class="sidebar-link"
        >
          <div style="width:32px; height:32px; border-radius:9px; display:flex; align-items:center; justify-content:center; flex-shrink:0; background:rgba(255,255,255,0.06);">
            <PhSidebarSimple style="width:16px; height:16px;" />
          </div>
          <span v-if="sidebarOpen" style="font-size:13px; font-weight:600;">Réduire</span>
        </button>
      </div>
    </aside>

    <!-- ── CONTENU PRINCIPAL ── -->
    <div
      :style="`
        flex:1;
        margin-left:${sidebarOpen ? '260px' : '72px'};
        transition:margin-left 0.3s cubic-bezier(0.16,1,0.3,1);
        min-height:100vh;
        display:flex;
        flex-direction:column;
      `"
    >
      <!-- Topbar -->
      <header style="background:white; border-bottom:1px solid rgba(13,27,42,0.07); padding:0 28px; height:60px; display:flex; align-items:center; justify-content:space-between; position:sticky; top:0; z-index:50; flex-shrink:0;">

        <!-- Breadcrumb / Titre page -->
        <div style="display:flex; align-items:center; gap:12px;">
          <div>
            <div style="font-size:15px; font-weight:800; color:#0D1B2A; letter-spacing:-0.01em;">
              {{ currentPageTitle }}
            </div>
            <div style="font-size:11px; color:rgba(13,27,42,0.4);">
              {{ now }}
            </div>
          </div>
        </div>

        <!-- Actions topbar -->
        <div style="display:flex; align-items:center; gap:12px;">

          <!-- Indicateur leads en attente -->
          <Link
            v-if="$page.props.pendingLeads > 0"
            :href="route('commercial.contacts.index')"
            style="display:flex; align-items:center; gap:7px; padding:7px 13px; border-radius:100px; background:rgba(201,168,76,0.1); border:1px solid rgba(201,168,76,0.25); text-decoration:none; transition:all 0.2s;"
            class="leads-btn"
          >
            <div style="width:7px; height:7px; border-radius:50%; background:#C9A84C; animation:pulse 2s infinite;"></div>
            <span style="font-size:12px; font-weight:700; color:#A07828;">
              {{ $page.props.pendingLeads }} lead{{ $page.props.pendingLeads > 1 ? 's' : '' }} en attente
            </span>
          </Link>

          <!-- Flash message inline -->
          <Transition name="flash">
            <div
              v-if="flashMessage"
              style="display:flex; align-items:center; gap:8px; padding:8px 16px; border-radius:100px; font-size:12px; font-weight:700;"
              :style="flashSuccess
                ? 'background:rgba(34,197,94,0.1); color:#16A34A; border:1px solid rgba(34,197,94,0.2);'
                : 'background:rgba(239,68,68,0.1); color:#DC2626; border:1px solid rgba(239,68,68,0.2);'"
            >
              <PhCheckCircle v-if="flashSuccess" style="width:14px; height:14px;" weight="fill" />
              <PhWarning v-else style="width:14px; height:14px;" weight="fill" />
              {{ flashMessage }}
            </div>
          </Transition>

          <!-- Avatar utilisateur -->
          <div style="position:relative;">
            <button
              @click="userMenuOpen = !userMenuOpen"
              style="display:flex; align-items:center; gap:9px; padding:6px 12px 6px 6px; border-radius:100px; border:1.5px solid rgba(13,27,42,0.1); background:white; cursor:pointer; transition:all 0.2s;"
              class="user-btn"
            >
              <img
                v-if="$page.props.auth?.user?.avatar_url"
                :src="$page.props.auth.user.avatar_url"
                style="width:28px; height:28px; border-radius:50%; object-fit:cover;"
              />
              <div v-else style="width:28px; height:28px; border-radius:50%; background:linear-gradient(135deg,#C9A84C,#E2C97E); display:flex; align-items:center; justify-content:center;">
                <span style="font-size:11px; font-weight:800; color:#0D1B2A;">{{ $page.props.auth?.user?.initials }}</span>
              </div>
              <span style="font-size:13px; font-weight:600; color:#0D1B2A; max-width:100px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                {{ $page.props.auth?.user?.first_name }}
              </span>
              <PhCaretDown style="width:12px; height:12px; color:rgba(13,27,42,0.4);" />
            </button>

            <!-- Dropdown user -->
            <Transition name="dropdown">
              <div
                v-if="userMenuOpen"
                style="position:absolute; right:0; top:calc(100% + 8px); background:white; border-radius:14px; border:1.5px solid rgba(13,27,42,0.08); box-shadow:0 8px 32px rgba(13,27,42,0.12); padding:8px; min-width:180px; z-index:200;"
                v-click-outside="() => userMenuOpen = false"
              >
                <Link
                  :href="route('admin.profile.edit')"
                  style="display:flex; align-items:center; gap:10px; padding:9px 12px; border-radius:9px; text-decoration:none; color:rgba(13,27,42,0.7); font-size:13px; font-weight:600; transition:all 0.15s;"
                  class="dropdown-item"
                >
                  <PhUser style="width:15px; height:15px;" />
                  Mon profil
                </Link>
                <div style="height:1px; background:rgba(13,27,42,0.06); margin:4px 0;"></div>
                <form @submit.prevent="logout">
                  <button
                    type="submit"
                    style="display:flex; align-items:center; gap:10px; padding:9px 12px; border-radius:9px; border:none; background:transparent; color:rgba(239,68,68,0.8); font-size:13px; font-weight:600; cursor:pointer; width:100%; transition:all 0.15s; font-family:inherit;"
                    class="dropdown-item-danger"
                  >
                    <PhSignOut style="width:15px; height:15px;" />
                    Se déconnecter
                  </button>
                </form>
              </div>
            </Transition>
          </div>
        </div>
      </header>

      <!-- Contenu page -->
      <main style="flex:1; padding:28px;">
        <slot />
      </main>
    </div>
  </div>
</template>

<script setup>
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
</script>

<style scoped>
.sidebar-link:hover { background:rgba(255,255,255,0.06) !important; color:rgba(255,255,255,0.7) !important; }
.user-btn:hover     { border-color:rgba(201,168,76,0.3) !important; background:#FAF7F2 !important; }
.leads-btn:hover    { background:rgba(201,168,76,0.18) !important; }
.dropdown-item:hover { background:#FAF7F2 !important; color:#0D1B2A !important; }
.dropdown-item-danger:hover { background:rgba(239,68,68,0.07) !important; }

.flash-enter-active, .flash-leave-active { transition:all 0.35s cubic-bezier(0.16,1,0.3,1); }
.flash-enter-from, .flash-leave-to { opacity:0; transform:translateY(-8px) scale(0.97); }

.dropdown-enter-active, .dropdown-leave-active { transition:all 0.25s cubic-bezier(0.16,1,0.3,1); }
.dropdown-enter-from, .dropdown-leave-to { opacity:0; transform:translateY(-8px) scale(0.97); }

@keyframes pulse { 0%,100%{opacity:1} 50%{opacity:0.4} }
</style>