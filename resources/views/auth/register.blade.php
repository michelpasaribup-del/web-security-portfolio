<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - SecureAI 3D</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>

    <style>
        body {
            font-family: 'Sora', sans-serif;
            background: radial-gradient(circle at 15% 20%, rgba(34, 229, 255, 0.15), transparent 40%),
                radial-gradient(circle at 85% 80%, rgba(255, 46, 154, 0.15), transparent 40%),
                #050510;
            overflow: hidden;
            perspective: 1200px;
        }

        #bg3d {
            position: fixed;
            inset: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            pointer-events: none;
        }

        .stage {
            position: relative;
            z-index: 10;
            animation: rise 1s cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
            transform-style: preserve-3d;
        }

        @keyframes rise {
            0% {
                opacity: 0;
                transform: translateY(50px) rotateX(20deg) scale(0.9);
            }

            100% {
                opacity: 1;
                transform: translateY(0) rotateX(0) scale(1);
            }
        }

        @property --angle {
            syntax: '<angle>';
            initial-value: 0deg;
            inherits: false;
        }

        @keyframes spin {
            to {
                --angle: 360deg;
            }
        }

        .card3d {
            position: relative;
            padding: 2px;
            border-radius: 24px;
            background: conic-gradient(from var(--angle), #22e5ff, #7c4dff, #ff2e9a, #ffd23f, #7dff6b, #22e5ff);
            animation: spin 6s linear infinite;
            box-shadow: 0 20px 60px -15px rgba(34, 229, 255, 0.4), 0 0 40px -10px rgba(255, 46, 154, 0.3);
            transform-style: preserve-3d;
            transition: transform 0.1s ease-out;
        }

        .glass {
            position: relative;
            border-radius: 22px;
            padding: 2rem;
            background: linear-gradient(160deg, rgba(15, 15, 35, 0.95), rgba(5, 5, 15, 0.98));
            transform-style: preserve-3d;
        }

        .z1 {
            transform: translateZ(30px);
        }

        .z2 {
            transform: translateZ(50px);
        }

        .z3 {
            transform: translateZ(70px);
        }

        .field {
            width: 100%;
            padding: 0.85rem 1rem;
            border-radius: 12px;
            color: #fff;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }

        .field::placeholder {
            color: rgba(203, 213, 225, 0.4);
        }

        .field:focus {
            outline: none;
            background: rgba(255, 255, 255, 0.08);
            border-color: #22e5ff;
            box-shadow: 0 0 0 3px rgba(34, 229, 255, 0.2), 0 0 20px rgba(34, 229, 255, 0.3);
        }

        .eye-btn {
            position: absolute;
            right: 0.5rem;
            top: 50%;
            transform: translateY(-50%);
            width: 2.5rem;
            height: 2.5rem;
            display: grid;
            place-items: center;
            border-radius: 8px;
            color: #94a3b8;
            transition: all 0.2s;
        }

        .eye-btn:hover {
            color: #22e5ff;
            background: rgba(34, 229, 255, 0.1);
        }

        .eye-btn[aria-pressed="true"] {
            color: #ffd23f;
        }

        .btn-main {
            width: 100%;
            padding: 1rem;
            border-radius: 12px;
            font-weight: 800;
            color: #050510;
            letter-spacing: 1px;
            background: linear-gradient(90deg, #22e5ff, #7c4dff, #ff2e9a, #ffd23f, #22e5ff);
            background-size: 200% auto;
            animation: shine 4s linear infinite;
            box-shadow: 0 10px 20px -5px rgba(255, 46, 154, 0.5);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        @keyframes shine {
            to {
                background-position: 200% center;
            }
        }

        .btn-main:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 15px 25px -5px rgba(34, 229, 255, 0.6);
        }

        .btn-main:active {
            transform: translateY(0) scale(0.98);
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-4 text-slate-200">

    <canvas id="bg3d"></canvas>

    <div class="stage w-full max-w-md">
        <div class="card3d" id="tiltCard">
            <div class="glass">

                <div class="z2 text-center mb-6">
                    <h1 class="text-3xl font-extrabold text-white">Buat <span class="text-cyan-400">Akun</span></h1>
                    <p class="text-sm text-slate-400 mt-2">Daftar untuk mengakses Dasbor AI</p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf

                    <div class="z1">
                        <label for="name" class="block text-sm font-semibold mb-2 text-slate-300">Nama lengkap</label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" class="field" required autofocus placeholder="Nama Anda">
                        @error('name') <p class="text-pink-400 text-xs mt-2">{{ $message }}</p> @enderror
                    </div>

                    <div class="z1">
                        <label for="email" class="block text-sm font-semibold mb-2 text-slate-300">Alamat email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" class="field" required placeholder="nama@email.com">
                        @error('email') <p class="text-pink-400 text-xs mt-2">{{ $message }}</p> @enderror
                    </div>

                    @php
                    $eyeOff = '
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />';
                    $eyeOpen = '
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />';
                    @endphp

                    <div class="z1">
                        <label for="password" class="block text-sm font-semibold mb-2 text-slate-300">Kata sandi</label>
                        <div class="relative">
                            <input id="password" type="password" name="password" class="field pr-12" required placeholder="Minimal 8 karakter">
                            <button type="button" class="eye-btn" data-toggle-password="password" aria-pressed="false">
                                <svg class="eye-off w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $eyeOff !!}</svg>
                                <svg class="eye-open hidden w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $eyeOpen !!}</svg>
                            </button>
                        </div>
                        @error('password') <p class="text-pink-400 text-xs mt-2">{{ $message }}</p> @enderror
                    </div>

                    <div class="z1">
                        <label for="password_confirmation" class="block text-sm font-semibold mb-2 text-slate-300">Ulangi sandi</label>
                        <div class="relative">
                            <input id="password_confirmation" type="password" name="password_confirmation" class="field pr-12" required placeholder="Ketik ulang kata sandi">
                            <button type="button" class="eye-btn" data-toggle-password="password_confirmation" aria-pressed="false">
                                <svg class="eye-off w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $eyeOff !!}</svg>
                                <svg class="eye-open hidden w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $eyeOpen !!}</svg>
                            </button>
                        </div>
                    </div>

                    <div class="z3 pt-2">
                        <button type="submit" class="btn-main">DAFTAR</button>
                    </div>

                    <p class="z1 text-center text-sm text-slate-400">
                        Sudah punya akun?
                        <a href="{{ route('login') }}" class="text-yellow-400 hover:text-yellow-300 font-semibold transition-colors">Masuk</a>
                    </p>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('[data-toggle-password]');
            if (!btn) return;
            const input = document.getElementById(btn.dataset.togglePassword);
            const show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            btn.setAttribute('aria-pressed', show);
            btn.querySelector('.eye-open').classList.toggle('hidden', !show);
            btn.querySelector('.eye-off').classList.toggle('hidden', show);
        });

        const card = document.getElementById('tiltCard');
        document.addEventListener('mousemove', (e) => {
            const x = (window.innerWidth / 2 - e.clientX) / 25;
            const y = (window.innerHeight / 2 - e.clientY) / 25;
            card.style.transform = `rotateY(${x}deg) rotateX(${y}deg)`;
        });

        const canvas = document.getElementById('bg3d');
        const renderer = new THREE.WebGLRenderer({
            canvas,
            antialias: true,
            alpha: true
        });
        renderer.setPixelRatio(Math.min(devicePixelRatio, 2));
        renderer.setSize(innerWidth, innerHeight);

        const scene = new THREE.Scene();
        const camera = new THREE.PerspectiveCamera(55, innerWidth / innerHeight, 0.1, 100);
        camera.position.z = 15;

        scene.add(new THREE.AmbientLight(0xffffff, 0.5));
        const light1 = new THREE.PointLight(0x22e5ff, 2, 50);
        light1.position.set(-10, 10, 10);
        const light2 = new THREE.PointLight(0xff2e9a, 2, 50);
        light2.position.set(10, -10, 10);
        scene.add(light1, light2);

        const colors = [0x22e5ff, 0x7c4dff, 0xff2e9a, 0xffd23f, 0x7dff6b];
        const geos = [
            new THREE.IcosahedronGeometry(1, 0), new THREE.OctahedronGeometry(1, 0),
            new THREE.TorusGeometry(0.8, 0.3, 16, 50), new THREE.TorusKnotGeometry(0.7, 0.2, 100, 16),
            new THREE.BoxGeometry(1.2, 1.2, 1.2)
        ];

        const objects = [];
        for (let i = 0; i < 25; i++) {
            const color = colors[i % colors.length];
            const mesh = new THREE.Mesh(
                geos[i % geos.length],
                new THREE.MeshStandardMaterial({
                    color,
                    metalness: 0.6,
                    roughness: 0.2,
                    emissive: color,
                    emissiveIntensity: 0.3
                })
            );
            let x = (Math.random() - 0.5) * 40;
            if (Math.abs(x) < 6) x += (x < 0 ? -1 : 1) * 8;
            mesh.position.set(x, (Math.random() - 0.5) * 20, (Math.random() - 0.5) * 15 - 5);
            mesh.scale.setScalar(0.5 + Math.random());
            mesh.userData = {
                speed: Math.random() * 0.02 + 0.01,
                rotX: Math.random() * 0.02,
                rotY: Math.random() * 0.02
            };
            scene.add(mesh);
            objects.push(mesh);
        }

        function animate() {
            requestAnimationFrame(animate);
            objects.forEach(obj => {
                obj.rotation.x += obj.userData.rotX;
                obj.rotation.y += obj.userData.rotY;
                obj.position.y += Math.sin(Date.now() * 0.001 + obj.position.x) * 0.01;
            });
            renderer.render(scene, camera);
        }
        animate();

        window.addEventListener('resize', () => {
            camera.aspect = innerWidth / innerHeight;
            camera.updateProjectionMatrix();
            renderer.setSize(innerWidth, innerHeight);
        });
    </script>
</body>

</html>