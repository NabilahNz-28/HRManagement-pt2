<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Slip Gaji - {{ $payroll->karyawan->nama_lengkap }}</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 13px; color: #1a1a1a; margin: 30px; }
        
        .header { display: flex; align-items: flex-start; gap: 20px; border-bottom: 3px solid #1e40af; padding-bottom: 14px; margin-bottom: 20px; }
        .header-logo { flex-shrink: 0; }
        .header-logo img { width: 70px; height: 70px; object-fit: contain; }
        .header-text { flex: 1; }
        .header-text h1 { margin: 0 0 2px 0; font-size: 20px; font-weight: bold; color: #1e40af; }
        .header-text p { margin: 2px 0 0; font-size: 11px; color: #666; }
        .slip-title { text-align: right; }
        .slip-title h2 { margin: 0; font-size: 16px; font-weight: bold; color: #333; }
        .slip-title p { margin: 3px 0 0; font-size: 11px; color: #888; }

        @if($payroll->karyawan->toko === 'Bingxue')
        .brand-color { color: #f97316; }
        .brand-bg { background-color: #fff7ed; }
        .brand-border { border-color: #f97316; }
        @else
        .brand-color { color: #1e40af; }
        .brand-bg { background-color: #eff6ff; }
        .brand-border { border-color: #1e40af; }
        @endif

        .info-table { width: 100%; margin-bottom: 20px; border-collapse: collapse; }
        .info-table td { padding: 5px 8px; font-size: 12px; }
        .info-table tr:nth-child(odd) { background-color: #f8fafc; }
        .info-title { font-weight: bold; width: 130px; color: #475569; }

        .toko-badge {
            display: inline-block;
            padding: 2px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
        }
        
        .calc-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .calc-table th, .calc-table td { padding: 9px 10px; border-bottom: 1px solid #e2e8f0; font-size: 12.5px; }
        .calc-table th { text-align: left; font-size: 11.5px; letter-spacing: 0.04em; }
        .text-right { text-align: right; }
        .section-pendapatan { background-color: #f0fdf4; color: #166534; font-weight: bold; text-transform: uppercase; letter-spacing: 0.05em; font-size: 11px; }
        .section-potongan { background-color: #fef2f2; color: #991b1b; font-weight: bold; text-transform: uppercase; letter-spacing: 0.05em; font-size: 11px; }
        .total-pendapatan { font-weight: bold; background-color: #dcfce7; color: #166534; }
        .total-final { font-weight: bold; font-size: 15px; background-color: #dbeafe; color: #1e40af; }
        
        .footer-grid { display: flex; gap: 20px; margin-top: 30px; }
        .footer-info { flex: 1; font-size: 11px; color: #666; background: #f8fafc; border-radius: 8px; padding: 12px; border: 1px solid #e2e8f0; }
        .footer-info p { margin: 3px 0; }
        .footer-sign { text-align: center; min-width: 200px; }
        .footer-sign p { font-size: 11px; color: #555; margin-bottom: 4px; }
        .sign-img { max-width: 160px; height: 70px; object-fit: contain; }
        .sign-name { font-weight: bold; font-size: 13px; margin-top: 4px; border-top: 1px solid #333; padding-top: 4px; display: inline-block; min-width: 160px; }
        .sign-role { font-size: 10px; color: #888; margin-top: 2px; }
        
        .hari-badge { font-size: 10px; color: #888; font-weight: normal; }
    </style>
</head>
<body>
    {{-- HEADER --}}
    <div class="header" style="border-bottom: 2px solid #000;">
        <div class="header-logo">
            @if($payroll->karyawan->toko === 'Bingxue')
                <img src="{{ public_path('img/bingxue.png') }}" alt="Bingxue" style="width: 80px; height: 80px;">
            @else
                <img src="{{ public_path('img/mixue.png') }}" alt="Mixue" style="width: 80px; height: 80px;">
            @endif
        </div>
        <div class="header-text" style="display: flex; flex-direction: column; justify-content: center;">
            <h1 style="color: #000; font-size: 22px; margin: 0 0 4px 0; text-transform: uppercase;">
                @if($payroll->karyawan->toko === 'Bingxue')
                    BINGXUE GADING SERPONG
                @else
                    MIXUE GADING SERPONG
                @endif
            </h1>
            <p style="color: #000; font-size: 13px; margin: 0; font-weight: normal;">Ruko Gading Serpong, Tangerang, Banten</p>
        </div>
    </div>

    <div style="text-align: center; margin-bottom: 20px;">
        <h2 style="margin: 0; font-size: 16px; font-weight: bold; color: #333; text-decoration: underline;">SLIP GAJI KARYAWAN</h2>
        <p style="margin: 3px 0 0; font-size: 12px; color: #555;">Periode: <strong>{{ $payroll->periode }}</strong></p>
    </div>

    {{-- INFO KARYAWAN --}}
    <table class="info-table">
        <tr>
            <td class="info-title">Nama Karyawan</td>
            <td>: <strong>{{ $payroll->karyawan->nama_lengkap }}</strong></td>
            <td class="info-title">NIK</td>
            <td>: <span style="font-family: monospace; font-weight: bold;">{{ $payroll->karyawan->nik }}</span></td>
        </tr>
        <tr>
            <td class="info-title">Jabatan</td>
            <td>: {{ $payroll->karyawan->jabatan }}</td>
            <td class="info-title">Toko</td>
            <td>: <strong>{{ $payroll->karyawan->toko }}</strong></td>
        </tr>
        <tr>
            <td class="info-title">Hari Masuk</td>
            <td>: <strong>{{ $payroll->hari_masuk }} Hari</strong></td>
            <td class="info-title">Tanggal Cetak</td>
            <td>: {{ date('d F Y') }}</td>
        </tr>
    </table>

    {{-- TABEL GAJI --}}
    <table class="calc-table">
        <tr>
            <th colspan="2" class="section-pendapatan">📈 Rincian Pendapatan</th>
        </tr>
        <tr>
            <td>Gaji Pokok <span class="hari-badge">{{ $payroll->hari_masuk > 0 ? '('.$payroll->hari_masuk.' Hari)' : '' }}</span></td>
            <td class="text-right">Rp {{ number_format($payroll->gaji_pokok, 0, ',', '.') }}</td>
        </tr>
        @if($payroll->uang_makan > 0)
        <tr>
            <td>Uang Makan <span class="hari-badge">{{ $payroll->hari_masuk > 0 ? '('.$payroll->hari_masuk.' Hari)' : '' }}</span></td>
            <td class="text-right">Rp {{ number_format($payroll->uang_makan, 0, ',', '.') }}</td>
        </tr>
        @endif
        @if($payroll->uang_transport > 0)
        <tr>
            <td>Uang Transport</td>
            <td class="text-right">Rp {{ number_format($payroll->uang_transport, 0, ',', '.') }}</td>
        </tr>
        @endif
        @if($payroll->lembur > 0)
        <tr>
            <td>Uang Lembur</td>
            <td class="text-right">Rp {{ number_format($payroll->lembur, 0, ',', '.') }}</td>
        </tr>
        @endif
        @if($payroll->angkat_barang > 0)
        <tr>
            <td>Angkat Barang</td>
            <td class="text-right">Rp {{ number_format($payroll->angkat_barang, 0, ',', '.') }}</td>
        </tr>
        @endif
        @if($payroll->bonus > 0)
        <tr>
            <td>Bonus</td>
            <td class="text-right">Rp {{ number_format($payroll->bonus, 0, ',', '.') }}</td>
        </tr>
        @endif
        @if($payroll->thr > 0)
        <tr>
            <td>THR</td>
            <td class="text-right">Rp {{ number_format($payroll->thr, 0, ',', '.') }}</td>
        </tr>
        @endif
        @php
            $totalPendapatan = $payroll->gaji_pokok + $payroll->uang_makan + $payroll->uang_transport + $payroll->lembur + $payroll->angkat_barang + $payroll->bonus + $payroll->thr;
        @endphp
        <tr class="total-pendapatan">
            <td>TOTAL PENDAPATAN</td>
            <td class="text-right">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
        </tr>

        @if($payroll->potongan_hutang > 0)
        <tr>
            <th colspan="2" class="section-potongan">📉 Potongan</th>
        </tr>
        <tr>
            <td style="color:#dc2626;">Angsuran Hutang</td>
            <td class="text-right" style="color:#dc2626;">- Rp {{ number_format($payroll->potongan_hutang, 0, ',', '.') }}</td>
        </tr>
        @endif

        <tr class="total-final">
            <td>💰 TAKE HOME PAY</td>
            <td class="text-right">Rp {{ number_format($payroll->total_gaji, 0, ',', '.') }}</td>
        </tr>
    </table>

    {{-- FOOTER --}}
    <div class="footer-grid">
        <div class="footer-info">
            <p><strong>Catatan:</strong></p>
            <p>• Slip gaji ini adalah dokumen resmi perusahaan.</p>
            <p>• Mohon disimpan sebagai bukti penerimaan gaji.</p>
            @if($payroll->potongan_hutang > 0)
            <p style="color: #dc2626; margin-top: 6px;">• Angsuran hutang Rp {{ number_format($payroll->potongan_hutang, 0, ',', '.') }} telah dipotong dari gaji periode ini.</p>
            @endif
        </div>
        <div class="footer-sign">
            <p>Disetujui oleh,</p>
            <img src="{{ public_path('img/ttd_ricky.png') }}" alt="TTD" class="sign-img">
            <div class="sign-name">Ricky Salim</div>
            <div class="sign-role">HR Manager</div>
        </div>
    </div>
</body>
</html>
