<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<style>

/* ═══════════════════════════════════════════════
   RESET & BASE
═══════════════════════════════════════════════ */
* { margin:0; padding:0; box-sizing:border-box; }
html, body {
  font-family: 'DejaVu Sans', sans-serif;
  font-size: 10.5px;
  color: #0D1B2A;
  background: #FFFFFF;
  line-height: 1.55;
}

/* ═══════════════════════════════════════════════
   COVER HEADER — pleine largeur, impactant
═══════════════════════════════════════════════ */
.cover {
  background: #0D1B2A;
  padding: 0;
  position: relative;
}

/* Bande or top */
.cover-stripe-top {
  height: 4px;
  background: #C9A84C;
  width: 100%;
}

/* Zone logo + titre */
.cover-body {
  padding: 28px 36px 0 36px;
}
.cover-table { width: 100%; }
.cover-table td { vertical-align: middle; }

/* Logo */
.logo-wrap { white-space: nowrap; }
.logo-circle {
  display: inline-block;
  width: 44px; height: 44px;
  background: #C9A84C;
  border-radius: 12px;
  text-align: center;
  line-height: 44px;
  font-size: 24px;
  font-weight: 900;
  color: #0D1B2A;
  vertical-align: middle;
}
.logo-text-wrap {
  display: inline-block;
  vertical-align: middle;
  margin-left: 11px;
}
.logo-main {
  color: #FFFFFF;
  font-size: 18px;
  font-weight: 800;
  display: block;
  line-height: 1.1;
  letter-spacing: -0.3px;
}
.logo-sub {
  color: #C9A84C;
  font-size: 8.5px;
  letter-spacing: 3px;
  text-transform: uppercase;
  display: block;
  margin-top: 1px;
}

/* Méta droite */
.cover-meta { text-align: right; }
.cover-meta-label {
  font-size: 8.5px;
  color: rgba(255,255,255,0.35);
  text-transform: uppercase;
  letter-spacing: 2px;
  display: block;
  margin-bottom: 4px;
}
.cover-meta-date {
  font-size: 11px;
  color: rgba(255,255,255,0.6);
}

/* Titre de la formation en grand */
.cover-title-block {
  padding: 28px 36px 0 36px;
}
.cover-category {
  display: inline-block;
  border: 1px solid rgba(201,168,76,0.5);
  background: rgba(201,168,76,0.12);
  color: #C9A84C;
  font-size: 9px;
  font-weight: 700;
  padding: 4px 13px;
  border-radius: 100px;
  text-transform: uppercase;
  letter-spacing: 0.12em;
  margin-bottom: 14px;
}
.cover-formation-title {
  font-size: 30px;
  font-weight: 900;
  color: #FFFFFF;
  line-height: 1.1;
  letter-spacing: -0.8px;
  margin-bottom: 12px;
}
.cover-formation-desc {
  font-size: 12px;
  color: rgba(255,255,255,0.5);
  line-height: 1.7;
  max-width: 500px;
  margin-bottom: 22px;
}

/* Pills meta cover */
.cover-pills { margin-bottom: 0; }
.cpill {
  display: inline-block;
  background: rgba(255,255,255,0.08);
  border: 1px solid rgba(255,255,255,0.12);
  color: rgba(255,255,255,0.7);
  font-size: 9.5px;
  font-weight: 700;
  padding: 5px 13px;
  border-radius: 6px;
  margin-right: 7px;
  margin-bottom: 6px;
}
.cpill-gold {
  display: inline-block;
  background: rgba(201,168,76,0.2);
  border: 1px solid rgba(201,168,76,0.45);
  color: #C9A84C;
  font-size: 9.5px;
  font-weight: 700;
  padding: 5px 13px;
  border-radius: 6px;
  margin-right: 7px;
  margin-bottom: 6px;
}

/* Bande de séparation cover/corps */
.cover-divider {
  height: 28px;
  background: #0D1B2A;
}
.cover-divider-line {
  height: 3px;
  background: #C9A84C;
  margin: 0 36px;
}
.cover-divider-bottom {
  height: 18px;
  background: #0D1B2A;
  border-bottom-left-radius: 0;
  border-bottom-right-radius: 0;
}
/* Vague décorative bas du cover — simulée avec une bordure */
.cover-foot {
  height: 20px;
  background: #FFFFFF;
  border-top: 20px solid #0D1B2A;
}

