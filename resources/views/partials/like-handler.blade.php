<script>
    document.addEventListener('DOMContentLoaded', function () {
        const meta = document.querySelector('meta[name="csrf-token"]');
        const buttons = document.querySelectorAll('.like-btn');
        if (!meta || !buttons.length) return;

        buttons.forEach(function (btn) {
            btn.classList.toggle('liked', btn.dataset.liked === '1');
            btn.addEventListener('click', function () {
                const original = btn.disabled;
                btn.disabled = true;

                fetch(btn.dataset.endpoint, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': meta.content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                })
                    .then(function (r) { return r.json(); })
                    .then(function (data) {
                        const count = btn.querySelector('.like-count');
                        if (count) count.textContent = Number(data.likes).toLocaleString('id-ID');
                        btn.classList.toggle('liked', Boolean(data.liked));
                    })
                    .catch(function () {})
                    .finally(function () { btn.disabled = original; });
            });
        });
    });
</script>