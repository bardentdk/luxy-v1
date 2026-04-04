<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<style>

/* ═══════════════════════════════════════
   BASE
═══════════════════════════════════════ */
* { margin:0; padding:0; box-sizing:border-box; }
html, body {
  font-family: 'DejaVu Sans', sans-serif;
  font-size: 10px;
  color: #1A1A1A;
  background: #FFFFFF;
  line-height: 1.5;
}

/* ═══════════════════════════════════════
   BANDE COULEUR GAUCHE — filet vertical
═══════════════════════════════════════ */
.page-wrap {
  padding: 0;
  position: relative;
}

/* ═══════════════════════════════════════
   HEADER — Logo + Titre DEVIS
═══════════════════════════════════════ */
.header {
  padding: 36px 48px 28px;
  border-bottom: 2px solid #F0F0F0;
}
.header-table { width: 100%; }
.header-table td { vertical-align: top; }

/* Zone logo */
.logo-area { width: 55%; }
.logo-img {
  max-width: 180px;
  max-height: 70px;
}
/* Fallback texte si pas de logo */
.logo-fallback-name {
  font-size: 20px;
  font-weight: 900;
  color: #0D1B2A;
  letter-spacing: -0.5px;
  line-height: 1.1;
}
.logo-fallback-sub {
  font-size: 8.5px;
  color: #C9A84C;
  text-transform: uppercase;
  letter-spacing: 2.5px;
  margin-top: 4px;
}

