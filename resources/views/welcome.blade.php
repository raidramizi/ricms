<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>UniKL MIIT Research System</title>

<style>
body{
    margin:0;
    font-family:'Segoe UI', sans-serif;
    min-height:100vh;
}

/* ================= BACKGROUND SLIDER ================= */
.bg-slider{
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    z-index:-2;
}

.bg-slider img{
    position:absolute;
    width:100%;
    height:100%;
    object-fit:cover;
    opacity:0;
    animation:slideShow 18s infinite;
}

.bg-slider img:nth-child(1){animation-delay:0s;}
.bg-slider img:nth-child(2){animation-delay:6s;}
.bg-slider img:nth-child(3){animation-delay:12s;}

@keyframes slideShow{
    0%{opacity:0;transform:scale(1);}
    10%{opacity:1;transform:scale(1.05);}
    30%{opacity:1;}
    40%{opacity:0;}
    100%{opacity:0;}
}

/* DARK OVERLAY */
.overlay{
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.55);
    z-index:-1;
}

/* ================= HEADER (BIGGER FIX) ================= */
.header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:18px 40px;          /* 🔥 bigger height */
    background:white;
    box-shadow:0 4px 12px rgba(0,0,0,0.08);
    position:sticky;
    top:0;
    z-index:10;
}

.logo{
    display:flex;
    align-items:center;
    gap:12px;
}

.logo img{
    height:70px;               /* 🔥 bigger logo */
}

.logo h1{
    font-size:20px;            /* 🔥 bigger title */
    color:#111827;
    font-weight:700;
}

.nav a{
    margin-left:22px;
    text-decoration:none;
    color:#374151;
    font-weight:500;
    font-size:15px;
}

.nav a:hover{
    color:#2563eb;
}

.register-btn{
    background:#2563eb;
    color:white !important;
    padding:8px 16px;
    border-radius:25px;
}

/* ================= HERO ================= */
.hero{
    min-height:100vh;
    display:flex;
    align-items:flex-start;
    justify-content:center;
    text-align:center;
    padding:110px 20px 0;
    color:white;
}

.hero-content{
    max-width:800px;
    animation:fadeUp 1s ease;
}

.hero h2{
    font-size:42px;
    margin-bottom:10px;
}

.hero h3{
    font-weight:400;
    color:#e5e7eb;
}

.hero p{
    margin-top:20px;
    color:#f3f4f6;
    line-height:1.6;
}

/* ================= FOOTER ================= */
.footer{
    background:#0f172a;
    padding:50px 20px;
    color:white;
}

.footer-grid{
    max-width:1100px;
    margin:auto;
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
    gap:30px;
}

.footer p,
.footer a{
    color:#94a3b8;
    font-size:14px;
}

.footer a:hover{
    color:white;
}

footer{
    text-align:center;
    padding:15px;
    border-top:1px solid rgba(255,255,255,0.1);
    color:#94a3b8;
}

/* ANIMATION */
@keyframes fadeUp{
    from{opacity:0;transform:translateY(30px);}
    to{opacity:1;transform:translateY(0);}
}
</style>
</head>

<body>

<!-- BACKGROUND -->
<div class="bg-slider">
    <img src="{{ asset('images/background.jpg') }}">
    <img src="{{ asset('images/background2.jpg') }}">
    <img src="{{ asset('images/background6.png') }}">
</div>

<div class="overlay"></div>

<!-- HEADER -->
<div class="header">
    <div class="logo">
        <img src="{{ asset('images/unikl logo.png') }}">
        <h1>Payment Request and Claim Management System UniKL MIIT</h1>
    </div>

    <div class="nav">
        @auth
            <a href="{{ url('/home') }}">Home</a>
        @else
            <a href="{{ route('login') }}">Login</a>

            @if (Route::has('register'))
                <a href="{{ route('register') }}" class="register-btn">Register</a>
            @endif
        @endauth
    </div>
</div>

<!-- HERO -->
<div class="hero">
    <div class="hero-content">
        <h2>Research & Innovation Claims Management System (RICMS)</h2>
        <h3>UniKL MIIT – Research & Innovation Section</h3>

        <p>
            A centralized platform designed to manage payment requests,
            claims, and approvals efficiently for academicians and administrators.
        </p>
    </div>
</div>

<!-- FOOTER -->
<div class="footer">

    <div class="footer-grid">

        <div>
            <h3>About</h3>
          <p> UniKL MIIT Payment Request &<br/> Claim Management System <br/>supports Research & Innovation<br/> section
                by digitalizing<br/> grant applications, claims, <br/>and funding workflows.</p>
        </div>

        <div>
            <h3>Contact</h3>
            <p>Research & Innovation (R&I)<br/>Universiti Kuala Lumpur MIIT<br/>Email: ri@unikl.edu.my<br/>Phone: +603-0000 0000</p>
        </div>

        <div>
            <h3>Links</h3>
            <p><a href="https://cas.unikl.edu.my/cas-web/login?service=https://portal.unikl.edu.my" target="_blank">UniKL Portal</a></p>
            <p><a href="https://vle.unikl.edu.my/my/" target="_blank">VLE</a></p>
        </div>
        <div>
            <h3>Social Media</h3>
            <p><a href="https://www.instagram.com/uniklmiit/" target="_blank">Instagram</a></p>
        </div>


    </div>

    <footer>
        © {{ date('Y') }}  UniKL MIIT Research & Innovation System. All rights reserved. Raid & Adil Fyp
    </footer>

</div>

</body>
</html>
