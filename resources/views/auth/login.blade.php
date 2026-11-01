<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Petugas - Maudy Laundry</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }

        /* Aesthetic Soap Bubbles */
        .bubble-ambient-container {
            position: fixed;
            inset: 0;
            pointer-events: none;
            overflow: hidden;
            z-index: 25; /* Di atas latar belakang agar gelembung dapat disentuh/ditekan */
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
                0 8px 24px -2px rgba(2, 136, 209, 0.2);
            backdrop-filter: blur(1px);
            -webkit-backdrop-filter: blur(1px);
            transition: transform 0.15s ease-out, filter 0.15s ease-out;
            will-change: transform, opacity;
            touch-action: manipulation;
        }

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
</head>
<body class="bg-gradient-to-br from-[#e0f2fe] via-[#f0f9ff] to-[#e0f2fe] min-h-screen flex items-center justify-center p-4 relative overflow-hidden">

    <!-- Ambient Soap Bubbles -->
    <div class="bubble-ambient-container" id="loginBubblesContainer" aria-hidden="true">
        <div class="soap-bubble" style="width: 70px; height: 70px; left: 8%; animation: floatUp1 14s infinite ease-in-out; animation-delay: -3s;"></div>
        <div class="soap-bubble" style="width: 35px; height: 35px; left: 18%; animation: floatUp2 10s infinite ease-in-out; animation-delay: -6s;"></div>
        <div class="soap-bubble" style="width: 95px; height: 95px; left: 82%; animation: floatUp3 17s infinite ease-in-out; animation-delay: -11s;"></div>
        <div class="soap-bubble" style="width: 45px; height: 45px; left: 92%; animation: floatUp1 12s infinite ease-in-out; animation-delay: -4s;"></div>
        <div class="soap-bubble" style="width: 28px; height: 28px; left: 28%; animation: floatUp2 15s infinite ease-in-out; animation-delay: -8s;"></div>
        <div class="soap-bubble" style="width: 60px; height: 60px; left: 72%; animation: floatUp3 13s infinite ease-in-out; animation-delay: -2s;"></div>
    </div>

    <div class="max-w-md w-full bg-white rounded-3xl shadow-xl border border-sky-100 p-8 relative overflow-hidden">
        <!-- Decorative blur background -->
        <div class="absolute -top-12 -right-12 w-40 h-40 bg-sky-200/50 rounded-full blur-2xl pointer-events-none"></div>
        <div class="absolute -bottom-12 -left-12 w-40 h-40 bg-blue-200/40 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-gradient-to-tr from-[#0288d1] to-[#29b6f6] text-white rounded-2xl flex items-center justify-center text-3xl mx-auto shadow-lg shadow-sky-500/20 mb-3">
                    🧺
                </div>
                <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Maudy Laundry</h1>
                <p class="text-xs text-slate-500 mt-1">Sistem Informasi Manajemen Laundry — Login Internal</p>
            </div>

            <!-- Flash Error -->
            @if(session('error'))
            <div class="mb-5 p-3.5 bg-rose-50 border border-rose-200 rounded-xl text-xs text-rose-700 font-medium">
                {{ session('error') }}
            </div>
            @endif

            @if(session('success'))
            <div class="mb-5 p-3.5 bg-emerald-50 border border-emerald-200 rounded-xl text-xs text-emerald-700 font-medium">
                {{ session('success') }}
            </div>
            @endif

            <!-- Form Login -->
            <form action="{{ route('login') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-700 mb-1">Alamat Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                           placeholder="nama@maudylaundry.com"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-[#0288d1] text-sm text-slate-800 transition">
                    @error('email')
                    <span class="text-rose-600 text-[11px] mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label for="password" class="block text-xs font-semibold text-slate-700">Password</label>
                    </div>
                    <input type="password" id="password" name="password" required
                           placeholder="••••••••"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-[#0288d1] text-sm text-slate-800 transition">
                    @error('password')
                    <span class="text-rose-600 text-[11px] mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 text-[#0288d1] rounded border-slate-300 focus:ring-sky-400">
                        <span class="text-xs text-slate-600 font-medium">Ingat Saya</span>
                    </label>
                    <a href="{{ route('home') }}" class="text-xs text-sky-600 hover:text-sky-700 font-medium">Ke Web Publik</a>
                </div>

                <button type="submit" 
                        class="w-full py-3 bg-[#0288d1] hover:bg-[#0277bd] text-white text-sm font-semibold rounded-xl shadow-lg shadow-sky-500/25 transition active:scale-[0.99] mt-2">
                    Masuk ke Sistem
                </button>
            </form>

            <!-- Quick Demo Login Buttons (Untuk Kemudahan Penguji UTS) -->
            <div class="mt-8 pt-6 border-t border-slate-100">
                <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider text-center mb-3">
                    Akses Cepat Demo Akun (UTS):
                </p>
                <div class="grid grid-cols-2 gap-2 text-xs">
                    <button type="button" onclick="fillLogin('winny@gmail.com', 'password')"
                            class="px-3 py-2 bg-amber-50 hover:bg-amber-100 border border-amber-200 text-amber-900 rounded-xl font-medium transition text-left">
                        <span class="block font-bold">👑 Akun Owner</span>
                        <span class="text-[10px] text-amber-700">winny</span>
                    </button>
                    <button type="button" onclick="fillLogin('dewi@gmail.com', 'password')"
                            class="px-3 py-2 bg-sky-50 hover:bg-sky-100 border border-sky-200 text-sky-900 rounded-xl font-medium transition text-left">
                        <span class="block font-bold">👨‍💼 Staff Cuci</span>
                        <span class="text-[10px] text-sky-700">dewi lestari</span>
                    </button>
                    <button type="button" onclick="fillLogin('jahdam@gmail.com', 'password')"
                            class="px-3 py-2 bg-purple-50 hover:bg-purple-100 border border-purple-200 text-purple-900 rounded-xl font-medium transition text-left">
                        <span class="block font-bold">👩‍💼 Staff Kasir</span>
                        <span class="text-[10px] text-purple-700">jahdam eka</span>
                    </button>
                    <button type="button" onclick="fillLogin('agus@gmail.com', 'password')"
                            class="px-3 py-2 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 text-emerald-900 rounded-xl font-medium transition text-left">
                        <span class="block font-bold">👔 Staff Setrika</span>
                        <span class="text-[10px] text-emerald-700">agus setiawan</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function fillLogin(email, password) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = password;
        }

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

            // 2. Butiran air berkilau memercik dengan gravitasi alami (Water Beads)
            const numDroplets = Math.floor(Math.random() * 6) + 18;
            for (let i = 0; i < numDroplets; i++) {
                const bead = document.createElement('div');
                bead.className = 'splash-water-bead';

                const randType = Math.random();
                let size;
                if (randType > 0.8) {
                    size = Math.random() * 3 + 6.5;
                } else if (randType > 0.35) {
                    size = Math.random() * 2 + 4;
                } else {
                    size = Math.random() * 1.5 + 2.5;
                }

                bead.style.width = size + 'px';
                bead.style.height = size + 'px';
                bead.style.left = (cx - size / 2) + 'px';
                bead.style.top = (cy - size / 2) + 'px';

                const angle = (i * (360 / numDroplets) + (Math.random() * 24 - 12)) * (Math.PI / 180);
                const distance = radius * 0.75 + (Math.random() * 55 + 25);
                const dx = Math.cos(angle) * distance;
                const dy = Math.sin(angle) * distance;

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

            bubble.style.animation = 'bubblePopShock 0.22s cubic-bezier(0.15, 0.85, 0.35, 1.2) forwards';

            setTimeout(() => {
                bubble.remove();
                setTimeout(spawnLoginBubble, 1200);
            }, 200);
        }

        function spawnLoginBubble() {
            const container = document.getElementById('loginBubblesContainer');
            if (!container || document.querySelectorAll('.soap-bubble').length >= 8) return;

            const b = document.createElement('div');
            b.className = 'soap-bubble';
            b.setAttribute('title', 'Tekan untuk meletuskan gelembung!');
            const size = Math.floor(Math.random() * 55) + 30;
            const left = Math.floor(Math.random() * 88) + 6;
            const dur = Math.floor(Math.random() * 7) + 11;
            const anims = ['floatUp1', 'floatUp2', 'floatUp3'];
            const anim = anims[Math.floor(Math.random() * anims.length)];

            b.style.width = size + 'px';
            b.style.height = size + 'px';
            b.style.left = left + '%';
            b.style.animation = `${anim} ${dur}s infinite ease-in-out`;
            b.style.animationDelay = '0s';

            container.appendChild(b);
        }

        // Global capture-phase listener
        const loginTouchEvents = ['pointerdown', 'touchstart', 'mousedown'];
        loginTouchEvents.forEach(function (evtName) {
            window.addEventListener(evtName, function (e) {
                const bubble = e.target.closest('.soap-bubble');
                if (bubble && bubble.dataset.popping !== 'true') {
                    popBubble(bubble, e);
                }
            }, { capture: true, passive: false });
        });

        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.soap-bubble').forEach(function (bubble) {
                bubble.setAttribute('title', 'Tekan untuk meletuskan gelembung!');
            });
        });
    </script>
</body>
</html>
