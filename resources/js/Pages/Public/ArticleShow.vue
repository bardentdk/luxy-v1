<template>
  <PublicLayout>
    <Head :title="article.meta_title ?? article.title" />

    <!-- Hero article -->
    <section style="background:#FFFFFF; padding:140px 0 0; position:relative; overflow:hidden;">
      <div class="orb orb-1" />
      <div style="position:absolute; left:0; top:0; bottom:0; width:3px; background:linear-gradient(to bottom, transparent, #C9A84C, transparent);" />
      <div style="max-width:900px; margin:0 auto; padding:0 3rem; position:relative; z-index:1;">

        <!-- Breadcrumb -->
        <div style="display:flex; align-items:center; gap:8px; margin-bottom:32px; font-size:13px; color:rgba(13,27,42,0.4);">
          <Link :href="route('home')" style="color:inherit; text-decoration:none;" class="hover-gold">Accueil</Link>
          <PhCaretRight style="width:12px; height:12px;" />
          <Link :href="route('articles.index')" style="color:inherit; text-decoration:none;" class="hover-gold">La vie du centre</Link>
          <PhCaretRight style="width:12px; height:12px;" />
          <span style="color:rgba(13,27,42,0.6); overflow:hidden; text-overflow:ellipsis; white-space:nowrap; max-width:200px;">{{ article.title }}</span>
        </div>

        <!-- Tags -->
        <div v-if="article.tags?.length" style="display:flex; gap:8px; flex-wrap:wrap; margin-bottom:20px;">
          <span
            v-for="tag in article.tags"
            :key="tag"
            style="background:rgba(201,168,76,0.1); border:1px solid rgba(201,168,76,0.25); color:#A07828; font-size:12px; font-weight:600; padding:5px 12px; border-radius:100px;"
          >
            {{ tag }}
          </span>
        </div>

        <h1 class="fade-up" style="--d:0s; font-size:clamp(32px,4.5vw,60px); font-weight:900; color:#0D1B2A; letter-spacing:-0.03em; line-height:1.1; margin-bottom:24px;">
          {{ article.title }}
        </h1>

        <p style="font-size:18px; color:rgba(13,27,42,0.55); line-height:1.75; margin-bottom:40px;">
          {{ article.excerpt }}
        </p>

        <!-- Meta auteur -->
        <div style="display:flex; align-items:center; justify-content:space-between; padding:24px 0 40px; border-top:1px solid rgba(13,27,42,0.08); flex-wrap:wrap; gap:16px;">
          <div style="display:flex; align-items:center; gap:14px;">
            <img v-if="article.author?.avatar_url" :src="article.author.avatar_url" :alt="article.author.full_name" style="width:44px; height:44px; border-radius:50%; object-fit:cover; border:2px solid rgba(201,168,76,0.25);" />
            <div>
              <div style="font-size:15px; font-weight:700; color:#0D1B2A;">{{ article.author?.full_name }}</div>
              <div style="font-size:13px; color:rgba(13,27,42,0.4);">{{ article.formatted_published_at }}</div>
            </div>
          </div>
          <div style="display:flex; align-items:center; gap:6px; color:rgba(13,27,42,0.4); font-size:13px;">
            <PhClock style="width:14px; height:14px; color:#C9A84C;" />
            {{ article.read_time }} min de lecture
          </div>
        </div>
      </div>

      <!-- Banner -->
      <div v-if="article.banner_url" style="max-width:1320px; margin:0 auto; padding:0 3rem; position:relative; z-index:1;">
        <img
          :src="article.banner_url"
          :alt="article.title"
          style="width:100%; height:480px; object-fit:cover; border-radius:24px 24px 0 0; display:block;"
        />
      </div>
    </section>

    <!-- Corps de l'article -->
    <section style="background:#FAF7F2; padding:80px 0 100px;">
      <div style="max-width:760px; margin:0 auto; padding:0 3rem;">
        <div style="background:white; border-radius:24px; padding:52px; border:1.5px solid rgba(13,27,42,0.07); box-shadow:0 4px 32px rgba(13,27,42,0.05);">
          <div class="prose-content" v-html="article.content" />
        </div>

        <!-- Tags bottom -->
        <div v-if="article.tags?.length" style="margin-top:48px; padding:28px; background:white; border-radius:16px; border:1.5px solid rgba(13,27,42,0.07); display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
          <span style="font-size:13px; font-weight:600; color:rgba(13,27,42,0.4);">Tags :</span>
          <span
            v-for="tag in article.tags"
            :key="tag"
            style="background:#FAF7F2; border:1px solid rgba(13,27,42,0.1); color:rgba(13,27,42,0.6); font-size:13px; font-weight:600; padding:5px 14px; border-radius:100px;"
          >
            {{ tag }}
          </span>
        </div>

        <!-- CTA — fond crème chaude au lieu de bleu nuit -->
        <div style="margin-top:64px; background:#FAF7F2; border-radius:24px; padding:48px; text-align:center; border:1.5px solid rgba(201,168,76,0.2); position:relative; overflow:hidden;">
          <div style="position:absolute; top:0; left:0; right:0; height:3px; background:linear-gradient(90deg, transparent, #C9A84C, #E2C97E, #C9A84C, transparent); border-radius:2px;" />
          <h3 style="font-size:26px; font-weight:900; color:#0D1B2A; margin-bottom:12px; letter-spacing:-0.02em;">
            Intéressé par nos formations ?
          </h3>
          <p style="font-size:15px; color:rgba(13,27,42,0.55); margin-bottom:32px; line-height:1.7;">
            Découvrez notre catalogue de formations professionnelles à La Réunion.
          </p>
          <div style="display:flex; justify-content:center; gap:12px; flex-wrap:wrap;">
            <Link :href="route('formations.index')" style="display:inline-flex; align-items:center; gap:8px; background:#C9A84C; color:#0D1B2A; font-weight:800; font-size:14px; padding:13px 28px; border-radius:100px; text-decoration:none;" class="cta-hover">
              <PhGraduationCap style="width:16px; height:16px;" />
              Voir les formations
            </Link>
            <Link :href="route('contact')" style="display:inline-flex; align-items:center; gap:8px; background:transparent; color:#0D1B2A; font-weight:700; font-size:14px; padding:13px 28px; border-radius:100px; text-decoration:none; border:1.5px solid rgba(13,27,42,0.18);" class="outline-hover">
              Nous contacter
            </Link>
          </div>
        </div>
      </div>
    </section>
  </PublicLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import PublicLayout from '@/Layouts/PublicLayout.vue'
