<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Claim') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>

    /* ================= BACKGROUND ================= */
    .auth-bg {
        min-height: 100vh;
        font-family: 'Figtree', sans-serif;

        background: linear-gradient(-45deg, #0f172a, #1e293b, #2563eb, #111827);
        background-size: 400% 400%;
        animation: gradientMove 14s ease infinite;

        position: relative;

        /* ✅ FIX: allow scrolling */
        overflow-x: hidden;
        overflow-y: auto;
    }

    @keyframes gradientMove {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    /* DARK OVERLAY */
    .auth-bg::before {
        content: "";
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.45);
        z-index: 0;
        pointer-events: none;
    }

    /* ================= WRAPPER ================= */
    .auth-wrapper {
        position: relative;
        z-index: 1;

        display: flex;
        flex-direction: column;
        align-items: center;

        justify-content: flex-start;

        padding: 60px 20px 120px;

        /* IMPORTANT: do NOT block scroll */
        min-height: 100vh;
    }

    /* ================= LOGO ================= */
    .auth-logo {
        margin-bottom: 20px;
        opacity: 0.95;
        filter: drop-shadow(0 10px 20px rgba(0,0,0,0.4));
    }

    /* ================= GLASS CARD ================= */
    .auth-card {
        width: 100%;
        max-width: 440px;

        padding: 34px;

        border-radius: 18px;

        background: rgba(255,255,255,0.14);
        backdrop-filter: blur(18px);
        -webkit-backdrop-filter: blur(18px);

        border: 1px solid rgba(255,255,255,0.25);

        box-shadow: 0 20px 60px rgba(0,0,0,0.45);

        animation: fadeUp 0.8s ease;
    }

    /* INPUT */
    .auth-card label {
        color: rgba(255,255,255,0.9) !important;
    }

    .auth-card input {
        background: rgba(255,255,255,0.12) !important;
        border: 1px solid rgba(255,255,255,0.25) !important;
        color: white !important;
    }

    .auth-card input::placeholder {
        color: rgba(255,255,255,0.55);
    }

    /* BUTTON */
    .auth-card button {
        background: linear-gradient(135deg, #2563eb, #1d4ed8) !important;
        border-radius: 10px !important;
        transition: 0.3s;
    }

    .auth-card button:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(37,99,235,0.4);
    }

    /* LINKS */
    .auth-card a {
        color: #93c5fd !important;
    }

    /* TEXT */
    .auth-card p {
        color: rgba(255,255,255,0.8);
    }

    /* ANIMATION */
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* BODY FIX */
    body {
        margin: 0;
        overflow-x: hidden;
        overflow-y: auto;
    }

    </style>
</head>

<body class="auth-bg">

<div class="auth-wrapper">

    <!-- LOGO -->
    <div class="auth-logo">
        <a href="/">
            <x-application-logo class="w-20 h-20 mx-auto text-white" />
        </a>
    </div>

    <!-- CARD -->
    <div class="auth-card">
        {{ $slot }}
    </div>

</div>

</body>
</html>
