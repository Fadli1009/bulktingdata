<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'OmniSync Commerce — Otomasi Katalog Marketplace & Campaign Excel' }}</title>
    <meta name="description" content="Sinkronisasi katalog Tokopedia & Shopee, pencocokan PN bundle otomatis, dan campaign Excel dalam hitungan detik.">
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>
    <style>
        .rv { opacity: 0; transform: translateY(26px); transition: opacity .7s cubic-bezier(.2, .7, .2, 1), transform .7s cubic-bezier(.2, .7, .2, 1); transition-delay: var(--d, 0s); }
        .rv.in { opacity: 1; transform: none; }
        .rv-l { transform: translateX(-26px); }
        .rv-r { transform: translateX(26px); }
        .bar { width: 0; transition: width 1.4s cubic-bezier(.2, .7, .2, 1) .3s; }
        .in .bar, .bar.in { width: var(--w); }
        .caret::after { content: ''; display: inline-block; width: 6px; height: 12px; margin-left: 3px; background: #60a5fa; vertical-align: -1px; animation: blink 1s steps(1) infinite; }
        @keyframes blink { 50% { opacity: 0; } }
        .faq-a { display: grid; grid-template-rows: 0fr; transition: grid-template-rows .3s ease; }
        .faq-a > div { overflow: hidden; }
        .faq.open .faq-a { grid-template-rows: 1fr; }
        .faq.open .chev { transform: rotate(180deg); }
        .price-num { transition: opacity .2s; }
        @media (prefers-reduced-motion: reduce) {
            .rv, .bar { transition: none; opacity: 1; transform: none; }
            .bar { width: var(--w); }
            .caret::after { animation: none; }
            html { scroll-behavior: auto; }
        }
    </style>
</head>
<body class="bg-white font-sans text-slate-700 antialiased">
    {{ $slot }}
    <script>
        (() => {
            const reduce = matchMedia('(prefers-reduced-motion: reduce)').matches;
            const count = el => {
                const to = parseFloat(el.dataset.count), dec = +el.dataset.dec || 0;
                if (reduce) return;
                const t0 = performance.now();
                const step = t => {
                    const p = Math.min((t - t0) / 1200, 1);
                    el.textContent = (to * (1 - Math.pow(1 - p, 3))).toFixed(dec);
                    if (p < 1) requestAnimationFrame(step);
                };
                requestAnimationFrame(step);
            };
            const io = new IntersectionObserver(es => es.forEach(e => {
                if (!e.isIntersecting) return;
                e.target.classList.add('in');
                e.target.querySelectorAll('[data-count]').forEach(count);
                io.unobserve(e.target);
            }), { threshold: .15, rootMargin: '0px 0px -40px 0px' });
            document.querySelectorAll('.rv, .bar').forEach(el => io.observe(el));
        })();
    </script>
</body>
</html>
