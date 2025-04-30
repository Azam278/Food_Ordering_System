<div class="container">
    <section class="section register d-flex flex-column align-items-center justify-content-center py-5 min-vh-100">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-5 col-md-7 d-flex flex-column align-items-center justify-content-center">  
            <div class="d-flex justify-content-center py-4">
              <a href="index.html" class="logo d-flex align-items-center w-auto">
                <img src="{{asset('assets/images/aof.jpg')}}" alt="AOF" class="img-fluid" style="max-width: 280px;">
              </a>
            </div><!-- End Logo -->
            
            <div class="card mb-3 shadow-lg border-0 rounded-4">  
              <div class="card-body p-5">  
                <div class="pt-3 pb-4">
                  <h4 class="card-title text-center pb-0 fw-bold">Register Your Account</h4>
                </div>  

                <div class="row g-4 needs-validation" novalidate>  
                    <div class="col-12">
                      <label for="yourUsername" class="form-label fw-semibold">Name</label>
                      <div class="input-group has-validation">
                        <span class="input-group-text bg-light"><i class="bi bi-envelope"></i></span>
                        <input type="email" class="form-control form-control-lg @error('cust.name') is-invalid @enderror" wire:model.debounce.500ms="cust.name" placeholder="Enter your name">
                        <div class="invalid-feedback">Please enter your name.</div>
                      </div>
                    </div>
                </div>
                
                <div class="row g-4 needs-validation" novalidate>  
                  <div class="col-12">
                    <label for="yourUsername" class="form-label fw-semibold">Email</label>
                    <div class="input-group has-validation">
                      <span class="input-group-text bg-light"><i class="bi bi-envelope"></i></span>
                      <input type="email" class="form-control form-control-lg @error('cust.email') is-invalid @enderror" wire:model.debounce.500ms="cust.email" placeholder="Enter your email">
                      <div class="invalid-feedback">Please enter your email.</div>
                    </div>
                  </div>
                  
                  <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                      <label for="yourPassword" class="form-label fw-semibold mb-0">Password</label>
                    </div>
                    <div class="input-group has-validation">
                      <span class="input-group-text bg-light"><i class="bi bi-lock"></i></span>
                      <input type="password" name="password" class="form-control form-control-lg @error('cust.password') is-invalid @enderror" wire:model.debounce.500ms="cust.password" placeholder="Enter your password">
                      <div class="invalid-feedback">Please enter your password!</div>
                    </div>
                  </div>
  
                  <div class="col-12 mt-3">
                    <button class="btn btn-primary w-100 py-3 fw-bold text-uppercase" wire:click="register">
                      Register <i class="bi bi-arrow-right ms-1"></i>
                    </button>
                  </div>
                  
                  <div class="col-12 text-center mt-3">
                    <p class="mb-0">Already have an account? <a href="{{route('cust.login')}}" class="text-decoration-none fw-semibold">Log In</a></p>
                  </div>
                </div>  
              </div>
            </div>
            
            <div class="text-center text-muted small mt-3">
              &copy; 2025 Awesome Ordering Food System. All rights reserved.
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
  
  <!-- Bootstrap Icons CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.3/font/bootstrap-icons.min.css">