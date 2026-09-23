<script>
    (function () {
        const links = Array.from(document.querySelectorAll('a.glightbox'));
        if (!links.length) return;

        let current = 0;
        let playing = false;
        let timer = null;
        const duration = Math.max(2500, parseInt(links[0].dataset.speed || '4000', 10));

        const overlay = document.createElement('div');
        overlay.className = 'fixed inset-0 z-50 flex flex-col items-center justify-center bg-black/95 p-4 opacity-0 pointer-events-none transition-opacity duration-300';
        overlay.innerHTML = `
            <div class="progress absolute inset-x-0 top-0 h-1 bg-white/15">
                <div class="progress-fill h-full bg-gradient-to-r from-rose-400 to-rose-600" style="width:0%"></div>
            </div>
            <button type="button" class="play absolute right-20 top-5 z-10 flex h-11 w-11 items-center justify-center rounded-full bg-white/10 text-xl text-white transition hover:bg-white/25 sm:right-24" aria-label="Putar / jeda">&#9656;</button>
            <button type="button" class="close absolute right-5 top-5 z-10 flex h-11 w-11 items-center justify-center rounded-full bg-white/10 text-xl text-white transition hover:bg-white/25" aria-label="Tutup">&times;</button>
            <button type="button" class="prev absolute left-4 top-1/2 z-10 flex h-12 w-12 -translate-y-1/2 items-center justify-center rounded-full bg-white/10 text-2xl text-white transition hover:bg-white/25 sm:left-8" aria-label="Sebelumnya">&lsaquo;</button>
            <button type="button" class="next absolute right-4 top-1/2 z-10 flex h-12 w-12 -translate-y-1/2 items-center justify-center rounded-full bg-white/10 text-2xl text-white transition hover:bg-white/25 sm:right-8" aria-label="Berikutnya">&rsaquo;</button>
            <img class="max-h-[78vh] max-w-[92vw] rounded-xl shadow-2xl" alt="">
            <p class="caption mt-5 min-h-6 max-w-lg text-center font-serif text-lg italic text-rose-100"></p>
            <p class="counter mt-2 text-xs tracking-widest text-white/50"></p>
        `;
        document.body.appendChild(overlay);

        const img = overlay.querySelector('img');
        const caption = overlay.querySelector('.caption');
        const counter = overlay.querySelector('.counter');
        const closeBtn = overlay.querySelector('.close');
        const prevBtn = overlay.querySelector('.prev');
        const nextBtn = overlay.querySelector('.next');
        const playBtn = overlay.querySelector('.play');
        const progressFill = overlay.querySelector('.progress-fill');

        function isOpen() {
            return !overlay.classList.contains('pointer-events-none');
        }

        function show(index) {
            current = (index + links.length) % links.length;
            const link = links[current];
            img.src = link.getAttribute('href');
            caption.textContent = link.dataset.caption || '';
            counter.textContent = (current + 1) + ' / ' + links.length;
            overlay.classList.remove('opacity-0', 'pointer-events-none');
            restartProgress();
        }

        function scheduleNext() {
            if (timer) clearTimeout(timer);
            if (playing) timer = setTimeout(nextTick, duration);
        }

        function nextTick() {
            show(current + 1);
            scheduleNext();
        }

        function restartProgress() {
            if (!playing) return;
            progressFill.style.transition = 'none';
            progressFill.style.width = '0%';
            void progressFill.offsetWidth;
            progressFill.style.transition = 'width ' + (duration / 1000) + 's linear';
            progressFill.style.width = '100%';
        }

        function play() {
            if (playing) return;
            playing = true;
            playBtn.innerHTML = '&#10074;&#10074;';
            overlay.classList.add('slideshow-playing');
            restartProgress();
            scheduleNext();
        }

        function pause() {
            playing = false;
            playBtn.innerHTML = '&#9656;';
            overlay.classList.remove('slideshow-playing');
            if (timer) clearTimeout(timer);
            progressFill.style.transition = 'none';
            progressFill.style.width = '0%';
        }

        function toggle() {
            if (!isOpen()) {
                show(0);
                play();
                return;
            }
            if (playing) pause();
            else play();
        }

        function close() {
            pause();
            overlay.classList.add('opacity-0', 'pointer-events-none');
            img.src = '';
        }

        links.forEach((link, i) => {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                show(i);
                if (link.dataset.autoplay === 'true') play();
                else pause();
            });
        });

        window.openLightbox = function (index, autoplay) {
            show(index || 0);
            if (autoplay) play();
            else pause();
        };

        closeBtn.addEventListener('click', close);
        playBtn.addEventListener('click', function (e) { e.stopPropagation(); toggle(); });
        prevBtn.addEventListener('click', function (e) { e.stopPropagation(); show(current - 1); scheduleNext(); });
        nextBtn.addEventListener('click', function (e) { e.stopPropagation(); show(current + 1); scheduleNext(); });

        overlay.addEventListener('click', function (e) {
            if (e.target === overlay) close();
        });

        document.addEventListener('keydown', function (e) {
            if (!isOpen()) return;
            if (e.key === 'Escape') close();
            if (e.key === 'ArrowLeft') { e.preventDefault(); show(current - 1); scheduleNext(); }
            if (e.key === 'ArrowRight') { e.preventDefault(); show(current + 1); scheduleNext(); }
            if (e.key === ' ') { e.preventDefault(); toggle(); }
        });
    })();
</script>