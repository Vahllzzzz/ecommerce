<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>About</title>
    <style>
        body {
            font-family: system-ui, -apple-system, sans-serif;
            max-width: 
            margin: 50px auto;
            padding: 20px;
        }
        h1 {
            color: #4f46e5;
        }
    </style>
</head>
  <body>
    <h1>Tentang Toko Online</h1>
    <p>Selamat datang di toko online kami.</p>
    <p>Dibuat dengan ❤️ menggunakan Laravel.</p>
    <p>Waktu saat ini: {{ now()->format('d M Y, H:i:s') }}</p>

    <a href="{{ route('produk.detail', ['id' => 1]) }}">Lihat Produk 1</a>
    <a href="{{ route('produk.detail', ['id' => 2]) }}">Lihat Produk 2</a>

    <a href="/">← Kembali ke Home</a>
    {{-- ↑ Link biasa ke halaman utama --}}
  </body>
</html>