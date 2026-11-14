<!DOCTYPE html>
<html>
<head>
    <title>Minat Investasi Baru</title>
</head>
<body>
    <h1>Minat Investasi Baru</h1>

    <p>Ada minat investasi baru dari:</p>

    <ul>
        <li><strong>Nama:</strong> {{ $minatInvestor->nama }}</li>
        <li><strong>Email:</strong> {{ $minatInvestor->email }}</li>
        <li><strong>Telepon:</strong> {{ $minatInvestor->telepon }}</li>
        @if($minatInvestor->perusahaan)
            <li><strong>Perusahaan:</strong> {{ $minatInvestor->perusahaan }}</li>
        @endif
        <li><strong>Potensi Investasi:</strong> {{ $minatInvestor->potensiInvestasi->nama }}</li>
        @if($minatInvestor->kelurahan)
            <li><strong>Kelurahan:</strong> {{ $minatInvestor->kelurahan->nama }} ({{ $minatInvestor->kelurahan->kecamatan }})</li>
        @endif
        @if($minatInvestor->pesan)
            <li><strong>Pesan:</strong> {{ $minatInvestor->pesan }}</li>
        @endif
        <li><strong>Waktu:</strong> {{ $minatInvestor->created_at->format('d/m/Y H:i') }}</li>
    </ul>

    <p>Silakan periksa di panel admin untuk detail lebih lanjut.</p>
</body>
</html>