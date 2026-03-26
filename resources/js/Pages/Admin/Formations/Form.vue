<template>
  <AdminLayout>
    <Head :title="isEdit ? 'Modifier la formation' : 'Nouvelle formation'" />

    <div style="max-width:900px;">

      <!-- Header -->
      <div style="display:flex; align-items:center; gap:16px; margin-bottom:32px;">
        <Link :href="route('admin.formations.index')" style="width:36px; height:36px; border-radius:10px; border:1.5px solid rgba(13,27,42,0.12); display:flex; align-items:center; justify-content:center; color:rgba(13,27,42,0.5); text-decoration:none;" class="back-btn">
          <PhArrowLeft style="width:16px; height:16px;" />
        </Link>
        <div>
          <h1 style="font-size:22px; font-weight:900; color:#0D1B2A; letter-spacing:-0.02em;">
            {{ isEdit ? 'Modifier la formation' : 'Nouvelle formation' }}
          </h1>
          <p style="font-size:13px; color:rgba(13,27,42,0.4); margin-top:2px;">
            {{ isEdit ? `Mise à jour de : ${formation?.title}` : 'Créer une nouvelle formation au catalogue' }}
          </p>
        </div>

        <!-- Badge assistant IA -->
        <div style="margin-left:auto; display:flex; align-items:center; gap:8px; background:rgba(201,168,76,0.08); border:1px solid rgba(201,168,76,0.2); border-radius:100px; padding:7px 14px;">
          <PhSparkleFill style="width:14px; height:14px; color:#C9A84C;" />
          <span style="font-size:12px; font-weight:700; color:#A07828;">Assistant IA Groq</span>
        </div>
      </div>

      <!-- ────────────────────────────────────────── -->
      <!-- PANNEAU IA GLOBAL (flottant)               -->
      <!-- ────────────────────────────────────────── -->
      <Transition name="ai-panel">
        <div
          v-if="aiPanel.visible"
          style="position:fixed; bottom:32px; right:32px; width:420px; background:white; border-radius:20px; border:1.5px solid rgba(201,168,76,0.25); box-shadow:0 16px 60px rgba(13,27,42,0.15); z-index:1000; overflow:hidden;"
        >
          <!-- Header panel -->
          <div style="padding:16px 20px; background:#FAF7F2; border-bottom:1px solid rgba(13,27,42,0.07); display:flex; align-items:center; justify-content:space-between;">
            <div style="display:flex; align-items:center; gap:8px;">
              <PhSparkleFill style="width:15px; height:15px; color:#C9A84C;" />
              <span style="font-size:13px; font-weight:800; color:#0D1B2A;">{{ aiPanel.fieldLabel }}</span>
            </div>
            <button @click="closeAiPanel" style="width:26px; height:26px; border-radius:6px; border:1px solid rgba(13,27,42,0.1); background:white; cursor:pointer; display:flex; align-items:center; justify-content:center; color:rgba(13,27,42,0.4);" class="ai-close-btn">
              <PhX style="width:13px; height:13px;" />
            </button>
          </div>

          <!-- Corps panel -->
          <div style="padding:18px 20px;">
            <!-- Chargement -->
            <div v-if="aiPanel.loading" style="display:flex; flex-direction:column; align-items:center; gap:14px; padding:16px 0;">
              <div style="display:flex; gap:6px;">
                <div class="ai-dot" style="animation-delay:0s;" />
                <div class="ai-dot" style="animation-delay:0.15s;" />
                <div class="ai-dot" style="animation-delay:0.3s;" />
              </div>
              <span style="font-size:12px; color:rgba(13,27,42,0.4);">Génération en cours...</span>
            </div>

            <!-- Résultat -->
            <div v-else-if="aiPanel.result">
              <div
                style="background:#FAF7F2; border-radius:12px; padding:14px; font-size:13px; color:rgba(13,27,42,0.75); line-height:1.7; max-height:200px; overflow-y:auto; border:1px solid rgba(13,27,42,0.07); margin-bottom:14px; white-space:pre-wrap;"
              >{{ aiPanel.result }}</div>

              <!-- Actions -->
              <div style="display:flex; gap:8px;">
                <button @click="applyAiResult" style="flex:2; display:flex; align-items:center; justify-content:center; gap:6px; padding:10px; border-radius:10px; border:none; background:#C9A84C; color:#0D1B2A; font-size:13px; font-weight:800; cursor:pointer; font-family:inherit; transition:all 0.2s;" class="ai-apply-btn">
                  <PhCheck style="width:14px; height:14px;" weight="bold" />
                  Insérer
                </button>
                <button @click="regenerate" style="flex:1; display:flex; align-items:center; justify-content:center; gap:5px; padding:10px; border-radius:10px; border:1.5px solid rgba(13,27,42,0.12); background:white; color:rgba(13,27,42,0.6); font-size:12px; font-weight:700; cursor:pointer; font-family:inherit; transition:all 0.2s;" class="ai-regen-btn">
                  <PhArrowCounterClockwise style="width:13px; height:13px;" />
                  Regénérer
                </button>
                <button @click="closeAiPanel" style="width:36px; height:36px; border-radius:10px; border:1.5px solid rgba(13,27,42,0.1); background:white; cursor:pointer; display:flex; align-items:center; justify-content:center; color:rgba(13,27,42,0.4);" class="ai-close-btn">
                  <PhX style="width:14px; height:14px;" />
                </button>
              </div>
            </div>

            <!-- Erreur -->
            <div v-else-if="aiPanel.error" style="display:flex; flex-direction:column; gap:10px;">
              <div style="background:rgba(239,68,68,0.06); border:1px solid rgba(239,68,68,0.2); border-radius:10px; padding:12px; font-size:13px; color:#DC2626;">
                {{ aiPanel.error }}
              </div>
              <button @click="regenerate" style="display:flex; align-items:center; justify-content:center; gap:6px; padding:9px; border-radius:10px; border:1.5px solid rgba(13,27,42,0.12); background:white; color:rgba(13,27,42,0.6); font-size:12px; font-weight:700; cursor:pointer; font-family:inherit;" class="ai-regen-btn">
                <PhArrowCounterClockwise style="width:13px; height:13px;" />
                Réessayer
              </button>
            </div>
          </div>
        </div>
      </Transition>

      <form @submit.prevent="submit" style="display:flex; flex-direction:column; gap:24px;">

        <!-- ═══════════════════════════════════════ -->
        <!-- SECTION 1 : Informations principales   -->
        <!-- ═══════════════════════════════════════ -->
        <div class="form-section">
          <div class="section-header">
            <div>
              <h2 class="section-title">Informations principales</h2>
              <p class="section-sub">Titre, catégorie, niveau, durée et tarifs</p>
            </div>
          </div>

          <div style="display:flex; flex-direction:column; gap:20px;">

            <!-- Titre -->
            <div>
              <label class="form-label">Titre de la formation <span class="required">*</span></label>
              <input v-model="form.title" type="text" placeholder="Ex: Formation Excel avancé" required class="form-input" :class="{ error: form.errors.title }" />
              <p v-if="form.errors.title" class="form-error">{{ form.errors.title }}</p>
            </div>

            <!-- Description courte avec IA -->
            <div>
              <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:8px;">
                <label class="form-label" style="margin-bottom:0;">Description courte</label>
                <AiButton field="short_description" label="Description courte" :ctx="aiContext" @generate="openAiPanel" />
              </div>
              <textarea v-model="form.short_description" rows="3" placeholder="Une description concise de la formation..." class="form-input" style="resize:vertical;" />
            </div>

            <!-- Catégorie + Niveau -->
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
              <div>
                <label class="form-label">Catégorie</label>
                <select v-model="form.category_id" class="form-input">
                  <option value="">Sélectionner une catégorie</option>
                  <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                </select>
              </div>
              <div>
                <label class="form-label">Niveau</label>
                <select v-model="form.level" class="form-input">
                  <option value="tous">Tous niveaux</option>
                  <option value="debutant">Débutant</option>
                  <option value="intermediaire">Intermédiaire</option>
                  <option value="avance">Avancé</option>
                </select>
              </div>
            </div>

            <!-- Durée + Prix -->
            <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:16px;">
              <div>
                <label class="form-label">Durée</label>
                <input v-model="form.duration" type="text" placeholder="Ex: 3 mois, 40h..." class="form-input" />
              </div>
              <div>
                <label class="form-label">Prix (€)</label>
                <input v-model="form.price" type="number" step="0.01" min="0" placeholder="0.00" class="form-input" />
              </div>
              <div>
                <label class="form-label">Prix promo (€)</label>
                <input v-model="form.price_promo" type="number" step="0.01" min="0" placeholder="0.00" class="form-input" />
              </div>
            </div>
          </div>
        </div>

        <!-- ═══════════════════════════════════════ -->
        <!-- SECTION 2 : Images                     -->
        <!-- ═══════════════════════════════════════ -->
        <div class="form-section">
          <div class="section-header">
            <div>
              <h2 class="section-title">Images</h2>
              <p class="section-sub">Miniature et bannière de la formation</p>
            </div>
          </div>

          <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
            <div>
              <label class="form-label">Miniature (800×600)</label>
              <div class="upload-zone" @click="$refs.thumbnailInput.click()" @dragover.prevent @drop.prevent="e => handleFileDrop(e, 'thumbnail')">
                <img v-if="thumbnailPreview || formation?.thumbnail_url" :src="thumbnailPreview ?? formation?.thumbnail_url" style="width:100%; height:140px; object-fit:cover; border-radius:10px; display:block;" />
                <div v-else style="padding:32px 20px; text-align:center; color:rgba(13,27,42,0.4);">
                  <PhImage style="width:28px; height:28px; margin:0 auto 8px; display:block;" />
                  <span style="font-size:13px;">Cliquer ou glisser une image</span>
                </div>
              </div>
              <input ref="thumbnailInput" type="file" accept="image/*" style="display:none;" @change="e => handleFileChange(e, 'thumbnail')" />
            </div>
            <div>
              <label class="form-label">Bannière (1920×600)</label>
              <div class="upload-zone" @click="$refs.bannerInput.click()" @dragover.prevent @drop.prevent="e => handleFileDrop(e, 'banner')">
                <img v-if="bannerPreview || formation?.banner_url" :src="bannerPreview ?? formation?.banner_url" style="width:100%; height:140px; object-fit:cover; border-radius:10px; display:block;" />
                <div v-else style="padding:32px 20px; text-align:center; color:rgba(13,27,42,0.4);">
                  <PhImage style="width:28px; height:28px; margin:0 auto 8px; display:block;" />
                  <span style="font-size:13px;">Cliquer ou glisser une image</span>
                </div>
              </div>
              <input ref="bannerInput" type="file" accept="image/*" style="display:none;" @change="e => handleFileChange(e, 'banner')" />
            </div>
          </div>
        </div>

        <!-- ═══════════════════════════════════════ -->
        <!-- SECTION 3 : Contenu pédagogique        -->
        <!-- ═══════════════════════════════════════ -->
        <div class="form-section">
          <div class="section-header">
            <div>
              <h2 class="section-title">Contenu pédagogique</h2>
              <p class="section-sub">Objectifs, prérequis et description complète</p>
            </div>
          </div>

          <!-- Objectifs -->
          <div style="margin-bottom:24px;">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:8px;">
              <label class="form-label" style="margin-bottom:0;">Objectifs d'apprentissage</label>
            </div>
            <div style="display:flex; flex-direction:column; gap:8px; margin-bottom:10px;">
              <div v-for="(obj, i) in objectives" :key="i" style="display:flex; gap:8px;">
                <input v-model="objectives[i]" type="text" placeholder="Un objectif..." class="form-input" style="flex:1;" />
                <button type="button" @click="objectives.splice(i, 1)" class="admin-icon-btn admin-icon-btn-danger" style="flex-shrink:0;">
                  <PhMinus style="width:14px; height:14px;" />
                </button>
              </div>
            </div>
            <button type="button" @click="objectives.push('')" class="add-item-btn">
              <PhPlus style="width:14px; height:14px;" />
              Ajouter un objectif
            </button>
          </div>

          <!-- Prérequis -->
          <div style="margin-bottom:24px;">
            <label class="form-label">Prérequis</label>
            <div style="display:flex; flex-direction:column; gap:8px; margin-bottom:10px;">
              <div v-for="(pre, i) in prerequisites" :key="i" style="display:flex; gap:8px;">
                <input v-model="prerequisites[i]" type="text" placeholder="Un prérequis..." class="form-input" style="flex:1;" />
                <button type="button" @click="prerequisites.splice(i, 1)" class="admin-icon-btn admin-icon-btn-danger" style="flex-shrink:0;">
                  <PhMinus style="width:14px; height:14px;" />
                </button>
              </div>
            </div>
            <button type="button" @click="prerequisites.push('')" class="add-item-btn">
              <PhPlus style="width:14px; height:14px;" />
              Ajouter un prérequis
            </button>
          </div>

          <!-- Description complète avec IA -->
          <div>
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:8px;">
              <label class="form-label" style="margin-bottom:0;">Description complète (HTML)</label>
              <AiButton field="content" label="Description complète" :ctx="aiContext" @generate="openAiPanel" />
            </div>
            <textarea v-model="form.content" rows="10" placeholder="<p>Contenu de la formation...</p>" class="form-input" style="resize:vertical; font-family:monospace; font-size:13px;" />
          </div>
        </div>

        <!-- ═══════════════════════════════════════ -->
        <!-- SECTION 4 : Profil & accessibilité     -->
        <!-- ═══════════════════════════════════════ -->
        <div class="form-section">
          <div class="section-header">
            <div>
              <h2 class="section-title">Profil & accessibilité</h2>
              <p class="section-sub">Public visé, accessibilité et délai d'accès</p>
            </div>
            <div class="section-badge">Nouveau</div>
          </div>

          <!-- Profil des participants -->
          <div style="margin-bottom:20px;">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:8px;">
              <label class="form-label" style="margin-bottom:0;">Profil des participants</label>
              <AiButton field="participant_profile" label="Profil des participants" :ctx="aiContext" @generate="openAiPanel" />
            </div>
            <textarea v-model="form.participant_profile" rows="4" placeholder="Ex: Cette formation s'adresse aux professionnels souhaitant..." class="form-input" style="resize:vertical;" />
          </div>

          <!-- Accessibilité -->
          <div style="margin-bottom:20px;">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:8px;">
              <label class="form-label" style="margin-bottom:0;">Accessibilité</label>
              <AiButton field="accessibility" label="Accessibilité" :ctx="aiContext" @generate="openAiPanel" />
            </div>
            <textarea v-model="form.accessibility" rows="3" placeholder="Ex: Notre centre est accessible aux personnes en situation de handicap..." class="form-input" style="resize:vertical;" />
          </div>

          <!-- Délai d'accès -->
          <div>
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:8px;">
              <label class="form-label" style="margin-bottom:0;">Délai d'accès</label>
              <AiButton field="access_delay" label="Délai d'accès" :ctx="aiContext" @generate="openAiPanel" />
            </div>
            <input v-model="form.access_delay" type="text" placeholder="Ex: Entrée en formation sous 2 à 4 semaines après validation du dossier" class="form-input" />
          </div>
        </div>

        <!-- ═══════════════════════════════════════ -->
        <!-- SECTION 5 : Modalités pédagogiques     -->
        <!-- ═══════════════════════════════════════ -->
        <div class="form-section">
          <div class="section-header">
            <div>
              <h2 class="section-title">Modalités pédagogiques</h2>
              <p class="section-sub">Méthodes, supports et évaluation</p>
            </div>
            <div class="section-badge">Nouveau</div>
          </div>

          <!-- Modalités pédagogiques -->
          <div style="margin-bottom:20px;">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:8px;">
              <label class="form-label" style="margin-bottom:0;">Modalités pédagogiques</label>
              <AiButton field="teaching_methods" label="Modalités pédagogiques" :ctx="aiContext" @generate="openAiPanel" />
            </div>
            <textarea v-model="form.teaching_methods" rows="4" placeholder="Ex: Formation en présentiel, alternance cours théoriques et ateliers pratiques..." class="form-input" style="resize:vertical;" />
          </div>

          <!-- Moyens et supports -->
          <div style="margin-bottom:20px;">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:8px;">
              <label class="form-label" style="margin-bottom:0;">Moyens et supports pédagogiques</label>
              <AiButton field="teaching_resources" label="Moyens et supports" :ctx="aiContext" @generate="openAiPanel" />
            </div>
            <textarea v-model="form.teaching_resources" rows="4" placeholder="Ex: Support de cours numérique, accès plateforme e-learning, exercices pratiques..." class="form-input" style="resize:vertical;" />
          </div>

          <!-- Modalités d'évaluation -->
          <div>
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:8px;">
              <label class="form-label" style="margin-bottom:0;">Modalités d'évaluation et de suivi</label>
              <AiButton field="evaluation_methods" label="Évaluation et suivi" :ctx="aiContext" @generate="openAiPanel" />
            </div>
            <textarea v-model="form.evaluation_methods" rows="4" placeholder="Ex: Évaluation initiale des acquis, QCM en fin de module, mise en situation finale..." class="form-input" style="resize:vertical;" />
          </div>
        </div>

        <!-- ═══════════════════════════════════════ -->
        <!-- SECTION 6 : Indicateurs de performance  -->
        <!-- ═══════════════════════════════════════ -->
        <div class="form-section">
          <div class="section-header">
            <div>
              <h2 class="section-title">Indicateurs de performance</h2>
              <p class="section-sub">Taux de réussite, satisfaction et retour à l'emploi</p>
            </div>
            <div class="section-badge">Nouveau</div>
          </div>

          <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:16px;">

            <!-- Taux de réussite -->
            <div style="background:#FAF7F2; border-radius:14px; padding:18px; border:1.5px solid rgba(34,197,94,0.15);">
              <div style="display:flex; align-items:center; gap:8px; margin-bottom:12px;">
                <div style="width:32px; height:32px; border-radius:9px; background:rgba(34,197,94,0.12); display:flex; align-items:center; justify-content:center;">
                  <PhTrophy style="width:15px; height:15px; color:#16A34A;" weight="fill" />
                </div>
                <label class="form-label" style="margin-bottom:0; font-size:12px;">Taux de réussite</label>
              </div>
              <div style="position:relative;">
                <input v-model="form.success_rate" type="number" step="0.1" min="0" max="100" placeholder="0.0" class="form-input" style="padding-right:40px;" />
                <span style="position:absolute; right:12px; top:50%; transform:translateY(-50%); font-size:14px; font-weight:700; color:rgba(13,27,42,0.4);">%</span>
              </div>
              <div v-if="form.success_rate" style="margin-top:10px;">
                <div style="height:5px; background:rgba(13,27,42,0.08); border-radius:3px; overflow:hidden;">
                  <div :style="`width:${Math.min(form.success_rate,100)}%; height:100%; background:#22C55E; border-radius:3px; transition:width 0.4s;`" />
                </div>
              </div>
            </div>

            <!-- Taux de satisfaction -->
            <div style="background:#FAF7F2; border-radius:14px; padding:18px; border:1.5px solid rgba(201,168,76,0.2);">
              <div style="display:flex; align-items:center; gap:8px; margin-bottom:12px;">
                <div style="width:32px; height:32px; border-radius:9px; background:rgba(201,168,76,0.12); display:flex; align-items:center; justify-content:center;">
                  <PhStar style="width:15px; height:15px; color:#C9A84C;" weight="fill" />
                </div>
                <label class="form-label" style="margin-bottom:0; font-size:12px;">Taux de satisfaction</label>
              </div>
              <div style="position:relative;">
                <input v-model="form.satisfaction_rate" type="number" step="0.1" min="0" max="100" placeholder="0.0" class="form-input" style="padding-right:40px;" />
                <span style="position:absolute; right:12px; top:50%; transform:translateY(-50%); font-size:14px; font-weight:700; color:rgba(13,27,42,0.4);">%</span>
              </div>
              <div v-if="form.satisfaction_rate" style="margin-top:10px;">
                <div style="height:5px; background:rgba(13,27,42,0.08); border-radius:3px; overflow:hidden;">
                  <div :style="`width:${Math.min(form.satisfaction_rate,100)}%; height:100%; background:#C9A84C; border-radius:3px; transition:width 0.4s;`" />
                </div>
              </div>
            </div>

            <!-- Taux retour emploi -->
            <div style="background:#FAF7F2; border-radius:14px; padding:18px; border:1.5px solid rgba(59,130,246,0.15);">
              <div style="display:flex; align-items:center; gap:8px; margin-bottom:12px;">
                <div style="width:32px; height:32px; border-radius:9px; background:rgba(59,130,246,0.1); display:flex; align-items:center; justify-content:center;">
                  <PhBriefcase style="width:15px; height:15px; color:#3B82F6;" weight="fill" />
                </div>
                <label class="form-label" style="margin-bottom:0; font-size:12px;">Retour à l'emploi</label>
              </div>
              <div style="position:relative;">
                <input v-model="form.employment_rate" type="number" step="0.1" min="0" max="100" placeholder="0.0" class="form-input" style="padding-right:40px;" />
                <span style="position:absolute; right:12px; top:50%; transform:translateY(-50%); font-size:14px; font-weight:700; color:rgba(13,27,42,0.4);">%</span>
              </div>
              <div v-if="form.employment_rate" style="margin-top:10px;">
                <div style="height:5px; background:rgba(13,27,42,0.08); border-radius:3px; overflow:hidden;">
                  <div :style="`width:${Math.min(form.employment_rate,100)}%; height:100%; background:#3B82F6; border-radius:3px; transition:width 0.4s;`" />
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- ═══════════════════════════════════════ -->
        <!-- SECTION 7 : Options de publication     -->
        <!-- ═══════════════════════════════════════ -->
        <div class="form-section">
          <div class="section-header">
            <div>
              <h2 class="section-title">Options de publication</h2>
              <p class="section-sub">Visibilité et mise en avant</p>
            </div>
          </div>

          <div style="display:flex; flex-direction:column; gap:12px;">
            <label class="toggle-label">
              <span>
                <div style="font-size:14px; font-weight:700; color:#0D1B2A;">Publier la formation</div>
                <div style="font-size:12px; color:rgba(13,27,42,0.45);">La formation sera visible sur le site</div>
              </span>
              <div class="toggle" :class="{ active: form.is_published }" @click="form.is_published = !form.is_published">
                <div class="toggle-thumb" />
              </div>
            </label>
            <label class="toggle-label">
              <span>
                <div style="font-size:14px; font-weight:700; color:#0D1B2A;">Mettre en vedette</div>
                <div style="font-size:12px; color:rgba(13,27,42,0.45);">Affichée sur la page d'accueil</div>
              </span>
              <div class="toggle" :class="{ active: form.is_featured }" @click="form.is_featured = !form.is_featured">
                <div class="toggle-thumb" />
              </div>
            </label>
            <label class="toggle-label">
              <span>
                <div style="font-size:14px; font-weight:700; color:#0D1B2A;">Formation certifiante</div>
                <div style="font-size:12px; color:rgba(13,27,42,0.45);">Un badge certification sera affiché</div>
              </span>
              <div class="toggle" :class="{ active: form.has_certification }" @click="form.has_certification = !form.has_certification">
                <div class="toggle-thumb" />
              </div>
            </label>
          </div>
        </div>

        <!-- Boutons -->
        <div style="display:flex; justify-content:flex-end; gap:12px; padding-bottom:40px;">
          <Link :href="route('admin.formations.index')" style="display:inline-flex; align-items:center; gap:8px; padding:12px 24px; border-radius:12px; border:1.5px solid rgba(13,27,42,0.12); background:white; font-size:14px; font-weight:700; color:rgba(13,27,42,0.6); text-decoration:none; transition:all 0.2s;" class="cancel-btn">
            Annuler
          </Link>
          <button
            type="submit"
            :disabled="form.processing"
            style="display:inline-flex; align-items:center; gap:8px; padding:12px 28px; border-radius:12px; border:none; background:#C9A84C; color:#0D1B2A; font-size:14px; font-weight:800; cursor:pointer; font-family:inherit; transition:all 0.2s;"
            class="submit-btn"
          >
            <PhCircleNotch v-if="form.processing" style="width:16px; height:16px;" class="spin" />
            <PhFloppyDisk v-else style="width:16px; height:16px;" />
            {{ form.processing ? 'Enregistrement...' : (isEdit ? 'Mettre à jour' : 'Créer la formation') }}
          </button>
        </div>
      </form>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, computed, reactive, h, defineComponent } from 'vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import axios from 'axios'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import {
  PhArrowLeft, PhImage, PhPlus, PhMinus,
  PhCircleNotch, PhFloppyDisk, PhX, PhCheck,
  PhArrowCounterClockwise, PhStar, PhTrophy, PhBriefcase,
} from '@phosphor-icons/vue'

