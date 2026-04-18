<button
    type="button"
    class="btn btn-outline-light rounded-circle position-relative d-inline-flex align-items-center justify-content-center"
    style="width: 44px; height: 44px;"
    wire:click="openBag"
    title="Open bag"
    aria-label="Open bag"
>
    <i class="bi bi-bag-fill fs-5" wire:loading.remove wire:target="openBag"></i>
    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" wire:loading wire:target="openBag"></span>
    @if ($count > 0)
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
            {{ $count }}
        </span>
    @endif
</button>
