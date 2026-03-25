<template>
  <header
    style="position:fixed; top:0; left:0; right:0; z-index:1000; transition:all 0.4s cubic-bezier(0.16,1,0.3,1);"
    :style="scrolled
      ? 'background:rgba(255,255,255,1); backdrop-filter:blur(20px); box-shadow:0 1px 0 rgba(13,27,42,0.08), 0 4px 24px rgba(13,27,42,0.06); padding:10px 0;'
      : 'background:rgba(255,255,255,0.85); backdrop-filter:blur(12px); border-bottom:1px solid rgba(13,27,42,0.06); padding:18px 0;'"
  >
    <nav style="max-width:1320px; margin:0 auto; padding:0 3rem; display:flex; align-items:center; gap:40px;">

      <!-- Logo -->
      <Link :href="route('home')" style="display:flex; align-items:center; gap:12px; text-decoration:none; flex-shrink:0;">
        <div style="position:relative;">
          <div style="width:40px; height:40px; border-radius:11px; background:linear-gradient(135deg,#C9A84C,#E2C97E); display:flex; align-items:center; justify-content:center; position:relative; z-index:1; box-shadow:0 4px 14px rgba(201,168,76,0.35);">
            <span style="color:#0D1B2A; font-weight:900; font-size:19px; line-height:1;">L</span>
          </div>
          <div style="position:absolute; inset:-3px; border-radius:14px; background:rgba(201,168,76,0.2); filter:blur(8px); z-index:0;" />
        </div>
        <div>
          <span style="font-weight:900; font-size:17px; display:block; line-height:1.15; color:#0D1B2A; letter-spacing:-0.025em;">Luxy</span>
          <span style="color:#C9A84C; font-size:10px; letter-spacing:0.18em; text-transform:uppercase; display:block; font-weight:700;">Formation</span>
        </div>
      </Link>

      <!-- Menu desktop -->
      <ul style="display:flex; align-items:center; gap:2px; list-style:none; flex:1;" class="desktop-nav">
        <li v-for="item in menuItems" :key="item.name">
          <Link
            :href="route(item.route)"
            style="display:block; padding:8px 16px; border-radius:100px; font-size:14px; font-weight:500; text-decoration:none; transition:all 0.25s; border:1px solid transparent;"
            :style="isActive(item.route)
              ? 'background:rgba(201,168,76,0.12); color:#0D1B2A; border-color:rgba(201,168,76,0.25);'
              : 'color:rgba(13,27,42,0.6);'"
            class="nav-item"
          >
            {{ item.name }}
          </Link>
        </li>
      </ul>

      <!-- Actions -->
      <div style="display:flex; align-items:center; gap:12px; flex-shrink:0;">

        <!-- Badge admin -->
        <Link
          v-if="$page.props.auth?.user?.is_admin"
          :href="route('admin.dashboard')"
          style="display:flex; align-items:center; gap:6px; font-size:12px; font-weight:700; color:#A07828; text-decoration:none; padding:6px 12px; border-radius:100px; background:rgba(201,168,76,0.1); border:1px solid rgba(201,168,76,0.25); transition:all 0.2s;"
          class="admin-badge"
        >
          <PhShieldCheck style="width:13px; height:13px;" weight="fill" />
          Admin
        </Link>

        <Link :href="route('contact')" style="display:inline-flex; align-items:center; gap:8px; background:#C9A84C; color:#0D1B2A; font-weight:800; font-size:14px; padding:10px 22px; border-radius:100px; text-decoration:none; transition:all 0.3s; white-space:nowrap;" class="cta-btn" id="nav-cta">
          <PhEnvelope style="width:14px; height:14px;" />
          Nous contacter
        </Link>

        <button
          @click="mobileOpen = !mobileOpen"
          style="width:38px; height:38px; border-radius:10px; border:1.5px solid rgba(13,27,42,0.12); background:white; color:rgba(13,27,42,0.6); display:none; align-items:center; justify-content:center; cursor:none; transition:all 0.2s; flex-shrink:0;"
          class="burger-btn"
          aria-label="Menu"
        >
          <PhList v-if="!mobileOpen" style="width:18px; height:18px;" />
          <PhX v-else style="width:18px; height:18px;" />
        </button>
      </div>
    </nav>

    <!-- Menu mobile -->
    <Transition name="mobile-menu">
      <div v-if="mobileOpen" style="border-top:1px solid rgba(13,27,42,0.07); background:rgba(255,255,255,0.98); backdrop-filter:blur(20px);">
        <div style="max-width:1320px; margin:0 auto; padding:20px 3rem; display:flex; flex-direction:column; gap:4px;">
          <Link
            v-for="item in menuItems"
            :key="item.name"
            :href="route(item.route)"
            @click="mobileOpen = false"
            style="display:flex; align-items:center; gap:12px; padding:14px 16px; border-radius:12px; font-size:15px; font-weight:500; text-decoration:none; transition:all 0.2s;"
            :style="isActive(item.route)
              ? 'background:rgba(201,168,76,0.1); color:#0D1B2A; border:1px solid rgba(201,168,76,0.2);'
              : 'color:rgba(13,27,42,0.65);'"
          >
            <component :is="item.icon" style="width:18px; height:18px; color:#C9A84C;" />
            {{ item.name }}
          </Link>
          <Link :href="route('contact')" @click="mobileOpen = false" style="display:flex; align-items:center; justify-content:center; gap:8px; background:#C9A84C; color:#0D1B2A; font-weight:800; font-size:14px; padding:14px; border-radius:100px; text-decoration:none; margin-top:8px;">
            <PhEnvelope style="width:16px; height:16px;" /> Nous contacter
          </Link>
        </div>
      </div>
    </Transition>
  </header>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import { PhHouse, PhGraduationCap, PhUsers, PhStar, PhEnvelope, PhList, PhX, PhShieldCheck } from '@phosphor-icons/vue'

const scrolled   = ref(false)
const mobileOpen = ref(false)

const menuItems = [
  { name: 'Accueil',          route: 'home',             icon: PhHouse },
  { name: 'La vie du centre', route: 'articles.index',   icon: PhUsers },
  { name: 'Nos formations',   route: 'formations.index', icon: PhGraduationCap },
  { name: 'Vos avis',         route: 'reviews.index',    icon: PhStar },
]

function isActive(routeName) {
  try { return window.location.pathname.startsWith(new URL(route(routeName)).pathname) }
  catch { return false }
}

function onScroll() { scrolled.value = window.scrollY > 30 }
onMounted(() => { window.addEventListener('scroll', onScroll, { passive: true }); onScroll() })
onBeforeUnmount(() => window.removeEventListener('scroll', onScroll))
</script>

<style scoped>
.nav-item:hover   { background:rgba(13,27,42,0.05) !important; color:#0D1B2A !important; border-color:rgba(13,27,42,0.1) !important; }
.admin-badge:hover{ background:rgba(201,168,76,0.18) !important; }
.cta-btn:hover    { background:#E2C97E !important; transform:translateY(-2px); box-shadow:0 6px 20px rgba(201,168,76,0.4); }
.mobile-menu-enter-active, .mobile-menu-leave-active { transition:all 0.35s cubic-bezier(0.16,1,0.3,1); }
.mobile-menu-enter-from, .mobile-menu-leave-to { opacity:0; transform:translateY(-10px); }
@media (max-width:1023px) {
  .desktop-nav { display:none !important; }
  .burger-btn  { display:flex !important; }
  #nav-cta     { display:none !important; }
}
</style>