// ── Icône Sparkle via h() — pas de template string ──
const PhSparkleFill = defineComponent({
  name: 'PhSparkleFill',
  setup() {
    return () => h('svg', {
      xmlns: 'http://www.w3.org/2000/svg',
      viewBox: '0 0 256 256',
      fill: 'currentColor',
      style: 'width:1em; height:1em;',
    }, [
      h('path', {
        d: 'M197.58,106.34l-48-16a8,8,0,0,1-4.92-4.92l-16-48a8,8,0,0,0-15.16,0l-16,48a8,8,0,0,1-4.92,4.92l-48,16a8,8,0,0,0,0,15.16l48,16a8,8,0,0,1,4.92,4.92l16,48a8,8,0,0,0,15.16,0l16-48a8,8,0,0,1,4.92-4.92l48-16a8,8,0,0,0,0-15.16ZM56,56l8-24,8,24-24,8ZM200,200l-8,24-8-24,24-8Z',
      }),
    ])
  },
})

// ── Bouton IA via h() — pas de template string ──
const AiButton = defineComponent({
  name: 'AiButton',
  props: {
    field: { type: String, required: true },
    label: { type: String, required: true },
    ctx:   { type: Object, required: true },
  },
  emits: ['generate'],
  setup(props, { emit }) {
    const sparkleIcon = h('svg', {
      xmlns: 'http://www.w3.org/2000/svg',
      viewBox: '0 0 256 256',
      fill: 'currentColor',
      style: 'width:12px; height:12px; flex-shrink:0;',
    }, [
      h('path', {
        d: 'M197.58,106.34l-48-16a8,8,0,0,1-4.92-4.92l-16-48a8,8,0,0,0-15.16,0l-16,48a8,8,0,0,1-4.92,4.92l-48,16a8,8,0,0,0,0,15.16l48,16a8,8,0,0,1,4.92,4.92l16,48a8,8,0,0,0,15.16,0l16-48a8,8,0,0,1,4.92-4.92l48-16a8,8,0,0,0,0-15.16Z',
      }),
    ])

    return () => h('button', {
      type: 'button',
      title: 'Générer avec l\'IA Groq',
      class: 'ai-gen-btn',
      style: 'display:inline-flex; align-items:center; gap:5px; padding:5px 11px; border-radius:8px; border:1px solid rgba(201,168,76,0.3); background:rgba(201,168,76,0.08); color:#A07828; font-size:11px; font-weight:700; cursor:pointer; font-family:inherit; transition:all 0.2s; white-space:nowrap;',
      onClick: () => emit('generate', { field: props.field, label: props.label, ctx: props.ctx }),
    }, [
      sparkleIcon,
      ' Générer avec l\'IA',
    ])
  },
})

