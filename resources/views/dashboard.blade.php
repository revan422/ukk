<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard - SkyLine Airlines</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-navy: #0a192f;
            --secondary-navy: #112240;
            --gold: #f4b400;
            --gold-dark: #d49a00;
            --light-blue: #e6f1ff;
            --pastel-pink: #ffb7c5;
            --pastel-blue: #a8d8ea;
            --pastel-purple: #c3aed6;
            --pastel-yellow: #fff3b0;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f8f9ff 0%, #eef1ff 50%, #f0e6ff 100%);
            color: #333; min-height: 100vh; overflow-x: hidden;
        }

        .anime-sky {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            z-index: 0; pointer-events: none; overflow: hidden;
        }
        .floating-cloud {
            position: absolute; background: rgba(255,255,255,0.6);
            border-radius: 50%; animation: floatCloud linear infinite;
        }
        .floating-cloud::before, .floating-cloud::after {
            content: ''; position: absolute; background: inherit; border-radius: 50%;
        }
        .cloud-1 { width: 200px; height: 60px; top: 10%; left: -10%; animation-duration: 25s; opacity: 0.4; }
        .cloud-1::before { width: 80px; height: 80px; top: -40px; left: 20px; }
        .cloud-1::after { width: 100px; height: 100px; top: -50px; left: 60px; }
        .cloud-2 { width: 160px; height: 45px; top: 25%; left: -15%; animation-duration: 30s; animation-delay: -8s; opacity: 0.3; }
        .cloud-2::before { width: 60px; height: 60px; top: -30px; left: 15px; }
        .cloud-2::after { width: 80px; height: 80px; top: -40px; left: 45px; }
        .cloud-3 { width: 180px; height: 50px; top: 40%; left: -20%; animation-duration: 35s; animation-delay: -15s; opacity: 0.35; }
        .cloud-3::before { width: 70px; height: 70px; top: -35px; left: 20px; }
        .cloud-3::after { width: 90px; height: 90px; top: -45px; left: 55px; }
        .floating-star {
            position: absolute; color: rgba(244,180,0,0.15);
            font-size: 14px; animation: twinkle 3s ease-in-out infinite;
        }
        .floating-heart {
            position: absolute; color: rgba(255,107,107,0.2);
            font-size: 12px; animation: heartFloat 4s ease-in-out infinite;
        }
        .floating-sakura {
            position: absolute; color: rgba(255,158,181,0.25);
            font-size: 10px; animation: sakuraFall 6s ease-in-out infinite;
        }

        @keyframes floatCloud {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(calc(100vw + 100%)); }
        }
        @keyframes twinkle { 0%,100%{opacity:0.15;transform:scale(1)} 50%{opacity:0.5;transform:scale(1.3)} }
        @keyframes heartFloat { 0%{transform:translateY(0)rotate(0deg);opacity:0} 50%{opacity:0.5} 100%{transform:translateY(-80px)rotate(360deg);opacity:0} }
        @keyframes sakuraFall { 0%{transform:translateY(-20px)rotate(0deg)scale(0.5);opacity:0} 20%{opacity:0.6} 80%{opacity:0.6} 100%{transform:translateY(100vh)rotate(720deg)scale(1.2);opacity:0} }

        .anime-scene {
            position: relative; width: 100%; max-width: 700px; margin: 0 auto;
            min-height: 380px; background: linear-gradient(180deg,rgba(135,206,235,0.15) 0%,rgba(255,255,255,0.3) 100%);
            border-radius: 30px; padding: 20px 0;
        }
        .pilot-container {
            position: absolute; left: 5%; bottom: 0; cursor: pointer;
            transition: transform 0.3s cubic-bezier(0.34,1.56,0.64,1);
            animation: characterFloat 3s ease-in-out infinite; z-index: 5;
        }
        .pilot-container:hover { transform: scale(1.08) translateY(-5px); filter: drop-shadow(0 10px 30px rgba(26,58,92,0.3)); }
        .pilot-container:active { transform: scale(0.95); }
        .pilot-container.bounce { animation: characterBounce 0.6s cubic-bezier(0.34,1.56,0.64,1); }
        .attendant-container {
            position: absolute; right: 5%; bottom: 0; cursor: pointer;
            transition: transform 0.3s cubic-bezier(0.34,1.56,0.64,1);
            animation: characterFloat 3s ease-in-out infinite 1.5s; z-index: 5;
        }
        .attendant-container:hover { transform: scale(1.08) translateY(-5px); filter: drop-shadow(0 10px 30px rgba(192,57,43,0.3)); }
        .attendant-container:active { transform: scale(0.95); }
        .attendant-container.bounce { animation: characterBounce 0.6s cubic-bezier(0.34,1.56,0.64,1); }

        @keyframes characterFloat { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-10px)} }
        @keyframes characterBounce { 0%{transform:scale(1)translateY(0)} 30%{transform:scale(1.15)translateY(-20px)} 60%{transform:scale(0.95)translateY(5px)} 100%{transform:scale(1)translateY(0)} }

        .speech-bubble {
            position: absolute; top: -80px; left: 50%; transform: translateX(-50%) scale(0);
            background: white; border-radius: 20px; padding: 14px 20px;
            font-size: 13px; font-weight: 500; color: var(--primary-navy);
            box-shadow: 0 10px 40px rgba(0,0,0,0.15); min-width: 200px; max-width: 280px;
            text-align: center; z-index: 20; opacity: 0;
            transition: all 0.5s cubic-bezier(0.34,1.56,0.64,1);
            pointer-events: none; border: 2.5px solid var(--gold); backdrop-filter: blur(10px);
        }
        .speech-bubble.show { opacity: 1; transform: translateX(-50%) scale(1); top: -100px; }
        .speech-bubble::after {
            content: ''; position: absolute; bottom: -12px; left: 50%; transform: translateX(-50%);
            width: 0; height: 0; border-left: 12px solid transparent; border-right: 12px solid transparent; border-top: 12px solid var(--gold);
        }
        .speech-bubble.pilot-bubble { border-color: #1a5276; background: linear-gradient(135deg,rgba(232,244,253,0.95),rgba(208,236,251,0.95)); }
        .speech-bubble.pilot-bubble::after { border-top-color: #1a5276; }
        .speech-bubble.attendant-bubble { border-color: #e74c3c; background: linear-gradient(135deg,rgba(253,232,232,0.95),rgba(253,208,208,0.95)); }
        .speech-bubble.attendant-bubble::after { border-top-color: #e74c3c; }
        .speech-bubble .bubble-emoji { font-size: 24px; display: block; margin-bottom: 6px; animation: emojiPop 0.5s cubic-bezier(0.34,1.56,0.64,1); }
        .speech-bubble .bubble-text { font-size: 12.5px; line-height: 1.5; display: block; font-weight: 500; }
        .speech-bubble .bubble-sub { font-size: 10px; display: block; margin-top: 6px; color: #888; font-style: italic; }
        @keyframes emojiPop { 0%{transform:scale(0)rotate(-30deg)} 100%{transform:scale(1)rotate(0deg)} }

        .pilot-svg { width: 160px; height: 250px; filter: drop-shadow(0 5px 15px rgba(0,0,0,0.1)); }
        .pilot-arm { transform-origin: 80px 120px; animation: pilotWave 3s ease-in-out infinite; }
        .pilot-arm.waving-fast { animation: pilotWaveFast 0.4s ease-in-out infinite; }
        .pilot-eye { animation: blink 4s ease-in-out infinite; }
        .pilot-eye.look-around { animation: lookAround 6s ease-in-out infinite; }
        .pilot-mouth { transition: all 0.3s; }
        .pilot-mouth.talking { animation: talkMouth 0.25s ease-in-out infinite alternate; }
        .pilot-body { animation: breathe 4s ease-in-out infinite; }
        .pilot-head { animation: headTilt 5s ease-in-out infinite; }

        .attendant-svg { width: 150px; height: 250px; filter: drop-shadow(0 5px 15px rgba(0,0,0,0.1)); }
        .attendant-arm { transform-origin: 75px 120px; animation: attendantWave 3s ease-in-out infinite 1.5s; }
        .attendant-arm.waving-fast { animation: attendantWaveFast 0.4s ease-in-out infinite; }
        .attendant-eye { animation: blink 4s ease-in-out infinite 2s; }
        .attendant-eye.look-around { animation: lookAround 6s ease-in-out infinite 3s; }
        .attendant-mouth { transition: all 0.3s; }
        .attendant-mouth.talking { animation: talkMouth 0.25s ease-in-out infinite alternate; }
        .attendant-body { animation: breathe 4s ease-in-out infinite 1s; }
        .attendant-head { animation: headTilt 5s ease-in-out infinite 2s; }
        .pilot-hair-lock, .attendant-hair-lock { animation: hairSway 3s ease-in-out infinite; }
        .attendant-hair-lock { animation-delay: 0.5s; }
        .attendant-hair-back { animation: hairSwayBack 4s ease-in-out infinite; }
        .attendant-scarf { animation: scarfFloat 3s ease-in-out infinite; }

        @keyframes blink { 0%,95%,100%{transform:scaleY(1)} 97%{transform:scaleY(0.1)} }
        @keyframes lookAround { 0%,40%,60%,100%{transform:translateX(0)} 20%{transform:translateX(3px)} 80%{transform:translateX(-3px)} }
        @keyframes talkMouth { 0%{transform:scaleY(0.2)} 100%{transform:scaleY(1)} }
        @keyframes breathe { 0%,100%{transform:scaleY(1)} 50%{transform:scaleY(1.02)} }
        @keyframes headTilt { 0%,90%,100%{transform:rotate(0deg)} 95%{transform:rotate(2deg)} }
        @keyframes pilotWave { 0%,100%{transform:rotate(-15deg)} 50%{transform:rotate(20deg)} }
        @keyframes pilotWaveFast { 0%,100%{transform:rotate(-30deg)} 50%{transform:rotate(30deg)} }
        @keyframes attendantWave { 0%,100%{transform:rotate(15deg)} 50%{transform:rotate(-20deg)} }
        @keyframes attendantWaveFast { 0%,100%{transform:rotate(30deg)} 50%{transform:rotate(-30deg)} }
        @keyframes hairSway { 0%,100%{transform:rotate(0deg)} 50%{transform:rotate(6deg)} }
        @keyframes hairSwayBack { 0%,100%{transform:rotate(0deg)} 50%{transform:rotate(-4deg)} }
        @keyframes scarfFloat { 0%,100%{transform:translateY(0)rotate(0deg)} 50%{transform:translateY(-3px)rotate(3deg)} }

        .click-effect {
            position: absolute; width: 30px; height: 30px; border-radius: 50%;
            background: radial-gradient(circle,var(--gold),transparent);
            pointer-events: none; animation: clickRipple 0.8s ease-out forwards; z-index: 15;
        }
        @keyframes clickRipple { 0%{transform:scale(0);opacity:0.9} 100%{transform:scale(4);opacity:0} }

        .sparkle-burst { position: absolute; pointer-events: none; z-index: 15; }
        .sparkle-particle { position: absolute; width: 6px; height: 6px; border-radius: 50%; animation: sparkleBurst 0.8s ease-out forwards; }
        @keyframes sparkleBurst { 0%{transform:translate(0,0)scale(1);opacity:1} 100%{transform:translate(var(--tx),var(--ty))scale(0);opacity:0} }

        .plane-container {
            position: absolute; top: 3%; left: 50%; transform: translateX(-50%); z-index: 3;
            animation: planeFly 8s ease-in-out infinite;
        }
        .plane-icon { font-size: 48px; color: white; filter: drop-shadow(0 0 20px rgba(244,180,0,0.4)); animation: planeTilt 2s ease-in-out infinite; display: inline-block; }
        .plane-trail { position: absolute; bottom: -8px; left: 50%; transform: translateX(-50%); width: 80px; height: 5px; background: linear-gradient(90deg,transparent,rgba(255,255,255,0.6),transparent); border-radius: 50%; animation: trailFade 2s ease-in-out infinite; }
        .plane-glow { position: absolute; top: 50%; left: 50%; transform: translate(-50%,-50%); width: 80px; height: 80px; background: radial-gradient(circle,rgba(244,180,0,0.15),transparent); border-radius: 50%; animation: glowPulse 2s ease-in-out infinite; }
        @keyframes planeFly { 0%,100%{transform:translateX(-50%)translateY(0)} 25%{transform:translateX(-35%)translateY(-10px)} 75%{transform:translateX(-65%)translateY(-5px)} }
        @keyframes planeTilt { 0%,100%{transform:rotate(-5deg)} 50%{transform:rotate(5deg)} }
        @keyframes trailFade { 0%,100%{opacity:0.3;width:60px} 50%{opacity:1;width:120px} }
        @keyframes glowPulse { 0%,100%{transform:translate(-50%,-50%)scale(0.8);opacity:0.5} 50%{transform:translate(-50%,-50%)scale(1.2);opacity:1} }

        .particles-container { position: absolute; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none; overflow: hidden; z-index: 2; }
        .particle { position: absolute; width: 6px; height: 6px; border-radius: 50%; animation: particleFloat linear infinite; }
        @keyframes particleFloat { 0%{transform:translateY(50px)scale(0);opacity:0} 20%{opacity:1} 100%{transform:translateY(-150px)scale(1.5);opacity:0} }

        .navbar-custom { background: linear-gradient(135deg,var(--primary-navy)0%,var(--secondary-navy)100%); box-shadow: 0 2px 20px rgba(0,0,0,0.1); position: relative; z-index: 10; }
        .navbar-brand { font-size: 24px; font-weight: 800; color: white !important; }
        .navbar-brand span { color: var(--gold); }

        .welcome-card {
            background: linear-gradient(135deg,var(--primary-navy)0%,#1a3a5c 100%);
            border-radius: 20px; border: none; color: white;
            box-shadow: 0 10px 30px rgba(10,25,47,0.15);
            position: relative; overflow: hidden; z-index: 5;
        }
        .welcome-card::after { content: '✈️'; position: absolute; bottom: -20px; right: -10px; font-size: 120px; opacity: 0.06; transform: rotate(-20deg); }
        .sparkle { position: absolute; width: 4px; height: 4px; background: var(--gold); border-radius: 50%; animation: sparkleFloat 3s ease-in-out infinite; }
        .sparkle:nth-child(1) { top: 20%; left: 10%; animation-delay: 0s; }
        .sparkle:nth-child(2) { top: 30%; right: 25%; animation-delay: 0.5s; }
        .sparkle:nth-child(3) { bottom: 25%; left: 20%; animation-delay: 1s; }
        .sparkle:nth-child(4) { bottom: 40%; right: 15%; animation-delay: 1.5s; }
        .sparkle:nth-child(5) { top: 60%; left: 40%; animation-delay: 2s; }
        @keyframes sparkleFloat { 0%,100%{transform:scale(0)rotate(0deg);opacity:0} 50%{transform:scale(1.5)rotate(180deg);opacity:0.8} }

        .menu-card {
            background: rgba(255,255,255,0.9); backdrop-filter: blur(10px);
            border-radius: 15px; border: none; box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            transition: all 0.3s; height: 100%; position: relative; z-index: 5; overflow: hidden;
        }
        .menu-card::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px;
            background: linear-gradient(90deg,var(--gold),var(--pastel-pink),var(--pastel-purple));
            transform: scaleX(0); transition: transform 0.3s;
        }
        .menu-card:hover::before { transform: scaleX(1); }
        .menu-card:hover { transform: translateY(-8px); box-shadow: 0 15px 30px rgba(0,0,0,0.1); }
        .menu-icon { width: 70px; height: 70px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 30px; margin: 0 auto 20px; transition: all 0.3s; }
        .menu-card:hover .menu-icon { transform: scale(1.15) rotate(5deg); }
        .icon-blue { background: rgba(17,153,142,0.1); color: #11998e; }
        .icon-gold { background: rgba(244,180,0,0.1); color: var(--gold); }
        .icon-purple { background: rgba(106,17,203,0.1); color: #6a11cb; }
        .btn-premium { background: linear-gradient(135deg,var(--gold)0%,var(--gold-dark)100%); color: var(--primary-navy); border: none; font-weight: 700; transition: all 0.3s; }
        .btn-premium:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(244,180,0,0.3); color: var(--primary-navy); }
        .btn-outline-primary { border-color: var(--primary-navy); color: var(--primary-navy); }
        .btn-outline-primary:hover { background: var(--primary-navy); color: white; }
        .btn-outline-warning { border-color: var(--gold); color: #b8860b; }
        .btn-outline-warning:hover { background: var(--gold); color: var(--primary-navy); }

        .click-hint {
            position: absolute; bottom: -28px; left: 50%; transform: translateX(-50%);
            font-size: 10px; color: rgba(0,0,0,0.35); white-space: nowrap;
            animation: hintPulse 2s ease-in-out infinite; font-weight: 500;
        }
        @keyframes hintPulse { 0%,100%{opacity:0.3;transform:translateX(-50%)scale(1)} 50%{opacity:0.8;transform:translateX(-50%)scale(1.05)} }

        .music-note { position: absolute; font-size: 18px; opacity: 0; pointer-events: none; animation: noteFloat 2.5s ease-out forwards; z-index: 15; }
        @keyframes noteFloat { 0%{transform:translateY(0)rotate(0deg)scale(0.5);opacity:0.9} 50%{opacity:0.6} 100%{transform:translateY(-80px)rotate(30deg)scale(1.2);opacity:0} }

        .heart-burst { position: absolute; pointer-events: none; z-index: 15; }
        .heart-particle { position: absolute; font-size: 14px; animation: heartBurst 1s ease-out forwards; }
        @keyframes heartBurst { 0%{transform:translate(0,0)scale(0);opacity:1} 100%{transform:translate(var(--hx),var(--hy))scale(1.5);opacity:0} }

        .status-indicator {
            position: absolute; bottom: 5px; right: 5px; width: 12px; height: 12px;
            border-radius: 50%; border: 2px solid white;
            animation: statusPulse 2s ease-in-out infinite;
        }
        .status-indicator.online { background: #2ecc71; }
        .status-indicator.talking { background: #f39c12; }
        @keyframes statusPulse { 0%,100%{box-shadow:0 0 0 0 rgba(46,204,113,0.4)} 50%{box-shadow:0 0 0 6px rgba(46,204,113,0)} }

        /* ===== DESTINASI CARD ===== */
        .destinasi-section { position: relative; z-index: 5; }
        .destinasi-card {
            border-radius: 15px; border: none; overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            transition: all 0.3s; cursor: pointer;
        }
        .destinasi-card:hover { transform: translateY(-5px); box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        .destinasi-image { position: relative; width: 100%; overflow: hidden; }
        .destinasi-image .airplane-svg { width: 100%; height: auto; display: block; transition: transform 0.5s; }
        .destinasi-card:hover .airplane-svg { transform: scale(1.05); }
        .destinasi-overlay {
            position: absolute; bottom: 0; left: 0; right: 0;
            background: linear-gradient(transparent, rgba(0,0,0,0.6));
            padding: 8px; text-align: center;
        }
        .destinasi-label {
            color: white; font-size: 11px; font-weight: 700;
            background: var(--gold); color: var(--primary-navy);
            padding: 3px 10px; border-radius: 20px; display: inline-block;
        }
        .destinasi-info { padding: 10px; text-align: center; }
        .destinasi-info h6 { font-size: 14px; }
        .destinasi-info small { font-size: 11px; }

        @media (max-width: 768px) {
            .anime-scene { min-height: 280px; padding: 10px 0; }
            .pilot-svg { width: 110px; height: 190px; }
            .attendant-svg { width: 105px; height: 190px; }
            .plane-icon { font-size: 32px; }
            .speech-bubble { font-size: 11px; min-width: 150px; max-width: 200px; padding: 10px 14px; top: -60px; }
            .speech-bubble.show { top: -80px; }
            .speech-bubble .bubble-emoji { font-size: 20px; }
            .click-hint { font-size: 9px; bottom: -22px; }
        }
    </style>
</head>
<body>
    <div class="anime-sky">
        <div class="floating-cloud cloud-1"></div>
        <div class="floating-cloud cloud-2"></div>
        <div class="floating-cloud cloud-3"></div>
        <div class="floating-star" style="top:5%;left:10%">✦</div>
        <div class="floating-star" style="top:8%;right:15%">✧</div>
        <div class="floating-star" style="top:15%;left:30%">✦</div>
        <div class="floating-star" style="top:12%;right:40%">✧</div>
        <div class="floating-star" style="top:3%;left:60%">✦</div>
        <div class="floating-heart" style="left:15%;bottom:20%;animation-delay:0s">♥</div>
        <div class="floating-heart" style="left:75%;bottom:40%;animation-delay:1s">♥</div>
        <div class="floating-heart" style="left:50%;bottom:10%;animation-delay:2s">♥</div>
        <div class="floating-heart" style="left:30%;bottom:60%;animation-delay:3s">♥</div>
        <div class="floating-sakura" style="left:5%;animation-delay:0s">🌸</div>
        <div class="floating-sakura" style="left:25%;animation-delay:2s">🌸</div>
        <div class="floating-sakura" style="left:45%;animation-delay:4s">🌸</div>
        <div class="floating-sakura" style="left:65%;animation-delay:1s">🌸</div>
        <div class="floating-sakura" style="left:85%;animation-delay:3s">🌸</div>
    </div>

    <nav class="navbar navbar-dark navbar-custom py-3">
        <div class="container">
            <a class="navbar-brand" href="{{ route('landing') }}"><i class="fas fa-plane-departure me-2"></i>Sky<span>Line</span> Airlines</a>
            <div class="d-flex align-items-center">
                <span class="text-white me-3 d-none d-sm-inline"><i class="far fa-user-circle me-1"></i> Halo, <strong>{{ Auth::user()->name }}</strong> ✨</span>
                <span class="badge bg-warning text-dark me-3 px-3 py-2" style="font-weight:600;"><i class="fas fa-star me-1"></i> Customer</span>
                <a href="{{ route('profile.settings') }}" class="btn btn-outline-light btn-sm px-3 me-2"><i class="fas fa-cog me-1"></i></a>
                <form action="{{ route('logout') }}" method="POST">@csrf<button class="btn btn-outline-light btn-sm px-3"><i class="fas fa-sign-out-alt me-1"></i> Keluar</button></form>
            </div>
        </div>
    </nav>

    <div class="container mt-4 mb-5 position-relative" style="z-index:5;">
        <div class="anime-scene mb-4">
            <div class="plane-container">
                <div class="plane-glow"></div>
                <div class="plane-icon">✈️</div>
                <div class="plane-trail"></div>
            </div>
            <div class="particles-container" id="particlesContainer"></div>

            <!-- PILOT -->
            <div class="pilot-container" id="pilotContainer" onclick="interactPilot(event)">
                <div class="speech-bubble pilot-bubble" id="pilotBubble">
                    <span class="bubble-emoji">👨‍✈️</span>
                    <span class="bubble-text" id="pilotText">Selamat datang di SkyLine!</span>
                    <span class="bubble-sub" id="pilotSub">~ Kapten Hiro ~</span>
                </div>
                <div class="status-indicator online" id="pilotStatus"></div>
                <svg class="pilot-svg" viewBox="0 0 160 250" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <ellipse cx="80" cy="245" rx="40" ry="6" fill="rgba(0,0,0,0.08)"/>
                    <rect x="60" y="200" width="12" height="25" rx="5" fill="#2c3e50"/>
                    <rect x="88" y="200" width="12" height="25" rx="5" fill="#2c3e50"/>
                    <ellipse cx="66" cy="225" rx="8" ry="5" fill="#1a1a2e"/>
                    <ellipse cx="94" cy="225" rx="8" ry="5" fill="#1a1a2e"/>
                    <g class="pilot-body">
                        <rect x="48" y="110" width="64" height="92" rx="12" fill="#1a3a5c"/>
                        <rect x="48" y="110" width="64" height="92" rx="12" fill="url(#pilotUniform)"/>
                        <rect x="63" y="110" width="34" height="14" rx="4" fill="white"/>
                        <polygon points="74,124 86,124 83,148 80,155 77,148" fill="#c0392b"/>
                        <circle cx="80" cy="135" r="3" fill="#f4b400"/>
                        <circle cx="80" cy="150" r="3" fill="#f4b400"/>
                        <circle cx="80" cy="165" r="3" fill="#f4b400"/>
                        <circle cx="92" cy="128" r="5" fill="#f4b400"/>
                        <text x="92" y="130" text-anchor="middle" font-size="5" fill="#0a192f" font-weight="bold">★</text>
                        <rect x="50" y="178" width="60" height="5" rx="2.5" fill="#1a1a2e"/>
                        <rect x="75" y="177" width="10" height="7" rx="2" fill="#f4b400"/>
                    </g>
                    <g class="pilot-arm"><rect x="30" y="115" width="18" height="10" rx="5" fill="#1a3a5c" transform="rotate(-15,39,120)"/><ellipse cx="32" cy="118" rx="5" ry="6" fill="#ffe0bd"/></g>
                    <g class="pilot-arm" style="animation-delay:0.3s"><rect x="112" y="115" width="18" height="10" rx="5" fill="#1a3a5c" transform="rotate(15,121,120)"/><ellipse cx="128" cy="118" rx="5" ry="6" fill="#ffe0bd"/></g>
                    <rect x="68" y="96" width="24" height="16" rx="5" fill="#ffe0bd"/>
                    <g class="pilot-head">
                        <ellipse cx="80" cy="72" rx="28" ry="30" fill="#ffe0bd"/>
                        <path d="M52 60 Q52 30 62 28 Q68 18 80 24 Q92 18 98 28 Q108 30 108 60" fill="#3a2518"/>
                        <path d="M52 60 Q49 52 54 48 Q51 42 58 40 Q55 34 62 32" fill="#3a2518"/>
                        <path d="M108 60 Q111 52 106 48 Q109 42 102 40 Q105 34 98 32" fill="#3a2518"/>
                        <path d="M70 28 Q80 16 90 28" fill="#3a2518"/>
                        <path class="pilot-hair-lock" d="M54 62 Q49 65 46 78 Q44 84 48 80" fill="#3a2518" opacity="0.85"/>
                        <path class="pilot-hair-lock" d="M106 62 Q111 65 114 78 Q116 84 112 80" fill="#3a2518" opacity="0.85" style="animation-delay:0.3s"/>
                        <g class="pilot-eye">
                            <ellipse cx="68" cy="68" rx="8" ry="9" fill="white"/>
                            <ellipse cx="68" cy="70" rx="6" ry="7" fill="#4a90d9"/>
                            <ellipse cx="68" cy="70" rx="3.5" ry="4.5" fill="#2c5f8a"/>
                            <ellipse cx="66" cy="66" rx="2.5" ry="2.5" fill="white" opacity="0.9"/>
                            <ellipse cx="65" cy="65" rx="2" ry="2" fill="white"/>
                        </g>
                        <g class="pilot-eye" style="animation-delay:2s">
                            <ellipse cx="92" cy="68" rx="8" ry="9" fill="white"/>
                            <ellipse cx="92" cy="70" rx="6" ry="7" fill="#4a90d9"/>
                            <ellipse cx="92" cy="70" rx="3.5" ry="4.5" fill="#2c5f8a"/>
                            <ellipse cx="90" cy="66" rx="2.5" ry="2.5" fill="white" opacity="0.9"/>
                            <ellipse cx="89" cy="65" rx="2" ry="2" fill="white"/>
                        </g>
                        <path d="M60 57 Q68 54 76 57" stroke="#3a2518" stroke-width="2" fill="none" stroke-linecap="round"/>
                        <path d="M84 57 Q92 54 100 57" stroke="#3a2518" stroke-width="2" fill="none" stroke-linecap="round"/>
                        <ellipse cx="60" cy="78" rx="5" ry="3" fill="#ffb7c5" opacity="0.35"/>
                        <ellipse cx="100" cy="78" rx="5" ry="3" fill="#ffb7c5" opacity="0.35"/>
                        <path class="pilot-mouth" d="M74 80 Q80 85 86 80" stroke="#d4736f" stroke-width="2" fill="none" stroke-linecap="round"/>
                        <path d="M80 72 Q83 75 80 77" stroke="#e0b89a" stroke-width="1.2" fill="none"/>
                        <ellipse cx="80" cy="42" rx="30" ry="7" fill="#1a3a5c"/>
                        <rect x="56" y="24" width="48" height="18" rx="6" fill="#1a3a5c"/>
                        <circle cx="80" cy="33" r="6" fill="#f4b400"/>
                        <text x="80" y="35" text-anchor="middle" font-size="6" fill="#0a192f" font-weight="bold">✈</text>
                        <ellipse cx="80" cy="42" rx="31" ry="6" fill="#0d2137"/>
                        <rect x="58" y="38" width="44" height="2.5" rx="1" fill="#f4b400"/>
                        <rect x="62" y="26" width="36" height="2.5" rx="1" fill="#f4b400" opacity="0.8"/>
                        <rect x="58" y="31" width="44" height="2.5" rx="1" fill="#f4b400" opacity="0.8"/>
                    </g>
                    <defs><linearGradient id="pilotUniform" x1="0" y1="0" x2="0" y2="1"><stop offset="0%" stop-color="#1a3a5c"/><stop offset="100%" stop-color="#0a192f"/></linearGradient></defs>
                </svg>
                <div class="click-hint">💬 Klik untuk sapaan!</div>
            </div>

            <!-- ATTENDANT -->
            <div class="attendant-container" id="attendantContainer" onclick="interactAttendant(event)">
                <div class="speech-bubble attendant-bubble" id="attendantBubble">
                    <span class="bubble-emoji">👩‍✈️</span>
                    <span class="bubble-text" id="attendantText">Selamat terbang! ✨</span>
                    <span class="bubble-sub" id="attendantSub">~ Yuki ~</span>
                </div>
                <div class="status-indicator online" id="attendantStatus"></div>
                <svg class="attendant-svg" viewBox="0 0 150 250" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <ellipse cx="75" cy="245" rx="35" ry="6" fill="rgba(0,0,0,0.08)"/>
                    <rect x="58" y="200" width="10" height="25" rx="4" fill="#2c1810"/>
                    <rect x="82" y="200" width="10" height="25" rx="4" fill="#2c1810"/>
                    <ellipse cx="63" cy="225" rx="7" ry="4" fill="#c0392b"/>
                    <ellipse cx="87" cy="225" rx="7" ry="4" fill="#c0392b"/>
                    <g class="attendant-body">
                        <path d="M50 195 L62 165 L88 165 L100 195 Z" fill="#c0392b"/>
                        <path d="M50 195 L62 165 L88 165 L100 195 Z" fill="url(#skirtGrad)"/>
                        <rect x="48" y="105" width="54" height="62" rx="10" fill="#c0392b"/>
                        <rect x="48" y="105" width="54" height="62" rx="10" fill="url(#dressGrad)"/>
                        <path d="M62 105 L75 114 L88 105" fill="white"/>
                        <circle cx="75" cy="128" r="2.5" fill="#f4b400"/>
                        <circle cx="75" cy="142" r="2.5" fill="#f4b400"/>
                        <circle cx="75" cy="156" r="2.5" fill="#f4b400"/>
                        <rect x="84" y="122" width="14" height="9" rx="2" fill="white"/>
                        <text x="91" y="129" text-anchor="middle" font-size="3.5" fill="#333" font-weight="bold">YUKI</text>
                    </g>
                    <path class="attendant-scarf" d="M48 110 Q75 120 102 110" stroke="#f39c12" stroke-width="5" fill="none" stroke-linecap="round"/>
                    <path d="M85 110 Q88 120 90 130" stroke="#f39c12" stroke-width="3.5" fill="none" stroke-linecap="round"/>
                    <rect x="50" y="168" width="50" height="3.5" rx="1.5" fill="#8e1a1a"/>
                    <rect x="72" y="167" width="6" height="5.5" rx="2" fill="#f4b400"/>
                    <g class="attendant-arm" style="transform-origin:48px 120px"><rect x="28" y="110" width="20" height="8" rx="4" fill="#ffe0bd" transform="rotate(-10,38,114)"/></g>
                    <g class="attendant-arm" style="transform-origin:102px 120px;animation-delay:0.5s"><rect x="102" y="110" width="20" height="8" rx="4" fill="#ffe0bd" transform="rotate(10,112,114)"/></g>
                    <rect x="65" y="94" width="20" height="13" rx="5" fill="#ffe0bd"/>
                    <g class="attendant-head">
                        <ellipse cx="75" cy="68" rx="26" ry="28" fill="#ffe0bd"/>
                        <path d="M49 58 Q49 38 56 32 Q62 22 75 26 Q88 22 94 32 Q101 38 101 58" fill="#1a0a00"/>
                        <path d="M49 58 Q45 52 48 46 Q47 38 53 36" fill="#1a0a00"/>
                        <path d="M101 58 Q105 52 102 46 Q103 38 97 36" fill="#1a0a00"/>
                        <path d="M56 32 Q62 24 75 28 Q88 24 94 32 Q90 30 86 34 Q80 30 75 33 Q70 30 64 34 Q60 30 56 32" fill="#1a0a00"/>
                        <path class="attendant-hair-lock" d="M51 58 Q47 68 44 85 Q42 98 46 92 Q48 80 50 68" fill="#1a0a00" opacity="0.9"/>
                        <path class="attendant-hair-lock" d="M99 58 Q103 68 106 85 Q108 98 104 92 Q102 80 100 68" fill="#1a0a00" opacity="0.9"/>
                        <path class="attendant-hair-back" d="M55 60 Q50 80 48 100 Q46 110 50 105 Q52 90 56 75" fill="#1a0a00" opacity="0.7"/>
                        <path class="attendant-hair-back" d="M95 60 Q100 80 102 100 Q104 110 100 105 Q98 90 94 75" fill="#1a0a00" opacity="0.7"/>
                        <ellipse cx="102" cy="52" rx="8" ry="5" fill="#f4b400" transform="rotate(25,102,52)"/>
                        <g class="attendant-eye">
                            <ellipse cx="63" cy="64" rx="7.5" ry="8.5" fill="white"/>
                            <ellipse cx="63" cy="66" rx="5.5" ry="6.5" fill="#6a1b9a"/>
                            <ellipse cx="63" cy="66" rx="3" ry="4" fill="#4a148c"/>
                            <ellipse cx="61" cy="62" rx="2.2" ry="2.2" fill="white" opacity="0.9"/>
                            <ellipse cx="60" cy="61" rx="1.5" ry="1.5" fill="white"/>
                        </g>
                        <g class="attendant-eye" style="animation-delay:2.5s">
                            <ellipse cx="87" cy="64" rx="7.5" ry="8.5" fill="white"/>
                            <ellipse cx="87" cy="66" rx="5.5" ry="6.5" fill="#6a1b9a"/>
                            <ellipse cx="87" cy="66" rx="3" ry="4" fill="#4a148c"/>
                            <ellipse cx="85" cy="62" rx="2.2" ry="2.2" fill="white" opacity="0.9"/>
                            <ellipse cx="84" cy="61" rx="1.5" ry="1.5" fill="white"/>
                        </g>
                        <path d="M55 56 Q58 53 60 55" stroke="#1a0a00" stroke-width="1.2" fill="none"/>
                        <path d="M90 56 Q92 53 95 55" stroke="#1a0a00" stroke-width="1.2" fill="none"/>
                        <path d="M56 54 Q63 51 70 54" stroke="#1a0a00" stroke-width="1.5" fill="none" stroke-linecap="round"/>
                        <path d="M80 54 Q87 51 94 54" stroke="#1a0a00" stroke-width="1.5" fill="none" stroke-linecap="round"/>
                        <ellipse cx="56" cy="74" rx="5" ry="3" fill="#ffb7c5" opacity="0.45"/>
                        <ellipse cx="94" cy="74" rx="5" ry="3" fill="#ffb7c5" opacity="0.45"/>
                        <path class="attendant-mouth" d="M69 77 Q75 83 81 77" stroke="#e8827a" stroke-width="2" fill="none" stroke-linecap="round"/>
                        <ellipse cx="75" cy="38" rx="26" ry="6" fill="#c0392b"/>
                        <rect x="54" y="24" width="42" height="14" rx="5" fill="#c0392b"/>
                        <circle cx="75" cy="31" r="5" fill="#f4b400"/>
                        <text x="75" y="33" text-anchor="middle" font-size="5" fill="#0a192f" font-weight="bold">✈</text>
                        <ellipse cx="75" cy="38" rx="27" ry="5.5" fill="#8e1a1a"/>
                        <circle cx="48" cy="62" r="2" fill="#f4b400" opacity="0.9"/>
                        <circle cx="102" cy="62" r="2" fill="#f4b400" opacity="0.9"/>
                    </g>
                    <defs><linearGradient id="dressGrad" x1="0" y1="0" x2="0" y2="1"><stop offset="0%" stop-color="#e74c3c"/><stop offset="100%" stop-color="#c0392b"/></linearGradient>
                    <linearGradient id="skirtGrad" x1="0" y1="0" x2="0" y2="1"><stop offset="0%" stop-color="#c0392b"/><stop offset="100%" stop-color="#a93226"/></linearGradient></defs>
                </svg>
                <div class="click-hint">💬 Klik untuk sapaan!</div>
            </div>
        </div>

        <!-- Welcome Banner -->
        <div class="card welcome-card p-4 mb-4">
            <div class="sparkle"></div><div class="sparkle"></div><div class="sparkle"></div><div class="sparkle"></div><div class="sparkle"></div>
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h3 class="fw-bold">✨ Selamat Datang di SkyLine Airlines! ✨</h3>
                    <p class="mb-0 opacity-75">Siap memulai petualangan baru Anda hari ini? Jelajahi destinasi impian, pesan tiket pesawat, dan nikmati pengalaman terbang terbaik bersama kami! 🌟</p>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <a href="{{ route('landing') }}#flights" class="btn btn-premium btn-lg px-4"><i class="fas fa-search-location me-2"></i>Cari Penerbangan</a>
                </div>
            </div>
        </div>

        <!-- ===== DESTINASI POPULER ===== -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="destinasi-section">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <h4 class="fw-bold mb-1" style="color:var(--primary-navy)"><i class="fas fa-globe-asia me-2" style="color:var(--gold)"></i>Destinasi Populer</h4>
                            <p class="text-muted small mb-0">Jelajahi destinasi favorit para traveler ✈️</p>
                        </div>
                        <a href="{{ route('landing') }}#flights" class="btn btn-sm btn-outline-warning px-3">Lihat Semua <i class="fas fa-arrow-right ms-1"></i></a>
                    </div>
                    <div class="row g-3">
                        <!-- 1. Tokyo - Boeing 787 -->
                        <div class="col-md-3 col-6">
                            <div class="card destinasi-card">
                                <div class="destinasi-image">
                                    <svg viewBox="0 0 200 130" style="width:100%;display:block;background:linear-gradient(180deg,#87CEEB,#E0F4FF);border-radius:10px 10px 0 0" xmlns="http://www.w3.org/2000/svg">
                                        <ellipse cx="30" cy="25" rx="20" ry="8" fill="white" opacity="0.7"/>
                                        <ellipse cx="45" cy="22" rx="15" ry="6" fill="white" opacity="0.6"/>
                                        <ellipse cx="160" cy="35" rx="18" ry="7" fill="white" opacity="0.5"/>
                                        <g transform="translate(60,65)rotate(-5)">
                                            <ellipse cx="50" cy="8" rx="42" ry="6" fill="#1a3a5c"/>
                                            <path d="M20 0 L10 -18 L30 -10 Z" fill="#2c3e50"/><path d="M80 0 L90 -18 L70 -10 Z" fill="#2c3e50"/>
                                            <path d="M20 8 L10 22 L30 16 Z" fill="#2c3e50"/><path d="M80 8 L90 22 L70 16 Z" fill="#2c3e50"/>
                                            <path d="M90 5 L100 -5 L100 12 Z" fill="#c0392b"/>
                                            <circle cx="35" cy="6" r="1.5" fill="white" opacity="0.8"/>
                                            <circle cx="42" cy="6" r="1.5" fill="white" opacity="0.8"/>
                                            <circle cx="49" cy="6" r="1.5" fill="white" opacity="0.8"/>
                                            <circle cx="56" cy="6" r="1.5" fill="white" opacity="0.8"/>
                                            <circle cx="63" cy="6" r="1.5" fill="white" opacity="0.8"/>
                                            <ellipse cx="30" cy="0" rx="4" ry="2.5" fill="#555"/>
                                            <ellipse cx="70" cy="0" rx="4" ry="2.5" fill="#555"/>
                                        </g>
                                        <text x="100" y="115" text-anchor="middle" font-size="10" fill="#333" font-weight="bold">Boeing 787</text>
                                        <text x="100" y="125" text-anchor="middle" font-size="8" fill="#666">Tokyo ✈️</text>
                                    </svg>
                                    <div class="destinasi-overlay"><span class="destinasi-label">Mulai Rp 2.499.000</span></div>
                                </div>
                                <div class="destinasi-info"><h6 class="fw-bold mb-0">🇯🇵 Tokyo</h6><small class="text-muted">Jepang • 7 jam</small></div>
                            </div>
                        </div>
                        <!-- 2. Bali - Airbus A380 -->
                        <div class="col-md-3 col-6">
                            <div class="card destinasi-card">
                                <div class="destinasi-image">
                                    <svg viewBox="0 0 200 130" style="width:100%;display:block;background:linear-gradient(180deg,#4FC3F7,#B3E5FC);border-radius:10px 10px 0 0" xmlns="http://www.w3.org/2000/svg">
                                        <ellipse cx="40" cy="20" rx="16" ry="6" fill="white" opacity="0.6"/>
                                        <ellipse cx="170" cy="30" rx="20" ry="8" fill="white" opacity="0.5"/>
                                        <g transform="translate(55,60)rotate(-3)">
                                            <ellipse cx="55" cy="10" rx="48" ry="8" fill="#f4b400"/>
                                            <path d="M25 3 L12 -22 L38 -14 Z" fill="#d49a00"/><path d="M85 3 L98 -22 L72 -14 Z" fill="#d49a00"/>
                                            <path d="M100 8 L110 -3 L110 15 Z" fill="#e74c3c"/>
                                            <circle cx="45" cy="7" r="1.5" fill="#333" opacity="0.6"/>
                                            <circle cx="52" cy="7" r="1.5" fill="#333" opacity="0.6"/>
                                            <circle cx="59" cy="7" r="1.5" fill="#333" opacity="0.6"/>
                                            <ellipse cx="40" cy="2" rx="4" ry="2.5" fill="#666"/>
                                            <ellipse cx="70" cy="2" rx="4" ry="2.5" fill="#666"/>
                                        </g>
                                        <text x="100" y="115" text-anchor="middle" font-size="10" fill="#333" font-weight="bold">Airbus A380</text>
                                        <text x="100" y="125" text-anchor="middle" font-size="8" fill="#666">Bali ✈️</text>
                                    </svg>
                                    <div class="destinasi-overlay"><span class="destinasi-label">Mulai Rp 899.000</span></div>
                                </div>
                                <div class="destinasi-info"><h6 class="fw-bold mb-0">🏝️ Bali</h6><small class="text-muted">Indonesia • 2 jam</small></div>
                            </div>
                        </div>
                        <!-- 3. Seoul - Boeing 747 -->
                        <div class="col-md-3 col-6">
                            <div class="card destinasi-card">
                                <div class="destinasi-image">
                                    <svg viewBox="0 0 200 130" style="width:100%;display:block;background:linear-gradient(180deg,#FFB74D,#FFE0B2);border-radius:10px 10px 0 0" xmlns="http://www.w3.org/2000/svg">
                                        <ellipse cx="50" cy="22" rx="18" ry="7" fill="white" opacity="0.5"/>
                                        <ellipse cx="150" cy="28" rx="14" ry="5" fill="white" opacity="0.4"/>
                                        <g transform="translate(50,58)rotate(-4)">
                                            <ellipse cx="60" cy="10" rx="50" ry="9" fill="#2c3e50"/>
                                            <ellipse cx="25" cy="4" rx="12" ry="5" fill="#34495e"/>
                                            <path d="M30 2 L15 -25 L42 -16 Z" fill="#1a252f"/><path d="M90 2 L105 -25 L78 -16 Z" fill="#1a252f"/>
                                            <path d="M108 6 L118 -5 L118 18 Z" fill="#e74c3c"/>
                                            <circle cx="38" cy="8" r="1.5" fill="white" opacity="0.7"/>
                                            <circle cx="46" cy="8" r="1.5" fill="white" opacity="0.7"/>
                                            <circle cx="54" cy="8" r="1.5" fill="white" opacity="0.7"/>
                                            <ellipse cx="35" cy="0" rx="4" ry="2.5" fill="#555"/>
                                            <ellipse cx="85" cy="0" rx="4" ry="2.5" fill="#555"/>
                                        </g>
                                        <text x="100" y="115" text-anchor="middle" font-size="10" fill="#333" font-weight="bold">Boeing 747</text>
                                        <text x="100" y="125" text-anchor="middle" font-size="8" fill="#666">Seoul ✈️</text>
                                    </svg>
                                    <div class="destinasi-overlay"><span class="destinasi-label">Mulai Rp 2.199.000</span></div>
                                </div>
                                <div class="destinasi-info"><h6 class="fw-bold mb-0">🇰🇷 Seoul</h6><small class="text-muted">Korea Selatan • 7 jam</small></div>
                            </div>
                        </div>
                        <!-- 4. Singapore - Airbus A320 -->
                        <div class="col-md-3 col-6">
                            <div class="card destinasi-card">
                                <div class="destinasi-image">
                                    <svg viewBox="0 0 200 130" style="width:100%;display:block;background:linear-gradient(180deg,#81D4FA,#E1F5FE);border-radius:10px 10px 0 0" xmlns="http://www.w3.org/2000/svg">
                                        <ellipse cx="35" cy="18" rx="15" ry="6" fill="white" opacity="0.7"/>
                                        <ellipse cx="165" cy="30" rx="17" ry="6" fill="white" opacity="0.5"/>
                                        <g transform="translate(65,65)rotate(-4)">
                                            <ellipse cx="40" cy="6" rx="35" ry="5" fill="white"/>
                                            <rect x="8" y="4" width="65" height="1.5" fill="#2ecc71" opacity="0.8"/>
                                            <path d="M18 0 L8 -14 L25 -8 Z" fill="#27ae60"/><path d="M62 0 L72 -14 L55 -8 Z" fill="#27ae60"/>
                                            <path d="M73 3 L80 -2 L80 9 Z" fill="#2ecc71"/>
                                            <circle cx="32" cy="5" r="1.2" fill="#333" opacity="0.5"/>
                                            <circle cx="39" cy="5" r="1.2" fill="#333" opacity="0.5"/>
                                            <circle cx="46" cy="5" r="1.2" fill="#333" opacity="0.5"/>
                                            <ellipse cx="22" cy="-1" rx="3" ry="2" fill="#999"/>
                                            <ellipse cx="58" cy="-1" rx="3" ry="2" fill="#999"/>
                                        </g>
                                        <text x="100" y="115" text-anchor="middle" font-size="10" fill="#333" font-weight="bold">Airbus A320</text>
                                        <text x="100" y="125" text-anchor="middle" font-size="8" fill="#666">Singapore ✈️</text>
                                    </svg>
                                    <div class="destinasi-overlay"><span class="destinasi-label">Mulai Rp 1.299.000</span></div>
                                </div>
                                <div class="destinasi-info"><h6 class="fw-bold mb-0">🇸🇬 Singapore</h6><small class="text-muted">Singapura • 2 jam</small></div>
                            </div>
                        </div>
                        <!-- 5. Dubai - Boeing 777 -->
                        <div class="col-md-3 col-6">
                            <div class="card destinasi-card">
                                <div class="destinasi-image">
                                    <svg viewBox="0 0 200 130" style="width:100%;display:block;background:linear-gradient(180deg,#FFD54F,#FFF8E1);border-radius:10px 10px 0 0" xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="170" cy="20" r="12" fill="#FF9800" opacity="0.6"/>
                                        <circle cx="170" cy="20" r="8" fill="#FFC107" opacity="0.8"/>
                                        <ellipse cx="40" cy="25" rx="14" ry="5" fill="white" opacity="0.4"/>
                                        <g transform="translate(55,62)rotate(-3)">
                                            <ellipse cx="50" cy="8" rx="45" ry="7" fill="#e74c3c"/>
                                            <rect x="8" y="6" width="85" height="1.5" fill="#f4b400" opacity="0.9"/>
                                            <path d="M22 1 L10 -20 L35 -12 Z" fill="#c0392b"/><path d="M78 1 L90 -20 L65 -12 Z" fill="#c0392b"/>
                                            <path d="M93 5 L102 -3 L102 14 Z" fill="#f4b400"/>
                                            <circle cx="38" cy="7" r="1.3" fill="white" opacity="0.8"/>
                                            <circle cx="46" cy="7" r="1.3" fill="white" opacity="0.8"/>
                                            <circle cx="54" cy="7" r="1.3" fill="white" opacity="0.8"/>
                                            <ellipse cx="25" cy="0" rx="4" ry="2.5" fill="#888"/>
                                            <ellipse cx="75" cy="0" rx="4" ry="2.5" fill="#888"/>
                                        </g>
                                        <text x="100" y="115" text-anchor="middle" font-size="10" fill="#333" font-weight="bold">Boeing 777</text>
                                        <text x="100" y="125" text-anchor="middle" font-size="8" fill="#666">Dubai ✈️</text>
                                    </svg>
                                    <div class="destinasi-overlay"><span class="destinasi-label">Mulai Rp 3.999.000</span></div>
                                </div>
                                <div class="destinasi-info"><h6 class="fw-bold mb-0">🇦🇪 Dubai</h6><small class="text-muted">UAE • 8 jam</small></div>
                            </div>
                        </div>
                        <!-- 6. Bangkok - Airbus A330 -->
                        <div class="col-md-3 col-6">
                            <div class="card destinasi-card">
                                <div class="destinasi-image">
                                    <svg viewBox="0 0 200 130" style="width:100%;display:block;background:linear-gradient(180deg,#CE93D8,#F3E5F5);border-radius:10px 10px 0 0" xmlns="http://www.w3.org/2000/svg">
                                        <ellipse cx="45" cy="20" rx="16" ry="6" fill="white" opacity="0.5"/>
                                        <ellipse cx="155" cy="28" rx="13" ry="5" fill="white" opacity="0.4"/>
                                        <g transform="translate(60,63)rotate(-3)">
                                            <ellipse cx="45" cy="7" rx="40" ry="6" fill="#8e44ad"/>
                                            <path d="M20 0 L8 -16 L28 -9 Z" fill="#6c3483"/><path d="M70 0 L82 -16 L62 -9 Z" fill="#6c3483"/>
                                            <path d="M83 4 L90 -2 L90 11 Z" fill="#6c3483"/>
                                            <circle cx="35" cy="6" r="1.2" fill="white" opacity="0.7"/>
                                            <circle cx="42" cy="6" r="1.2" fill="white" opacity="0.7"/>
                                            <circle cx="49" cy="6" r="1.2" fill="white" opacity="0.7"/>
                                            <ellipse cx="22" cy="-1" rx="3.5" ry="2" fill="#777"/>
                                            <ellipse cx="68" cy="-1" rx="3.5" ry="2" fill="#777"/>
                                        </g>
                                        <text x="100" y="115" text-anchor="middle" font-size="10" fill="#333" font-weight="bold">Airbus A330</text>
                                        <text x="100" y="125" text-anchor="middle" font-size="8" fill="#666">Bangkok ✈️</text>
                                    </svg>
                                    <div class="destinasi-overlay"><span class="destinasi-label">Mulai Rp 1.499.000</span></div>
                                </div>
                                <div class="destinasi-info"><h6 class="fw-bold mb-0">🇹🇭 Bangkok</h6><small class="text-muted">Thailand • 3 jam</small></div>
                            </div>
                        </div>
                        <!-- 7. London - Boeing 787-9 -->
                        <div class="col-md-3 col-6">
                            <div class="card destinasi-card">
                                <div class="destinasi-image">
                                    <svg viewBox="0 0 200 130" style="width:100%;display:block;background:linear-gradient(180deg,#90A4AE,#ECEFF1);border-radius:10px 10px 0 0" xmlns="http://www.w3.org/2000/svg">
                                        <ellipse cx="30" cy="18" rx="18" ry="7" fill="white" opacity="0.4"/>
                                        <ellipse cx="160" cy="22" rx="16" ry="6" fill="white" opacity="0.4"/>
                                        <g transform="translate(52,60)rotate(-3)">
                                            <ellipse cx="55" cy="9" rx="48" ry="7.5" fill="#1a237e"/>
                                            <rect x="10" y="6" width="90" height="1.5" fill="#e74c3c"/>
                                            <path d="M25 2 L12 -22 L38 -13 Z" fill="#0d1b6e"/><path d="M85 2 L98 -22 L72 -13 Z" fill="#0d1b6e"/>
                                            <path d="M101 6 L110 -3 L110 14 Z" fill="#e74c3c"/>
                                            <circle cx="40" cy="8" r="1.3" fill="white" opacity="0.8"/>
                                            <circle cx="48" cy="8" r="1.3" fill="white" opacity="0.8"/>
                                            <circle cx="56" cy="8" r="1.3" fill="white" opacity="0.8"/>
                                            <ellipse cx="30" cy="1" rx="4" ry="2.5" fill="#666"/>
                                            <ellipse cx="80" cy="1" rx="4" ry="2.5" fill="#666"/>
                                        </g>
                                        <text x="100" y="115" text-anchor="middle" font-size="10" fill="#333" font-weight="bold">Boeing 787-9</text>
                                        <text x="100" y="125" text-anchor="middle" font-size="8" fill="#666">London ✈️</text>
                                    </svg>
                                    <div class="destinasi-overlay"><span class="destinasi-label">Mulai Rp 4.999.000</span></div>
                                </div>
                                <div class="destinasi-info"><h6 class="fw-bold mb-0">🇬🇧 London</h6><small class="text-muted">Inggris • 15 jam</small></div>
                            </div>
                        </div>
                        <!-- 8. Paris - Airbus A350 -->
                        <div class="col-md-3 col-6">
                            <div class="card destinasi-card">
                                <div class="destinasi-image">
                                    <svg viewBox="0 0 200 130" style="width:100%;display:block;background:linear-gradient(180deg,#F8BBD0,#FCE4EC);border-radius:10px 10px 0 0" xmlns="http://www.w3.org/2000/svg">
                                        <text x="25" y="22" font-size="10" fill="#e91e63" opacity="0.4">♥</text>
                                        <text x="170" y="30" font-size="8" fill="#e91e63" opacity="0.3">♥</text>
                                        <ellipse cx="45" cy="20" rx="14" ry="5" fill="white" opacity="0.5"/>
                                        <g transform="translate(58,62)rotate(-3)">
                                            <ellipse cx="48" cy="8" rx="42" ry="7" fill="#e91e63"/>
                                            <path d="M22 1 L10 -18 L30 -10 Z" fill="#c2185b"/><path d="M74 1 L86 -18 L66 -10 Z" fill="#c2185b"/>
                                            <path d="M88 5 L96 -2 L96 12 Z" fill="#c2185b"/>
                                            <circle cx="38" cy="7" r="1.3" fill="white" opacity="0.8"/>
                                            <circle cx="46" cy="7" r="1.3" fill="white" opacity="0.8"/>
                                            <circle cx="54" cy="7" r="1.3" fill="white" opacity="0.8"/>
                                            <ellipse cx="25" cy="0" rx="3.5" ry="2" fill="#888"/>
                                            <ellipse cx="71" cy="0" rx="3.5" ry="2" fill="#888"/>
                                        </g>
                                        <text x="100" y="115" text-anchor="middle" font-size="10" fill="#333" font-weight="bold">Airbus A350</text>
                                        <text x="100" y="125" text-anchor="middle" font-size="8" fill="#666">Paris ✈️</text>
                                    </svg>
                                    <div class="destinasi-overlay"><span class="destinasi-label">Mulai Rp 5.499.000</span></div>
                                </div>
                                <div class="destinasi-info"><h6 class="fw-bold mb-0">🇫🇷 Paris</h6><small class="text-muted">Prancis • 16 jam</small></div>
                            </div>
                        </div>
                        <!-- 9. Surabaya - ATR 72 (Propeller) -->
                        <div class="col-md-3 col-6">
                            <div class="card destinasi-card">
                                <div class="destinasi-image">
                                    <svg viewBox="0 0 200 130" style="width:100%;display:block;background:linear-gradient(180deg,#66BB6A,#E8F5E9);border-radius:10px 10px 0 0" xmlns="http://www.w3.org/2000/svg">
                                        <ellipse cx="40" cy="20" rx="16" ry="6" fill="white" opacity="0.6"/>
                                        <ellipse cx="165" cy="28" rx="14" ry="5" fill="white" opacity="0.4"/>
                                        <!-- Sun -->
                                        <circle cx="170" cy="18" r="10" fill="#FFD54F" opacity="0.5"/>
                                        <circle cx="170" cy="18" r="6" fill="#FFC107" opacity="0.7"/>
                                        <!-- ATR 72 Turboprop -->
                                        <g transform="translate(62,62)rotate(-3)">
                                            <ellipse cx="42" cy="7" rx="38" ry="6" fill="#1565C0"/>
                                            <rect x="6" y="5" width="72" height="2" fill="white" opacity="0.7"/>
                                            <!-- Windows -->
                                            <circle cx="28" cy="6" r="1.3" fill="white" opacity="0.8"/>
                                            <circle cx="36" cy="6" r="1.3" fill="white" opacity="0.8"/>
                                            <circle cx="44" cy="6" r="1.3" fill="white" opacity="0.8"/>
                                            <circle cx="52" cy="6" r="1.3" fill="white" opacity="0.8"/>
                                            <!-- High wings -->
                                            <path d="M22 1 L12 -14 L30 -8 Z" fill="#0d47a1"/>
                                            <path d="M62 1 L72 -14 L54 -8 Z" fill="#0d47a1"/>
                                            <!-- Tail -->
                                            <path d="M78 5 L85 -2 L85 12 Z" fill="#e53935"/>
                                            <!-- Propeller engines on wings -->
                                            <circle cx="18" cy="-2" r="3" fill="#555"/>
                                            <circle cx="66" cy="-2" r="3" fill="#555"/>
                                            <!-- Propeller blades -->
                                            <line x1="18" y1="-7" x2="18" y2="3" stroke="#888" stroke-width="1.5" opacity="0.6"/>
                                            <line x1="13" y1="-2" x2="23" y2="-2" stroke="#888" stroke-width="1.5" opacity="0.6"/>
                                            <line x1="66" y1="-7" x2="66" y2="3" stroke="#888" stroke-width="1.5" opacity="0.6"/>
                                            <line x1="61" y1="-2" x2="71" y2="-2" stroke="#888" stroke-width="1.5" opacity="0.6"/>
                                        </g>
                                        <text x="100" y="115" text-anchor="middle" font-size="10" fill="#333" font-weight="bold">ATR 72</text>
                                        <text x="100" y="125" text-anchor="middle" font-size="8" fill="#666">Surabaya ✈️</text>
                                    </svg>
                                    <div class="destinasi-overlay"><span class="destinasi-label">Mulai Rp 599.000</span></div>
                                </div>
                                <div class="destinasi-info"><h6 class="fw-bold mb-0">🌊 Surabaya</h6><small class="text-muted">Jawa Timur • 1.5 jam</small></div>
                            </div>
                        </div>
                        <!-- 10. Batam - Bombardier CRJ -->
                        <div class="col-md-3 col-6">
                            <div class="card destinasi-card">
                                <div class="destinasi-image">
                                    <svg viewBox="0 0 200 130" style="width:100%;display:block;background:linear-gradient(180deg,#4DD0E1,#E0F7FA);border-radius:10px 10px 0 0" xmlns="http://www.w3.org/2000/svg">
                                        <ellipse cx="35" cy="22" rx="14" ry="5" fill="white" opacity="0.5"/>
                                        <ellipse cx="160" cy="30" rx="16" ry="6" fill="white" opacity="0.4"/>
                                        <!-- Waves -->
                                        <path d="M0 100 Q25 90 50 100 Q75 110 100 100 Q125 90 150 100 Q175 110 200 100 V130 H0 Z" fill="#B2EBF2" opacity="0.3"/>
                                        <!-- Bombardier CRJ Regional Jet -->
                                        <g transform="translate(60,60)rotate(-3)">
                                            <ellipse cx="45" cy="7" rx="40" ry="5.5" fill="#FF6F00"/>
                                            <rect x="8" y="5" width="74" height="1.5" fill="white" opacity="0.8"/>
                                            <!-- Windows -->
                                            <circle cx="30" cy="6" r="1.2" fill="white" opacity="0.8"/>
                                            <circle cx="38" cy="6" r="1.2" fill="white" opacity="0.8"/>
                                            <circle cx="46" cy="6" r="1.2" fill="white" opacity="0.8"/>
                                            <circle cx="54" cy="6" r="1.2" fill="white" opacity="0.8"/>
                                            <!-- Wings -->
                                            <path d="M22 1 L12 -15 L30 -8 Z" fill="#e65100"/>
                                            <path d="M68 1 L78 -15 L60 -8 Z" fill="#e65100"/>
                                            <path d="M22 7 L12 18 L30 13 Z" fill="#e65100"/>
                                            <path d="M68 7 L78 18 L60 13 Z" fill="#e65100"/>
                                            <!-- Tail (T-tail) -->
                                            <rect x="80" y="-2" width="10" height="12" rx="2" fill="#e65100"/>
                                            <rect x="75" y="-4" width="20" height="3" rx="1.5" fill="#FF6F00"/>
                                            <!-- Engines -->
                                            <ellipse cx="25" cy="-1" rx="3" ry="2" fill="#777"/>
                                            <ellipse cx="65" cy="-1" rx="3" ry="2" fill="#777"/>
                                        </g>
                                        <text x="100" y="115" text-anchor="middle" font-size="10" fill="#333" font-weight="bold">Bombardier CRJ</text>
                                        <text x="100" y="125" text-anchor="middle" font-size="8" fill="#666">Batam ✈️</text>
                                    </svg>
                                    <div class="destinasi-overlay"><span class="destinasi-label">Mulai Rp 699.000</span></div>
                                </div>
                                <div class="destinasi-info"><h6 class="fw-bold mb-0">🏝️ Batam</h6><small class="text-muted">Kep. Riau • 1.5 jam</small></div>
                            </div>
                        </div>
                        <!-- 11. Makassar - Boeing 737 -->
                        <div class="col-md-3 col-6">
                            <div class="card destinasi-card">
                                <div class="destinasi-image">
                                    <svg viewBox="0 0 200 130" style="width:100%;display:block;background:linear-gradient(180deg,#42A5F5,#BBDEFB);border-radius:10px 10px 0 0" xmlns="http://www.w3.org/2000/svg">
                                        <!-- Sun -->
                                        <circle cx="30" cy="25" r="14" fill="#FF7043" opacity="0.4"/>
                                        <circle cx="30" cy="25" r="9" fill="#FF8A65" opacity="0.6"/>
                                        <ellipse cx="165" cy="22" rx="15" ry="6" fill="white" opacity="0.4"/>
                                        <ellipse cx="180" cy="28" rx="10" ry="4" fill="white" opacity="0.3"/>
                                        <!-- Boeing 737-800NG -->
                                        <g transform="translate(58,63)rotate(-3)">
                                            <ellipse cx="48" cy="7" rx="42" ry="6" fill="white"/>
                                            <rect x="8" y="4" width="80" height="1.5" fill="#1565C0" opacity="0.8"/>
                                            <rect x="8" y="6.5" width="80" height="1.5" fill="#e53935" opacity="0.8"/>
                                            <!-- Windows -->
                                            <circle cx="30" cy="5.5" r="1.3" fill="#333" opacity="0.5"/>
                                            <circle cx="38" cy="5.5" r="1.3" fill="#333" opacity="0.5"/>
                                            <circle cx="46" cy="5.5" r="1.3" fill="#333" opacity="0.5"/>
                                            <circle cx="54" cy="5.5" r="1.3" fill="#333" opacity="0.5"/>
                                            <circle cx="62" cy="5.5" r="1.3" fill="#333" opacity="0.5"/>
                                            <!-- Wings -->
                                            <path d="M22 1 L10 -18 L30 -10 Z" fill="#1565C0"/>
                                            <path d="M74 1 L86 -18 L66 -10 Z" fill="#1565C0"/>
                                            <path d="M22 8 L10 22 L30 15 Z" fill="#1565C0"/>
                                            <path d="M74 8 L86 22 L66 15 Z" fill="#1565C0"/>
                                            <!-- Tail -->
                                            <path d="M88 4 L96 -3 L96 12 Z" fill="#e53935"/>
                                            <!-- Engines -->
                                            <ellipse cx="25" cy="-1" rx="3.5" ry="2.5" fill="#999"/>
                                            <ellipse cx="71" cy="-1" rx="3.5" ry="2.5" fill="#999"/>
                                        </g>
                                        <text x="100" y="115" text-anchor="middle" font-size="10" fill="#333" font-weight="bold">Boeing 737-800</text>
                                        <text x="100" y="125" text-anchor="middle" font-size="8" fill="#666">Makassar ✈️</text>
                                    </svg>
                                    <div class="destinasi-overlay"><span class="destinasi-label">Mulai Rp 799.000</span></div>
                                </div>
                                <div class="destinasi-info"><h6 class="fw-bold mb-0">🌴 Makassar</h6><small class="text-muted">Sulawesi Selatan • 2.5 jam</small></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Menu Grid -->
        <div class="row g-4 mt-2">
            <div class="col-md-4">
                <div class="card menu-card p-4 text-center">
                    <div class="menu-icon icon-blue"><i class="fas fa-ticket-alt"></i></div>
                    <h5 class="fw-bold text-dark">Pesan Tiket Pesawat</h5>
                    <p class="text-muted small">Cari tiket pesawat termurah dan pesan kursi pilihan Anda secara instan. ✈️</p>
                    <a href="{{ route('landing') }}#flights" class="btn btn-outline-primary mt-2">Pesan Sekarang</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card menu-card p-4 text-center">
                    <div class="menu-icon icon-gold"><i class="fas fa-history"></i></div>
                    <h5 class="fw-bold text-dark">Riwayat Pemesanan</h5>
                    <p class="text-muted small">Lihat riwayat transaksi, detail tiket, dan e-ticket Anda. 📋</p>
                    <a href="{{ route('bookings.history') }}" class="btn btn-outline-warning mt-2">Riwayat Transaksi</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card menu-card p-4 text-center">
                    <div class="menu-icon icon-purple"><i class="fas fa-user-cog"></i></div>
                    <h5 class="fw-bold text-dark">Pengaturan Profil</h5>
                    <p class="text-muted small">Kelola data pribadi dan keamanan akun Anda. 🔧</p>
                    <a href="{{ route('profile.settings') }}" class="btn btn-outline-primary mt-2">Pengaturan Akun</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const pilotMessages = [
            { emoji:'👨‍✈️', text:'Halo {{ Auth::user()->name }}! Siap terbang hari ini? ✈️', sub:'Kapten Hiro siap mengantar Anda!' },
            { emoji:'🛩️', text:'Cuaca cerah di seluruh rute! Waktunya booking tiket! ☀️', sub:'Harga promo masih tersedia!' },
            { emoji:'🗺️', text:'Ke Jepang? Korea? Bali? Ayo wujudkan liburan impian! 🌏', sub:'Diskon hingga 30% untuk rute internasional!' },
            { emoji:'⭐', text:'Ada promo spesial 25% untuk penerbangan minggu ini! 🎉', sub:'Buruan, kuota terbatas!' },
            { emoji:'👨‍✈️', text:'Kapten Hiro rekomendasikan booking sekarang! Harga akan naik! 😉', sub:'Dapatkan harga terbaik hari ini!' },
            { emoji:'🌴', text:'Liburan impianmu tinggal selangkah lagi! Pesan sekarang! ✨', sub:'Destinasi favorit: Bali, Lombok, Raja Ampat!' },
            { emoji:'🎯', text:'Tiket promo terbatas! Buruan ambil sebelum kehabisan! 🏃‍♂️', sub:'Sudah 150 tiket terjual hari ini!' },
            { emoji:'💪', text:'Ayo wujudkan mimpi travelingmu! Mulai dari Rp 500rb! 🌟', sub:'Harga termurah se-Indonesia!' },
            { emoji:'🇯🇵', text:'Penerbangan ke Tokyo diskon 35%! Limited edition! 🎌', sub:'Sakura season special!' },
            { emoji:'✈️', text:'Bersama SkyLine, terbang jadi nyaman dan aman! 😊', sub:'Maskapai terbaik 2026!' },
            { emoji:'🎊', text:'Early bird spesial! Diskon 30% untuk 10 pemesan pertama! ⏰', sub:'Hanya hari ini!' },
            { emoji:'💎', text:'Nikmati fasilitas VIP dengan harga ekonomis! 👑', sub:'Lounge, meal spesial, bagasi 30kg!' },
            { emoji:'🌏', text:'Jelajahi 50+ destinasi menarik bersama SkyLine! 🗺️', sub:'Dari Asia hingga Eropa!' },
            { emoji:'🎁', text:'Member baru? Dapatkan voucher diskon 20%! 🎉', sub:'Gratis pendaftaran!' },
            { emoji:'🏆', text:'SkyLine Airlines: #1 di Indonesia! 🥇', sub:'99% pelanggan puas!' },
        ];
        const attendantMessages = [
            { emoji:'👩‍✈️', text:'Halo {{ Auth::user()->name }}! Senang bertemu Anda! 💕', sub:'Yuki siap melayani Anda!' },
            { emoji:'🌸', text:'Ada paket honeymoon spesial lho! Romantis banget! 🎀', sub:'Hotel + Tiket diskon 40%!' },
            { emoji:'🎁', text:'Member baru dapat diskon 25%! Yuk daftar! 🎉', sub:'Gratis bagasi 20kg!' },
            { emoji:'✨', text:'Pengalaman terbang 5 bintang menanti Anda! ⭐⭐⭐⭐⭐', sub:'Review terbaik dari pelanggan setia!' },
            { emoji:'💝', text:'Pesan tiket sekarang, nikmati fasilitas VIP eksklusif! 👑', sub:'Akses lounge, prioritas boarding!' },
            { emoji:'🛍️', text:'Belanja di bandara? Dapatkan bagasi gratis 15kg! 🧳', sub:'Untuk pembelian tiket hari ini!' },
            { emoji:'🌺', text:'Destinasi favorit: Bali, Lombok, Raja Ampat, Labuan Bajo! 🏝️', sub:'Paket liburan mulai Rp 750rb!' },
            { emoji:'🎊', text:'Early bird spesial! Diskon 30% untuk 5 pemesan pertama! ⏰', sub:'Cepat sebelum kehabisan!' },
            { emoji:'💫', text:'Mau duduk di window seat? Pesan sekarang! 🪟', sub:'Pilih kursi favorit Anda!' },
            { emoji:'🍱', text:'Makanan spesial: Halal, Vegetarian, Kids Meal, Japanese! 🍣', sub:'Pesan sekarang, pilih menu favorit!' },
            { emoji:'🎀', text:'Liburan keluarga? Diskon khusus untuk 4 orang! 👨‍👩‍👧‍👦', sub:'Hemat hingga 35%!' },
            { emoji:'🌟', text:'Yuki rekomendasikan penerbangan pagi, view sunrise cantik! 🌅', sub:'Tiket pagi diskon 15%!' },
            { emoji:'💕', text:'Bulan madu ke Maldives? Ada paket spesial! 🏖️', sub:'Hotel bintang 5 + tiket PP mulai 5jt!' },
            { emoji:'🎵', text:'Nikmati hiburan di pesawat: film, musik, game! 🎮', sub:'Terbang jadi tidak membosankan!' },
            { emoji:'🌈', text:'Apapun destinasi impianmu, SkyLine siap mengantarmu! ✈️', sub:'Book now, pay later tersedia!' },
        ];

        let pilotIndex = 0, attendantIndex = 0, pilotTimer = null, attendantTimer = null;
        let isPilotTalking = false, isAttendantTalking = false;

        function interactPilot(event) {
            if (isPilotTalking) return; isPilotTalking = true;
            document.getElementById('pilotStatus').className = 'status-indicator talking';
            const container = document.getElementById('pilotContainer');
            container.classList.remove('bounce'); void container.offsetWidth; container.classList.add('bounce');
            createClickEffect(event);
            const msg = pilotMessages[pilotIndex % pilotMessages.length]; pilotIndex++;
            const bubble = document.getElementById('pilotBubble');
            const text = document.getElementById('pilotText');
            const emoji = bubble.querySelector('.bubble-emoji');
            const sub = document.getElementById('pilotSub');
            emoji.textContent = msg.emoji; text.textContent = msg.text; sub.textContent = '~ ' + (msg.sub || 'Kapten Hiro ~');
            bubble.classList.add('show');
            const arms = container.querySelectorAll('.pilot-arm'); arms.forEach(a => a.classList.add('waving-fast'));
            const mouth = container.querySelector('.pilot-mouth'); mouth.classList.add('talking');
            spawnParticles(event); createSparkleBurst(event);
            if (pilotTimer) clearTimeout(pilotTimer);
            pilotTimer = setTimeout(() => {
                bubble.classList.remove('show');
                arms.forEach(a => a.classList.remove('waving-fast'));
                mouth.classList.remove('talking');
                isPilotTalking = false;
                document.getElementById('pilotStatus').className = 'status-indicator online';
            }, 4500);
        }

        function interactAttendant(event) {
            if (isAttendantTalking) return; isAttendantTalking = true;
            document.getElementById('attendantStatus').className = 'status-indicator talking';
            const container = document.getElementById('attendantContainer');
            container.classList.remove('bounce'); void container.offsetWidth; container.classList.add('bounce');
            createClickEffect(event);
            const msg = attendantMessages[attendantIndex % attendantMessages.length]; attendantIndex++;
            const bubble = document.getElementById('attendantBubble');
            const text = document.getElementById('attendantText');
            const emoji = bubble.querySelector('.bubble-emoji');
            const sub = document.getElementById('attendantSub');
            emoji.textContent = msg.emoji; text.textContent = msg.text; sub.textContent = '~ ' + (msg.sub || 'Yuki ~');
            bubble.classList.add('show');
            const arms = container.querySelectorAll('.attendant-arm'); arms.forEach(a => a.classList.add('waving-fast'));
            const mouth = container.querySelector('.attendant-mouth'); mouth.classList.add('talking');
            spawnParticles(event); createSparkleBurst(event); createMusicNote(event); createHeartBurst(event);
            if (attendantTimer) clearTimeout(attendantTimer);
            attendantTimer = setTimeout(() => {
                bubble.classList.remove('show');
                arms.forEach(a => a.classList.remove('waving-fast'));
                mouth.classList.remove('talking');
                isAttendantTalking = false;
                document.getElementById('attendantStatus').className = 'status-indicator online';
            }, 4500);
        }

        function createClickEffect(event) {
            const container = event.currentTarget;
            const rect = container.getBoundingClientRect();
            const x = (event.clientX || rect.left + rect.width/2) - rect.left;
            const y = (event.clientY || rect.top + rect.height/2) - rect.top;
            const effect = document.createElement('div'); effect.className = 'click-effect';
            effect.style.left = x + 'px'; effect.style.top = y + 'px';
            container.appendChild(effect); setTimeout(() => effect.remove(), 800);
        }

        function createSparkleBurst(event) {
            const container = event.currentTarget;
            const rect = container.getBoundingClientRect();
            const cx = rect.width/2, cy = rect.height/2;
            const burst = document.createElement('div'); burst.className = 'sparkle-burst';
            burst.style.left = cx+'px'; burst.style.top = cy+'px';
            const colors = ['#f4b400','#ffb7c5','#a8d8ea','#c3aed6','#fff3b0','#ff6b6b','#48dbfb'];
            for (let i = 0; i < 8; i++) {
                const p = document.createElement('div'); p.className = 'sparkle-particle';
                const angle = (i/8)*Math.PI*2; const dist = 30+Math.random()*40;
                p.style.setProperty('--tx',Math.cos(angle)*dist+'px'); p.style.setProperty('--ty',Math.sin(angle)*dist+'px');
                p.style.background = colors[Math.floor(Math.random()*colors.length)];
                p.style.width = (3+Math.random()*5)+'px'; p.style.height = p.style.width;
                p.style.animationDelay = (Math.random()*0.2)+'s'; burst.appendChild(p);
            }
            container.appendChild(burst); setTimeout(() => burst.remove(), 1000);
        }

        function spawnParticles(event) {
            const container = document.getElementById('particlesContainer');
            const colors = ['#f4b400','#ffb7c5','#a8d8ea','#c3aed6','#fff3b0','#ff6b6b','#48dbfb','#2ecc71'];
            for (let i = 0; i < 15; i++) {
                const p = document.createElement('div'); p.className = 'particle';
                p.style.left = Math.random()*100+'%'; p.style.top = 40+Math.random()*40+'%';
                p.style.background = colors[Math.floor(Math.random()*colors.length)];
                p.style.width = (3+Math.random()*7)+'px'; p.style.height = p.style.width;
                p.style.animationDuration = (1.5+Math.random()*2.5)+'s'; p.style.animationDelay = (Math.random()*0.5)+'s';
                container.appendChild(p); setTimeout(() => p.remove(), 4000);
            }
        }

        function createMusicNote(event) {
            const container = event.currentTarget;
            const notes = ['♪','♫','♬','🎵','🎶'];
            for (let i = 0; i < 4; i++) {
                const n = document.createElement('div'); n.className = 'music-note';
                n.textContent = notes[Math.floor(Math.random()*notes.length)];
                n.style.left = (15+Math.random()*70)+'%'; n.style.top = (10+Math.random()*40)+'%';
                n.style.color = ['#f4b400','#ffb7c5','#a8d8ea','#c3aed6'][Math.floor(Math.random()*4)];
                n.style.animationDelay = (i*0.15)+'s'; n.style.fontSize = (14+Math.random()*10)+'px';
                container.appendChild(n); setTimeout(() => n.remove(), 3000);
            }
        }

        function createHeartBurst(event) {
            const container = event.currentTarget;
            const rect = container.getBoundingClientRect();
            const cx = rect.width/2, cy = rect.height/3;
            const burst = document.createElement('div'); burst.className = 'heart-burst';
            burst.style.left = cx+'px'; burst.style.top = cy+'px';
            const hearts = ['♥','💕','💗','💖','❤️'];
            for (let i = 0; i < 6; i++) {
                const h = document.createElement('div'); h.className = 'heart-particle';
                h.textContent = hearts[Math.floor(Math.random()*hearts.length)];
                const angle = (i/6)*Math.PI*2+(Math.random()-0.5)*0.5;
                const dist = 40+Math.random()*50;
                h.style.setProperty('--hx',Math.cos(angle)*dist+'px'); h.style.setProperty('--hy',Math.sin(angle)*dist+'px');
                h.style.animationDelay = (Math.random()*0.3)+'s'; burst.appendChild(h);
            }
            container.appendChild(burst); setTimeout(() => burst.remove(), 1500);
        }

        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => { const c = document.getElementById('pilotContainer'); interactPilot({currentTarget:c,clientX:0,clientY:0}); }, 1500);
            setTimeout(() => { const c = document.getElementById('attendantContainer'); interactAttendant({currentTarget:c,clientX:0,clientY:0}); }, 3000);
            setInterval(() => { if(!isPilotTalking) { const c = document.getElementById('pilotContainer'); interactPilot({currentTarget:c,clientX:0,clientY:0}); } }, 12000);
            setInterval(() => { if(!isAttendantTalking) { const c = document.getElementById('attendantContainer'); interactAttendant({currentTarget:c,clientX:0,clientY:0}); } }, 14000);
        });
    </script>
</body>
</html>
