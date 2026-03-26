<template>
  <aside
    style="
      position:fixed; left:0; top:0; bottom:0; z-index:40;
      background:#0D1B2A;
      display:flex; flex-direction:column;
      transition:width 0.3s cubic-bezier(0.4,0,0.2,1);
      overflow:hidden;
      box-shadow:4px 0 32px rgba(13,27,42,0.15);
    "
    :style="{ width: isMobileComputed ? (mobileOpen ? '260px' : '0px') : (collapsed ? '64px' : '260px') }"
  >
    <!-- Ligne or top -->
    <div style="position:absolute; top:0; left:0; right:0; height:2px; background:linear-gradient(90deg,#C9A84C,#E2C97E,#C9A84C); z-index:1; flex-shrink:0;" />

    <!-- Logo -->
    <div style="padding:20px 12px 16px; border-bottom:1px solid rgba(255,255,255,0.07); flex-shrink:0;">
      <div style="display:flex; align-items:center; gap:10px; min-width:0;">
        <!-- Icône -->
        <div style="width:36px; height:36px; border-radius:10px; background:linear-gradient(135deg,#C9A84C,#E2C97E); display:flex; align-items:center; justify-content:center; flex-shrink:0; box-shadow:0 4px 12px rgba(201,168,76,0.4);">
          <span style="color:#0D1B2A; font-weight:900; font-size:17px; line-height:1;">L</span>
        </div>
        <!-- Texte logo -->
        <div v-show="!collapsed" style="min-width:0; overflow:hidden; flex:1;">
          <div style="font-size:13px; font-weight:900; color:white; white-space:nowrap; letter-spacing:-0.01em; line-height:1.2;">Luxy Formation</div>
          <div style="font-size:10px; font-weight:600; color:rgba(201,168,76,0.7); white-space:nowrap; letter-spacing:0.08em; text-transform:uppercase; margin-top:2px;">Administration</div>
        </div>
        <!-- Bouton collapse -->
        <button
          @click="$emit('toggle')"
          style="width:24px; height:24px; border-radius:6px; background:rgba(255,255,255,0.06); border:1px solid rgba(255,255,255,0.08); color:rgba(255,255,255,0.4); display:flex; align-items:center; justify-content:center; cursor:pointer; transition:all 0.2s; flex-shrink:0; margin-left:auto;"
          class="collapse-btn"
        >
          <PhCaretLeft v-if="!collapsed" style="width:12px; height:12px;" />
          <PhCaretRight v-else style="width:12px; height:12px;" />
        </button>
      </div>
    </div>

    <!-- Navigation -->
    <nav style="flex:1; overflow-y:auto; overflow-x:hidden; padding:10px 8px; scrollbar-width:thin; scrollbar-color:rgba(255,255,255,0.08) transparent;">

      <!-- Section : Principal -->
      <div style="margin-bottom:2px;">
        <div v-show="!collapsed" style="font-size:10px; font-weight:700; color:rgba(255,255,255,0.25); letter-spacing:0.1em; text-transform:uppercase; padding:8px 10px 5px; white-space:nowrap;">
          Principal
        </div>
        <SidebarItem v-for="item in navPrincipal" :key="item.label" :item="item" :collapsed="collapsed" />
      </div>

      <!-- Section : Contenu -->
      <div style="margin-top:8px; margin-bottom:2px;">
        <div v-show="!collapsed" style="font-size:10px; font-weight:700; color:rgba(255,255,255,0.25); letter-spacing:0.1em; text-transform:uppercase; padding:8px 10px 5px; white-space:nowrap;">
          Contenu
        </div>
        <SidebarItem v-for="item in navContenu" :key="item.label" :item="item" :collapsed="collapsed" />
      </div>

      <!-- Section : Gestion -->
      <div style="margin-top:8px; margin-bottom:2px;">
        <div v-show="!collapsed" style="font-size:10px; font-weight:700; color:rgba(255,255,255,0.25); letter-spacing:0.1em; text-transform:uppercase; padding:8px 10px 5px; white-space:nowrap;">
          Gestion
        </div>
        <SidebarItem v-for="item in navGestion" :key="item.label" :item="item" :collapsed="collapsed" />
      </div>

      <!-- Section : Système -->
      <div style="margin-top:8px;">
        <div v-show="!collapsed" style="font-size:10px; font-weight:700; color:rgba(255,255,255,0.25); letter-spacing:0.1em; text-transform:uppercase; padding:8px 10px 5px; white-space:nowrap;">
          Système
        </div>
        <SidebarItem v-for="item in navSysteme" :key="item.label" :item="item" :collapsed="collapsed" />
      </div>
    </nav>

    <!-- Footer : profil -->
    <div style="padding:8px 8px 12px; border-top:1px solid rgba(255,255,255,0.07); flex-shrink:0;">
      <Link
        :href="route('admin.profile.edit')"
        style="display:flex; align-items:center; gap:10px; padding:10px; border-radius:12px; text-decoration:none; transition:all 0.2s; min-width:0; overflow:hidden;"
        class="profile-link"
      >
        <img
          v-if="user?.avatar_url"
          :src="user.avatar_url"
          :alt="user?.full_name"
          style="width:32px; height:32px; border-radius:9px; object-fit:cover; flex-shrink:0; border:2px solid rgba(201,168,76,0.3);"
        />
        <div v-else style="width:32px; height:32px; border-radius:9px; background:rgba(201,168,76,0.15); border:2px solid rgba(201,168,76,0.3); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
          <PhUser style="width:15px; height:15px; color:#C9A84C;" weight="fill" />
        </div>
        <div v-show="!collapsed" style="flex:1; min-width:0; overflow:hidden;">
          <div style="font-size:13px; font-weight:700; color:white; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; line-height:1.3;">{{ user?.full_name }}</div>
          <div style="font-size:11px; color:rgba(255,255,255,0.35); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; margin-top:1px;">{{ user?.email }}</div>
        </div>
      </Link>
    </div>
  </aside>