// ────────────────────────────────────────────────

const props = defineProps({
  formation:  Object,
  categories: Array,
})

const isEdit = computed(() => !!props.formation?.id)

// ── Listes dynamiques ──
const objectives    = ref(props.formation?.objectives   ?? [])
const prerequisites = ref(props.formation?.prerequisites ?? [])

// ── Previews images ──
const thumbnailPreview = ref(null)
const bannerPreview    = ref(null)

// ── Formulaire principal ──
const form = useForm({
  title:               props.formation?.title              ?? '',
  short_description:   props.formation?.short_description  ?? '',
  category_id:         props.formation?.category_id        ?? '',
  level:               props.formation?.level              ?? 'tous',
  duration:            props.formation?.duration           ?? '',
  price:               props.formation?.price              ?? '',
  price_promo:         props.formation?.price_promo        ?? '',
  content:             props.formation?.content            ?? '',
  is_published:        props.formation?.is_published       ?? false,
  is_featured:         props.formation?.is_featured        ?? false,
  has_certification:   props.formation?.has_certification  ?? false,
  thumbnail:           null,
  banner:              null,
  participant_profile: props.formation?.participant_profile ?? '',
  accessibility:       props.formation?.accessibility       ?? '',
  access_delay:        props.formation?.access_delay        ?? '',
  teaching_methods:    props.formation?.teaching_methods    ?? '',
  teaching_resources:  props.formation?.teaching_resources  ?? '',
  evaluation_methods:  props.formation?.evaluation_methods  ?? '',
  success_rate:        props.formation?.success_rate        ?? '',
  satisfaction_rate:   props.formation?.satisfaction_rate   ?? '',
  employment_rate:     props.formation?.employment_rate     ?? '',
})

