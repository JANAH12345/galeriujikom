<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Laporan Galeri - PDF</title>
  <style>
    /* Base */
    @page { margin: 24px 28px; }
    body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; color: #16202e; }
    .text-center{ text-align:center; }
    .small{ font-size:10.5px; color:#5b6572; }

    /* Header */
    .report-header{ margin-bottom: 14px; }
    .report-title{ font-size: 18px; font-weight: 700; color:#1f4e79; margin:0; }
    .report-sub{ margin: 4px 0 8px; color:#445268; }
    .accent-bar{ height: 4px; width: 120px; background: linear-gradient(90deg,#9db9d8,#2e5a88); border-radius: 999px; margin: 6px auto 0; }

    /* Table (modern, subtle borders) */
    table{ width:100%; border-collapse: separate; border-spacing: 0; }
    thead th{
      background: #f3f7fb;
      color: #284b75;
      font-weight: 700;
      border-bottom: 1px solid #d6e1ef;
      padding: 8px 10px;
    }
    tbody td{
      border-top: 1px solid #e8eef6;
      padding: 7px 9px;
    }
    table.rounded {
      border: 1px solid #d6e1ef;
      border-radius: 10px;
      overflow: hidden;
    }
    /* Zebra */
    tbody tr:nth-child(odd) { background: #fbfdff; }

    /* Badges & chips */
    .badge{ display:inline-block; padding:2px 8px; border-radius: 999px; font-weight:700; font-size: 11px; }
    .badge.blue{ background: #e6f0fb; color:#1f4e79; border: 1px solid #c9dcf4; }
    .chip{ display:inline-block; min-width: 18px; padding:2px 8px; border-radius:999px; font-weight:700; font-size:11px; text-align:center; }
    .chip.pos{ background:#e8f6ee; color:#11623a; border:1px solid #c7e8d2; }
    .chip.neg{ background:#fdecec; color:#8a1c1c; border:1px solid #f6c9c9; }
    .chip.neu{ background:#eef2f7; color:#384657; border:1px solid #dde6f0; }

    /* Preview thumb */
    .thumb{ border-radius:6px; border:1px solid #dfe8f2; }

    /* Header layout */
    .header-grid{ width:100%; margin-bottom: 8px; }
    .header-grid td{ vertical-align: middle; }
    .logo{ width: 56px; height:56px; border-radius:8px; object-fit:cover; border:1px solid #d6e1ef; }

    /* Totals chips */
    .totals { margin: 6px 0 10px; }
    .totals .chip { margin-right: 6px; }
  </style>
</head>
<body>
  <div class="report-header">
    <table class="header-grid">
      <tr>
        <td style="width:64px">
          @php $logoPath = public_path('images/logo.png'); @endphp
          @if(is_file($logoPath))
            <img class="logo" src="{{ asset('images/logo.png') }}" alt="Logo">
          @endif
        </td>
        <td class="text-center">
          <div class="report-title">Laporan Interaksi Galeri</div>
          <div class="report-sub small">Dibuat: {{ $generatedAt ?? date('d M Y H:i') }}</div>
          <div class="accent-bar"></div>
        </td>
      </tr>
    </table>

  @if(!empty($recentComments))
  <h3 style="margin:14px 0 6px; color:#1f4e79;">Komentar Terbaru</h3>
  <table class="rounded">
    <thead>
      <tr>
        <th>Nama</th>
        <th>Komentar</th>
        <th>Foto</th>
        <th class="text-center">Waktu</th>
      </tr>
    </thead>
    <tbody>
      @foreach($recentComments as $c)
      <tr>
        <td>{{ $c['name'] ?? 'Anonim' }}</td>
        <td>{{ $c['message'] ?? '' }}</td>
        <td>{{ $c['filename'] ?? '' }}</td>
        <td class="text-center">{{ \Carbon\Carbon::parse($c['created_at'] ?? now())->format('d M Y H:i') }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
  @endif
    @if(!empty($totals))
    <div class="totals">
      <span class="chip neu">Foto: {{ $totals['photos'] ?? 0 }}</span>
      <span class="chip pos">Like: {{ $totals['likes'] ?? 0 }}</span>
      <span class="chip neg">Tidak Suka: {{ $totals['dislikes'] ?? 0 }}</span>
      <span class="chip neu">Komentar: {{ $totals['comments'] ?? 0 }}</span>
      <span class="chip neu">Unduh: {{ $totals['downloads'] ?? 0 }}</span>
    </div>
    @endif
  </div>

  <table class="rounded">
    <thead>
      <tr>
        <th>Preview</th>
        <th>Judul</th>
        <th>Kategori</th>
        <th class="text-center">Like</th>
        <th class="text-center">Tidak Suka</th>
        <th class="text-center">Komentar</th>
        <th class="text-center">Dilihat</th>
        <th class="text-center">Unduh</th>
        <th class="text-center">Skor</th>
      </tr>
    </thead>
    <tbody>
      @foreach(($rows ?? []) as $r)
      <tr>
        <td class="text-center">
          @php $src = $r['pdf_src'] ?? $r['url'] ?? null; @endphp
          @if(!empty($src))
            <img class="thumb" src="{{ $src }}" alt="prev" style="max-width:120px; max-height:70px; object-fit:cover;">
          @endif
        </td>
        <td>{{ $r['title'] ?? $r['filename'] }}</td>
        <td><span class="badge blue">{{ $r['category'] ?? 'Lainnya' }}</span></td>
        <td class="text-center"><span class="chip pos">{{ $r['likes'] ?? 0 }}</span></td>
        <td class="text-center"><span class="chip neg">{{ $r['dislikes'] ?? 0 }}</span></td>
        <td class="text-center"><span class="chip neu">{{ $r['comments'] ?? 0 }}</span></td>
        <td class="text-center"><span class="chip neu">{{ $r['views'] ?? 0 }}</span></td>
        <td class="text-center"><span class="chip neu">{{ $r['downloads'] ?? 0 }}</span></td>
        <td class="text-center">
          @php $s = (int) ($r['score'] ?? 0); @endphp
          <span class="chip {{ $s>0 ? 'pos' : ($s<0 ? 'neg' : 'neu') }}">{{ $s }}</span>
        </td>
      </tr>
      @endforeach
      @if(empty($rows))
      <tr><td colspan="8" class="text-center small">Tidak ada data.</td></tr>
      @endif
    </tbody>
  </table>
</body>
</html>
