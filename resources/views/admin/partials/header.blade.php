<header class="header w-100" style="background-color: #3D5A5C; color: #F0F1F7; padding: 5px 10px;">
  <nav class="navbar navbar-expand-lg py-1">
      <img src="{{ asset('assests/image/burger-bar.png') }}" width="3%" id="navbar-toggler" class="d-none d-lg-block">
      <a class="navbar-brand fw-bold text-uppercase text-white text-center" href="{{ route('dashboard') }}">
          <h4 class="responsive-h4">OCNHS-HRD({{ auth()->user()->role }})</h4>
      </a>
      <ul class="ms-auto d-flex align-items-center list-unstyled mb-0">
          <li class="nav-item dropdown ms-auto">
              <a class="nav-link pe-0 text-white" id="userInfo" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <img class="avatar p-1"
                       src="{{ isset(auth()->user()->employee) && auth()->user()->employee->employee_image ? Storage::url(auth()->user()->employee->employee_image) : asset('assests/image/default.png') }}"
                       alt="a">
              </a>
              <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated" aria-labelledby="userInfo">
                  <div class="dropdown-header text-gray-700">
                      <h6 class="text-uppercase font-weight-bold">{{ auth()->user()->name }}</h6>
                      <small class="text-uppercase">
                          {{ auth()->user()->role == 'System Admin' ? 'System Admin' : (auth()->user()->role == 'Employee' ? 'Employee User' : 'Admin') }}
                      </small>
                  </div>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item text-uppercase" href="{{ route('users.profile.view', Auth::user()->id) }}">View Profile</a>
                  <a class="dropdown-item text-uppercase" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">Logout</a>
              </div>
          </li>
      </ul>
  </nav>
</header>


<!-- Logout Confirmation Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="logoutModalLabel">Confirm Logout</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to log out?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
        <!-- Logout Form -->
        <form action="{{ route('admin.logout') }}" method="GET" style="display: inline;">
            @csrf
            <button type="submit" class="btn btn-success">Logout</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap JS and Dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

<style>
  .navbar {
      padding: 5px 10px; /* Smaller padding for a compact look */
  }
  .avatar {
      width: 40px; /* Smaller avatar */
      height: 40px;
    }

  #navbar-toggler {
      width: 3%; /* Smaller burger icon */
  }
  .responsive-padding {
      padding-left: 5px!important;
      padding-right: 5px!important;
  }

  /* Medium screens and above */
  @media (min-width: 768px) {
      .responsive-padding {
          padding-left: 5px!important;
          padding-right:5px!important;
      }
  }

  /* Large screens and above */
  @media (min-width: 992px) {
    .responsive-padding {
          padding-left: 5px!important;
          padding-right: 5px!important;
  }

  /* Responsive text size for the H4 element */
  .navbar-brand h4  {Margin-left: -20% !important;
      font-size: 1.5rem  !important;/* Default font size for smaller screens */
  }
  }
      

  /* Adjust the font size for larger screens */
  @media (min-width: 768px) {
      
  .navbar-brand h4 { 
    font-size:.9rem !important;
  }
  }

  @media (min-width: 992px) {
    
  .navbar-brand h4 { 
    font-size:1.9rem !important;

  }
  }

</style>