// ── Contexte IA ──
const aiContext = computed(() => ({
  title:             form.title,
  short_description: form.short_description,
  category:          props.categories?.find(c => c.id == form.category_id)?.name ?? '',
  duration:          form.duration,
  level:             form.level,
}))

// ── Panneau IA ──
const aiPanel = reactive({
  visible:    false,
  loading:    false,
  field:      '',
  fieldLabel: '',
  result:     '',
  error:      '',
  ctx:        {},
})

function openAiPanel({ field, label, ctx }) {
  if (!form.title.trim()) {
    alert('Veuillez d\'abord saisir le titre de la formation avant de générer du contenu.')
    return
  }
  aiPanel.field      = field
  aiPanel.fieldLabel = label
  aiPanel.ctx        = ctx
  aiPanel.result     = ''
  aiPanel.error      = ''
  aiPanel.visible    = true
  generate()
}

async function generate() {
  aiPanel.loading = true
  aiPanel.result  = ''
  aiPanel.error   = ''

  try {
    const { data } = await axios.post(route('admin.ai.generate'), {
      field:             aiPanel.field,
      title:             aiPanel.ctx.title,
      category:          aiPanel.ctx.category,
      duration:          aiPanel.ctx.duration,
      level:             aiPanel.ctx.level,
      short_description: aiPanel.ctx.short_description,
    })

    if (data.error) {
      aiPanel.error = data.error
    } else {
      aiPanel.result = data.content
    }
  } catch (e) {
    // ← Affiche l'erreur réelle au lieu du message générique
    const status = e.response?.status
    const msg    = e.response?.data?.message
                ?? e.response?.data?.error
                ?? e.response?.data
                ?? e.message

    aiPanel.error = `[${status ?? '?'}] ${JSON.stringify(msg)}`
  } finally {
    aiPanel.loading = false
  }
}

