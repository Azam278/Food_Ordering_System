<div>
    <a href="{{ route('customer.cart') }}" class="btn btn-secondary btn-circle position-relative">
        <span class="bi bi-cart-fill"></span>
        @if($cartCount > 0)
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                {{ $cartCount }}
                <span class="visually-hidden">items in cart</span>
            </span>
        @endif
    </a>
</div>