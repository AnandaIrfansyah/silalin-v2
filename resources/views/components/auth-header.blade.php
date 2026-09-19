<style>
    .login-brand {
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fff;
        border-radius: 16px;
        padding: 1rem 1.5rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, .06);
        margin-bottom: 1.5rem;
        width: fit-content;
        margin-left: auto;
        margin-right: auto;
    }

    .login-brand-item img {
        height: 60px;
        width: 60px;
        object-fit: contain;
    }

    .login-brand-divider {
        width: 1px;
        height: 50px;
        background: #e3e8f1;
        margin: 0 1.25rem;
    }
</style>

<div class="login-brand card-primary">
    <div class="login-brand-item">
        <img src="{{ asset('img/logo/dishubpng.png') }}" alt="logo">
    </div>
    <div class="login-brand-divider"></div>
    <div class="login-brand-item">
        <img src="{{ asset('img/logo/logo-pemkot.png') }}" alt="logo">
    </div>
</div>