function regenerate() {
  aiPanel.result = ''
  aiPanel.error  = ''
  generate()
}

function applyAiResult() {
  if (!aiPanel.result) return
  const field = aiPanel.field
  if (field in form) {
    form[field] = aiPanel.result
  }
  closeAiPanel()
}

function closeAiPanel() {
  aiPanel.visible = false
  aiPanel.result  = ''
  aiPanel.error   = ''
}

// ── Gestion fichiers ──
function handleFileChange(event, field) {
  const file = event.target.files[0]
  if (!file) return
  form[field] = file
  const url = URL.createObjectURL(file)
  if (field === 'thumbnail') thumbnailPreview.value = url
  else bannerPreview.value = url
}

function handleFileDrop(event, field) {
  const file = event.dataTransfer.files[0]
  if (!file || !file.type.startsWith('image/')) return
  form[field] = file
  const url = URL.createObjectURL(file)
  if (field === 'thumbnail') thumbnailPreview.value = url
  else bannerPreview.value = url
}

// ── Soumission — mécanique FormData conservée ──
function buildFormData(data) {
  const fd = new FormData()
  Object.entries(data).forEach(([key, value]) => {
    if (value === null || value === undefined) return
    if (value instanceof File) {
      fd.append(key, value)
    } else if (Array.isArray(value)) {
      value.forEach((item, i) => {
        if (typeof item === 'object' && item !== null) {
          Object.entries(item).forEach(([k, v]) => fd.append(`${key}[${i}][${k}]`, v ?? ''))
        } else {
          fd.append(`${key}[${i}]`, item ?? '')
        }
      })
    } else if (typeof value === 'boolean') {
      fd.append(key, value ? '1' : '0')
    } else {
      fd.append(key, value)
    }
  })
  return fd
}