/* ═══════════════════════════════════════════════
   LAYOUT PRINCIPAL — 2 COLONNES
═══════════════════════════════════════════════ */
.main-wrap { padding: 24px 36px; }
.main-table { width: 100%; }
.col-content { width: 63%; vertical-align: top; padding-right: 22px; }
.col-sidebar  { width: 37%; vertical-align: top; }

/* ═══════════════════════════════════════════════
   SECTIONS — COLONNE PRINCIPALE
═══════════════════════════════════════════════ */
.sec { margin-bottom: 22px; }

/* Titre de section */
.sec-head {
  margin-bottom: 12px;
  padding-bottom: 0;
}
.sec-head-table { width: 100%; border-collapse: collapse; }
.sec-head-bar {
  width: 4px;
  background: #C9A84C;
  border-radius: 2px;
  padding: 0;
}
.sec-head-line-cell {
  padding-left: 10px;
  vertical-align: middle;
  border-bottom: 1px solid #EDE9DF;
  padding-bottom: 8px;
}
.sec-title {
  font-size: 10px;
  font-weight: 900;
  color: #0D1B2A;
  text-transform: uppercase;
  letter-spacing: 0.12em;
}
.sec-title-icon {
  display: inline-block;
  width: 20px; height: 20px;
  border-radius: 6px;
  text-align: center;
  line-height: 20px;
  font-size: 11px;
  margin-right: 8px;
  vertical-align: middle;
}

/* ── Bloc texte standard ── */
.text-block {
  font-size: 11px;
  color: rgba(13,27,42,0.68);
  line-height: 1.7;
  padding: 12px 14px;
  background: #FDFCF9;
  border-radius: 8px;
  border: 1px solid #EDE9DF;
}

/* ── Objectifs ── */
.obj-table { width: 100%; border-collapse: collapse; }
.obj-td { width: 50%; vertical-align: top; padding: 0 5px 7px 0; }
.obj-td:nth-child(even) { padding-right: 0; }
.obj-item {
  background: #FDFCF9;
  border-left: 3px solid #C9A84C;
  border-radius: 0 7px 7px 0;
  padding: 8px 11px;
  font-size: 10.5px;
  color: rgba(13,27,42,0.72);
  line-height: 1.5;
  min-height: 32px;
}
.obj-check {
  color: #C9A84C;
  font-weight: 900;
  margin-right: 5px;
}

