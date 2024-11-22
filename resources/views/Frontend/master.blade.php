<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    @notifyCss
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="keywords" content="">
    <title>Human Resource OCNHS</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="https://easetemplate.com/free-website-templates/hrms/css/bootstrap.min.css" rel="stylesheet">
    <!-- Style CSS -->
    <link href="https://easetemplate.com/free-website-templates/hrms/css/style.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css?family=Montserrat:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <!-- Owl Carousel CSS -->
    <link href="https://easetemplate.com/free-website-templates/hrms/css/owl.carousel.css" rel="stylesheet">
    <link href="https://easetemplate.com/free-website-templates/hrms/css/owl.theme.default.css" rel="stylesheet">
    <!-- FontAwesome CSS -->
    <link rel="stylesheet" type="text/css" href="css/fontello.css">
    <link href="https://easetemplate.com/free-website-templates/hrms/css/font-awesome.min.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style>
    .logo img {
        max-width: 100px;
        height: auto;
        border-radius: 50px;
    }

    .ft-logo img {
        max-width: 200px;
        height: auto;
        border-radius: 50px;
    }

    .slider-img {
        position: relative;
        overflow: hidden;
    }

    .slider-img::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(2, 71, 142, 0.5);
        z-index: 1;
    }

    .slider-captions {
        position: relative;
        z-index: 2;
        color: white;
    }

    .notify {
        z-index: 9999;
        justify-content: center;
    }

    .custom-color {
        color: rgba(2, 71, 142, 0.5);
    }

    /* Responsive spacing */
    .space-medium {
        padding: 60px 0;
    }

    /* Border for feature-center elements */
    .feature-center {
        padding: 30px;
        border: 1px solid #ddd;
        text-align: center;
        margin-bottom: 30px;
    }

    .feature-center .line {
        border-right: 1px solid #ddd;
    }

    .hidden-service-cards.d-none {
        display: none !important;
    }

    .client-block .client-img img {
        border-radius: 10px;
    }

    /* Responsive client block spacing */
    @media (max-width: 767px) {
        .feature-center,
        .service-block,
        .client-block {
            margin-bottom: 20px;
        }
    }
    .map-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh; /* Full viewport height */
        }
    </style>

</head>

<body>
    @include('notify::components.notify')
    <!-- top-header-section-->
    <div class="menu-toggel">
        <a href="#" id="dots" class="dots-icon"><i class="fa fa-ellipsis-v visible-xs"></i></a>
    </div>
    <!-- /.top-header-section-->
    <!-- header-section-->

    @include('Frontend.partials.navbar')
    <!-- /. header-section-->



    <!-- slider -->
    @yield('content')


    <!-- footer -->
    @include('Frontend.partials.footer')
    <!-- /.footer -->


    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://easetemplate.com/free-website-templates/hrms/js/jquery.min.js" type="text/javascript"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="https://easetemplate.com/free-website-templates/hrms/js/bootstrap.min.js" type="text/javascript">
    </script>
    <script src="https://easetemplate.com/free-website-templates/hrms/js/menumaker.js" type="text/javascript"></script>
    <script type="text/javascript" src="https://easetemplate.com/free-website-templates/hrms/js/jquery.sticky.js">
    </script>
    <script type="text/javascript" src="https://easetemplate.com/free-website-templates/hrms/js/sticky-header.js">
    </script>
    <script type="text/javascript" src="https://easetemplate.com/free-website-templates/hrms/js/owl.carousel.min.js">
    </script>
    <script type="text/javascript" src="https://easetemplate.com/free-website-templates/hrms/js/multiple-carousel.js">
    </script>
    <script type="text/javascript">
        $("#dots").click(function() {
                $(".top-header").toggle("slow", function() {
                    // Animation complete.
                });
            });
    </script>

    <script>
        // Add an event listener to the "View Services" link
            document.querySelector('.view-services').addEventListener('click', function(event) {
                event.preventDefault();

                // Hide the "View Services" link
                this.style.display = 'none';

                // Show the hidden service cards
                const hiddenCards = document.querySelectorAll('.hidden-card');
                hiddenCards.forEach(card => {
                    card.style.display = 'block';
                });
            });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>
    @notifyJs
    <script src="https://kit.fontawesome.com/5c95e5cc68.js" crossorigin="anonymous"></script>



</body>

</html>