@php
    $flashPayload = [
        'success' => session('success'),
        'error' => session('error'),
        'status' => is_string(session('status')) ? session('status') : null,
    ];
@endphp

<div id="manake-confirm-overlay" class="fixed inset-0 z-[120] hidden items-center justify-center bg-slate-900/55 p-4" role="dialog" aria-modal="true" aria-labelledby="manake-confirm-title">
    <div class="w-full max-w-md rounded-2xl border border-slate-200 bg-white p-5 shadow-2xl">
        <h2 id="manake-confirm-title" class="text-lg font-semibold text-slate-900"></h2>
        <p id="manake-confirm-message" class="mt-2 text-sm text-slate-600"></p>
        <div class="mt-5 flex items-center justify-end gap-2">
            <button id="manake-confirm-cancel" type="button" class="btn-secondary rounded-xl px-4 py-2 text-sm font-semibold">
                {{ __('ui.dialog.cancel') }}
            </button>
            <button id="manake-confirm-submit" type="button" class="rounded-xl px-4 py-2 text-sm font-semibold text-white">
                {{ __('ui.dialog.confirm') }}
            </button>
        </div>
    </div>
</div>

<div id="manake-toast-root" class="pointer-events-none fixed inset-x-0 top-5 z-[130] mx-auto flex w-full max-w-xl flex-col gap-2 px-4"></div>
<script id="manake-flash-payload" type="application/json">@json($flashPayload)</script>

<script>
    (() => {
        const overlay = document.getElementById('manake-confirm-overlay');
        const titleNode = document.getElementById('manake-confirm-title');
        const messageNode = document.getElementById('manake-confirm-message');
        const cancelButton = document.getElementById('manake-confirm-cancel');
        const confirmButton = document.getElementById('manake-confirm-submit');
        const toastRoot = document.getElementById('manake-toast-root');
        const flashPayloadNode = document.getElementById('manake-flash-payload');

        let confirmHandler = null;

        const hideConfirm = () => {
            if (!overlay) {
                return;
            }

            overlay.classList.add('hidden');
            overlay.classList.remove('flex');
            confirmHandler = null;
        };

        const showConfirm = ({
            title = '{{ __('ui.dialog.title') }}',
            message = '',
            confirmText = '{{ __('ui.dialog.confirm') }}',
            cancelText = '{{ __('ui.dialog.cancel') }}',
            variant = 'danger',
            onConfirm = null,
        }) => {
            if (!overlay || !titleNode || !messageNode || !confirmButton || !cancelButton) {
                if (typeof onConfirm === 'function') {
                    onConfirm();
                }
                return;
            }

            titleNode.textContent = title;
            messageNode.textContent = message;
            cancelButton.textContent = cancelText;
            confirmButton.textContent = confirmText;
            confirmButton.className = variant === 'danger'
                ? 'rounded-xl bg-rose-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-rose-700'
                : 'rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-700';

            overlay.classList.remove('hidden');
            overlay.classList.add('flex');
            confirmHandler = onConfirm;
            confirmButton.focus();
        };

        const showToast = (message, type = 'info') => {
            if (!toastRoot || !message) {
                return;
            }

            const classes = {
                info: 'border-slate-200 bg-white text-slate-700',
                success: 'border-emerald-200 bg-emerald-50 text-emerald-700',
                error: 'border-rose-200 bg-rose-50 text-rose-700',
            };

            const toast = document.createElement('div');
            toast.className = `pointer-events-auto rounded-xl border px-4 py-3 text-sm shadow ${classes[type] || classes.info}`;
            toast.textContent = message;
            toastRoot.appendChild(toast);

            window.setTimeout(() => {
                toast.classList.add('opacity-0', 'translate-y-[-4px]');
                window.setTimeout(() => toast.remove(), 220);
            }, 2600);
        };

        cancelButton?.addEventListener('click', hideConfirm);
        overlay?.addEventListener('click', (event) => {
            if (event.target === overlay) {
                hideConfirm();
            }
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                hideConfirm();
            }
        });

        confirmButton?.addEventListener('click', () => {
            const handler = confirmHandler;
            hideConfirm();
            if (typeof handler === 'function') {
                handler();
            }
        });

        document.addEventListener('submit', (event) => {
            const form = event.target instanceof HTMLFormElement ? event.target : null;
            if (!form || !form.hasAttribute('data-confirm')) {
                return;
            }

            if (form.dataset.confirmed === '1') {
                form.dataset.confirmed = '';
                return;
            }

            event.preventDefault();
            showConfirm({
                title: form.dataset.confirmTitle || '{{ __('ui.dialog.title') }}',
                message: form.dataset.confirmMessage || form.getAttribute('data-confirm') || '',
                confirmText: form.dataset.confirmButton || '{{ __('ui.dialog.confirm') }}',
                cancelText: form.dataset.cancelButton || '{{ __('ui.dialog.cancel') }}',
                variant: form.dataset.confirmVariant || 'danger',
                onConfirm: () => {
                    form.dataset.confirmed = '1';
                    if (typeof form.requestSubmit === 'function') {
                        form.requestSubmit();
                    } else {
                        form.submit();
                    }
                },
            });
        });

        try {
            if (flashPayloadNode) {
                const payload = JSON.parse(flashPayloadNode.textContent || '{}');
                if (payload.success) {
                    showToast(payload.success, 'success');
                }
                if (payload.error) {
                    showToast(payload.error, 'error');
                }
            }
        } catch (error) {
            // Ignore invalid flash payload.
        }

        window.manakeConfirm = showConfirm;
        window.manakeToast = showToast;
    })();
</script>
