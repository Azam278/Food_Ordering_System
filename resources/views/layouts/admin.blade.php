<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">  
    @livewireStyles
    <link rel="stylesheet" href="sweetalert2.min.css">
    <link rel="stylesheet" href="{{asset('css/admin.css')}}"> 
</head>
<body>
    
    <!-- Header -->
    <nav class="navbar navbar-dark bg-dark">
        <div class="d-flex align-items-center">
            <button class="btn btn-dark toggle-sidebar me-2">
                <i class="fas fa-bars"></i>
            </button>
            <a class="navbar-brand" href="#">
                <i class="fas fa-utensils"></i>
                Restaurant Manager Panel
            </a>
        </div>
        <div class="dropdown">
            <button class="btn btn-dark dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <div class="user-avatar">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <span>{{Auth::user()->name}}</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li>                    
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item">
                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </nav>

    <div class="wrapper">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="nav-header">MANAGEMENT</div>
            <ul class="sidebar-menu">
                <li>
                    <a href="{{route('admin.restaurant-approval')}}">
                        <i class="fas fa-store"></i> Restaurant Approval
                    </a>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            {{ $slot }}
        </div>
    </div>

    @livewireScripts
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src ="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script>
        window.livewire.on('showModal', (modalID) => {
            console.log(modalID);
            
            $('#'+modalID).modal('show');
        });
        window.livewire.on('hideModal', (modalID) => {
            $('#'+modalID).modal('hide');
        });
        window.livewire.on('swal:alert', (event) => {
            Swal.fire({
                position: event.position == null ? 'center' : event.position,
                backdrop : event.backdrop == null ? true : event.backdrop,
                icon: event.icon,
                title: event.title,
                timer: event.timer == null ? '10000' : event.timer,
                width: 720,
                html: event.html,
                confirmButtonColor: '#1BC5BD',
                confirmButtonText: 'Close',
                showConfirmButton: event.showButton,
                timerProgressBar: event.timerProgressBar == null ? false : true,
            })
        });
    </script>
</body>
</html>