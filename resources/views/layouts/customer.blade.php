<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    @livewireStyles
    <link rel="stylesheet" href="{{asset('css/customer.css')}}">   
</head>
<body>
    
    <!-- Header -->
    <nav class="navbar navbar-dark bg-dark justify-content-between">
        <a class="navbar-brand ms-3" href="#">AOF System</a>
        <div class="d-flex align-items-center me-3">
            <!-- Cart Counter Component -->
            @livewire('customer.cart-counter')
            
            <button class="btn btn-dark dropdown-toggle ms-2" type="button" data-bs-toggle="dropdown">
                {{Auth::user()->name}}
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li>                    
                    <form method="POST" action="{{ route('cust.logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item">
                            Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </nav>

    <div class="wrapper">
        <!-- Main -->
        <div class="main-content">
            {{ $slot }}
        </div>
    </div>

    @livewireScripts
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Initialize toasts
        document.addEventListener('DOMContentLoaded', function() {
            var toastElList = [].slice.call(document.querySelectorAll('.toast'))
            var toastList = toastElList.map(function(toastEl) {
                return new bootstrap.Toast(toastEl)
            });
            
            // Show toasts
            toastList.forEach(toast => toast.show());
        });
    </script>
</body>
</html>