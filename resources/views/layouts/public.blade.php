<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Pemesanan & Layanan') - Maudy Laundry</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        maudy: {
                            blue: '#0288d1',
                            dark: '#01579b',
                            light: '#e1f5fe',
                            accent: '#29b6f6',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', '-apple-system', 'Segoe UI', 'Roboto', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }

        /* ========================================================
           AESTHETIC LAUNDRY SOAP BUBBLES ANIMATIONS
           ======================================================== */
        .bubble-ambient-container {
            position: fixed;
            inset: 0;
            pointer-events: none;
            overflow: hidden;
            z-index: 45; /* Di atas konten agar gelembung dapat disentuh dan ditekan pecah */
        }

        .soap-bubble {
            position: absolute;
            border-radius: 50%;
            pointer-events: auto;
            cursor: pointer;
            user-select: none;
            background: radial-gradient(
                circle at 35% 30%,
                rgba(255, 255, 255, 0.92) 0%,
                rgba(255, 255, 255, 0.4) 15%,
                rgba(186, 230, 253, 0.45) 40%,
                rgba(244, 114, 182, 0.28) 65%,
                rgba(196, 181, 253, 0.38) 85%,
                rgba(147, 197, 253, 0.55) 100%
            );
            border: 1.5px solid rgba(255, 255, 255, 0.8);
            box-shadow: 
                inset 0 0 14px rgba(255, 255, 255, 0.9),
                inset 3px 3px 6px rgba(255, 255, 255, 0.95),
                inset -3px -3px 8px rgba(56, 189, 248, 0.4),
                0 8px 24px -2px rgba(2, 136, 209, 0.22);
            backdrop-filter: blur(1px);
            -webkit-backdrop-filter: blur(1px);
            transition: transform 0.15s ease-out, filter 0.15s ease-out;
            will-change: transform, opacity;
            touch-action: manipulation;
        }

        /* Pantulan kilau cahaya sabun (Top Glint) */
        .soap-bubble::before {
            content: '';
            position: absolute;
            top: 14%;
            left: 18%;
            width: 26%;
            height: 18%;
            border-radius: 50%;
            background: radial-gradient(ellipse at center, rgba(255, 255, 255, 0.98) 0%, rgba(255, 255, 255, 0.4) 60%, transparent 100%);
            transform: rotate(-35deg);
        }

        /* Pantulan kedua sudut bawah (Bottom Rim Glint) */
        .soap-bubble::after {
            content: '';
            position: absolute;
            bottom: 14%;
            right: 18%;
            width: 14%;
            height: 10%;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.6);
            transform: rotate(25deg);
        }

        .soap-bubble:hover {
            transform: scale(1.15) !important;
            filter: brightness(1.2) drop-shadow(0 0 8px rgba(56, 189, 248, 0.4));
        }

        .soap-bubble:active {
            transform: scale(0.92) !important;
        }

        /* Animasi meletus/pecah gelembung (Bubble Pop Shockwave) */
        @keyframes bubblePopShock {
            0% {
                transform: scale(1);
                opacity: 1;
                filter: brightness(1.3);
            }
            30% {
                transform: scale(1.4);
                opacity: 0.9;
                border-color: #ffffff;
                filter: brightness(2) blur(0.5px);
            }
            100% {
                transform: scale(1.85);
                opacity: 0;
                filter: blur(5px) brightness(2.4);
            }
        }

        /* Riak Lingkaran Gelombang Kejut Air (Water Ripple Ring) */
        .water-ripple-ring {
            position: fixed;
            border-radius: 50%;
            pointer-events: none;
            border: 2px solid rgba(56, 189, 248, 0.85);
            box-shadow: 0 0 12px rgba(56, 189, 248, 0.6), inset 0 0 8px rgba(255, 255, 255, 0.9);
            z-index: 9999;
            transform-origin: center center;
            animation: waterRippleExpand 0.38s cubic-bezier(0.1, 0.8, 0.3, 1) forwards;
        }

        @keyframes waterRippleExpand {
            0% {
                transform: translate(-50%, -50%) scale(0.2);
                opacity: 0.95;
            }
            100% {
                transform: translate(-50%, -50%) scale(2.2);
                opacity: 0;
            }
        }

        /* Butiran Percikan Air Nyata (Realistic Water Splash Beads) */
        .splash-water-bead {
            position: fixed;
            border-radius: 50%;
            pointer-events: none;
            background: radial-gradient(circle at 30% 30%, #ffffff 0%, #e0f2fe 30%, #38bdf8 75%, #0284c7 100%);
            box-shadow: 0 1px 3px rgba(2, 132, 199, 0.4), inset 0 0 2px rgba(255, 255, 255, 0.9);
            z-index: 9999;
            will-change: transform, opacity;
            animation: waterBeadTrajectory var(--duration, 0.5s) cubic-bezier(0.12, 0.85, 0.35, 1) forwards;
        }

        /* Kilau pantulan cahaya pada butiran air */
        .splash-water-bead::after {
            content: '';
            position: absolute;
            top: 15%;
            left: 20%;
            width: 30%;
            height: 30%;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.95);
        }

        /* Gerakan percikan air dengan gravitasi melengkung alami */
        @keyframes waterBeadTrajectory {
            0% {
                transform: translate(0, 0) scale(1.2);
                opacity: 1;
            }
            65% {
                opacity: 0.9;
            }
            100% {
                transform: translate(var(--dx), calc(var(--dy) + var(--gravity, 25px))) scale(0.15);
                opacity: 0;
            }
        }

        /* Partikel Halus Kabut Busa Air (Foam Mist) */
        .foam-mist-particle {
            position: fixed;
            border-radius: 50%;
            pointer-events: none;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.9) 0%, rgba(186, 230, 253, 0.7) 60%, transparent 100%);
            filter: blur(1px);
            z-index: 9998;
            animation: foamMistFade 0.35s ease-out forwards;
        }

        @keyframes foamMistFade {
            0% {
                transform: translate(-50%, -50%) scale(0.3);
                opacity: 0.9;
            }
            100% {
                transform: translate(calc(-50% + var(--mdx)), calc(-50% + var(--mdy))) scale(1.6);
                opacity: 0;
            }
        }

        @keyframes floatUp1 {
            0% { transform: translateY(105vh) translateX(0) scale(0.85); opacity: 0; }
            10% { opacity: 0.85; }
            40% { transform: translateY(65vh) translateX(28px) scale(0.98); }
            70% { transform: translateY(30vh) translateX(-22px) scale(1.03); }
            90% { opacity: 0.85; }
            100% { transform: translateY(-15vh) translateX(15px) scale(1.08); opacity: 0; }
        }

        @keyframes floatUp2 {
            0% { transform: translateY(105vh) translateX(0) scale(0.8); opacity: 0; }
            12% { opacity: 0.8; }
            45% { transform: translateY(60vh) translateX(-32px) scale(0.95); }
            75% { transform: translateY(25vh) translateX(26px) scale(1.05); }
            90% { opacity: 0.8; }
            100% { transform: translateY(-15vh) translateX(-18px) scale(1.1); opacity: 0; }
        }

        @keyframes floatUp3 {
            0% { transform: translateY(105vh) translateX(0) scale(0.9); opacity: 0; }
            8% { opacity: 0.85; }
            50% { transform: translateY(50vh) translateX(36px) scale(1.02); }
            85% { opacity: 0.85; }
            100% { transform: translateY(-15vh) translateX(-30px) scale(1.08); opacity: 0; }
        }
    </style>
    @stack('styles')