import { PhCaretRight, PhClock, PhGraduationCap } from '@phosphor-icons/vue'

defineProps({ article: Object })
</script>

<style scoped>
.orb { position:absolute; border-radius:50%; filter:blur(100px); pointer-events:none; }
.orb-1 { width:700px; height:700px; background:radial-gradient(circle,rgba(201,168,76,0.12) 0%,transparent 70%); top:-250px; right:-150px; animation:floatOrb 10s ease-in-out infinite; }
@keyframes floatOrb { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-20px)} }
.fade-up { animation:fadeUp 0.7s cubic-bezier(0.16,1,0.3,1) both; animation-delay:var(--d,0s); }
@keyframes fadeUp { from{opacity:0;transform:translateY(24px)} to{opacity:1;transform:translateY(0)} }
.hover-gold:hover { color:#C9A84C !important; }
.cta-hover:hover { background:#E2C97E !important; transform:translateY(-2px); box-shadow:0 8px 32px rgba(201,168,76,0.35); }
.outline-hover:hover { background:#0D1B2A !important; color:white !important; border-color:#0D1B2A !important; transform:translateY(-2px); }
.prose-content { font-size:17px; color:rgba(13,27,42,0.75); line-height:1.85; }
.prose-content h2 { font-size:28px; font-weight:900; color:#0D1B2A; letter-spacing:-0.02em; margin:48px 0 16px; padding-bottom:12px; border-bottom:2px solid rgba(201,168,76,0.2); }
.prose-content h3 { font-size:22px; font-weight:800; color:#0D1B2A; margin:36px 0 12px; }
.prose-content p { margin-bottom:20px; }
.prose-content ul, .prose-content ol { padding-left:24px; margin-bottom:20px; }
.prose-content li { margin-bottom:8px; }
.prose-content strong { color:#0D1B2A; font-weight:800; }
.prose-content blockquote { border-left:3px solid #C9A84C; padding:16px 24px; margin:32px 0; font-style:italic; color:rgba(13,27,42,0.6); background:rgba(201,168,76,0.05); border-radius:0 12px 12px 0; }
.prose-content img { width:100%; border-radius:16px; margin:32px 0; }
.prose-content a { color:#C9A84C; text-decoration:underline; }
</style>