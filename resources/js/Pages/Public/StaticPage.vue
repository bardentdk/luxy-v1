<template>
  <PublicLayout>
    <Head :title="page.meta_title ?? page.title" />

    <!-- Hero -->
    <section style="background:#FFFFFF; padding:140px 0 60px; position:relative; overflow:hidden;">
      <div class="orb orb-1" />
      <div style="position:absolute; left:0; top:0; bottom:0; width:3px; background:linear-gradient(to bottom, transparent, #C9A84C, transparent);" />

      <div style="max-width:900px; margin:0 auto; padding:0 3rem; position:relative; z-index:1;">
        <div style="display:flex; align-items:center; gap:8px; margin-bottom:24px; font-size:13px; color:rgba(13,27,42,0.4); flex-wrap:wrap;">
          <Link :href="route('home')" style="color:inherit; text-decoration:none;" class="hover-gold">Accueil</Link>
          <PhCaretRight style="width:12px; height:12px;" />
          <span style="color:rgba(13,27,42,0.65);">{{ page.title }}</span>
        </div>
        <h1 style="font-size:clamp(36px,4.5vw,60px); font-weight:900; color:#0D1B2A; letter-spacing:-0.03em; line-height:1.1;">
          {{ page.title }}
        </h1>
      </div>
    </section>

    <!-- Contenu -->
    <section style="background:#FAF7F2; padding:60px 0 100px;">
      <div style="max-width:900px; margin:0 auto; padding:0 3rem;">
        <div style="background:white; border-radius:24px; padding:52px; border:1.5px solid rgba(13,27,42,0.07); box-shadow:0 4px 32px rgba(13,27,42,0.05);">
          <div class="prose-content" v-html="page.content" />
        </div>
      </div>
    </section>
  </PublicLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import PublicLayout from '@/Layouts/PublicLayout.vue'
import { PhCaretRight } from '@phosphor-icons/vue'

defineProps({ page: Object })
</script>

<style scoped>
.orb { position:absolute; border-radius:50%; filter:blur(100px); pointer-events:none; }
.orb-1 { width:500px; height:500px; background:radial-gradient(circle,rgba(201,168,76,0.12) 0%,transparent 70%); top:-200px; right:-100px; animation:floatOrb 12s ease-in-out infinite; }
@keyframes floatOrb { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-20px)} }
.hover-gold:hover { color:#C9A84C !important; }
.prose-content { font-size:16px; color:rgba(13,27,42,0.75); line-height:1.85; }
.prose-content h2 { font-size:26px; font-weight:900; color:#0D1B2A; margin:48px 0 16px; letter-spacing:-0.02em; padding-bottom:12px; border-bottom:2px solid rgba(201,168,76,0.2); }
.prose-content h3 { font-size:20px; font-weight:800; color:#0D1B2A; margin:32px 0 12px; }
.prose-content p { margin-bottom:18px; }
.prose-content ul, .prose-content ol { padding-left:24px; margin-bottom:18px; }
.prose-content li { margin-bottom:8px; }
.prose-content strong { color:#0D1B2A; font-weight:800; }
.prose-content a { color:#C9A84C; text-decoration:underline; }
.prose-content table { width:100%; border-collapse:collapse; margin-bottom:24px; border-radius:12px; overflow:hidden; }
.prose-content th { background:#FAF7F2; padding:12px 16px; text-align:left; font-weight:700; font-size:14px; border-bottom:2px solid rgba(13,27,42,0.08); }
.prose-content td { padding:12px 16px; font-size:14px; border-bottom:1px solid rgba(13,27,42,0.06); }
</style>