</head>
<body class="bg-[#f8fafc] text-slate-700 antialiased min-h-screen flex flex-col justify-between relative overflow-x-hidden">

    <!-- AESTHETIC LAUNDRY SOAP BUBBLES AMBIENT BACKGROUND -->
    <div class="bubble-ambient-container" id="ambientBubblesContainer" aria-hidden="true">
        <div class="soap-bubble" style="width: 55px; height: 55px; left: 5%; animation: floatUp1 15s infinite ease-in-out; animation-delay: -3s;"></div>
        <div class="soap-bubble" style="width: 25px; height: 25px; left: 14%; animation: floatUp2 11s infinite ease-in-out; animation-delay: -7s;"></div>
        <div class="soap-bubble" style="width: 80px; height: 80px; left: 24%; animation: floatUp3 18s infinite ease-in-out; animation-delay: -12s;"></div>
        <div class="soap-bubble" style="width: 35px; height: 35px; left: 38%; animation: floatUp1 13s infinite ease-in-out; animation-delay: -5s;"></div>
        <div class="soap-bubble" style="width: 105px; height: 105px; left: 50%; animation: floatUp2 21s infinite ease-in-out; animation-delay: -9s;"></div>
        <div class="soap-bubble" style="width: 28px; height: 28px; left: 63%; animation: floatUp3 12s infinite ease-in-out; animation-delay: -4s;"></div>
        <div class="soap-bubble" style="width: 65px; height: 65px; left: 74%; animation: floatUp1 16s infinite ease-in-out; animation-delay: -14s;"></div>
        <div class="soap-bubble" style="width: 22px; height: 22px; left: 83%; animation: floatUp2 10s infinite ease-in-out; animation-delay: -2s;"></div>
        <div class="soap-bubble" style="width: 90px; height: 90px; left: 91%; animation: floatUp3 19s infinite ease-in-out; animation-delay: -10s;"></div>
        <div class="soap-bubble" style="width: 40px; height: 40px; left: 45%; animation: floatUp1 14s infinite ease-in-out; animation-delay: -6s;"></div>
    </div>

    <!-- TOP NAVIGATION BAR -->
    <header class="bg-white/95 backdrop-blur border-b border-slate-200/80 sticky top-0 z-40 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-18 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-tr from-[#0288d1] to-[#29b6f6] text-white rounded-xl flex items-center justify-center text-xl shadow-md shadow-sky-500/20">
                    🧺
                </div>
                <div>
                    <span class="text-lg font-bold text-slate-800 tracking-tight block leading-tight">Maudy Laundry</span>
                    <span class="text-[11px] text-sky-600 font-semibold tracking-wide">Pemberian Servis Laundry Terbaik</span>
                </div>
            </a>

            <!-- Nav links -->
            <nav class="hidden md:flex items-center gap-8 text-sm font-medium">
                <a href="{{ route('home') }}#beranda" class="text-slate-600 hover:text-[#0288d1] transition">Beranda</a>
                <a href="{{ route('home') }}#layanan" class="text-slate-600 hover:text-[#0288d1] transition">Layanan & Tarif</a>
                <a href="{{ route('public.tracking') }}" class="text-slate-600 hover:text-[#0288d1] transition">Cek Status Resi</a>
                <a href="{{ route('home') }}#pesan" class="text-[#0288d1] font-semibold hover:text-[#0277bd] transition">Pesan Online</a>
            </nav>

            <!-- Actions -->
            <div class="flex items-center gap-3">
                <a href="{{ route('public.tracking') }}" 
                   class="hidden sm:inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold text-sky-700 bg-sky-50 hover:bg-sky-100 rounded-xl transition border border-sky-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <span>Lacak Pesanan</span>
                </a>
                
                @if(Auth::check())
                <a href="{{ route('dashboard') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold text-white bg-[#0288d1] hover:bg-[#0277bd] rounded-xl shadow-sm transition">
                    <span>Ke Dashboard Internal</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
                @else
                <a href="{{ route('login') }}" 
                   class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-semibold text-slate-700 hover:text-[#0288d1] bg-slate-100 hover:bg-slate-200/80 rounded-xl transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                    <span>Login Internal</span>
                </a>
                @endif
            </div>
        </div>
    </header>

    <!-- CONTENT -->
    <main class="flex-1">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 pt-6">
            @if(session('error'))
            <div class="p-4 mb-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 text-xs flex items-start gap-3 shadow-xs">
                <span class="text-base">⚠️</span>
                <div>
                    <strong class="font-bold">Pemberitahuan:</strong> {{ session('error') }}
                </div>
            </div>
            @endif

            @if(session('success'))
            <div class="p-4 mb-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs flex items-start gap-3 shadow-xs">
                <span class="text-base">✓</span>
                <div>
                    <strong class="font-bold">Berhasil:</strong> {{ session('success') }}
                </div>
            </div>
            @endif

            @if($errors->any())
            <div class="p-4 mb-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-900 text-xs flex items-start gap-3 shadow-xs">
                <span class="text-base">✕</span>
                <div>
                    <strong class="font-bold">Mohon periksa data:</strong>
                    <ul class="list-disc list-inside mt-1 space-y-0.5">
                        @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif
        </div>

        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer class="bg-slate-900 text-slate-400 text-xs py-10 mt-16 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 pb-8 border-b border-slate-800">
                <div>
                    <div class="flex items-center gap-2 text-white font-bold text-base mb-2">
                        <span>🧺</span> Maudy Laundry
                    </div>
                    <p class="text-slate-400 leading-relaxed text-xs">
                        Sistem Informasi Manajemen Laundry Terpadu. Menghadirkan solusi cuci cepat, higienis, dan terpercaya dengan teknologi penelusuran resi real-time.
                    </p>
                </div>
                <div>
                    <h4 class="text-white font-semibold text-sm mb-3">Jam Operasional & Kontak</h4>
                    <p class="text-slate-400">Senin - Sabtu: 07:00 - 21:00 WIB</p>
                    <p class="text-slate-400">Minggu: 08:00 - 17:00 WIB</p>
                    <p class="text-slate-400 mt-2 font-mono">WhatsApp/Telp: 0812-0000-1111</p>
                    <p class="text-slate-400">Dusun Maju, Desa Durian, Kec. Rantau, Kab. Aceh Tamiang</p>
                </div>
                <div>
                    <h4 class="text-white font-semibold text-sm mb-3">Proyek Akademik</h4>
                    <p class="text-slate-400">Tugas Ujian Tengah Semester (UTS)</p>
                    <p class="text-slate-400">Mata Kuliah: Web Programming 2</p>
                    <p class="text-slate-400">Arsitektur Laravel MVC & MySQL Laragon</p>
                </div>
            </div>
            <div class="pt-6 flex flex-col sm:flex-row items-center justify-between text-slate-500 gap-3">
                <p>&copy; 2026 Maudy Laundry. Hak cipta dilindungi undang-undang.</p>
                <div class="flex items-center gap-4">
                    <a href="{{ route('public.tracking') }}" class="hover:text-sky-400 transition">Cek Resi</a>
                    <a href="{{ route('login') }}" class="hover:text-sky-400 transition">Portal Karyawan</a>
                </div>
            </div>
        </div>
    </footer>

    @stack('scripts')
    <script>
        // Web Audio API synthesized bubble pop + water splash snap sound
        function playPopSound() {
            try {
                const AudioCtx = window.AudioContext || window.webkitAudioContext;
                if (!AudioCtx) return;
                const ctx = new AudioCtx();
                const startTime = ctx.currentTime;

                // 1. Oscillator frekuensi rendah-sedang (suara membran letupan balon air "plop")
                const osc1 = ctx.createOscillator();
                const gain1 = ctx.createGain();
                osc1.type = 'sine';
                osc1.frequency.setValueAtTime(420, startTime);
                osc1.frequency.exponentialRampToValueAtTime(1050, startTime + 0.035);
                osc1.frequency.exponentialRampToValueAtTime(160, startTime + 0.08);

                gain1.gain.setValueAtTime(0.3, startTime);
                gain1.gain.exponentialRampToValueAtTime(0.01, startTime + 0.08);

                osc1.connect(gain1);
                gain1.connect(ctx.destination);
                osc1.start(startTime);
                osc1.stop(startTime + 0.085);

                // 2. Oscillator frekuensi tinggi (suara klik percikan air renyah "snap/splash")
                const osc2 = ctx.createOscillator();
                const gain2 = ctx.createGain();
                osc2.type = 'triangle';
                osc2.frequency.setValueAtTime(1900, startTime);
                osc2.frequency.exponentialRampToValueAtTime(2500, startTime + 0.02);
                osc2.frequency.exponentialRampToValueAtTime(500, startTime + 0.06);

                gain2.gain.setValueAtTime(0.2, startTime);
                gain2.gain.exponentialRampToValueAtTime(0.01, startTime + 0.06);

                osc2.connect(gain2);
                gain2.connect(ctx.destination);
                osc2.start(startTime);
                osc2.stop(startTime + 0.065);
            } catch (e) {}
        }

        // Generator percikan air nyata (Realistic Water Splash beads, ripples & foam mist)
        function createRealisticWaterSplash(cx, cy, radius) {
            // 1. Riak gelombang kejut air membesar (Water Ripple Ring)
            const ripple = document.createElement('div');
            ripple.className = 'water-ripple-ring';
            ripple.style.left = cx + 'px';
            ripple.style.top = cy + 'px';
            const rippleSize = Math.max(radius * 2, 45);
            ripple.style.width = rippleSize + 'px';
            ripple.style.height = rippleSize + 'px';
            document.body.appendChild(ripple);
            setTimeout(() => ripple.remove(), 420);

            // 2. Butiran air berkilau memercik dengan kurva gravitasi alami (Water Beads)
            const numDroplets = Math.floor(Math.random() * 6) + 18; // 18 - 23 butiran air
            for (let i = 0; i < numDroplets; i++) {
                const bead = document.createElement('div');
                bead.className = 'splash-water-bead';

                // Variasi butiran (butiran besar 6-9px, sedang 4-6px, mist halus 2.5-4px)
                const randType = Math.random();
                let size;
                if (randType > 0.8) {
                    size = Math.random() * 3 + 6.5; // Butiran air besar
                } else if (randType > 0.35) {
                    size = Math.random() * 2 + 4; // Butiran air sedang
                } else {
                    size = Math.random() * 1.5 + 2.5; // Butiran semprotan halus
                }

                bead.style.width = size + 'px';
                bead.style.height = size + 'px';
                bead.style.left = (cx - size / 2) + 'px';
                bead.style.top = (cy - size / 2) + 'px';

                // Sudut hamburan 360 derajat
                const angle = (i * (360 / numDroplets) + (Math.random() * 24 - 12)) * (Math.PI / 180);
                // Kecepatan dan jarak lontaran percikan
                const distance = radius * 0.75 + (Math.random() * 55 + 25);
                const dx = Math.cos(angle) * distance;
                const dy = Math.sin(angle) * distance;

                // Gravitasi alami (butiran air melengkung jatuh ke bawah)
                const gravity = Math.random() * 22 + 18;
                const duration = (Math.random() * 0.15 + 0.38) + 's';

                bead.style.setProperty('--dx', `${dx}px`);
                bead.style.setProperty('--dy', `${dy}px`);
                bead.style.setProperty('--gravity', `${gravity}px`);
                bead.style.setProperty('--duration', duration);

                document.body.appendChild(bead);
                setTimeout(() => bead.remove(), 550);
            }

            // 3. Partikel kabut busa halus di pusat letupan (Soap Foam Mist)
            for (let j = 0; j < 5; j++) {
                const mist = document.createElement('div');
                mist.className = 'foam-mist-particle';
                const mistSize = Math.random() * 16 + 14;
                mist.style.width = mistSize + 'px';
                mist.style.height = mistSize + 'px';
                mist.style.left = cx + 'px';
                mist.style.top = cy + 'px';

                const mAngle = Math.random() * Math.PI * 2;
                const mDist = Math.random() * 22;
                mist.style.setProperty('--mdx', `${Math.cos(mAngle) * mDist}px`);
                mist.style.setProperty('--mdy', `${Math.sin(mAngle) * mDist}px`);

                document.body.appendChild(mist);
                setTimeout(() => mist.remove(), 380);
            }
        }

        // Fungsi meletuskan gelembung dengan efek pecah dan percikan air
        function popBubble(bubble, e) {
            if (!bubble || bubble.dataset.popping === 'true') return;
            bubble.dataset.popping = 'true';

            if (e) {
                if (e.cancelable) e.preventDefault();
                e.stopPropagation();
            }

            playPopSound();

            const rect = bubble.getBoundingClientRect();
            const cx = rect.left + rect.width / 2;
            const cy = rect.top + rect.height / 2;
            const radius = Math.max(rect.width / 2, 16);

            createRealisticWaterSplash(cx, cy, radius);

            // Terapkan animasi meletus cepat
            bubble.style.animation = 'bubblePopShock 0.22s cubic-bezier(0.15, 0.85, 0.35, 1.2) forwards';

            const isHeroBubble = bubble.classList.contains('hero-bubble');
            const heroMatch = isHeroBubble ? bubble.className.match(/hero-bubble-\d/) : null;
            const heroClass = heroMatch ? heroMatch[0] : null;
            const parentContainer = bubble.parentElement;

            setTimeout(() => {
                bubble.remove();

                // Respawn hero bubble pada maskot keranjang agar tidak kosong selamanya
                if (isHeroBubble && parentContainer && heroClass) {
                    setTimeout(() => {
                        const newHero = document.createElement('div');
                        newHero.className = `hero-bubble ${heroClass}`;
                        newHero.setAttribute('title', 'Tekan untuk meletuskan gelembung!');
                        parentContainer.appendChild(newHero);
                    }, 2500);
                } else if (!isHeroBubble) {
                    // Respawn gelembung latar belakang ambient
                    setTimeout(spawnNewBubble, 1000);
                }
            }, 200);
        }

        // Spawn gelembung ambient baru untuk menggantikan yang meletus
        function spawnNewBubble() {
            const container = document.getElementById('ambientBubblesContainer');
            if (!container || document.querySelectorAll('.soap-bubble').length >= 14) return;

            const b = document.createElement('div');
            b.className = 'soap-bubble';
            b.setAttribute('title', 'Tekan untuk meletuskan gelembung!');
            const size = Math.floor(Math.random() * 65) + 24; // 24px - 89px
            const left = Math.floor(Math.random() * 92) + 4; // 4% - 96%
            const dur = Math.floor(Math.random() * 8) + 12; // 12s - 20s
            const anims = ['floatUp1', 'floatUp2', 'floatUp3'];
            const anim = anims[Math.floor(Math.random() * anims.length)];

            b.style.width = size + 'px';
            b.style.height = size + 'px';
            b.style.left = left + '%';
            b.style.animation = `${anim} ${dur}s infinite ease-in-out`;
            b.style.animationDelay = '0s';

            container.appendChild(b);
        }

        // Global capture-phase event listener: Menjamin 100% semua gelembung (ambient, maskot hero, dll) langsung pecah saat disentuh/diklik tanpa hambatan
        const bubbleTouchEvents = ['pointerdown', 'touchstart', 'mousedown'];
        bubbleTouchEvents.forEach(function (evtName) {
            window.addEventListener(evtName, function (e) {
                const bubble = e.target.closest('.soap-bubble, .hero-bubble, .sidebar-bubble');
                if (bubble && bubble.dataset.popping !== 'true') {
                    popBubble(bubble, e);
                }
            }, { capture: true, passive: false });
        });

        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.soap-bubble, .hero-bubble').forEach(function (b) {
                b.setAttribute('title', 'Tekan untuk meletuskan gelembung!');
            });
        });
    </script>
</body>
</html>