</template>

<script setup>
import { computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import {
  PhGauge, PhGraduationCap, PhNewspaper, PhStar,
  PhEnvelopeSimple, PhUsers, PhShieldCheck, PhTerminal,
  PhCaretLeft, PhCaretRight, PhTag, PhUser,
} from '@phosphor-icons/vue'

// ── Composant SidebarItem — vrai SFC inline via defineComponent ──
import { defineComponent, h } from 'vue'
import { usePage as usePageFn } from '@inertiajs/vue3'

const SidebarItem = defineComponent({
  name: 'SidebarItem',
  props: {
    item:     { type: Object, required: true },
    collapsed:{ type: Boolean, default: false },
  },
  setup(props) {
    const page = usePageFn()

    const isActive = computed(() => {
      try {
        const target = props.item.href
        const targetPath = new URL(target, window.location.origin).pathname
        return page.url.startsWith(targetPath)
      } catch {
        return false
      }
    })

    return () => {
      const { item, collapsed } = props
      const active = isActive.value

      return h(Link, {
        href: item.href,
        style: [
          'display:flex; align-items:center; gap:10px; padding:9px 10px; border-radius:11px; text-decoration:none; transition:all 0.2s; margin-bottom:2px; position:relative; overflow:hidden; min-width:0;',
          active
            ? 'background:rgba(201,168,76,0.18); border:1px solid rgba(201,168,76,0.22);'
            : 'background:transparent; border:1px solid transparent;',
        ].join(''),
        class: 'sidebar-nav-item',
      }, () => [
        // Trait actif gauche
        active ? h('div', {
          style: 'position:absolute; left:0; top:20%; bottom:20%; width:2.5px; border-radius:0 2px 2px 0; background:#C9A84C;',
        }) : null,

        // Icône
        h('div', {
          style: [
            'width:32px; height:32px; border-radius:9px; display:flex; align-items:center; justify-content:center; flex-shrink:0; transition:all 0.2s;',
            active ? 'background:rgba(201,168,76,0.2);' : 'background:rgba(255,255,255,0.05);',
          ].join(''),
        }, [
          h(item.icon, {
            style: [
              'width:16px; height:16px;',
              active ? 'color:#E2C97E;' : 'color:rgba(255,255,255,0.45);',
            ].join(''),
            weight: 'fill',
          }),
        ]),

        // Label
        !collapsed ? h('span', {
          style: [
            'font-size:13px; font-weight:700; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; flex:1; min-width:0;',
            active ? 'color:white;' : 'color:rgba(255,255,255,0.5);',
          ].join(''),
        }, item.label) : null,

        // Badge (si non collapsed)
        !collapsed && item.badge > 0 ? h('span', {
          style: 'flex-shrink:0; min-width:20px; height:20px; border-radius:100px; background:#C9A84C; color:#0D1B2A; font-size:11px; font-weight:800; display:flex; align-items:center; justify-content:center; padding:0 5px;',
        }, item.badge > 99 ? '99+' : String(item.badge)) : null,

        // Point badge collapsed
        collapsed && item.badge > 0 ? h('div', {
          style: 'position:absolute; top:5px; right:5px; width:8px; height:8px; border-radius:50%; background:#C9A84C;',
        }) : null,
      ])
    }
  },
})

// ── Props & emits ──
defineProps({
  collapsed:  Boolean,
  mobileOpen: Boolean,
})
defineEmits(['toggle', 'close-mobile'])

// ── Page & user ──
const page  = usePage()
const user  = computed(() => page.props.auth?.user)

const isMobileComputed = computed(() => {
  if (typeof window === 'undefined') return false
  return window.innerWidth < 1024
})

const pendingReviews = computed(() => page.props.stats?.pending_reviews ?? 0)
const unreadContacts = computed(() => page.props.stats?.unread_contacts ?? 0)

// ── Menus ──
const navPrincipal = computed(() => [
  { href: route('admin.dashboard'), icon: PhGauge, label: 'Dashboard', badge: 0 },
])

const navContenu = computed(() => [
  { href: route('admin.formations.index'),            icon: PhGraduationCap, label: 'Formations',  badge: 0 },
  { href: route('admin.formations.categories.index'),  icon: PhTag,           label: 'Catégories',  badge: 0 },
  { href: route('admin.articles.index'),              icon: PhNewspaper,     label: 'Articles',    badge: 0 },
  { href: route('admin.reviews.index'),               icon: PhStar,          label: 'Avis',        badge: pendingReviews.value },
])

const navGestion = computed(() => [
  { href: route('admin.contacts.index'), icon: PhEnvelopeSimple, label: 'Messages',     badge: unreadContacts.value },
  { href: route('admin.users.index'),    icon: PhUsers,          label: 'Utilisateurs', badge: 0 },
  { href: route('admin.roles.index'),    icon: PhShieldCheck,    label: 'Rôles',        badge: 0 },
])

const navSysteme = computed(() => [
  { href: route('admin.logs.index'), icon: PhTerminal, label: 'Logs', badge: 0 },
])
</script>

<style scoped>
/* ── Nav item hover ── */
.sidebar-nav-item:hover {
  background: rgba(255,255,255,0.07) !important;
  border-color: rgba(255,255,255,0.07) !important;
}

/* ── Collapse btn ── */
.collapse-btn:hover {
  background: rgba(201,168,76,0.12) !important;
  border-color: rgba(201,168,76,0.22) !important;
  color: #C9A84C !important;
}

/* ── Profile link ── */
.profile-link:hover { background: rgba(255,255,255,0.06) !important; }

/* ── Scrollbar ── */
nav::-webkit-scrollbar       { width:4px; }
nav::-webkit-scrollbar-track { background:transparent; }
nav::-webkit-scrollbar-thumb { background:rgba(255,255,255,0.08); border-radius:2px; }
nav::-webkit-scrollbar-thumb:hover { background:rgba(201,168,76,0.2); }
</style>