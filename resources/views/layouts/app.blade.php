<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Ordering System</title>

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @livewireStyles
    <link rel="stylesheet" href="sweetalert2.min.css">
</head>
<body>
    <div class="container">
        {{$slot}}
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    @livewireScripts
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
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