<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <style>
    body { font-family: Arial, sans-serif; color: #1A1A1A; font-size: 14px; background: #F4F4F4; margin: 0; padding: 20px; }
    .wrap { max-width: 600px; margin: 0 auto; background: white; border-radius: 8px; overflow: hidden; }
    .header { background: #0D1B2A; padding: 28px 32px; }
    .header-title { color: white; font-size: 20px; font-weight: bold; margin: 0; }
    .header-sub { color: #C9A84C; font-size: 12px; margin-top: 4px; }
    .body { padding: 32px; }
    .ref-box { background: #FAF7F2; border-left: 4px solid #C9A84C; padding: 14px 18px; border-radius: 0 6px 6px 0; margin-bottom: 24px; }
    .ref-label { font-size: 11px; color: #999; text-transform: uppercase; letter-spacing: 1px; }
    .ref-num { font-size: 18px; font-weight: bold; color: #0D1B2A; margin-top: 4px; }
    .btn { display: inline-block; background: #C9A84C; color: #0D1B2A; font-weight: bold; padding: 12px 28px; border-radius: 6px; text-decoration: none; margin-top: 24px; font-size: 14px; }
    .total-line { font-size: 22px; font-weight: bold; color: #C9A84C; margin-top: 16px; }
    .footer { background: #F4F4F4; padding: 18px 32px; font-size: 11px; color: #999; border-top: 1px solid #E8E8E8; }
  </style>
</head>
<body>
  <div class="wrap">
    <div class="header">
      <div class="header-title">Luxy Formation</div>
      <div class="header-sub">Centre de formation professionnelle · La Réunion</div>
    </div>
    <div class="body">
      <p style="margin-bottom:16px;">Bonjour <strong>{{ $quote->contact->full_name }}</strong>,</p>
      <p style="margin-bottom:20px; line-height:1.7; color:#444;">
        Veuillez trouver ci-joint votre devis. Il reste valable jusqu'au
        <strong>{{ $quote->expires_at->translatedFormat('d F Y') }}</strong>.
        N'hésitez pas à nous contacter pour toute question.
      </p>

      <div class="ref-box">
        <div class="ref-label">Référence du devis</div>
        <div class="ref-num">{{ $quote->reference }}</div>
      </div>

      <table style="width:100%; border-collapse:collapse; margin-bottom:8px;">
        <tr style="background:#FAF7F2;">
          <th style="padding:8px 12px; text-align:left; font-size:11px; color:#999; text-transform:uppercase; letter-spacing:1px;">Désignation</th>
          <th style="padding:8px 12px; text-align:right; font-size:11px; color:#999; text-transform:uppercase; letter-spacing:1px;">Total HT</th>
        </tr>
        @foreach($quote->items as $item)
        <tr style="border-bottom:1px solid #F0F0F0;">
          <td style="padding:10px 12px; font-size:13px;">{{ $item->name }}</td>
          <td style="padding:10px 12px; text-align:right; font-size:13px; font-weight:bold;">{{ number_format($item->total, 2, ',', ' ') }} €</td>
        </tr>
        @endforeach
      </table>

      <div class="total-line">Total TTC : {{ number_format($quote->total, 2, ',', ' ') }} €</div>

      <p style="margin-top:28px; font-size:13px; color:#555; line-height:1.7;">
        Pour accepter ce devis, veuillez nous retourner ce document signé avec la mention
        <em>« Bon pour accord »</em>, ou répondre à cet email.
      </p>
    </div>
    <div class="footer">
      © {{ date('Y') }} Luxy Coaching &amp; Formation · contact@luxyformation.re · +262 262 00 00 00
      <br>Ce document est non contractuel et valable jusqu'au {{ $quote->expires_at->format('d/m/Y') }}.
    </div>
  </div>
</body>
</html>