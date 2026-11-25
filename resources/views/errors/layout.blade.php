<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>@yield('code') — @yield('title') · TelyuCan</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo-telyucan.svg') }}">
    <style>
        @font-face {
            font-family: "Archivo";
            font-style: normal;
            font-weight: 600 900;
            font-display: swap;
            src: url("/fonts/archivo-latin-ext.woff2") format("woff2");
            unicode-range: U+0100-02BA, U+02BD-02C5, U+02C7-02CC, U+02CE-02D7, U+02DD-02FF, U+0304, U+0308, U+0329, U+1D00-1DBF, U+1E00-1E9F, U+1EF2-1EFF, U+2020, U+20A0-20AB, U+20AD-20C4, U+2113, U+2C60-2C7F, U+A720-A7FF;
        }
        @font-face {
            font-family: "Archivo";
            font-style: normal;
            font-weight: 600 900;
            font-display: swap;
            src: url("/fonts/archivo-latin.woff2") format("woff2");
            unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
        }
        @font-face {
            font-family: "Figtree";
            font-style: normal;
            font-weight: 400 700;
            font-display: swap;
            src: url("/fonts/figtree-latin-ext.woff2") format("woff2");
            unicode-range: U+0100-02BA, U+02BD-02C5, U+02C7-02CC, U+02CE-02D7, U+02DD-02FF, U+0304, U+0308, U+0329, U+1D00-1DBF, U+1E00-1E9F, U+1EF2-1EFF, U+2020, U+20A0-20AB, U+20AD-20C4, U+2113, U+2C60-2C7F, U+A720-A7FF;
        }
        @font-face {
            font-family: "Figtree";
            font-style: normal;
            font-weight: 400 700;
            font-display: swap;
            src: url("/fonts/figtree-latin.woff2") format("woff2");
            unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
        }
        @font-face {
            font-family: "Chivo Mono";
            font-style: normal;
            font-weight: 500 700;
            font-display: swap;
            src: url("/fonts/chivo-mono-latin-ext.woff2") format("woff2");
            unicode-range: U+0100-02BA, U+02BD-02C5, U+02C7-02CC, U+02CE-02D7, U+02DD-02FF, U+0304, U+0308, U+0329, U+1D00-1DBF, U+1E00-1E9F, U+1EF2-1EFF, U+2020, U+20A0-20AB, U+20AD-20C4, U+2113, U+2C60-2C7F, U+A720-A7FF;
        }
        @font-face {
            font-family: "Chivo Mono";
            font-style: normal;
            font-weight: 500 700;
            font-display: swap;
            src: url("/fonts/chivo-mono-latin.woff2") format("woff2");
            unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
        }

        :root {
            --tc-obsidian: #101820;
            --tc-obsidian-elevated: #18222D;
            --tc-obsidian-border: rgba(255, 255, 255, 0.11);
            --tc-on-dark: #F8FAFC;
            --tc-on-dark-muted: #CBD5E1;
            --tc-on-dark-subtle: #94A3B8;
            --tc-crimson: #B9112E;
            --tc-crimson-hover: #960D24;
            --tc-emerald: #0B7A50;
            --tc-amber: #F59E0B;
            --tc-amber-body: #291D05;
            --tc-surface: #FFFFFF;
            --tc-ink: #101820;
            --tc-r-bento: 28px;
            --tc-r-pill: 999px;
            --tc-ease: cubic-bezier(0.16, 1, 0.3, 1);
            --tc-font-display: "Archivo", system-ui, -apple-system, sans-serif;
            --tc-font-sans: "Figtree", system-ui, -apple-system, sans-serif;
            --tc-font-mono: "Chivo Mono", ui-monospace, SFMono-Regular, monospace;
        }

        *, *::before, *::after {
            box-sizing: border-box;
        }

        ::selection {
            background: var(--tc-crimson);
            color: #FFFFFF;
        }

        html {
            scrollbar-color: #94A3B8 transparent;
            scrollbar-width: thin;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background-color: var(--tc-obsidian);
            color: var(--tc-on-dark);
            font-family: var(--tc-font-sans);
            caret-color: var(--tc-crimson);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        a {
            text-underline-offset: 3px;
        }

        :focus-visible {
            outline: 2px solid #FFFFFF;
            outline-offset: 2px;
        }

        .tc-err-tone-amber :focus-visible {
            outline-color: var(--tc-ink);
        }

        .tc-err-container {
            width: 100%;
            max-width: 1080px;
            margin: 0 auto;
            padding: 0 1.25rem;
        }

        .tc-err-header {
            min-height: 76px;
            border-bottom: 1px solid var(--tc-obsidian-border);
            display: flex;
            align-items: center;
        }

        .tc-err-header-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding-block: 0.85rem;
        }

        .tc-err-brand {
            display: inline-flex;
            align-items: center;
            gap: 0.65rem;
            text-decoration: none;
            color: #FFFFFF;
            font-family: var(--tc-font-display);
            font-weight: 900;
            font-size: 1.32rem;
            letter-spacing: -0.025em;
        }

        .tc-err-nav {
            display: flex;
            align-items: center;
            gap: 0.35rem;
            flex-wrap: wrap;
        }

        .tc-err-nav a {
            color: var(--tc-on-dark-muted);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 600;
            padding: 0.45rem 0.9rem;
            border-radius: var(--tc-r-pill);
            transition: color 180ms var(--tc-ease), background-color 180ms var(--tc-ease);
        }

        .tc-err-nav a:hover {
            color: #FFFFFF;
            background-color: rgba(255, 255, 255, 0.09);
        }

        .tc-err-main {
            flex: 1;
            display: flex;
            align-items: center;
            padding: 2.25rem 0 2.75rem;
        }

        .tc-err-grid {
            display: grid;
            grid-template-columns: 1.35fr 0.78fr;
            grid-template-rows: auto auto;
            gap: 1rem;
        }

        .tc-err-tile {
            border-radius: var(--tc-r-bento);
            padding: 1.65rem;
            position: relative;
            overflow: hidden;
        }

        .tc-err-primary {
            grid-row: 1 / 3;
            background-color: var(--tc-obsidian-elevated);
            border: 1px solid var(--tc-obsidian-border);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 1.45rem;
            padding-bottom: 0;
        }

        .tc-err-title {
            font-family: var(--tc-font-display);
            font-weight: 900;
            font-size: clamp(2rem, 3.6vw, 3rem);
            line-height: 1.02;
            letter-spacing: -0.03em;
            color: #FFFFFF;
            margin: 0 0 0.85rem;
            text-wrap: balance;
        }

        .tc-err-desc {
            font-size: 0.98rem;
            line-height: 1.58;
            color: var(--tc-on-dark-muted);
            max-width: 48ch;
            margin: 0;
            text-wrap: pretty;
        }

        .tc-err-dock {
            margin: auto -1.65rem 0;
            padding: 1.25rem 1.65rem 1.4rem;
            background-color: var(--tc-obsidian);
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            flex-direction: column;
            gap: 0.9rem;
        }

        .tc-err-dock-meta {
            padding-top: 0.85rem;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            font-size: 0.84rem;
            line-height: 1.45;
            color: var(--tc-on-dark-subtle);
            margin: 0;
        }

        .tc-err-search {
            display: flex;
            gap: 0.6rem;
            align-items: center;
        }

        .tc-err-search input {
            flex: 1;
            min-width: 0;
            background-color: var(--tc-surface);
            color: var(--tc-ink);
            border: none;
            border-radius: var(--tc-r-pill);
            padding: 0.82rem 1.2rem;
            font-family: var(--tc-font-sans);
            font-size: 0.93rem;
            font-weight: 500;
            transition: box-shadow 180ms var(--tc-ease);
        }

        .tc-err-search input:focus-visible {
            outline: 2px solid #FFFFFF;
            outline-offset: 2px;
            box-shadow: 0 0 0 4px rgba(185, 17, 46, 0.35);
        }

        .tc-err-search input::placeholder {
            color: #64748B;
        }

        .tc-err-actions {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.65rem;
        }

        .tc-err-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            border-radius: var(--tc-r-pill);
            padding: 0.78rem 1.45rem;
            font-family: var(--tc-font-sans);
            font-size: 0.92rem;
            font-weight: 700;
            text-decoration: none;
            white-space: nowrap;
            cursor: pointer;
            border: 1px solid transparent;
            transition: background-color 180ms var(--tc-ease), transform 180ms var(--tc-ease), box-shadow 180ms var(--tc-ease), border-color 180ms var(--tc-ease);
        }

        .tc-err-btn-primary {
            background-color: var(--tc-crimson);
            color: #FFFFFF;
            border-color: var(--tc-crimson);
        }

        .tc-err-btn-primary:hover {
            background-color: var(--tc-crimson-hover);
            border-color: var(--tc-crimson-hover);
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(185, 17, 46, 0.24);
        }

        .tc-err-btn-ghost {
            background-color: rgba(255, 255, 255, 0.08);
            color: #FFFFFF;
            border-color: rgba(255, 255, 255, 0.14);
        }

        .tc-err-btn-ghost:hover {
            background-color: rgba(255, 255, 255, 0.15);
            border-color: rgba(255, 255, 255, 0.24);
            transform: translateY(-1px);
        }

        .tc-err-btn:active {
            transform: translateY(0);
        }

        .tc-err-code-tile {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 0.9rem;
            min-height: 172px;
        }

        .tc-err-tone-crimson {
            background-color: var(--tc-crimson);
            color: #FFFFFF;
        }

        .tc-err-tone-amber {
            background-color: var(--tc-amber);
            color: var(--tc-ink);
        }

        .tc-err-code-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 0.75rem;
        }

        .tc-err-code-num {
            font-family: var(--tc-font-mono);
            font-variant-numeric: tabular-nums;
            font-weight: 700;
            font-size: clamp(3.3rem, 5.4vw, 4.75rem);
            line-height: 0.92;
            letter-spacing: -0.04em;
        }

        .tc-err-icon-ring {
            width: 44px;
            height: 44px;
            border-radius: var(--tc-r-pill);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .tc-err-tone-crimson .tc-err-icon-ring {
            background-color: rgba(16, 24, 32, 0.22);
            color: #FFFFFF;
        }

        .tc-err-tone-amber .tc-err-icon-ring {
            background-color: rgba(16, 24, 32, 0.12);
            color: var(--tc-ink);
        }

        .tc-err-code-label {
            font-family: var(--tc-font-display);
            font-weight: 800;
            font-size: 1.25rem;
            line-height: 1.15;
            letter-spacing: -0.02em;
            margin: 0;
        }

        .tc-err-code-note {
            font-size: 0.86rem;
            line-height: 1.45;
            margin: 0.35rem 0 0;
            text-wrap: pretty;
        }

        .tc-err-tone-crimson .tc-err-code-note {
            color: rgba(255, 255, 255, 0.9);
        }

        .tc-err-tone-amber .tc-err-code-note {
            color: var(--tc-amber-body);
            font-weight: 600;
        }

        .tc-err-helper-slate {
            background-color: var(--tc-obsidian-elevated);
            border: 1px solid var(--tc-obsidian-border);
            color: #FFFFFF;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 0.85rem;
        }

        .tc-err-helper-emerald {
            background-color: var(--tc-emerald);
            color: #FFFFFF;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 0.85rem;
        }

        .tc-err-helper-title {
            font-family: var(--tc-font-display);
            font-weight: 800;
            font-size: 1.15rem;
            line-height: 1.18;
            letter-spacing: -0.02em;
            margin: 0;
            color: #FFFFFF;
        }

        .tc-err-helper-copy {
            font-size: 0.88rem;
            line-height: 1.5;
            margin: 0.4rem 0 0;
            text-wrap: pretty;
        }

        .tc-err-helper-slate .tc-err-helper-copy {
            color: var(--tc-on-dark-muted);
        }

        .tc-err-helper-emerald .tc-err-helper-copy {
            color: #ECFDF5;
        }

        .tc-err-zone-list {
            display: flex;
            flex-direction: column;
            margin-top: 0.25rem;
        }

        .tc-err-zone-link {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            padding: 0.65rem 0.5rem;
            margin-inline: -0.5rem;
            border-radius: 12px;
            color: #FFFFFF;
            text-decoration: none;
            font-weight: 700;
            font-size: 0.9rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.16);
            transition: background-color 180ms var(--tc-ease);
        }

        .tc-err-zone-link svg {
            transition: transform 180ms var(--tc-ease);
        }

        .tc-err-zone-link:last-child {
            border-bottom: none;
            padding-bottom: 0.45rem;
        }

        .tc-err-zone-link:hover {
            background-color: rgba(16, 24, 32, 0.16);
        }

        .tc-err-zone-link:hover svg {
            transform: translate(3px, -3px);
        }

        .tc-err-status-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            align-self: flex-start;
            padding: 0.36rem 0.85rem;
            border-radius: var(--tc-r-pill);
            font-size: 0.78rem;
            font-weight: 700;
            background-color: rgba(255, 255, 255, 0.1);
            color: #FFFFFF;
        }

        .tc-err-status-pill-crimson {
            background-color: var(--tc-crimson);
            color: #FFFFFF;
        }

        .tc-err-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .tc-err-footer {
            border-top: 1px solid var(--tc-obsidian-border);
            padding: 1.25rem 0 max(1.25rem, env(safe-area-inset-bottom));
            font-size: 0.84rem;
            color: var(--tc-on-dark-subtle);
        }

        .tc-err-footer-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        @media (max-width: 900px) {
            .tc-err-grid {
                grid-template-columns: 1fr;
                grid-template-rows: auto;
            }
            .tc-err-primary {
                grid-row: auto;
            }
        }

        @media (max-width: 640px) {
            .tc-err-header {
                min-height: 0;
            }
            .tc-err-header-inner {
                flex-wrap: wrap;
                row-gap: 0.5rem;
                padding-block: 0.75rem;
            }
            .tc-err-nav {
                width: 100%;
                gap: 0.25rem;
                margin-left: -0.55rem;
            }
            .tc-err-nav a {
                font-size: 0.84rem;
                padding: 0.35rem 0.65rem;
            }
            .tc-err-main {
                padding: 1.25rem 0 1.85rem;
            }
            .tc-err-tile {
                padding: 1.35rem;
                border-radius: 24px;
            }
            .tc-err-primary {
                padding-bottom: 0;
                gap: 1.25rem;
            }
            .tc-err-dock {
                margin: auto -1.35rem 0;
                padding: 1.15rem 1.35rem 1.3rem;
            }
            .tc-err-search {
                flex-direction: column;
                align-items: stretch;
            }
            .tc-err-search .tc-err-btn,
            .tc-err-actions .tc-err-btn {
                width: 100%;
            }
            .tc-err-code-tile {
                min-height: 152px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                transition-duration: 0.01ms !important;
                animation-duration: 0.01ms !important;
            }
        }
    </style>
