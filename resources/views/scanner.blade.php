<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SecureAI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Sora', sans-serif;
            background-color: #050510;
            color: #e2e8f0;
        }

        .glass-panel {
            background: rgba(15, 15, 35, 0.8);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .input-field {
            width: 100%;
            padding: 1rem;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
            transition: 0.3s;
        }

        .input-field:focus {
            border-color: #22e5ff;
            box-shadow: 0 0 15px rgba(34, 229, 255, 0.3);
            outline: none;
        }

        .btn-scan {
            background: linear-gradient(90deg, #22e5ff, #7c4dff, #ff2e9a);
            background-size: 200% auto;
            animation: shine 4s linear infinite;
            color: #050510;
            font-weight: bold;
            transition: 0.3s;
        }

        .btn-scan:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(255, 46, 154, 0.6);
        }

        @keyframes shine {
            to {
                background-position: 200% center;
            }
        }
    </style>
</head>

<body class="min-h-screen p-6">

    <!-- Navbar -->
    <nav class="max-w-6xl mx-auto glass-panel px-6 py-4 rounded-2xl flex justify-between items-center mb-10">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-cyan-400 to-purple-600 flex items-center justify-center shadow-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
            </div>
            <span class="text-xl font-extrabold text-white">Secure<span class="text-cyan-400">AI</span></span>
        </div>
        <div class="flex items-center gap-4">
            <span class="text-sm text-slate-400">Halo, <strong class="text-white">{{ Auth::user()->name }}</strong></span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-sm bg-red-500/10 text-red-400 hover:bg-red-500/20 px-4 py-2 rounded-lg transition-all border border-red-500/20">Logout</button>
            </form>
        </div>
    </nav>

    <!-- Konten Utama -->
    <div class="max-w-3xl mx-auto">

        <div class="text-center mb-10">
            <h1 class="text-4xl font-extrabold text-white mb-2">Deteksi Ancaman Siber</h1>
            <p class="text-slate-400">Gunakan AI untuk memindai URL Phishing dan File Berbahaya.</p>
        </div>

        <div class="glass-panel p-8 rounded-2xl shadow-2xl">
            <form action="{{ route('scan.process') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="md:col-span-1">
                        <label class="block text-sm font-semibold text-slate-300 mb-2">Tipe Pemindaian</label>
                        <select name="tipe" class="input-field">
                            <option value="phishing" {{ (isset($tipe) && $tipe == 'phishing') ? 'selected' : '' }}>URL Phishing (AI)</option>
                            <option value="trojan" {{ (isset($tipe) && $tipe == 'trojan') ? 'selected' : '' }}>File Trojan</option>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-300 mb-2">URL / Nama File</label>
                        <input type="text" name="target" required value="{{ old('target', $target ?? '') }}"
                            placeholder="Contoh: https://google.com atau virus.exe"
                            class="input-field">
                    </div>
                </div>

                <button type="submit" class="w-full py-4 btn-scan rounded-xl text-lg flex justify-center items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    MULAI PEMINDAIAN
                </button>
            </form>

            @if(isset($hasil))
            <div class="mt-8 p-6 rounded-xl border-l-4 {{ str_contains(strtolower($hasil), 'aman') ? 'bg-green-500/10 border-green-500' : 'bg-red-500/10 border-red-500' }}">
                <div class="flex items-start">
                    <div class="mr-4">
                        @if(str_contains(strtolower($hasil), 'aman'))
                        <svg class="w-10 h-10 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        @else
                        <svg class="w-10 h-10 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        @endif
                    </div>
                    <div>
                        <p class="text-sm text-slate-400 mb-1">Hasil Pemindaian:</p>
                        <h4 class="text-2xl font-bold {{ str_contains(strtolower($hasil), 'aman') ? 'text-green-400' : 'text-red-400' }}">{{ $hasil }}</h4>

                        @if(isset($alasan) && is_array($alasan))
                        <div class="mt-4 pt-4 border-t border-slate-700">
                            <h5 class="text-white font-semibold mb-2">Laporan Analisis Keamanan:</h5>
                            <ul class="list-disc list-inside text-sm text-slate-400 space-y-1">
                                @foreach($alasan as $a)
                                <li>{{ $a }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <div class="mt-4 text-xs text-slate-500">
                            <p><strong>Waktu:</strong> {{ now()->format('d M Y, H:i:s') }} WIB</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

</body>

</html>