function submit() {
  form.objectives    = objectives.value.filter(o => o.trim() !== '')
  form.prerequisites = prerequisites.value.filter(p => p.trim() !== '')

  if (isEdit.value) {
    form
      .transform((data) => {
        const fd = buildFormData(data)
        fd.append('_method', 'PUT')
        return fd
      })
      .post(route('admin.formations.update', props.formation.id))
  } else {
    form
      .transform((data) => buildFormData(data))
      .post(route('admin.formations.store'))
  }
}
</script>

<style scoped>
/* ── Layout sections ── */
.form-section { background:white; border-radius:20px; padding:32px; box-shadow:0 1px 8px rgba(13,27,42,0.06); }
.section-header { display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:24px; padding-bottom:16px; border-bottom:1.5px solid rgba(13,27,42,0.06); }
.section-title { font-size:16px; font-weight:800; color:#0D1B2A; margin:0 0 3px; }
.section-sub { font-size:12px; color:rgba(13,27,42,0.4); margin:0; }
.section-badge { display:inline-flex; align-items:center; background:rgba(201,168,76,0.1); border:1px solid rgba(201,168,76,0.25); color:#A07828; font-size:11px; font-weight:700; padding:4px 10px; border-radius:100px; white-space:nowrap; flex-shrink:0; }

/* ── Formulaire ── */
.form-label { display:block; font-size:13px; font-weight:700; color:#0D1B2A; margin-bottom:8px; }
.required { color:#C9A84C; }
.form-input { width:100%; padding:12px 14px; border:1.5px solid rgba(13,27,42,0.12); border-radius:12px; font-size:14px; color:#0D1B2A; outline:none; font-family:inherit; background:#FAF7F2; transition:border-color 0.2s; box-sizing:border-box; }
.form-input:focus { border-color:rgba(201,168,76,0.6); background:white; }
.form-input.error { border-color:#EF4444; }
.form-error { font-size:12px; color:#EF4444; margin-top:5px; }
.add-item-btn { display:inline-flex; align-items:center; gap:6px; font-size:13px; font-weight:600; color:#C9A84C; background:rgba(201,168,76,0.08); border:1px dashed rgba(201,168,76,0.3); padding:8px 16px; border-radius:10px; cursor:pointer; transition:all 0.2s; }
.add-item-btn:hover { background:rgba(201,168,76,0.14); }

/* ── Upload ── */
.upload-zone { border:2px dashed rgba(13,27,42,0.15); border-radius:14px; overflow:hidden; cursor:pointer; transition:border-color 0.2s; min-height:80px; }
.upload-zone:hover { border-color:rgba(201,168,76,0.4); background:rgba(201,168,76,0.03); }

/* ── Toggles ── */
.toggle-label { display:flex; align-items:center; justify-content:space-between; padding:14px 16px; background:#FAF7F2; border-radius:12px; cursor:pointer; border:1px solid transparent; transition:border-color 0.2s; }
.toggle-label:hover { border-color:rgba(201,168,76,0.15); }
.toggle { width:44px; height:24px; border-radius:100px; background:rgba(13,27,42,0.15); position:relative; transition:background 0.2s; flex-shrink:0; }
.toggle.active { background:#C9A84C; }
.toggle-thumb { width:18px; height:18px; border-radius:50%; background:white; position:absolute; top:3px; left:3px; transition:transform 0.2s; box-shadow:0 1px 4px rgba(0,0,0,0.2); }
.toggle.active .toggle-thumb { transform:translateX(20px); }

/* ── Boutons ── */
.admin-icon-btn { width:32px; height:32px; border-radius:8px; border:1.5px solid rgba(13,27,42,0.1); background:white; display:inline-flex; align-items:center; justify-content:center; color:rgba(13,27,42,0.5); cursor:pointer; transition:all 0.2s; }
.admin-icon-btn-danger:hover { background:#FEE2E2 !important; color:#EF4444 !important; border-color:#FCA5A5 !important; }
.back-btn:hover { background:#FAF7F2; color:#0D1B2A !important; }
.submit-btn:hover:not(:disabled) { background:#E2C97E !important; transform:translateY(-1px); box-shadow:0 6px 20px rgba(201,168,76,0.35); }
.submit-btn:disabled { opacity:0.65; cursor:not-allowed; }
.cancel-btn:hover { background:#FAF7F2; }
.ai-gen-btn:hover { background:rgba(201,168,76,0.15) !important; border-color:rgba(201,168,76,0.4) !important; }
.ai-apply-btn:hover { background:#E2C97E !important; transform:translateY(-1px); }
.ai-regen-btn:hover { background:#FAF7F2 !important; }
.ai-close-btn:hover { background:#FAF7F2 !important; color:#0D1B2A !important; }

/* ── Panneau IA ── */
.ai-panel-enter-active { transition:all 0.35s cubic-bezier(0.16,1,0.3,1); }
.ai-panel-leave-active { transition:all 0.2s ease; }
.ai-panel-enter-from, .ai-panel-leave-to { opacity:0; transform:translateY(16px) scale(0.97); }

/* ── Dots chargement IA ── */
.ai-dot { width:8px; height:8px; border-radius:50%; background:#C9A84C; animation:ai-bounce 0.8s ease-in-out infinite; }
@keyframes ai-bounce { 0%,80%,100%{transform:scale(0.6); opacity:0.4} 40%{transform:scale(1); opacity:1} }

/* ── Divers ── */
.spin { animation:spin 1s linear infinite; }
@keyframes spin { to { transform:rotate(360deg); } }
</style>