</head>
<body>
    <header class="tc-err-header">
        <div class="tc-err-container tc-err-header-inner">
            <a href="{{ url('/') }}" class="tc-err-brand">
                <x-brand-logo :size="34" />
                <span>TelyuCan</span>
            </a>

            <nav class="tc-err-nav" aria-label="Navigasi utama">
                <a href="{{ url('/') }}">Beranda</a>
                <a href="{{ url('/menus') }}">Katalog Menu</a>
                <a href="{{ url('/search') }}">Lokasi Kantin</a>
            </nav>
        </div>
    </header>

    <main class="tc-err-main">
        <div class="tc-err-container">
            <div class="tc-err-grid">
                <section class="tc-err-tile tc-err-primary">
                    <div>
                        <h1 class="tc-err-title">@yield('headline')</h1>
                        <p class="tc-err-desc">@yield('message')</p>
                    </div>

                    <div class="tc-err-dock">
                        @yield('recovery')
                    </div>
                </section>

                <aside class="tc-err-tile tc-err-code-tile @yield('code_tone', 'tc-err-tone-crimson')" aria-label="Kode status HTTP">
                    <div class="tc-err-code-top">
                        <span class="tc-err-code-num">@yield('code')</span>
                        <span class="tc-err-icon-ring" aria-hidden="true">
                            @yield('code_icon')
                        </span>
                    </div>
                    <div>
                        <h2 class="tc-err-code-label">@yield('status_label')</h2>
                        <p class="tc-err-code-note">@yield('status_note')</p>
                    </div>
                </aside>

                @yield('helper_tile')
            </div>
        </div>
    </main>

    <footer class="tc-err-footer">
        <div class="tc-err-container tc-err-footer-inner">
            <span>&copy; {{ date('Y') }} TelyuCan — Sistem Informasi Kantin Telkom University.</span>
            <span>Pemesanan Dine-in &amp; Takeaway Kampus</span>
        </div>
    </footer>
</body>
</html>
