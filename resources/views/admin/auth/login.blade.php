<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - SMKN 4 Bogor</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;800&display=swap" rel="stylesheet">
    <style>
        html,body{height:100%}
        body{font-family:'Inter',system-ui,-apple-system,Segoe UI,Roboto,'Helvetica Neue',Arial,'Noto Sans','Apple Color Emoji','Segoe UI Emoji';}
        .glass{background:rgba(255,255,255,.08);backdrop-filter:blur(12px);-webkit-backdrop-filter:blur(12px); border:1px solid rgba(255,255,255,.15)}
        .shape{position:absolute; filter:blur(6px); opacity:.28}
        /* Background image for admin login */
        .bg-admin-login{
          background-image: linear-gradient(rgba(14,42,71,.60), rgba(14,42,71,.60)), url("{{ asset('images/login admin.jpeg') }}");
          background-size: cover;
          background-repeat: no-repeat;
          background-position: center;
          background-attachment: fixed;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/@motionone/dom/dist/motion.min.js"></script>
    <script>
      document.addEventListener('DOMContentLoaded',()=>{
        const card=document.querySelector('#login-card');
        if(card){ card.animate({opacity:[0,1], transform:['translateY(14px)','translateY(0)']},{duration:380, easing:'ease-out'}); }
        const welcome=document.querySelector('#welcome');
        if(welcome){ welcome.animate({opacity:[0,1], transform:['translateY(8px)','translateY(0)']},{duration:420, easing:'ease-out', delay:100}); }
      });
    </script>
  </head>
  <body class="min-h-full bg-admin-login flex items-center justify-center p-6">
    <div class="relative w-full max-w-xl mx-auto">
      <!-- Single centered card -->
      <div id="login-card" class="rounded-2xl shadow-2xl overflow-hidden glass text-white">
        <!-- Header -->
        <div class="px-6 pt-8 pb-5 text-center">
          <img src="{{ asset('images/smkn login.png') }}" alt="Logo SMKN 4 Bogor" class="w-16 h-16 mx-auto mb-2 rounded" />
          <h1 class="text-2xl md:text-3xl font-extrabold text-white">SMK Negeri 4 Kota Bogor</h1>
          <div class="text-white/80 text-sm">Admin Panel</div>
        </div>
        <div class="h-px bg-white/20"></div>

        <!-- Form content -->
        <div class="p-6 md:p-8">
          <h2 class="text-center text-lg font-bold mb-5 text-white">Login Admin</h2>

          @if ($errors->any())
            <div class="mb-4 rounded-md bg-red-500/15 border border-red-400/40 text-red-200 px-3 py-2 text-sm">{{ $errors->first() }}</div>
          @endif

          <form method="POST" action="{{ route('admin.login.submit') }}" class="grid gap-4">
            @csrf
            <div>
              <label class="block text-sm font-medium text-white/90 mb-1">Username</label>
              <input type="text" name="username" value="{{ old('username') }}" class="w-full rounded-md border border-white/30 bg-white/10 text-white placeholder-white/70 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-white/50" required autofocus placeholder="Masukkan username">
            </div>
            <div>
              <label class="block text-sm font-medium text-white/90 mb-1">Password</label>
              <div class="relative">
                <input id="password" type="password" name="password" class="w-full rounded-md border border-white/30 bg-white/10 text-white placeholder-white/70 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-white/50 pr-10" required placeholder="Masukkan password">
                <button type="button" aria-label="toggle password" class="absolute inset-y-0 right-0 px-3 text-white/70 hover:text-white" onclick="const p=document.getElementById('password'); p.type = p.type==='password' ? 'text' : 'password';">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                </button>
              </div>
            </div>

            <!-- Placeholder for captcha if needed later -->
            <!--<div class="rounded-md border border-slate-300 p-3 text-sm text-slate-600">reCAPTCHA placeholder</div>-->

            <button class="mt-2 inline-flex items-center justify-center rounded-md bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-5 py-3 text-sm font-semibold shadow-md hover:brightness-110 transition">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M3 3a2 2 0 012-2h10a2 2 0 012 2v10a2 2 0 01-2 2H8l-5 5v-5H5a2 2 0 002-2V3z"/></svg>
              Login
            </button>
          </form>

          <div class="flex items-center gap-4 mt-6">
            <div class="h-px bg-white/20 flex-1"></div>
            <div class="text-xs text-white/70">SMKN 4 Bogor</div>
            <div class="h-px bg-white/20 flex-1"></div>
          </div>
          <div class="text-center text-xs text-white/70 mt-3">&copy; {{ date('Y') }} SMKN 4 Bogor</div>
        </div>
      </div>
      <div class="text-center mt-4">
        <a href="{{ url('/') }}" class="text-white/90 text-sm underline-offset-4 hover:underline">Lihat Halaman Utama</a>
      </div>
    </div>
  </body>
</html>
