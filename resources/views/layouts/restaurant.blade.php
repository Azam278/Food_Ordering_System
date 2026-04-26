<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Manager Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">  
    @livewireStyles
    <link rel="stylesheet" href="sweetalert2.min.css">
    <link rel="stylesheet" href="{{asset('css/manager.css')}}">
</head>
<body>    
    <!-- Fixed Header -->
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
                    <form method="POST" action="{{ route('manager.logout') }}">
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
                    <a href="{{ route('manager.restaurant-profile') }}" class="{{ request()->routeIs('manager.restaurant-profile') ? 'active' : '' }}">
                        <i class="fas fa-store"></i> Restaurant Profile
                    </a>
                </li>
                @if ($managerRestaurants->isNotEmpty())
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-utensils"></i> Menu Management
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="{{ route('manager.restaurant-menu', ['restaurantId' => Crypt::encryptString($managerRestaurants->first()->id)]) }}">
                                    Menu
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="{{ route('manager.restaurant-total-sales') }}">
                            <i class="fas fa-clipboard-list"></i> Total Sales
                        </a>
                    </li>
                @endif
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
        window.livewire.on('swal:alert', (event) =>{
          Swal.fire({
              position: event.position == null ? 'top' : event.position,
              backdrop: event.backdrop == null ? true : event.backdrop,
              icon: event.icon,
              title: event.title,
              timer: event.timer == null ? '2500' : event.timer,
              witdh: 720,
              html: event.html,
              confirmButtonColor: '#1BC5BD',
              confirmButtonText: 'close',
              showConfirmButton: event.showButton,
              timerProgressBar: event.timerProgressBar == null ? false : true,
          });
        })
      </script>
</body>
</html>