<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="referrer" content="no-referrer" />
    <title>ZestShop — Welcome</title>

    {{-- Bootstrap 5 --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" />
    {{-- Tabler Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@2.47.0/tabler-icons.min.css" />
    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700&family=DM+Sans:ital,wght@0,300;0,400;0,500;1,400&display=swap" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    @stack('css')
    <style>
        /* ─── RESET & BASE ─── */
        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        :root {
            --orange: #FF6B1A;
            --orange-dark: #E05510;
            --orange-mid: #FF8040;
            --orange-light: #FFF0E8;
            --orange-pale: #FFF8F4;
            --orange-border: #FFD6BB;
            --white: #ffffff;
            --text-dark: #111111;
            --text-mid: #555555;
            --text-muted: #999999;
            --border-light: #f0f0f0;
            --nav-h: 64px;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: #fff;
            color: var(--text-dark);
            overflow-x: hidden;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        img {
            max-width: 100%;
            display: block;
        }

        /* ─── SCROLLBAR ─── */
        ::-webkit-scrollbar {
            width: 5px;
            height: 4px;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--orange-border);
            border-radius: 4px;
        }

        /* ═══════════════════════════════════════
           NAVBAR
        ═══════════════════════════════════════ */
        .main-nav {
            position: sticky;
            top: 0;
            z-index: 1050;
            background: var(--white);
            border-bottom: 1px solid var(--orange-border);
            height: var(--nav-h);
        }

        .nav-inner {
            display: flex;
            align-items: center;
            height: var(--nav-h);
            gap: 0.75rem;
            padding: 0 1.25rem;
        }

        /* Cart button wrapper */
        .cart-btn {
            position: relative;
        }

        /* Wishlist button wrapper */
        .wishlist-btn {
            position: relative;
        }

        /* Wishlist count badge */
        #wishlist-count {
            position: absolute;
            top: -6px;
            right: -6px;
            min-width: 18px;
            height: 18px;
            padding: 0 5px;
            background: var(--orange);
            color: #fff;
            font-size: 11px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            border: 2px solid #fff;
            line-height: 1;
            transition: all 0.2s ease;
        }

        .wishlist-btn:hover #wishlist-count {
            background: var(--orange-dark);
            transform: scale(1.05);
        }

        /* Cart count badge */
        #cart-count {
            position: absolute;
            top: -6px;
            right: -6px;

            min-width: 18px;
            height: 18px;
            padding: 0 5px;

            background: var(--orange);
            color: #fff;

            font-size: 11px;
            font-weight: 700;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 999px;

            border: 2px solid #fff;

            line-height: 1;
            transition: all 0.2s ease;
        }

        /* Hover effect */
        .cart-btn:hover #cart-count {
            background: var(--orange-dark);
            transform: scale(1.05);
        }

        /* Brand */
        .brand {
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 1.35rem;
            letter-spacing: -0.5px;
            white-space: nowrap;
            color: var(--orange);
        }

        .brand-dark {
            color: var(--text-dark);
        }

        /* Desktop links */
        .nav-links {
            display: flex;
            gap: 0;
            margin: 0;
        }

        .nav-links li a {
            display: block;
            padding: 0 0.85rem;
            height: var(--nav-h);
            line-height: var(--nav-h);
            font-size: 13.5px;
            font-weight: 500;
            color: #444;
            white-space: nowrap;
            border-bottom: 2px solid transparent;
            transition: color .2s, border-color .2s;
        }

        .nav-links li a:hover,
        .nav-links li.active a {
            color: var(--orange);
            border-bottom-color: var(--orange);
        }

        /* Search */
        .nav-search {
            position: relative;
            flex: 1;
            max-width: 260px;
            min-width: 100px;
        }

        .nav-search input {
            width: 100%;
            border: 1px solid var(--orange-border);
            border-radius: 24px;
            padding: 7px 14px 7px 36px;
            font-size: 13px;
            font-family: 'DM Sans', sans-serif;
            background: var(--orange-pale);
            color: var(--text-dark);
            outline: none;
            transition: border .2s, background .2s;
        }

        .nav-search input:focus {
            border-color: var(--orange);
            background: #fff;
        }

        .nav-search .search-icon {
            position: absolute;
            left: 11px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--orange);
            font-size: 15px;
            pointer-events: none;
        }

        /* Icon buttons */
        .icon-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            border: 1px solid var(--orange-border);
            background: var(--orange-pale);
            color: var(--orange);
            font-size: 16px;
            cursor: pointer;
            flex-shrink: 0;
            transition: background .2s, color .2s, border-color .2s;
            text-decoration: none;
        }

        .icon-btn:hover {
            background: var(--orange);
            color: #fff;
            border-color: var(--orange);
        }

        /* Language selector */
        .lang-select {
            display: flex;
            align-items: center;
            gap: 4px;
            border: 1px solid var(--orange-border);
            border-radius: 20px;
            padding: 5px 10px 5px 8px;
            background: var(--orange-pale);
            font-size: 12.5px;
            color: #555;
            cursor: pointer;
            white-space: nowrap;
            transition: border .2s;
        }

        .lang-select:hover {
            border-color: var(--orange);
        }

        .lang-select select {
            border: none;
            background: transparent;
            font-family: 'DM Sans', sans-serif;
            font-size: 12.5px;
            color: #555;
            cursor: pointer;
            outline: none;
            -webkit-appearance: none;
            appearance: none;
            max-width: 38px;
        }

        /* Profile */
        .profile-btn {
            display: flex;
            align-items: center;
            gap: 7px;
            border: none;
            background: none;
            cursor: pointer;
            padding: 4px 6px;
            border-radius: 24px;
            transition: background .2s;
            white-space: nowrap;
        }

        .profile-btn:hover {
            background: var(--orange-light);
        }

        .avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--orange), #FF9C5B);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 600;
            color: #fff;
            flex-shrink: 0;
        }

        .profile-name {
            font-size: 13px;
            font-weight: 500;
            color: var(--text-dark);
            line-height: 1.2;
        }

        .profile-name small {
            display: block;
            font-size: 11px;
            color: var(--text-muted);
            font-weight: 400;
        }

        /* Hamburger */
        .hamburger {
            display: none;
            flex-direction: column;
            justify-content: center;
            gap: 5px;
            width: 36px;
            height: 36px;
            border: 1px solid var(--orange-border);
            border-radius: 8px;
            background: var(--orange-pale);
            cursor: pointer;
            padding: 6px;
            flex-shrink: 0;
        }

        .hamburger span {
            display: block;
            height: 2px;
            background: var(--orange);
            border-radius: 2px;
            transition: all .3s;
        }

        .hamburger.open span:nth-child(1) {
            transform: translateY(7px) rotate(45deg);
        }

        .hamburger.open span:nth-child(2) {
            opacity: 0;
        }

        .hamburger.open span:nth-child(3) {
            transform: translateY(-7px) rotate(-45deg);
        }

        /* Mobile drawer */
        .mobile-menu {
            display: none;
            position: fixed;
            top: var(--nav-h);
            left: 0;
            right: 0;
            background: #fff;
            border-bottom: 1px solid var(--orange-border);
            z-index: 1040;
            padding: 0.75rem 1.25rem 1rem;
            box-shadow: 0 8px 24px rgba(0, 0, 0, .08);
        }

        .mobile-menu.open {
            display: block;
        }

        .mobile-menu .mob-search {
            position: relative;
            margin-bottom: 0.75rem;
        }

        .mobile-menu .mob-search input {
            width: 100%;
            border: 1px solid var(--orange-border);
            border-radius: 24px;
            padding: 9px 14px 9px 36px;
            font-size: 14px;
            background: var(--orange-pale);
            outline: none;
        }

        .mobile-menu .mob-search .ti {
            position: absolute;
            left: 11px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--orange);
            font-size: 16px;
        }

        .mobile-menu ul {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .mobile-menu ul li a {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 8px;
            font-size: 14.5px;
            font-weight: 500;
            color: #333;
            border-radius: 10px;
            transition: background .2s, color .2s;
        }

        .mobile-menu ul li a .ti {
            font-size: 18px;
            color: var(--orange);
        }

        .mobile-menu ul li a:hover,
        .mobile-menu ul li.active a {
            background: var(--orange-light);
            color: var(--orange);
        }

        .mob-bottom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 0.75rem;
            padding-top: 0.75rem;
            border-top: 1px solid var(--orange-border);
        }

        /* ═══════════════════════════════════════
           CATEGORY TABS
        ═══════════════════════════════════════ */
        .cat-bar {
            background: #fff;
            border-bottom: 1px solid var(--border-light);
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
        }

        .cat-bar::-webkit-scrollbar {
            display: none;
        }

        .cat-tabs {
            display: flex;
            white-space: nowrap;
            min-width: max-content;
            padding: 0 1rem;
        }

        .cat-tabs li a {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 13px 15px;
            font-size: 13px;
            font-weight: 500;
            color: #555;
            border-bottom: 2px solid transparent;
            transition: color .2s, border-color .2s;
        }

        .cat-tabs li a .ti {
            font-size: 16px;
        }

        .cat-tabs li a:hover,
        .cat-tabs li.active a {
            color: var(--orange);
            border-bottom-color: var(--orange);
        }

        /* ═══════════════════════════════════════
           HERO SLIDER
        ═══════════════════════════════════════ */
        .hero {
            position: relative;
            overflow: hidden;
        }

        .slides-track {
            display: flex;
            transition: transform .55s cubic-bezier(.4, 0, .2, 1);
        }

        .slide {
            min-width: 100%;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
        }

        .slide-1 {
            background: linear-gradient(130deg, #FF6B1A 0%, #FF9C5B 55%, #FFD6BB 100%);
            min-height: 420px;
        }

        .slide-2 {
            background: linear-gradient(130deg, #1a1a1a 0%, #333 55%, #FF6B1A 100%);
            min-height: 420px;
        }

        .slide-3 {
            background: linear-gradient(130deg, #FFF0E8 0%, #FFD6BB 55%, #FF6B1A 100%);
            min-height: 420px;
        }

        .slide-body {
            padding: 2.5rem 2rem;
            z-index: 2;
            position: relative;
            flex: 1;
            max-width: 560px;
        }

        .slide-tag {
            display: inline-block;
            background: rgba(255, 255, 255, .2);
            border: 1px solid rgba(255, 255, 255, .4);
            border-radius: 20px;
            padding: 4px 14px;
            font-size: 12px;
            font-weight: 500;
            color: #fff;
            margin-bottom: 14px;
            letter-spacing: .4px;
        }

        .slide-3 .slide-tag {
            background: rgba(255, 107, 26, .12);
            border-color: rgba(255, 107, 26, .3);
            color: var(--orange);
        }

        .slide h1 {
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            line-height: 1.1;
            margin-bottom: 12px;
            color: #fff;
            font-size: clamp(1.7rem, 4vw, 2.8rem);
        }

        .slide-3 h1 {
            color: #111;
        }

        .slide p {
            font-size: clamp(13px, 2.5vw, 15px);
            line-height: 1.65;
            margin-bottom: 22px;
            max-width: 400px;
            color: rgba(255, 255, 255, .88);
        }

        .slide-3 p {
            color: #555;
        }

        .slide-btns {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn-hero {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 11px 22px;
            border-radius: 30px;
            font-size: 13.5px;
            font-weight: 600;
            font-family: 'DM Sans', sans-serif;
            border: none;
            cursor: pointer;
            transition: all .22s;
            text-decoration: none;
        }

        .btn-white {
            background: #fff;
            color: var(--orange);
        }

        .btn-white:hover {
            background: var(--orange-light);
            color: var(--orange-dark);
        }

        .btn-outline {
            background: transparent;
            color: #fff;
            border: 2px solid rgba(255, 255, 255, .55);
        }

        .btn-outline:hover {
            background: rgba(255, 255, 255, .15);
            color: #fff;
        }

        .btn-orange {
            background: var(--orange);
            color: #fff;
        }

        .btn-orange:hover {
            background: var(--orange-dark);
            color: #fff;
        }

        /* Slide image area */
        .slide-img-area {
            position: absolute;
            right: 2rem;
            top: 50%;
            transform: translateY(-50%);
            z-index: 1;
            width: clamp(240px, 35vw, 480px);
            height: 320px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border-radius: 14px;
        }

        .slide-img-box {
            width: clamp(140px, 25vw, 240px);
            aspect-ratio: 1 / .85;
            border-radius: 18px;
            background: rgba(255, 255, 255, .18);
            border: 2px dashed rgba(255, 255, 255, .4);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .slide-img-box .ti {
            font-size: clamp(36px, 6vw, 56px);
            color: rgba(255, 255, 255, .75);
        }

        .slide-img-box span {
            font-size: 12px;
            color: rgba(255, 255, 255, .7);
        }

        .slide-3 .slide-img-box {
            border-color: rgba(255, 107, 26, .3);
            background: rgba(255, 107, 26, .08);
        }

        .slide-3 .slide-img-box .ti {
            color: var(--orange);
            opacity: .6;
        }

        .slide-3 .slide-img-box span {
            color: var(--orange);
            opacity: .8;
        }

        /* Arrows */
        .slider-arrows {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 100%;
            display: flex;
            justify-content: space-between;
            padding: 0 0.75rem;
            z-index: 10;
            pointer-events: none;
        }

        .arrow-btn {
            pointer-events: all;
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .22);
            border: 1px solid rgba(255, 255, 255, .4);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: #fff;
            font-size: 18px;
            transition: background .2s;
        }

        .arrow-btn:hover {
            background: rgba(255, 255, 255, .38);
        }

        /* Dots */
        .slider-dots {
            position: absolute;
            bottom: 16px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 7px;
            z-index: 10;
        }

        .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .45);
            border: none;
            cursor: pointer;
            transition: all .3s;
            padding: 0;
        }

        .dot.active {
            width: 22px;
            border-radius: 4px;
            background: #fff;
        }

        /* ═══════════════════════════════════════
           SECTION
        ═══════════════════════════════════════ */
        .section {
            padding: clamp(1.5rem, 4vw, 3rem) clamp(1rem, 3vw, 2rem);
        }

        .section-header {
            margin-bottom: 1.5rem;
        }

        .section-title {
            font-family: 'Syne', sans-serif;
            font-size: clamp(1.25rem, 3vw, 1.6rem);
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 3px;
        }

        .section-title span {
            color: var(--orange);
        }

        .section-sub {
            font-size: 13px;
            color: var(--text-muted);
        }

        /* ═══════════════════════════════════════
           PRODUCT GRID
        ═══════════════════════════════════════ */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(min(100%, 185px), 1fr));
            gap: 14px;
        }

        .product-card {
            border: 1px solid #FFE8D8;
            border-radius: 16px;
            overflow: hidden;
            background: #fff;
            transition: transform .22s, box-shadow .22s;
        }

        .product-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 36px rgba(255, 107, 26, .12);
        }

        .prod-img {
            width: 100%;
            aspect-ratio: 4/3;
            background: var(--orange-light);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: clamp(36px, 6vw, 52px);
            position: relative;
        }

        .prod-badge {
            position: absolute;
            top: 9px;
            left: 9px;
            background: var(--orange);
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            padding: 3px 9px;
            border-radius: 20px;
            letter-spacing: .3px;
        }

        .prod-badge.dark {
            background: #222;
        }

        .prod-info {
            padding: 12px;
        }

        .prod-name {
            font-family: 'Syne', sans-serif;
            font-size: 14px;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .prod-desc {
            font-size: 12px;
            color: var(--text-muted);
            margin-bottom: 8px;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .prod-price {
            font-size: 15px;
            font-weight: 700;
            color: var(--orange);
        }

        .prod-price s {
            font-size: 12px;
            color: #ccc;
            font-weight: 400;
            margin-left: 4px;
        }

        .prod-actions {
            display: flex;
            gap: 7px;
            margin-top: 9px;
        }

        .btn-cart {
            flex: 1;
            background: var(--orange);
            color: #fff;
            border: none;
            border-radius: 20px;
            padding: 7px 10px;
            font-size: 12px;
            font-weight: 600;
            font-family: 'DM Sans', sans-serif;
            cursor: pointer;
            transition: background .2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
        }

        .btn-cart:hover {
            background: var(--orange-dark);
        }

        .btn-wish {
            width: 32px;
            height: 32px;
            border: 1px solid var(--orange-border);
            border-radius: 50%;
            background: var(--orange-pale);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: var(--orange);
            font-size: 14px;
            flex-shrink: 0;
            transition: all .2s;
        }

        .btn-wish:hover {
            background: var(--orange);
            color: #fff;
            border-color: var(--orange);
        }

        /* ═══════════════════════════════════════
           PROMO BANNER
        ═══════════════════════════════════════ */
        .promo-banner {
            background: linear-gradient(130deg, var(--orange), #FF9C5B);
            border-radius: 18px;
            margin: 0 clamp(1rem, 3vw, 2rem) clamp(1.5rem, 4vw, 3rem);
            padding: clamp(1.25rem, 3vw, 2.5rem) clamp(1.25rem, 4vw, 3rem);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .banner-text h2 {
            font-family: 'Syne', sans-serif;
            font-size: clamp(1.1rem, 3vw, 1.7rem);
            font-weight: 700;
            color: #fff;
            margin-bottom: 5px;
        }

        .banner-text p {
            font-size: 13.5px;
            color: rgba(255, 255, 255, .85);
            margin: 0;
        }

        .btn-banner {
            background: #fff;
            color: var(--orange);
            border: none;
            border-radius: 30px;
            padding: 11px 26px;
            font-size: 13.5px;
            font-weight: 700;
            font-family: 'DM Sans', sans-serif;
            cursor: pointer;
            white-space: nowrap;
            transition: background .2s;
        }

        .btn-banner:hover {
            background: var(--orange-light);
        }

        /* ═══════════════════════════════════════
           FOOTER
        ═══════════════════════════════════════ */
        footer {
            background: var(--orange);
            color: #ffffff;
            padding: 4rem 1.5rem 2rem 1.5rem;
            font-size: 14px;
            border-top: none;
        }

        footer h5 {
            color: #ffffff;
            font-weight: 700;
            font-family: 'Syne', sans-serif;
            font-size: 16px;
            margin-bottom: 1.5rem;
            position: relative;
        }

        footer h5::after {
            content: '';
            position: absolute;
            bottom: -6px;
            left: 0;
            width: 35px;
            height: 2px;
            background: #ffffff;
            border-radius: 2px;
        }

        footer a {
            color: rgba(255, 255, 255, 0.85);
            text-decoration: none;
            transition: all 0.2s ease;
        }

        footer a:hover {
            color: #ffffff;
            padding-left: 4px;
        }

        footer .social-link {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.15);
            color: #ffffff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            margin-right: 8px;
        }

        footer .social-link:hover {
            background: #ffffff;
            color: var(--orange);
            transform: translateY(-3px);
            padding-left: 0;
        }

        footer .map-container {
            border-radius: 12px;
            overflow: hidden;
            border: 2px solid rgba(255, 255, 255, 0.25);
            height: 150px;
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            padding-top: 1.5rem;
            margin-top: 3rem;
            font-size: 13px;
        }

        /* ═══════════════════════════════════════
           BOTTOM MOBILE NAV
        ═══════════════════════════════════════ */
        .bottom-nav {
            display: none;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: #fff;
            border-top: 1px solid var(--orange-border);
            z-index: 1050;
            padding: 6px 0 env(safe-area-inset-bottom, 6px);
            box-shadow: 0 -2px 12px rgba(0, 0, 0, .06);
        }

        .bottom-nav ul {
            display: flex;
            justify-content: space-around;
            margin: 0;
            padding: 0;
        }

        .bottom-nav li {
            flex: 1;
        }

        .bottom-nav li a {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 2px;
            padding: 4px 8px;
            font-size: 10px;
            font-weight: 500;
            color: #888;
            transition: color .2s;
            position: relative;
        }

        .bottom-nav li a .ti {
            font-size: 22px;
        }

        .bottom-nav li.active a,
        .bottom-nav li a:hover {
            color: var(--orange);
        }

        /* Bottom nav cart badge */
        .bottom-nav .bnav-badge {
            position: absolute;
            top: 0;
            right: 50%;
            transform: translateX(14px);
            min-width: 16px;
            height: 16px;
            padding: 0 4px;
            background: var(--orange);
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            border: 2px solid #fff;
            line-height: 1;
        }

        /* ═══════════════════════════════════════
           GUEST USER ICON DROPDOWN
        ═══════════════════════════════════════ */
        .guest-user-dropdown .dropdown-toggle::after {
            display: none;
        }

        .guest-user-dropdown .icon-btn {
            position: relative;
        }

        .guest-user-dropdown .dropdown-menu {
            border: none;
            border-radius: 12px;
            padding: 0.5rem;
            min-width: 160px;
            box-shadow: 0 10px 24px rgba(0, 0, 0, .1);
            margin-top: 8px !important;
        }

        .guest-user-dropdown .dropdown-menu .dropdown-item {
            border-radius: 8px;
            padding: 9px 14px;
            font-size: 13.5px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
            color: #444;
            transition: background .15s, color .15s;
        }

        .guest-user-dropdown .dropdown-menu .dropdown-item:hover {
            background: var(--orange-light);
            color: var(--orange);
        }

        .guest-user-dropdown .dropdown-menu .dropdown-item .ti {
            font-size: 17px;
            color: var(--orange);
        }

        .guest-user-dropdown .dropdown-menu .dropdown-divider {
            margin: 0.25rem 0;
            border-color: var(--border-light);
        }

        /* ═══════════════════════════════════════
           RESPONSIVE BREAKPOINTS
        ═══════════════════════════════════════ */

        /* Tablet ≤ 992px */
        @media (max-width: 992px) {
            .nav-links {
                display: none;
            }

            .hamburger {
                display: flex;
            }

            .nav-search {
                max-width: 200px;
            }

            .profile-name {
                display: none;
            }

            .slide-img-area {
                opacity: .4;
                right: 0.5rem;
            }
        }

        /* Mobile ≤ 768px */
        @media (max-width: 768px) {
            :root {
                --nav-h: 56px;
            }

            .nav-search {
                display: none;
            }

            .lang-select select {
                max-width: 30px;
            }

            .slide-img-area {
                display: none;
            }

            .slide-body {
                padding: 2rem 1.25rem 2.5rem;
                max-width: 100%;
            }

            .slide-1,
            .slide-2,
            .slide-3 {
                min-height: 340px;
            }

            .bottom-nav {
                display: block;
            }

            body {
                padding-bottom: 68px;
            }

            .icon-btn.d-hide {
                display: none;
            }

            /* Hide cart from top navbar on mobile — it's in the bottom bar */
            .icon-btn.cart-btn {
                display: none;
            }

            /* Hide user/account from top navbar on mobile — it's in the bottom bar */
            .guest-user-dropdown,
            .dropdown:has(.profile-btn) {
                display: none;
            }

            .products-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 10px;
            }
        }

        /* Small mobile ≤ 400px */
        @media (max-width: 400px) {
            .products-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 8px;
            }

            .prod-info {
                padding: 9px;
            }

            .btn-hero {
                padding: 10px 16px;
                font-size: 12.5px;
            }
        }

        /* ─── LIVE SEARCH AUTOCOMPLETE ─── */
        .live-search-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: #ffffff;
            border: 1px solid var(--orange-border);
            border-radius: 12px;
            margin-top: 8px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            max-height: 380px;
            overflow-y: auto;
            z-index: 1100;
            padding: 8px;
        }

        .live-search-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 12px;
            border-radius: 8px;
            text-decoration: none;
            color: var(--text-dark);
            transition: background 0.2s, transform 0.1s;
        }

        .live-search-item:hover {
            background: var(--orange-light);
            color: var(--orange-dark);
            transform: translateX(3px);
        }

        .live-search-item img {
            width: 40px;
            height: 50px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid var(--orange-border);
            flex-shrink: 0;
        }

        .live-search-info {
            flex: 1;
            min-width: 0;
        }

        .live-search-title {
            font-size: 13px;
            font-weight: 600;
            margin: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .live-search-price {
            font-size: 12px;
            color: var(--text-muted);
            margin: 2px 0 0 0;
        }

        .live-search-empty {
            padding: 16px;
            text-align: center;
            color: var(--text-muted);
            font-size: 13px;
        }

        /* ─── TOAST NOTIFICATIONS ─── */
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 100000;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .toast-item {
            background: #ffffff;
            border-left: 4px solid var(--orange);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            padding: 12px 20px;
            border-radius: 8px;
            font-family: 'DM Sans', sans-serif;
            font-size: 13.5px;
            font-weight: 500;
            color: var(--text-dark);
            min-width: 250px;
            max-width: 320px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            animation: slideInRight 0.3s ease-out forwards;
        }
        .toast-item.toast-success {
            border-left-color: #2ec4b6;
        }
        .toast-item.toast-info {
            border-left-color: var(--orange);
        }
        @keyframes slideInRight {
            from { transform: translateX(120%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes fadeOutToast {
            to { transform: translateY(-10px); opacity: 0; }
        }
    </style>
</head>

<body class="container">
    {{-- ════════════════════════════════
     MOBILE DRAWER MENU
════════════════════════════════ --}}
    <div class="mobile-menu" id="mobileMenu" role="dialog" aria-modal="true" aria-label="Mobile navigation">
        <form action="{{ url('/shop') }}" method="GET" class="mob-search" style="position: relative;">
            <i class="ti ti-search" aria-hidden="true"></i>
            <input type="search" id="mobile-search-input" name="q" value="{{ request('q') }}" placeholder="Search products…" aria-label="Search products" autocomplete="off" />
            <div id="mobile-search-dropdown" class="live-search-dropdown" style="display: none;"></div>
        </form>
        <ul>
            <li class="{{ request()->is('/') ? 'active' : '' }}">
                <a href="{{ url('/') }}">
                    <i class="ti ti-home"></i> Home
                </a>
            </li>

            <li class="{{ request()->is('shop') ? 'active' : '' }}">
                <a href="{{ url('/shop') }}">
                    <i class="ti ti-shopping-bag"></i> Shop
                </a>
            </li>

            <li class="{{ request()->is('delivery') ? 'active' : '' }}">
                <a href="{{ url('/delivery') }}">
                    <i class="ti ti-tag"></i> delivery
                </a>
            </li>

            <li class="{{ request()->is('about') ? 'active' : '' }}">
                <a href="{{ url('/about') }}">
                    <i class="ti ti-info-circle"></i> About
                </a>
            </li>

            <li class="{{ request()->is('contact') ? 'active' : '' }}">
                <a href="{{ url('/contact') }}">
                    <i class="ti ti-phone"></i> Contact
                </a>
            </li>
        </ul>

        <div class="mob-bottom" style="flex-direction: column; align-items: stretch;">
            @auth
            {{-- User card --}}
            <div style="display:flex; align-items:center; gap:10px; padding:10px 12px; background:var(--orange-pale); border-radius:12px; margin-bottom:10px;">
                <div class="avatar" style="width:38px;height:38px;font-size:13px;">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
                <div>
                    <div style="font-size:14px;font-weight:600;color:var(--text-dark);">{{ Auth::user()->name }}</div>
                    <div style="font-size:11px;color:var(--text-muted);">{{ Auth::user()->is_admin ? 'Admin' : 'Member' }}</div>
                </div>
            </div>

            {{-- Language --}}
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:10px;">
                <div class="lang-select">
                    <i class="ti ti-world" style="font-size:15px;color:var(--orange)" aria-hidden="true"></i>
                    <select aria-label="Select language">
                        <option>EN</option>
                        <option>KH</option>
                        <option>FR</option>
                        <option>ZH</option>
                        <option>JP</option>
                    </select>
                    <i class="ti ti-chevron-down" style="font-size:11px;color:#aaa" aria-hidden="true"></i>
                </div>
            </div>

            {{-- Action buttons --}}
            <div style="display:flex; flex-direction:column; gap:8px;">
                @if(Auth::user()->is_admin)
                <a href="{{ url('/admin') }}" style="display:flex;align-items:center;justify-content:center;gap:6px;padding:10px;border:1.5px solid var(--orange);border-radius:12px;font-size:13px;font-weight:600;color:var(--orange);background:transparent;text-decoration:none;transition:all .2s;">
                    <i class="ti ti-layout-dashboard" style="font-size:16px;"></i> Admin Dashboard
                </a>
                @endif
                <form action="{{ url('/logout') }}" method="POST">
                    @csrf
                    <button type="submit" style="width:100%;display:flex;align-items:center;justify-content:center;gap:6px;padding:10px;border:none;border-radius:12px;font-size:13px;font-weight:600;color:#fff;background:var(--orange);cursor:pointer;font-family:'DM Sans',sans-serif;transition:background .2s;">
                        <i class="ti ti-logout" style="font-size:16px;"></i> Log Out
                    </button>
                </form>
            </div>
            @else
            {{-- Guest: Language + Login/Register --}}
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:12px;">
                <div class="lang-select">
                    <i class="ti ti-world" style="font-size:15px;color:var(--orange)" aria-hidden="true"></i>
                    <select aria-label="Select language">
                        <option>EN</option>
                        <option>KH</option>
                        <option>FR</option>
                        <option>ZH</option>
                        <option>JP</option>
                    </select>
                    <i class="ti ti-chevron-down" style="font-size:11px;color:#aaa" aria-hidden="true"></i>
                </div>
            </div>
            <div style="display:flex; gap:8px;">
                <a href="{{ url('/login') }}" style="flex:1;display:flex;align-items:center;justify-content:center;gap:5px;padding:10px;border:1.5px solid var(--orange);border-radius:12px;font-size:13px;font-weight:600;color:var(--orange);background:transparent;text-decoration:none;transition:all .2s;">
                    <i class="ti ti-login" style="font-size:16px;"></i> Login
                </a>
                <a href="{{ url('/register') }}" style="flex:1;display:flex;align-items:center;justify-content:center;gap:5px;padding:10px;border:none;border-radius:12px;font-size:13px;font-weight:600;color:#fff;background:var(--orange);text-decoration:none;transition:background .2s;">
                    <i class="ti ti-user-plus" style="font-size:16px;"></i> Register
                </a>
            </div>
            @endauth
        </div>
    </div>
    {{-- ════════════════════════════════
     TOP NAVBAR
════════════════════════════════ --}}
    <nav class="main-nav" role="navigation" aria-label="Main navigation">
        <div class="nav-inner">

            {{-- Hamburger (mobile) --}}
            <button class="hamburger" id="hamburger" aria-label="Toggle menu" aria-expanded="false">
                <span></span><span></span><span></span>
            </button>

            {{-- Brand --}}
            <a href="{{ url('/') }}" class="brand"><span>Zest</span><span class="brand-dark">Shop</span></a>

            {{-- Desktop Nav Links --}}
            <ul class="nav-links">
                <li class="{{ request()->is('/') ? 'active' : '' }}">
                    <a href="{{ url('/') }}">Home</a>
                </li>

                <li class="{{ request()->is('shop') ? 'active' : '' }}">
                    <a href="{{ url('/shop') }}">Shop</a>
                </li>

                <li class="{{ request()->is('delivery') ? 'active' : '' }}">
                    <a href="{{ url('/delivery') }}">Delivery</a>
                </li>

                <li class="{{ request()->is('about') ? 'active' : '' }}">
                    <a href="{{ url('/about') }}">About</a>
                </li>

                <li class="{{ request()->is('contact') ? 'active' : '' }}">
                    <a href="{{ url('/contact') }}">Contact</a>
                </li>
            </ul>
            {{-- Search (desktop) --}}
            <form action="{{ url('/shop') }}" method="GET" class="nav-search ms-auto" style="position: relative;">
                <i class="ti ti-search search-icon" aria-hidden="true"></i>
                <input type="search" id="header-search-input" name="q" value="{{ request('q') }}" placeholder="Search products…" aria-label="Search products" autocomplete="off" />
                <div id="live-search-dropdown" class="live-search-dropdown" style="display: none;"></div>
            </form>

            {{-- Right Icons --}}
            <a href="#" class="icon-btn d-hide" aria-label="Notifications">
                <i class="ti ti-bell" aria-hidden="true"></i>
            </a>
            <a href="{{ url('/wishlist') }}" class="icon-btn wishlist-btn d-hide" aria-label="Wishlist">
                <i class="ti ti-heart" aria-hidden="true"></i>
                <span id="wishlist-count" class="{{ count(session('wishlist', [])) > 0 ? '' : 'd-none' }}">{{ count(session('wishlist', [])) }}</span>
            </a>
            <a href="{{ url('cart') }}" class="icon-btn cart-btn">
                <i class="ti ti-shopping-cart"></i>
                <span id="cart-count">{{ count(session('cart', [])) }}</span>
            </a>

            {{-- Language / Translate --}}
            <div class="lang-select" role="group" aria-label="Language selector">
                <i class="ti ti-world" style="font-size:15px;color:var(--orange)" aria-hidden="true"></i>
                <select aria-label="Select language" id="langSelect">
                    <option style=" text-align:center;color:var(--orange)" value="en">EN</option>
                    <option style=" text-align:center;color:var(--orange)" value="km">KH</option>
                    <option style=" text-align:center;color:var(--orange)" value="fr">FR</option>
                    <option style=" text-align:center;color:var(--orange)" value="zh">ZH</option>
                    <option style=" text-align:center;color:var(--orange)" value="ja">JP</option>
                </select>
                <i class="ti ti-chevron-down" style="font-size:11px;color:#aaa" aria-hidden="true"></i>
            </div>

            {{-- Profile dropdown or guest auth --}}
            @auth
            <div class="dropdown">
                <button class="profile-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="User profile">
                    <div class="avatar" aria-hidden="true">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
                    <span class="profile-name">
                        {{ Auth::user()->name }}
                        <small>{{ Auth::user()->is_admin ? 'Admin' : 'Member' }}</small>
                    </span>
                    <i class="ti ti-chevron-down" style="font-size:12px;color:#bbb" aria-hidden="true"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 p-2 mt-2" style="border-radius:12px; min-width:180px; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1) !important;">
                    @if(Auth::user()->is_admin)
                    <li>
                        <a class="dropdown-item rounded-3 py-2" href="{{ url('/admin') }}">
                            <i class="ti ti-layout-dashboard me-2 text-warning" style="color:var(--orange) !important;"></i> Admin Dashboard
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider bg-light">
                    </li>
                    @endif
                    <li>
                        <form action="{{ url('/logout') }}" method="POST" class="d-block w-100">
                            @csrf
                            <button type="submit" class="dropdown-item rounded-3 py-2 text-danger">
                                <i class="ti ti-logout me-2"></i> Log Out
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
            @else
            <div class="dropdown guest-user-dropdown">
                <button class="icon-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Account">
                    <i class="ti ti-user" aria-hidden="true"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="{{ url('/login') }}">
                            <i class="ti ti-login"></i> Login
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ url('/register') }}">
                            <i class="ti ti-user-plus"></i> Register
                        </a>
                    </li>
                </ul>
            </div>
            @endauth

        </div>
    </nav>


    @if(session('error') || session('success'))
    <div class="container mt-3">
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm border-0 px-4 py-3" role="alert" style="background-color: #FEE2E2; color: #991B1B; border: 1px solid #FCA5A5;">
            <i class="ti ti-alert-triangle me-2 fs-5 align-middle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm border-0 px-4 py-3" role="alert" style="background-color: #DEF7EC; color: #03543F; border: 1px solid #BCF0DA;">
            <i class="ti ti-circle-check me-2 fs-5 align-middle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
    </div>
    @endif

    @yield('content')

    {{-- Premium Footer with Google Maps --}}
    @if(request()->is('/'))
    <footer style="margin-bottom: 60px;">
        <div class="container">
            <div class="row g-4 text-start">

                {{-- Column 1: Brand Info --}}
                <div class="col-lg-3 col-md-6">
                    <h4 class="fw-bold mb-3" style="font-family: 'Syne', sans-serif;"><span style="color: #ffffff;">Zest</span><span style="color: rgba(255,255,255,0.75);">Shop</span></h4>
                    <p class="mb-4" style="line-height: 1.6; color: rgba(255, 255, 255, 0.85); font-size: 13.5px;">
                        Your ultimate destination for premium clothing and accessories. We deliver unmatched quality with a modern digital shopping experience.
                    </p>
                    <div class="d-flex">
                        <a href="#" class="social-link" aria-label="Facebook"><i class="ti ti-brand-facebook"></i></a>
                        <a href="#" class="social-link" aria-label="Instagram"><i class="ti ti-brand-instagram"></i></a>
                        <a href="#" class="social-link" aria-label="Twitter"><i class="ti ti-brand-twitter"></i></a>
                        <a href="#" class="social-link" aria-label="Youtube"><i class="ti ti-brand-youtube"></i></a>
                    </div>
                </div>

                {{-- Column 2: Quick Links --}}
                <div class="col-lg-2 col-md-6 col-6">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled d-flex flex-column gap-2 mt-3">
                        <li><a href="{{ url('/') }}">Home</a></li>
                        <li><a href="{{ url('/shop') }}">Shop Catalog</a></li>
                        <li><a href="{{ url('/delivery') }}">Track Delivery</a></li>
                        <li><a href="{{ url('/about') }}">About Us</a></li>
                        <li><a href="{{ url('/contact') }}">Contact Us</a></li>
                    </ul>
                </div>

                {{-- Column 3: Contact Details --}}
                <div class="col-lg-3 col-md-6 col-6">
                    <h5>Get in Touch</h5>
                    <ul class="list-unstyled d-flex flex-column gap-3 mt-3 text-white" style="font-size: 13.5px;">
                        <li class="d-flex align-items-start gap-2">
                            <i class="ti ti-map-pin text-white mt-1"></i>
                            <span>Phnom Penh, Cambodia</span>
                        </li>
                        <li class="d-flex align-items-center gap-2">
                            <i class="ti ti-phone text-white"></i>
                            <span>+855 12 345 678</span>
                        </li>
                        <li class="d-flex align-items-center gap-2">
                            <i class="ti ti-mail text-white"></i>
                            <span>support@zestshop.com</span>
                        </li>
                    </ul>
                </div>

                {{-- Column 4: Google Map --}}
                <div class="col-lg-4 col-md-6">
                    <h5>Our Location</h5>
                    <div class="map-container mt-3">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d125064.29815049301!2d104.83226998638102!3d11.56847250426543!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3109513dc76a6be3%3A0x9c010ee85ab525bb!2sPhnom%20Penh!5e0!3m2!1sen!2skh!4v1700000000000!5m2!1sen!2skh"
                            width="100%"
                            height="150"
                            style="border:0;"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                </div>

            </div>

            {{-- Footer Bottom --}}
            <div class="footer-bottom text-center">
                <p class="m-0 text-white-50" style="font-size: 12.5px;">
                    &copy; {{ date('Y') }} ZestShop. All rights reserved. Crafted with <i class="ti ti-heart-filled text-white"></i> for modern shoppers.
                </p>
            </div>
        </div>
    </footer>
    @endif

    {{-- ════════════════════════════════
     BOTTOM MOBILE NAV
════════════════════════════════ --}}
    <nav class="bottom-nav" aria-label="Mobile bottom navigation">
        <ul>
            <li class="{{ request()->is('/') ? 'active' : '' }}">
                <a href="{{ url('/') }}">
                    <i class="ti ti-home" aria-hidden="true"></i>
                    Home
                </a>
            </li>
            <li class="{{ request()->is('shop') ? 'active' : '' }}">
                <a href="{{ url('/shop') }}">
                    <i class="ti ti-search" aria-hidden="true"></i>
                    Shop
                </a>
            </li>
            <li>
                <a href="{{ url('cart') }}">
                    <i class="ti ti-shopping-cart" aria-hidden="true"></i>
                    <span class="bnav-badge" id="cart-count-bottom">{{ count(session('cart', [])) }}</span>
                    Cart
                </a>
            </li>
            <li>
                <a href="{{ url('/wishlist') }}">
                    <i class="ti ti-heart" aria-hidden="true"></i>
                    <span class="bnav-badge {{ count(session('wishlist', [])) > 0 ? '' : 'd-none' }}" id="wishlist-count-bottom">{{ count(session('wishlist', [])) }}</span>
                    Favorite
                </a>
            </li>
            <li>
                @auth
                <a href="{{ url('/admin') }}">
                    <i class="ti ti-user-circle" aria-hidden="true"></i>
                    Account
                </a>
                @else
                <a href="{{ url('/login') }}">
                    <i class="ti ti-user-circle" aria-hidden="true"></i>
                    Account
                </a>
                @endauth
            </li>
        </ul>
    </nav>



    {{-- jQuery --}}
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    {{-- Bootstrap JS (requires jQuery or Popper; bundle includes Popper) --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(function() {
            'use strict';

            /* ── Hamburger / Mobile Menu ── */
            $('#hamburger').on('click', function() {
                const isOpen = $('#mobileMenu').toggleClass('open').hasClass('open');
                $(this).toggleClass('open', isOpen).attr('aria-expanded', isOpen.toString());
            });

            /* Close menu on outside click */
            $(document).on('click', function(e) {
                if (!$(e.target).closest('#hamburger, #mobileMenu').length) {
                    $('#mobileMenu').removeClass('open');
                    $('#hamburger').removeClass('open').attr('aria-expanded', 'false');
                }
            });

            /* ── Hero Slider ── */
            const $track = $('#slidesTrack');
            const $dots = $('.dot');
            const total = $dots.length;
            let current = 0;
            let autoPlay;

            function goTo(index) {
                current = (index + total) % total;
                $track.css('transform', 'translateX(-' + (current * 100) + '%)');
                $dots.each(function(i) {
                    $(this)
                        .toggleClass('active', i === current)
                        .attr('aria-selected', (i === current).toString());
                });
            }

            function next() {
                goTo(current + 1);
            }

            function prev() {
                goTo(current - 1);
            }

            function startAuto() {
                stopAuto();
                autoPlay = setInterval(next, 4500);
            }

            function stopAuto() {
                clearInterval(autoPlay);
            }

            $('#nextBtn').on('click', function() {
                next();
                startAuto();
            });
            $('#prevBtn').on('click', function() {
                prev();
                startAuto();
            });

            $dots.on('click', function() {
                goTo(parseInt($(this).data('slide')));
                startAuto();
            });

            /* Touch swipe on slider */
            let touchStartX = 0;
            $track[0].addEventListener('touchstart', function(e) {
                touchStartX = e.touches[0].clientX;
            }, {
                passive: true
            });
            $track[0].addEventListener('touchend', function(e) {
                const diff = touchStartX - e.changedTouches[0].clientX;
                if (Math.abs(diff) > 50) {
                    diff > 0 ? next() : prev();
                    startAuto();
                }
            }, {
                passive: true
            });

            startAuto();

            /* ── Language Switcher ── */
            $('#langSelect').on('change', function() {
                const lang = $(this).val();
                /* In a real Laravel app uncomment the line below: */
                /* window.location.href = '/lang/' + lang; */
                console.log('Language changed to:', lang);
            });

            /* ── Category tab highlight ── */
            $('.cat-tabs li a').on('click', function(e) {
                e.preventDefault();
                $('.cat-tabs li').removeClass('active');
                $(this).closest('li').addClass('active');
            });

            /* ── Bottom nav highlight ── */
            $('.bottom-nav li a').on('click', function() {
                $('.bottom-nav li').removeClass('active');
                $(this).closest('li').addClass('active');
            });

            // Live Search Logic (Desktop and Mobile)
            function initLiveSearch(inputId, dropdownId) {
                const $input = $('#' + inputId);
                const $dropdown = $('#' + dropdownId);
                let debounceTimeout;

                $input.on('input', function () {
                    const query = $(this).val().trim();
                    clearTimeout(debounceTimeout);

                    if (query.length < 2) {
                        $dropdown.hide().empty();
                        return;
                    }

                    debounceTimeout = setTimeout(() => {
                        $.ajax({
                            url: '/search/live',
                            type: 'GET',
                            data: { q: query },
                            success: function (results) {
                                $dropdown.empty();
                                if (results.length > 0) {
                                    results.forEach(product => {
                                        const html = `
                                            <a href="${product.url}" class="live-search-item">
                                                <img src="${product.image}" alt="${product.title}">
                                                <div class="live-search-info">
                                                    <h4 class="live-search-title">${product.title}</h4>
                                                    <p class="live-search-price">${product.price}</p>
                                                </div>
                                            </a>
                                        `;
                                        $dropdown.append(html);
                                    });
                                    $dropdown.slideDown(200);
                                } else {
                                    $dropdown.html('<div class="live-search-empty">No products found.</div>').slideDown(200);
                                }
                            },
                            error: function () {
                                $dropdown.hide();
                            }
                        });
                    }, 250);
                });

                $(document).on('click', function (e) {
                    if (!$(e.target).closest($input).length && !$(e.target).closest($dropdown).length) {
                        $dropdown.hide();
                    }
                });

                $input.on('focus', function () {
                    if ($input.val().trim().length >= 2 && $dropdown.children().length > 0) {
                        $dropdown.slideDown(200);
                    }
                });
            }

            initLiveSearch('header-search-input', 'live-search-dropdown');
            initLiveSearch('mobile-search-input', 'mobile-search-dropdown');

        });

        // Global Wishlist Toggle Logic (defined outside document ready for immediate availability)
        window.toggleWishlist = function(event, productId, btn) {
            if (event) {
                event.preventDefault();
                event.stopPropagation();
            }

            const $btn = $(btn);
            const $icon = $btn.is('i') ? $btn : $btn.find('i');
            
            $.ajax({
                url: "{{ url('/wishlist/toggle') }}",
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: productId
                },
                success: function(res) {
                    if (res.status === 'added') {
                        $icon.removeClass('ti-heart text-muted')
                             .addClass('ti-heart-filled text-danger');
                        
                        showToast('Added to Favorites', res.message, 'success');
                    } else {
                        $icon.removeClass('ti-heart-filled text-danger')
                             .addClass('ti-heart text-muted');
                        
                        showToast('Removed', res.message, 'info');
                        
                        if (window.location.pathname.indexOf('/wishlist') !== -1) {
                            $btn.closest('.col-6').fadeOut(300, function() {
                                $(this).remove();
                                if ($('#product-grid-content .elegant-card').length === 0) {
                                    $('#product-grid-content').html(`
                                        <div class="text-muted text-center py-5">
                                            <i class="ti ti-heart-broken" style="font-size: 48px; color: #ddd; margin-bottom: 15px; display: block;"></i>
                                            <p>You haven't added any products to your favorites yet.</p>
                                            <a href="/shop" class="btn btn-outline-orange mt-3 rounded-pill px-4">Shop Now</a>
                                        </div>
                                    `);
                                }
                            });
                        }
                    }
                    
                    updateWishlistCount(res.count);
                },
                error: function(err) {
                    console.error('Error toggling wishlist:', err);
                }
            });
        };

        function updateWishlistCount(count) {
            const $badge = $('#wishlist-count');
            $badge.text(count);
            if (count > 0) {
                $badge.removeClass('d-none');
            } else {
                $badge.addClass('d-none');
            }

            const $badgeBottom = $('#wishlist-count-bottom');
            $badgeBottom.text(count);
            if (count > 0) {
                $badgeBottom.removeClass('d-none');
            } else {
                $badgeBottom.addClass('d-none');
            }
        }

        window.showToast = function(title, message, type = 'info') {
            let container = $('.toast-container');
            if (container.length === 0) {
                container = $('<div class="toast-container"></div>').appendTo('body');
            }
            const itemClass = type === 'success' ? 'toast-success' : 'toast-info';
            const toast = $(`
                <div class="toast-item ${itemClass}">
                    <div>
                        <strong style="display:block; font-size:11px; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:2px; color: ${type === 'success' ? '#2ec4b6' : 'var(--orange)'};">${title}</strong>
                        <span>${message}</span>
                    </div>
                    <button style="background:none; border:none; color:#ccc; font-size:16px; font-weight:bold; cursor:pointer; padding: 0 0 0 10px;">&times;</button>
                </div>
            `);
            
            toast.find('button').on('click', function() {
                toast.remove();
            });
            
            container.append(toast);
            setTimeout(() => {
                toast.css('animation', 'fadeOutToast 0.3s ease-in forwards');
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        };
    </script>

    @stack('js')
</body>

</html>