/* Zone titre DEVIS */
.title-area { text-align: right; }
.doc-type {
  font-size: 28px;
  font-weight: 900;
  color: #0D1B2A;
  letter-spacing: -1px;
  line-height: 1;
  text-transform: uppercase;
}
.doc-ref {
  font-size: 12px;
  font-weight: 700;
  color: #C9A84C;
  margin-top: 6px;
  letter-spacing: 0.5px;
}
.doc-status {
  display: inline-block;
  margin-top: 8px;
  font-size: 8.5px;
  font-weight: 800;
  padding: 3px 12px;
  border-radius: 2px;
  text-transform: uppercase;
  letter-spacing: 0.1em;
}
.s-draft    { background: #F3F4F6; color: #6B7280; }
.s-sent     { background: #FEF3C7; color: #D97706; }
.s-accepted { background: #D1FAE5; color: #065F46; }
.s-refused  { background: #FEE2E2; color: #991B1B; }
.s-expired  { background: #F3F4F6; color: #9CA3AF; }

/* ═══════════════════════════════════════
   PARTIES — émetteur / destinataire
═══════════════════════════════════════ */
.parties-section { padding: 28px 48px; }
.parties-table { width: 100%; }
.parties-table td { vertical-align: top; width: 50%; }

.party-block { padding-right: 32px; }
.party-block-right { padding-left: 16px; padding-right: 0; }

.party-label {
  font-size: 7.5px;
  font-weight: 900;
  color: #C9A84C;
  text-transform: uppercase;
  letter-spacing: 0.2em;
  margin-bottom: 10px;
  display: block;
}
.party-name {
  font-size: 12px;
  font-weight: 800;
  color: #0D1B2A;
  margin-bottom: 5px;
}
.party-line {
  font-size: 10px;
  color: #555555;
  margin-bottom: 2px;
  line-height: 1.55;
}

/* ═══════════════════════════════════════
   FICHE INFOS — dates & conditions
═══════════════════════════════════════ */
.meta-section {
  margin: 0 48px 24px;
  background: #FAFAFA;
  border: 1px solid #EBEBEB;
  border-left: 3px solid #C9A84C;
  border-radius: 0 4px 4px 0;
}
.meta-inner-table { width: 100%; border-collapse: collapse; }
.meta-inner-table td {
  padding: 10px 18px;
  vertical-align: middle;
  border-right: 1px solid #EBEBEB;
}
.meta-inner-table td:last-child { border-right: none; }
.meta-key {
  font-size: 7.5px;
  font-weight: 700;
  color: #999999;
  text-transform: uppercase;
  letter-spacing: 0.12em;
  display: block;
  margin-bottom: 3px;
}
.meta-val {
  font-size: 10.5px;
  font-weight: 700;
  color: #0D1B2A;
}
.meta-val-gold { color: #C9A84C; }

/* ═══════════════════════════════════════
   TABLEAU LIGNES
═══════════════════════════════════════ */
.items-section { padding: 0 48px; }

.items-table {
  width: 100%;
  border-collapse: collapse;
}

/* En-tête tableau */
.items-table thead tr {
  background: #0D1B2A;
}
.items-table thead th {
  padding: 9px 12px;
  font-size: 8px;
  font-weight: 700;
  color: rgba(255,255,255,0.75);
  text-transform: uppercase;
  letter-spacing: 0.12em;
  text-align: left;
}
.items-table thead th.r { text-align: right; }
.items-table thead th.c { text-align: center; }

/* Lignes */
.items-table tbody tr { border-bottom: 1px solid #F0F0F0; }
.items-table tbody tr:last-child { border-bottom: 2px solid #E0E0E0; }
.items-table tbody tr.alt { background: #FAFAFA; }

.items-table tbody td {
  padding: 11px 12px;
  vertical-align: top;
}
.item-name {
  font-size: 10.5px;
  font-weight: 700;
  color: #0D1B2A;
}
.item-desc {
  font-size: 9px;
  color: #888888;
  margin-top: 3px;
  line-height: 1.5;
}
.td-num    { font-size: 10.5px; color: #333333; text-align: right; white-space: nowrap; }
.td-center { font-size: 10.5px; color: #333333; text-align: center; }
.td-red    { font-size: 10px; color: #DC2626; text-align: center; }
.td-total  { font-size: 11px; font-weight: 800; color: #0D1B2A; text-align: right; white-space: nowrap; }

/* ═══════════════════════════════════════
   TOTAUX
═══════════════════════════════════════ */
.totals-section { padding: 0 48px; margin-top: 0; }
.totals-right { margin-left: auto; width: 280px; margin-top: 4px; }

.totals-table { width: 100%; border-collapse: collapse; }
.totals-table tr td { padding: 5px 12px; font-size: 10.5px; }
.totals-table tr td:last-child { text-align: right; font-weight: 700; }
.t-sub   td { color: #555555; border-bottom: 1px solid #F0F0F0; }
.t-disc  td { color: #DC2626; border-bottom: 1px solid #F0F0F0; }
.t-ht    td { color: #555555; border-bottom: 1px solid #E0E0E0; }
.t-tva   td { color: #555555; border-bottom: 1px solid #EBEBEB; }
.t-total { background: #0D1B2A; }
.t-total td {
  padding: 11px 14px;
  font-size: 13px;
  font-weight: 900;
  color: #FFFFFF;
}
.t-total td:last-child { color: #C9A84C; font-size: 15px; }

/* ═══════════════════════════════════════
   NOTES / CONDITIONS
═══════════════════════════════════════ */
.bottom-section { padding: 24px 48px 0; }
.bottom-table { width: 100%; }
.bottom-table td { vertical-align: top; padding-right: 20px; }
.bottom-table td:last-child { padding-right: 0; }

.bottom-box {
  border: 1px solid #EBEBEB;
  border-top: 2px solid #C9A84C;
  border-radius: 0 0 3px 3px;
  padding: 12px 14px;
}
.bottom-label {
  font-size: 7.5px;
  font-weight: 800;
  color: #C9A84C;
  text-transform: uppercase;
  letter-spacing: 0.15em;
  margin-bottom: 7px;
}
.bottom-text {
  font-size: 9.5px;
  color: #555555;
  line-height: 1.7;
}

/* ═══════════════════════════════════════
   SIGNATURE / ACCEPTATION
═══════════════════════════════════════ */
.sign-section { padding: 28px 48px 0; }
.sign-table { width: 100%; }
.sign-table td { vertical-align: top; }
.sign-box {
  border: 1px dashed #CCCCCC;
  border-radius: 3px;
  padding: 14px;
  min-height: 70px;
}
.sign-label {
  font-size: 7.5px;
  font-weight: 800;
  color: #999999;
  text-transform: uppercase;
  letter-spacing: 0.12em;
  margin-bottom: 5px;
}
.sign-instruction {
  font-size: 9px;
  color: #BBBBBB;
  line-height: 1.6;
}
.sign-date-line {
  border-top: 1px solid #E0E0E0;
  margin-top: 24px;
  padding-top: 6px;
  font-size: 8.5px;
  color: #AAAAAA;
}

/* ═══════════════════════════════════════
   FOOTER
═══════════════════════════════════════ */
.footer {
  margin-top: 36px;
  border-top: 1px solid #EBEBEB;
  padding: 14px 48px;
  background: #FAFAFA;
}
.footer-table { width: 100%; }
.footer-table td {
  font-size: 8.5px;
  color: #AAAAAA;
  vertical-align: middle;
}
.footer-table td:nth-child(2) { text-align: center; }
.footer-table td:last-child    { text-align: right; }
.footer-gold { color: #C9A84C; font-weight: 700; }

/* ═══════════════════════════════════════
   PAGE BREAK
═══════════════════════════════════════ */
.page-break { page-break-before: always; }

</style>
</head>
<body>
<div class="page-wrap">

  {{-- ════════════════════════════════════
       HEADER — Logo + Titre
  ════════════════════════════════════ --}}
  <div class="header">
    <table class="header-table">
      <tr>
        {{-- Logo --}}
        <td class="logo-area">
          @php
            $logoPath = public_path('images/logo.png');
          @endphp

          @if(file_exists($logoPath))
            <img src="{{ $logoPath }}" class="logo-img" alt="Logo Luxy Formation" />
          @else
            {{-- Fallback texte --}}
            <div class="logo-fallback-name">Luxy Formation</div>
            <div class="logo-fallback-sub">Centre de formation professionnelle</div>
          @endif
        </td>

        {{-- Titre document --}}
        <td class="title-area">
          <div class="doc-type">Devis</div>
          <div class="doc-ref">{{ $quote->reference }}</div>
          @php
            $statusClass = match($quote->status) {
              'draft'    => 's-draft',
              'sent'     => 's-sent',
              'accepted' => 's-accepted',
              'refused'  => 's-refused',
              'expired'  => 's-expired',
              default    => 's-draft',
            };
            $statusLabel = match($quote->status) {
              'draft'    => 'Brouillon',
              'sent'     => 'Envoyé',
              'accepted' => 'Accepté',
              'refused'  => 'Refusé',
              'expired'  => 'Expiré',
              default    => $quote->status,
            };
          @endphp
          <div>
            <span class="doc-status {{ $statusClass }}">{{ $statusLabel }}</span>
          </div>
        </td>
      </tr>
    </table>
  </div>

  {{-- ════════════════════════════════════
       ÉMETTEUR / DESTINATAIRE
  ════════════════════════════════════ --}}
  <div class="parties-section">
    <table class="parties-table">
      <tr>
        {{-- Émetteur --}}
        <td>
          <div class="party-block">
            <span class="party-label">Émetteur</span>
            <div class="party-name">Luxy Coaching &amp; Formation</div>
            <div class="party-line">Centre de formation professionnelle</div>
            <div class="party-line">Saint-Denis, La Réunion</div>
            <div class="party-line">contact@luxyformation.re</div>
            <div class="party-line">+262 262 00 00 00</div>
            <div class="party-line">luxyformation.re</div>
          </div>
        </td>

        {{-- Destinataire --}}
        <td>
          <div class="party-block-right">
            <span class="party-label">Destinataire</span>
            <div class="party-name">{{ $quote->contact->full_name }}</div>
            @if($quote->contact->company)
              <div class="party-line">{{ $quote->contact->company }}</div>
            @endif
            @if($quote->contact->job_title)
              <div class="party-line">{{ $quote->contact->job_title }}</div>
            @endif
            <div class="party-line">{{ $quote->contact->email }}</div>
            @if($quote->contact->phone)
              <div class="party-line">{{ $quote->contact->phone }}</div>
            @endif
          </div>
        </td>
      </tr>
    </table>
  </div>

  {{-- ════════════════════════════════════
       MÉTA INFOS — Dates / Conditions
  ════════════════════════════════════ --}}
  <div class="meta-section">
    <table class="meta-inner-table">
      <tr>
        <td>
          <span class="meta-key">Référence</span>
          <span class="meta-val">{{ $quote->reference }}</span>
        </td>
        <td>
          <span class="meta-key">Date d'émission</span>
          <span class="meta-val">{{ $quote->issued_at->format('d/m/Y') }}</span>
        </td>
        <td>
          <span class="meta-key">Date d'expiration</span>
          <span class="meta-val">{{ $quote->expires_at->format('d/m/Y') }}</span>
        </td>
        <td>
          <span class="meta-key">Créé par</span>
          <span class="meta-val">{{ $quote->createdBy->full_name }}</span>
        </td>
        @if($quote->discount_percent > 0)
        <td>
          <span class="meta-key">Remise globale</span>
          <span class="meta-val meta-val-gold">{{ number_format($quote->discount_percent, 1, ',', '') }}%</span>
        </td>
        @endif
      </tr>
    </table>
  </div>

  {{-- ════════════════════════════════════
       LIGNES DU DEVIS
  ════════════════════════════════════ --}}
  <div class="items-section">
    <table class="items-table">
      <thead>
        <tr>
          <th style="width:42%;">Désignation</th>
          <th class="r" style="width:13%;">Prix unit. HT</th>
          <th class="c" style="width:9%;">Qté</th>
          <th class="c" style="width:8%;">Unité</th>
          <th class="c" style="width:8%;">Remise</th>
          <th class="c" style="width:8%;">TVA</th>
          <th class="r" style="width:12%;">Total HT</th>
        </tr>
      </thead>
      <tbody>
        @foreach($quote->items as $i => $item)
        <tr class="{{ $i % 2 === 1 ? 'alt' : '' }}">
          <td>
            <div class="item-name">{{ $item->name }}</div>
            @if($item->description)
              <div class="item-desc">{{ $item->description }}</div>
            @endif
          </td>
          <td class="td-num">{{ number_format($item->unit_price, 2, ',', ' ') }} €</td>
          <td class="td-center">{{ number_format($item->quantity, 2, ',', '') }}</td>
          <td class="td-center">{{ $item->unit }}</td>
          <td class="{{ $item->discount_percent > 0 ? 'td-red' : 'td-center' }}">
            {{ $item->discount_percent > 0 ? '-' . number_format($item->discount_percent, 1, ',', '') . '%' : '—' }}
          </td>
          <td class="td-center">
            {{ $item->tax_rate > 0 ? number_format($item->tax_rate, 1, ',', '') . '%' : '—' }}
          </td>
          <td class="td-total">{{ number_format($item->total, 2, ',', ' ') }} €</td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  {{-- ════════════════════════════════════
       TOTAUX
  ════════════════════════════════════ --}}
  <div class="totals-section">
    <div class="totals-right">
      <table class="totals-table">

        {{-- Sous-total brut --}}
        <tr class="t-sub">
          <td>Sous-total HT brut</td>
          <td>{{ number_format($quote->items->sum('total'), 2, ',', ' ') }} €</td>
        </tr>

        {{-- Remise globale --}}
        @if($quote->discount_percent > 0)
        <tr class="t-disc">
          <td>Remise globale ({{ number_format($quote->discount_percent, 1, ',', '') }}%)</td>
          <td>— {{ number_format($quote->items->sum('total') * $quote->discount_percent / 100, 2, ',', ' ') }} €</td>
        </tr>
        @endif

        {{-- Total HT net --}}
        <tr class="t-ht">
          <td>Total HT net</td>
          <td>{{ number_format($quote->subtotal, 2, ',', ' ') }} €</td>
        </tr>

        {{-- TVA --}}
        <tr class="t-tva">
          <td>TVA</td>
          <td>{{ number_format($quote->tax_total, 2, ',', ' ') }} €</td>
        </tr>

        {{-- Total TTC --}}
        <tr class="t-total">
          <td>Total TTC</td>
          <td>{{ number_format($quote->total, 2, ',', ' ') }} €</td>
        </tr>

      </table>
    </div>
  </div>

  {{-- ════════════════════════════════════
       NOTES + CONDITIONS
  ════════════════════════════════════ --}}
  @if($quote->notes || $quote->terms)
  <div class="bottom-section">
    <table class="bottom-table">
      <tr>
        @if($quote->notes)
        <td>
          <div class="bottom-box">
            <div class="bottom-label">Notes</div>
            <div class="bottom-text">{{ $quote->notes }}</div>
          </div>
        </td>
        @endif
        @if($quote->terms)
        <td>
          <div class="bottom-box">
            <div class="bottom-label">Conditions de paiement</div>
            <div class="bottom-text">{{ $quote->terms }}</div>
          </div>
        </td>
        @endif
      </tr>
    </table>
  </div>
  @endif

  {{-- ════════════════════════════════════
       ZONE SIGNATURE / ACCEPTATION
  ════════════════════════════════════ --}}
  <div class="sign-section">
    <table class="sign-table">
      <tr>
        {{-- Bon pour accord --}}
        <td style="width:48%; padding-right:16px;">
          <div class="sign-box">
            <div class="sign-label">Bon pour accord — Signature du client</div>
            <div class="sign-instruction">
              Précédé de la mention « Bon pour accord »,<br>
              date et signature du client.
            </div>
            <div class="sign-date-line">Date : _____ / _____ / _________</div>
          </div>
        </td>

        {{-- Cachet société --}}
        <td style="width:52%;">
          <div class="sign-box">
            <div class="sign-label">Cachet et signature de l'organisme</div>
            <div class="sign-instruction">
              Luxy Coaching &amp; Formation<br>
              Centre de formation professionnelle · La Réunion
            </div>
            <div class="sign-date-line">Date : _____ / _____ / _________</div>
          </div>
        </td>
      </tr>
    </table>
  </div>

  {{-- ════════════════════════════════════
       FOOTER
  ════════════════════════════════════ --}}
  <div class="footer">
    <table class="footer-table">
      <tr>
        <td>© {{ date('Y') }} <span class="footer-gold">Luxy Coaching &amp; Formation</span></td>
        <td>Document non contractuel · valable jusqu'au {{ $quote->expires_at->format('d/m/Y') }}</td>
        <td>{{ $quote->reference }} · Généré le {{ now()->format('d/m/Y') }}</td>
      </tr>
    </table>
  </div>

</div>
</body>
</html>