/* ── Prérequis ── */
.prereq-item {
  padding: 6px 0;
  border-bottom: 1px solid rgba(13,27,42,0.05);
  font-size: 11px;
  color: rgba(13,27,42,0.68);
}
.prereq-arrow { color: #C9A84C; font-weight: 900; margin-right: 8px; }

/* ── Programme ── */
.prog-item {
  padding: 9px 0;
  border-bottom: 1px solid rgba(13,27,42,0.05);
}
.prog-table { width: 100%; }
.prog-num-col { width: 30px; vertical-align: top; padding-top: 1px; }
.prog-num {
  width: 24px; height: 24px;
  background: #0D1B2A;
  border-radius: 7px;
  text-align: center;
  line-height: 24px;
  font-size: 9px;
  font-weight: 900;
  color: #FFFFFF;
  display: block;
}
.prog-t { font-size: 11px; font-weight: 700; color: #0D1B2A; line-height: 1.4; }
.prog-d { font-size: 10px; color: rgba(13,27,42,0.48); margin-top: 2px; line-height: 1.5; }

/* ── Indicateurs — 3 cartes ── */
.rates-table { width: 100%; border-collapse: collapse; }
.rate-td { vertical-align: top; padding-right: 8px; }
.rate-td:last-child { padding-right: 0; }

.rate-card {
  border-radius: 10px;
  padding: 13px 11px;
  text-align: center;
}
.rc-green { background: #F0FDF4; border: 1.5px solid rgba(34,197,94,0.25); }
.rc-gold  { background: #FFFBF0; border: 1.5px solid rgba(201,168,76,0.3); }
.rc-blue  { background: #EFF6FF; border: 1.5px solid rgba(59,130,246,0.25); }

.rate-icon {
  width: 28px; height: 28px;
  border-radius: 8px;
  text-align: center;
  line-height: 28px;
  font-size: 14px;
  margin: 0 auto 7px auto;
  display: block;
}
.ri-green { background: rgba(34,197,94,0.15); color: #16A34A; }
.ri-gold  { background: rgba(201,168,76,0.15); color: #C9A84C; }
.ri-blue  { background: rgba(59,130,246,0.15); color: #3B82F6; }

.rate-pct { font-size: 24px; font-weight: 900; line-height: 1; margin-bottom: 4px; }
.rp-green { color: #16A34A; }
.rp-gold  { color: #C9A84C; }
.rp-blue  { color: #2563EB; }

.rate-lbl {
  font-size: 8.5px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.08em;
  line-height: 1.3;
  color: rgba(13,27,42,0.45);
  margin-bottom: 8px;
}

.bar-bg { width: 100%; height: 5px; background: rgba(13,27,42,0.07); border-radius: 3px; }
.bar-g  { height: 5px; background: #22C55E; border-radius: 3px; }
.bar-go { height: 5px; background: #C9A84C; border-radius: 3px; }
.bar-b  { height: 5px; background: #3B82F6; border-radius: 3px; }

/* ── Sessions ── */
.sess-card {
  border-radius: 9px;
  border: 1px solid #EDE9DF;
  margin-bottom: 8px;
  overflow: hidden;
}
.sess-head {
  padding: 9px 13px 8px;
  background: #FDFCF9;
  border-bottom: 1px solid #EDE9DF;
}
.sess-head-table { width: 100%; }
.sess-date { font-size: 12px; font-weight: 800; color: #0D1B2A; }
.sess-badge {
  display: inline-block;
  font-size: 8.5px;
  font-weight: 800;
  padding: 2px 10px;
  border-radius: 100px;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}
.sb-ouvert  { background: rgba(34,197,94,0.1);  color: #16A34A; border: 1px solid rgba(34,197,94,0.2); }
.sb-bientot { background: rgba(245,158,11,0.1); color: #D97706; border: 1px solid rgba(245,158,11,0.2); }
.sb-complet { background: rgba(239,68,68,0.1);  color: #DC2626; border: 1px solid rgba(239,68,68,0.2); }
.sb-encours { background: rgba(59,130,246,0.1); color: #2563EB; border: 1px solid rgba(59,130,246,0.2); }

.sess-body { padding: 8px 13px 9px; }
.sess-meta { font-size: 9.5px; color: rgba(13,27,42,0.5); }
.sess-tag {
  display: inline-block;
  background: #F4F0E8;
  border-radius: 5px;
  padding: 2px 8px;
  margin-right: 5px;
  margin-bottom: 3px;
  font-size: 9.5px;
  color: rgba(13,27,42,0.6);
  font-weight: 600;
}
.sess-price { font-size: 14px; font-weight: 900; color: #C9A84C; }

/* ═══════════════════════════════════════════════
   SIDEBAR — COLONNE DROITE
═══════════════════════════════════════════════ */

/* Card prix — dark premium */
.price-card {
  background: #0D1B2A;
  border-radius: 12px;
  padding: 20px;
  text-align: center;
  margin-bottom: 14px;
  border-bottom: 3px solid #C9A84C;
}
.price-tag {
  font-size: 8.5px;
  color: rgba(255,255,255,0.35);
  text-transform: uppercase;
  letter-spacing: 2px;
  margin-bottom: 8px;
}
.price-amount {
  font-size: 34px;
  font-weight: 900;
  color: #C9A84C;
  line-height: 1;
  letter-spacing: -1px;
}
.price-currency { font-size: 18px; }
.price-old {
  font-size: 12px;
  color: rgba(255,255,255,0.3);
  text-decoration: line-through;
  margin-top: 4px;
}
.price-promo-badge {
  display: inline-block;
  background: rgba(201,168,76,0.2);
  border: 1px solid rgba(201,168,76,0.4);
  color: #C9A84C;
  font-size: 9px;
  font-weight: 700;
  padding: 3px 11px;
  border-radius: 100px;
  margin-top: 6px;
}

/* Card infos — style carte de visite */
.info-card {
  background: #FDFCF9;
  border: 1px solid #EDE9DF;
  border-radius: 11px;
  margin-bottom: 12px;
  overflow: hidden;
}
.info-card-head {
  background: #0D1B2A;
  padding: 8px 14px;
}
.info-card-head-title {
  font-size: 9px;
  font-weight: 800;
  color: #C9A84C;
  text-transform: uppercase;
  letter-spacing: 0.15em;
}
.info-card-body { padding: 0; }
.info-row {
  padding: 9px 14px;
  border-bottom: 1px solid #EDE9DF;
}
.info-row:last-child { border-bottom: none; }
.info-row-table { width: 100%; }
.info-key {
  font-size: 9px;
  color: rgba(13,27,42,0.38);
  text-transform: uppercase;
  letter-spacing: 0.1em;
  font-weight: 700;
  vertical-align: middle;
  width: 40%;
}
.info-val {
  font-size: 10.5px;
  font-weight: 700;
  color: #0D1B2A;
  text-align: right;
  vertical-align: middle;
}
.info-val-gold { color: #C9A84C; }
.info-val-small {
  font-size: 9.5px;
  font-weight: 400;
  color: rgba(13,27,42,0.55);
  text-align: right;
  vertical-align: middle;
}

/* Card contact */
.contact-card {
  background: #FDFCF9;
  border: 1px solid #EDE9DF;
  border-radius: 11px;
  margin-bottom: 12px;
  overflow: hidden;
}
.contact-head {
  background: #0D1B2A;
  padding: 8px 14px;
}
.contact-head-title {
  font-size: 9px;
  font-weight: 800;
  color: #C9A84C;
  text-transform: uppercase;
  letter-spacing: 0.15em;
}
.contact-body { padding: 11px 14px; }
.contact-line {
  margin-bottom: 8px;
}
.contact-line:last-child { margin-bottom: 0; }
.contact-line-label {
  font-size: 8.5px;
  color: rgba(13,27,42,0.38);
  text-transform: uppercase;
  letter-spacing: 0.08em;
  font-weight: 700;
  margin-bottom: 1px;
}
.contact-line-value {
  font-size: 10.5px;
  color: #0D1B2A;
  font-weight: 600;
}

/* URL card */
.url-card {
  background: #0D1B2A;
  border-radius: 11px;
  padding: 14px;
  text-align: center;
  margin-bottom: 12px;
}
.url-label {
  font-size: 8.5px;
  color: rgba(255,255,255,0.35);
  text-transform: uppercase;
  letter-spacing: 2px;
  margin-bottom: 6px;
}
.url-separator {
  width: 24px;
  height: 1px;
  background: rgba(201,168,76,0.5);
  margin: 0 auto 6px auto;
}
.url-val {
  font-size: 9.5px;
  color: #C9A84C;
  font-weight: 700;
  word-break: break-all;
  line-height: 1.5;
}

/* Note légale */
.legal-note {
  border: 1px dashed rgba(13,27,42,0.12);
  border-radius: 8px;
  padding: 10px 12px;
  text-align: center;
}
.legal-note-text {
  font-size: 8.5px;
  color: rgba(13,27,42,0.35);
  line-height: 1.6;
  font-style: italic;
}

/* Certification badge sidebar */
.certif-badge {
  background: rgba(201,168,76,0.1);
  border: 1px solid rgba(201,168,76,0.3);
  border-left: 3px solid #C9A84C;
  border-radius: 0 8px 8px 0;
  padding: 10px 12px;
  margin-bottom: 12px;
  text-align: center;
}
.certif-badge-text {
  font-size: 10px;
  font-weight: 800;
  color: #8B6914;
}

/* ═══════════════════════════════════════════════
   FOOTER
═══════════════════════════════════════════════ */
.footer-stripe { height: 3px; background: #C9A84C; }
.footer-body {
  background: #0D1B2A;
  padding: 13px 36px;
}
.footer-table { width: 100%; }
.footer-left { font-size: 9.5px; color: rgba(255,255,255,0.35); vertical-align: middle; }
.footer-center { text-align: center; vertical-align: middle; }
.footer-right { text-align: right; font-size: 9.5px; color: rgba(255,255,255,0.35); vertical-align: middle; }
.footer-logo-sm { font-size: 11px; font-weight: 900; color: #C9A84C; }
.footer-logo-sub { font-size: 8px; color: rgba(255,255,255,0.25); text-transform: uppercase; letter-spacing: 1.5px; }

/* Séparateur inter-sections */
.spacer-8  { height: 8px; }
.spacer-14 { height: 14px; }

</style>
</head>
<body>

{{-- ═══════════════════════════════════════════════════
     COVER HEADER
═══════════════════════════════════════════════════ --}}
<div class="cover">
  <div class="cover-stripe-top"></div>

  {{-- Logo + date --}}
  <div class="cover-body">
    <table class="cover-table">
      <tr>
        <td class="logo-wrap">
          <span class="logo-circle">L</span>
          <span class="logo-text-wrap">
            <span class="logo-main">Luxy Formation</span>
            <span class="logo-sub">Centre de formation professionnelle</span>
          </span>
        </td>
        <td class="cover-meta">
          <span class="cover-meta-label">Fiche de formation</span>
          <span class="cover-meta-date">{{ now()->translatedFormat('d F Y') }}</span>
        </td>
      </tr>
    </table>
  </div>

  {{-- Titre formation --}}
  <div class="cover-title-block">
    @if($formation->category)
      <div class="cover-category">{{ $formation->category->name }}</div>
    @endif

    <div class="cover-formation-title">{{ $formation->title }}</div>

    @if($formation->short_description)
      <div class="cover-formation-desc">{{ $formation->short_description }}</div>
    @endif

    <div class="cover-pills">
      @if($formation->level)
        <span class="cpill">{{ match($formation->level) {
          'debutant'      => '● Niveau Débutant',
          'intermediaire' => '● Niveau Intermédiaire',
          'avance'        => '● Niveau Avancé',
          default         => '● Tous niveaux',
        } }}</span>
      @endif

      @if($formation->duration)
        <span class="cpill">⏱ {{ $formation->duration }}</span>
      @endif

      @if($formation->has_certification)
        <span class="cpill-gold">★ Formation certifiante</span>
      @endif

      @if($formation->current_price)
        <span class="cpill-gold">{{ number_format($formation->current_price, 0, ',', ' ') }} €</span>
      @endif
    </div>
  </div>

  <div class="spacer-14"></div>
  <div class="cover-divider-line"></div>
  <div class="cover-divider-bottom"></div>
</div>

{{-- Transition visuelle cover → corps --}}
<div style="height:3px; background:#FFFFFF; border-top:3px solid #0D1B2A;"></div>

{{-- ═══════════════════════════════════════════════════
     CORPS PRINCIPAL
═══════════════════════════════════════════════════ --}}
<div class="main-wrap">
  <table class="main-table">
    <tr>

      {{-- ══════════════════════════
           COLONNE CONTENU
      ══════════════════════════ --}}
      <td class="col-content">

        {{-- ① Objectifs d'apprentissage --}}
        @if(!empty($formation->objectives))
        <div class="sec">
          <div class="sec-head">
            <table class="sec-head-table">
              <tr>
                <td class="sec-head-bar" style="width:4px;">&nbsp;</td>
                <td class="sec-head-line-cell">
                  <span class="sec-title">Objectifs d'apprentissage</span>
                </td>
              </tr>
            </table>
          </div>
          <table class="obj-table">
            <tr>
              @foreach($formation->objectives as $i => $obj)
                <td class="obj-td">
                  <div class="obj-item"><span class="obj-check">✓</span>{{ $obj }}</div>
                </td>
                @if($i % 2 === 1 && !$loop->last)
                  </tr><tr>
                @endif
              @endforeach
              @if(count($formation->objectives) % 2 !== 0)
                <td class="obj-td"></td>
              @endif
            </tr>
          </table>
        </div>
        @endif

        {{-- ② Profil des participants --}}
        @if($formation->participant_profile)
        <div class="sec">
          <div class="sec-head">
            <table class="sec-head-table">
              <tr>
                <td class="sec-head-bar">&nbsp;</td>
                <td class="sec-head-line-cell">
                  <span class="sec-title">Profil des participants</span>
                </td>
              </tr>
            </table>
          </div>
          <div class="text-block">{{ $formation->participant_profile }}</div>
        </div>
        @endif

        {{-- ③ Prérequis --}}
        @if(!empty($formation->prerequisites))
        <div class="sec">
          <div class="sec-head">
            <table class="sec-head-table">
              <tr>
                <td class="sec-head-bar">&nbsp;</td>
                <td class="sec-head-line-cell">
                  <span class="sec-title">Prérequis</span>
                </td>
              </tr>
            </table>
          </div>
          @foreach($formation->prerequisites as $pre)
            <div class="prereq-item">
              <span class="prereq-arrow">›</span>{{ $pre }}
            </div>
          @endforeach
        </div>
        @endif

        {{-- ④ Indicateurs de performance --}}
        @if($formation->success_rate || $formation->satisfaction_rate || $formation->employment_rate)
        <div class="sec">
          <div class="sec-head">
            <table class="sec-head-table">
              <tr>
                <td class="sec-head-bar">&nbsp;</td>
                <td class="sec-head-line-cell">
                  <span class="sec-title">Indicateurs de performance</span>
                </td>
              </tr>
            </table>
          </div>
          <table class="rates-table">
            <tr>
              @if($formation->success_rate)
              <td class="rate-td">
                <div class="rate-card rc-green">
                  <!-- <span class="rate-icon ri-green">★</span> -->
                  <div class="rate-pct rp-green">{{ number_format($formation->success_rate, 1, ',', '') }}%</div>
                  <div class="rate-lbl">Taux de réussite</div>
                  <div class="bar-bg">
                    <div class="bar-g" style="width:{{ min($formation->success_rate, 100) }}%;"></div>
                  </div>
                </div>
              </td>
              @endif

              @if($formation->satisfaction_rate)
              <td class="rate-td">
                <div class="rate-card rc-gold">
                  <!-- <span class="rate-icon ri-gold">♥</span> -->
                  <div class="rate-pct rp-gold">{{ number_format($formation->satisfaction_rate, 1, ',', '') }}%</div>
                  <div class="rate-lbl">Taux de satisfaction</div>
                  <div class="bar-bg">
                    <div class="bar-go" style="width:{{ min($formation->satisfaction_rate, 100) }}%;"></div>
                  </div>
                </div>
              </td>
              @endif

              @if($formation->employment_rate)
              <td class="rate-td" style="padding-right:0;">
                <div class="rate-card rc-blue">
                  <!-- <span class="rate-icon ri-blue">▲</span> -->
                  <div class="rate-pct rp-blue">{{ number_format($formation->employment_rate, 1, ',', '') }}%</div>
                  <div class="rate-lbl">Retour à l'emploi</div>
                  <div class="bar-bg">
                    <div class="bar-b" style="width:{{ min($formation->employment_rate, 100) }}%;"></div>
                  </div>
                </div>
              </td>
              @endif
            </tr>
          </table>
        </div>
        @endif

        {{-- ⑤ Modalités pédagogiques --}}
        @if($formation->teaching_methods)
        <div class="sec">
          <div class="sec-head">
            <table class="sec-head-table">
              <tr>
                <td class="sec-head-bar">&nbsp;</td>
                <td class="sec-head-line-cell">
                  <span class="sec-title">Modalités pédagogiques</span>
                </td>
              </tr>
            </table>
          </div>
          <div class="text-block">{{ $formation->teaching_methods }}</div>
        </div>
        @endif

        {{-- ⑥ Moyens et supports --}}
        @if($formation->teaching_resources)
        <div class="sec">
          <div class="sec-head">
            <table class="sec-head-table">
              <tr>
                <td class="sec-head-bar">&nbsp;</td>
                <td class="sec-head-line-cell">
                  <span class="sec-title">Moyens et supports pédagogiques</span>
                </td>
              </tr>
            </table>
          </div>
          <div class="text-block">{{ $formation->teaching_resources }}</div>
        </div>
        @endif

        {{-- ⑦ Évaluation et suivi --}}
        @if($formation->evaluation_methods)
        <div class="sec">
          <div class="sec-head">
            <table class="sec-head-table">
              <tr>
                <td class="sec-head-bar">&nbsp;</td>
                <td class="sec-head-line-cell">
                  <span class="sec-title">Modalités d'évaluation et de suivi</span>
                </td>
              </tr>
            </table>
          </div>
          <div class="text-block">{{ $formation->evaluation_methods }}</div>
        </div>
        @endif

        {{-- ⑧ Accessibilité --}}
        @if($formation->accessibility)
        <div class="sec">
          <div class="sec-head">
            <table class="sec-head-table">
              <tr>
                <td class="sec-head-bar">&nbsp;</td>
                <td class="sec-head-line-cell">
                  <span class="sec-title">Accessibilité</span>
                </td>
              </tr>
            </table>
          </div>
          <div class="text-block">{{ $formation->accessibility }}</div>
        </div>
        @endif

        {{-- ⑨ Délai d'accès --}}
        @if($formation->access_delay)
        <div class="sec">
          <div class="sec-head">
            <table class="sec-head-table">
              <tr>
                <td class="sec-head-bar">&nbsp;</td>
                <td class="sec-head-line-cell">
                  <span class="sec-title">Délai d'accès</span>
                </td>
              </tr>
            </table>
          </div>
          <div class="text-block">{{ $formation->access_delay }}</div>
        </div>
        @endif

        {{-- ⑩ Programme --}}
        @if(!empty($formation->program))
        <div class="sec">
          <div class="sec-head">
            <table class="sec-head-table">
              <tr>
                <td class="sec-head-bar">&nbsp;</td>
                <td class="sec-head-line-cell">
                  <span class="sec-title">Programme de la formation</span>
                </td>
              </tr>
            </table>
          </div>
          @foreach($formation->program as $i => $module)
            <div class="prog-item">
              <table class="prog-table">
                <tr>
                  <td class="prog-num-col">
                    <span class="prog-num">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                  </td>
                  <td style="vertical-align:top;">
                    <div class="prog-t">{{ is_array($module) ? ($module['title'] ?? '') : $module }}</div>
                    @if(is_array($module) && !empty($module['description']))
                      <div class="prog-d">{{ $module['description'] }}</div>
                    @endif
                  </td>
                </tr>
              </table>
            </div>
          @endforeach
        </div>
        @endif

        {{-- ⑪ Sessions --}}
        @if($formation->sessions->count())
        <div class="sec">
          <div class="sec-head">
            <table class="sec-head-table">
              <tr>
                <td class="sec-head-bar">&nbsp;</td>
                <td class="sec-head-line-cell">
                  <span class="sec-title">Prochaines sessions</span>
                </td>
              </tr>
            </table>
          </div>

          @foreach($formation->sessions as $session)
            @php
              $sbClass = match($session->status) {
                'ouvert'   => 'sb-ouvert',
                'bientot'  => 'sb-bientot',
                'complet'  => 'sb-complet',
                'en_cours' => 'sb-encours',
                default    => 'sb-ouvert',
              };
              $sbLabel = match($session->status) {
                'ouvert'   => 'Ouvert',
                'bientot'  => 'Bientôt',
                'complet'  => 'Complet',
                'en_cours' => 'En cours',
                default    => $session->status,
              };
            @endphp
            <div class="sess-card">
              <div class="sess-head">
                <table class="sess-head-table">
                  <tr>
                    <td style="vertical-align:middle;">
                      <span class="sess-date">
                        {{ $session->formatted_start_date }}
                        @if($session->formatted_end_date)
                          &nbsp;→&nbsp;{{ $session->formatted_end_date }}
                        @endif
                        @if($session->label)
                          &nbsp;—&nbsp;<span style="font-weight:500; color:rgba(13,27,42,0.5);">{{ $session->label }}</span>
                        @endif
                      </span>
                    </td>
                    <td style="text-align:right; vertical-align:middle; white-space:nowrap;">
                      <span class="sess-badge {{ $sbClass }}">{{ $sbLabel }}</span>
                    </td>
                  </tr>
                </table>
              </div>
              <div class="sess-body">
                <table style="width:100%;">
                  <tr>
                    <td style="vertical-align:middle;">
                      @if($session->modality_label)
                        <span class="sess-tag">{{ $session->modality_label }}</span>
                      @endif
                      @if($session->schedule)
                        <span class="sess-tag">{{ $session->schedule }}</span>
                      @endif
                      @if($session->location)
                        <span class="sess-tag">{{ $session->location }}</span>
                      @endif
                      @if($session->seats_available !== null)
                        <span class="sess-tag">{{ $session->seats_available }} place(s)</span>
                      @endif
                    </td>
                    @if($session->price_override)
                    <td style="text-align:right; vertical-align:middle; white-space:nowrap;">
                      <span class="sess-price">{{ number_format($session->price_override, 0, ',', ' ') }} €</span>
                    </td>
                    @endif
                  </tr>
                </table>
              </div>
            </div>
          @endforeach
        </div>
        @endif

      </td>{{-- /col-content --}}

      {{-- ══════════════════════════
           SIDEBAR
      ══════════════════════════ --}}
      <td class="col-sidebar">

        {{-- Badge certification --}}
        @if($formation->has_certification)
        <div class="certif-badge">
          <div class="certif-badge-text">★ Formation certifiante</div>
        </div>
        @endif

        {{-- Prix --}}
        @if($formation->current_price)
        <div class="price-card">
          <div class="price-tag">Tarif de la formation</div>
          <div class="price-amount">
            {{ number_format($formation->current_price, 0, ',', ' ') }}
            <span class="price-currency"> €</span>
          </div>
          @if($formation->is_on_promo)
            <div class="price-old">au lieu de {{ number_format($formation->price, 0, ',', ' ') }} €</div>
            <div><span class="price-promo-badge">Tarif promotionnel</span></div>
          @endif
        </div>
        @endif

        {{-- Infos formation --}}
        <div class="info-card">
          <div class="info-card-head">
            <div class="info-card-head-title">Infos formation</div>
          </div>
          <div class="info-card-body">

            @if($formation->level)
            <div class="info-row">
              <table class="info-row-table">
                <tr>
                  <td class="info-key">Niveau</td>
                  <td class="info-val">{{ match($formation->level) {
                    'debutant'      => 'Débutant',
                    'intermediaire' => 'Intermédiaire',
                    'avance'        => 'Avancé',
                    default         => 'Tous niveaux',
                  } }}</td>
                </tr>
              </table>
            </div>
            @endif

            @if($formation->duration)
            <div class="info-row">
              <table class="info-row-table">
                <tr>
                  <td class="info-key">Durée</td>
                  <td class="info-val">{{ $formation->duration }}</td>
                </tr>
              </table>
            </div>
            @endif

            @if($formation->category)
            <div class="info-row">
              <table class="info-row-table">
                <tr>
                  <td class="info-key">Domaine</td>
                  <td class="info-val">{{ $formation->category->name }}</td>
                </tr>
              </table>
            </div>
            @endif

            @if($formation->has_certification)
            <div class="info-row">
              <table class="info-row-table">
                <tr>
                  <td class="info-key">Certif.</td>
                  <td class="info-val info-val-gold">✓ Incluse</td>
                </tr>
              </table>
            </div>
            @endif

            @if($formation->access_delay)
            <div class="info-row">
              <table class="info-row-table">
                <tr>
                  <td class="info-key">Accès</td>
                  <td class="info-val-small">{{ Str::limit($formation->access_delay, 60) }}</td>
                </tr>
              </table>
            </div>
            @endif

          </div>
        </div>

        {{-- Contact --}}
        <div class="contact-card">
          <div class="contact-head">
            <div class="contact-head-title">Nous contacter</div>
          </div>
          <div class="contact-body">
            <div class="contact-line">
              <div class="contact-line-label">Téléphone</div>
              <div class="contact-line-value">+262 262 00 00 00</div>
            </div>
            <div class="contact-line">
              <div class="contact-line-label">Email</div>
              <div class="contact-line-value">contact@luxyformation.re</div>
            </div>
            <div class="contact-line">
              <div class="contact-line-label">Adresse</div>
              <div class="contact-line-value">Saint-Denis, La Réunion</div>
            </div>
          </div>
        </div>

        {{-- URL fiche en ligne --}}
        <div class="url-card">
          <div class="url-label">Fiche en ligne</div>
          <div class="url-separator"></div>
          <div class="url-val">luxyformation.re/nos-formations/{{ $formation->slug }}</div>
        </div>

        {{-- Note légale --}}
        <div class="legal-note">
          <div class="legal-note-text">
            Document non contractuel.<br>
            Sous réserve de modifications.<br>
            © {{ date('Y') }} Luxy Coaching &amp; Formation
          </div>
        </div>

      </td>{{-- /col-sidebar --}}

    </tr>
  </table>
</div>

{{-- ═══════════════════════════════════════════════════
     FOOTER
═══════════════════════════════════════════════════ --}}
<div class="footer-stripe"></div>
<div class="footer-body">
  <table class="footer-table">
    <tr>
      <td class="footer-left">
        © {{ date('Y') }} Luxy Coaching &amp; Formation — Tous droits réservés
      </td>
      <td class="footer-center">
        <div class="footer-logo-sm">L</div>
        <div class="footer-logo-sub">luxyformation.re</div>
      </td>
      <td class="footer-right">
        Généré le {{ now()->translatedFormat('d F Y') }}
      </td>
    </tr>
  </table>
</div>

</body>
</html>