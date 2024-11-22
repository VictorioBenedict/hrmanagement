<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    @notifyCss
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Human Resource System</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;700&display=swap" rel="stylesheet">
    
    <!-- Custom Stylesheets -->
    <link rel="stylesheet" href="../../css/prism-toolbar.css">
    <link rel="stylesheet" href="../../css/prism-okaidia.css">
    <link rel="stylesheet" href="../../css/main-theme.css">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Bundle JS (includes Popper.js) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <style>
        /* General Styles */
        body {
            font-family: 'Poppins', Arial, sans-serif;
            background-color: #CECECE;
            color: #2c3e50;
            margin: 0;
            padding: 0;
            transition: margin-left 0.3s ease;
        } body {
            font-family: 'Poppins', Arial, sans-serif;
            background-color: #CECECE;
            color: #2c3e50;
            margin: 0;
            padding: 0;
            transition: margin-left 0.3s ease;
        }
    .shadow {
        background-color: #DF7A30 !important ; 
    }
    .card-header {
        background-color: #5A6D7B !important ; 
    }
    .pagination .page-link{
    color:black !important;
    }
    .text{
        color: white !important ; 
    }
    .text-uppercase{
        color: White !important;" ; 
    }

    .text-uppercasess{
        color: black !important;"
        background-color: #5A6D7B !important; padding-top:10px; ; 
    }
    .text-uppercases{
        color: black !important;" ; 
    }
            /* Page Header */
            .page-header {
                text-align: center;
                background-color: #3498db; /* Solid color background for header */
                color: #fff;
                border-radius: 8px;
                margin-bottom: 20px;
                padding: 20px 0;
            }

            .page-header .page-heading {
                font-size: 32px;
                font-weight: 600;
            }

            /* Sidebar Styles */
            .sidebar {
                width: 250px;
                position: fixed;
                top: 0;
                left: 0;
                bottom: 0;
                transform: translateX(-250px);
                transition: transform 0.3s ease;
                z-index: 1050;
                padding-top: 20px;
                background-color: #2c3e50; /* Dark sidebar */
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .sidebar ul {
                padding: 0;
                list-style: none;
            }

            .sidebar li {
                padding: 15px 25px;
                color: #fff;
                transition: background-color 0.3s ease;
            }

            .sidebar a {
                text-decoration: none;
                color: inherit;
                font-weight: 500;
            }

            .sidebar a:hover {
                background-color: #34495e;
                border-radius: 8px;
            }

            /* Navbar Toggle Icon */
            .navbar-toggler-icon {
                background-color: #3498db;
            }

            /* Body Shift When Sidebar is Active */
            body.sidebar-active {
                margin-left: 250px;
            }

            /* Content Wrapper */
            .content-wrapper {
                flex-grow: 1;
                transition: margin-left 0.3s ease;
                background-color: #ffffff;
                border-radius: 8px;
                box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
                padding: 20px;
            }

            /* Footer *//* Footer Styling */
    footer {
        background-color: #3D5A5C;
        color: #ffffff;
        text-align: center;
        padding: 10px 0;
        position: sticky;
        bottom: 0;
        width: 100%;
        z-index: 1050; /* Ensure it stays above other elements */
        display: block;
        box-shadow: 0px -2px 5px rgba(0, 0, 0, 0.1); /* Optional: subtle shadow to give it a floating look */
    }

    body:not(.sidebar-active) footer {
        display: none; /* Hide footer when sidebar is active */
    }


            footer p {
                margin: 0;
            }

            /* Buttons and Links */
            .btn-primary {
                background-color: #3498db;
                border-color: #2980b9;
            }

            .btn-primary:hover {
                background-color: #2980b9;
                border-color: #3498db;
            }

        

            /* Sidebar Link Highlight */
            .sidebar-link.active,
            .sidebar-link:hover {
                background-color: #007bff;
                border-radius: 8px;
            }

        /* Custom Image Size */
        .custom-small-img {
            width: 40px;
            height: auto;
        }

        /* Hover Effects */
        .card:hover .card-body h4 {
            color: #e0e0e0;
        }

        /* Links */
        .text-decoration-none {
            text-decoration: none !important;
        }

        .text-dark {
            color: #343a40;
        }

        /* Utility Classes */
        .mt-4 {
            margin-top: 1.5rem !important;
        }

        .mb-4 {
            margin-bottom: 1.5rem !important;
        }

        /* Page Header Animated Text */
        .animated-text span {
            animation: fadeIn 1s ease-in-out;
        }
span{
    color:white !important;
}h4{
    color:white !important;
}
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* Sidebar Icon (Dashboard) */
        .custom-small-img {
            width: 80px;
            height: auto;
        }

        /* Keyframe Animation */
        @keyframes fadeInWords {
            0% { opacity: 0; transform: translateX(0); }
            25%, 50%, 75%, 100% { opacity: 1; transform: translateX(0); }
        }

        .animated-text {
            white-space: nowrap;
            overflow: hidden;
        }

        .animated-text span {
            display: inline-block;
            opacity: 0;
            animation: fadeInWords 5s linear infinite;
        }

        /* Notification Styles */
        .notify {
            z-index: 9999;
            justify-content: center;
        }

        /* Calendar */
        #calendar {
            max-width: 900px;
            margin: 0 auto;
        }

        /* Loader Styles */
        .loader {
            width: 100%;
            height: 100%;
            position: fixed;
            display: flex;
            justify-content: center;
            align-items: center;
            background: rgba(51, 51, 51, 0.8);
            z-index: 99999;
        }
    </style>

<style>
    th, td {

        border: 1px solid black !important; /* Adjust thickness of lines */
        padding: 10px; /* Optional: adds padding inside the cells */
    }
    th {
        background-color: #3D5A5C !important; /* Light background for header */
    }
    /* Style the first row */
th:first-child {
    color: white !important; /* Text color */
    background-color: #3D5A5C; /* Background color */
}
table{
    background-color: white !important;
}
    .form-control{
        background: white !important;
        color: black !important;
    }
    .modal-body{
        background-color: white !important;
    }
    .modal-body p{
        color: black !important;
    }
    .modal-footer{
        background-color: white !important;
    }
    .modal-footer p{
        color: black !important;
    }
    .modal-footer button{
        color: white !important;
    }
    
    .modal-body{
        color: black !important;
    }
    .modal-header{
        color: white !important;
        background-color: #3D5A5C !important;
    }
.shadow {
    background-color:   ; 
}
.card-header {
    background-color:   ; 
}
.text{
    color: white !important ; 
}
.text-uppercase{
    color: black !important;" ; 
}
.text-uppercases{
    color: white !important;" ; 
}
        /* Page Header */
        .page-header {
            text-align: center;
            background-color: #3498db; /* Solid color background for header */
            color: #fff;
            border-radius: 8px;
            margin-bottom: 20px;
            padding: 20px 0;
        }

        .page-header .page-heading {
            font-size: 32px;
            font-weight: 600;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            transform: translateX(-250px);
            transition: transform 0.3s ease;
            z-index: 1050;
            padding-top: 20px;
            background-color: #2c3e50; /* Dark sidebar */
        }

        .sidebar.active {
            transform: translateX(0);
        }

        .sidebar ul {
            padding: 0;
            list-style: none;
        }

        .sidebar li {
            padding: 15px 25px;
            color: #fff;
            transition: background-color 0.3s ease;
        }

        .sidebar a {
            text-decoration: none;
            color: inherit;
            font-weight: 500;
        }

        .sidebar a:hover {
            background-color: #34495e;
            border-radius: 8px;
        }

        /* Navbar Toggle Icon */
        .navbar-toggler-icon {
            background-color: #3498db;
        }

        /* Body Shift When Sidebar is Active */
        body.sidebar-active {
            margin-left: 250px;
        }

        /* Content Wrapper */
        .content-wrapper {
            flex-grow: 1;
            transition: margin-left 0.3s ease;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        /* Footer *//* Footer Styling */
footer {
    background-color: #19305C;
    color: #ffffff;
    text-align: center;
    padding: 10px 0;
    position: sticky;
    bottom: 0;
    width: 100%;
    z-index: 1050; /* Ensure it stays above other elements */
    display: block;
    box-shadow: 0px -2px 5px rgba(0, 0, 0, 0.1); /* Optional: subtle shadow to give it a floating look */
}

body:not(.sidebar-active) footer {
    display: none; /* Hide footer when sidebar is active */
}


        footer p {
            margin: 0;
        }

        /* Buttons and Links */
        .btn-primary {
            background-color: #3498db;
            border-color: #2980b9;
        }

        .btn-primary:hover {
            background-color: #2980b9;
            border-color: #3498db;
        }

        /* Table Styles */
        .table {
            border-radius: 0.5rem;
            overflow: hidden;
        }

        .table th, .table td {
            vertical-align: middle;
        }

        .table th {
            background-color: #34495e;
            color: #fff;
        }

        .table tbody tr:hover {
            background-color: #ecf0f1;
        }

        /* Sidebar Link Highlight */
        .sidebar-link.active,
        .sidebar-link:hover {
            background-color: #007bff;
            border-radius: 8px;
        }

        /* Custom Image Size */
        .custom-small-img {
            width: 40px;
            height: auto;
        }

        /* Hover Effects */
        .card:hover .card-body h4 {
            color: #007bff;
        }

        /* Links */
        .text-decoration-none {
            text-decoration: none !important;
        }

        .text-dark {
            color: #343a40;
        }

        /* Utility Classes */
        .mt-4 {
            margin-top: 1.5rem !important;
        }

        .mb-4 {
            margin-bottom: 1.5rem !important;
        }

        /* Page Header Animated Text */
        .animated-text span {
            animation: fadeIn 1s ease-in-out;
        }
span{
    color:white !important;
}h4{
    color:white !important;
}
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* Sidebar Icon (Dashboard) */
        .custom-small-img {
            width: 80px;
            height: auto;
        }

        /* Keyframe Animation */
        @keyframes fadeInWords {
            0% { opacity: 0; transform: translateX(0); }
            25%, 50%, 75%, 100% { opacity: 1; transform: translateX(0); }
        }

        .animated-text {
            white-space: nowrap;
            overflow: hidden;
        }

        .animated-text span {
            display: inline-block;
            opacity: 0;
            animation: fadeInWords 5s linear infinite;
        }

        /* Notification Styles */
        .notify {
            z-index: 9999;
            justify-content: center;
        }

        /* Calendar */
        #calendar {
            max-width: 900px;
            margin: 0 auto;
        }

        /* Loader Styles */
        .loader {
            width: 100%;
            height: 100%;
            position: fixed;
            display: flex;
            justify-content: center;
            align-items: center;
            background: rgba(51, 51, 51, 0.8);
            z-index: 99999;
        }
    </style>
<style>
    table {
        border-collapse: collapse;
        width: 100%;
    }
    th, td {
        border: 1px solid black !important; /* Adjust thickness of lines */
        padding: 10px; /* Optional: adds padding inside the cells */
    }
    th {
        background-color: #19305C !important; /* Light background for header */
    }
    tr:nth-child(even) {
        background-color: #f2f2f2; /* Optional: alternating row colors */
    }
    .form-control{
        background: white !important;
        color: black !important;
    }
    .search-container {
        display: flex !important;
        justify-content: flex-end !important;
        margin-bottom: 30px !important; /* Adjust as needed */
    }
    .pagination {
        margin-top: 20px;
    }

    /* Disable state for previous and next buttons */
    .pagination .page-item.disabled .page-link {
        color: #A0A6B1; /* Light gray color for disabled buttons */
        pointer-events: none; /* Prevent clicks */
    }

    /* Active page number styling */
    .pagination .page-item.active .page-link {
        background-color: #3D5A5C !important; /* Custom active color */
        border-color: #3D5A5C !important;
        color: white !important /* Light text color */
    }

    /* Page link styling */
    .pagination .page-link {
        color: white; /* Dark color for page numbers */
        border: 1px solid #D1D6E8; /* Light border for links */
        padding: 0.5rem 0.75rem;
    }

    /* Hover effect for page links */
   .pagination .page-item:not(.disabled) .page-link:hover {
        background-color: #3D5A5C !important;
        color: white !important;
    }

    /* Custom icon styles */
    .pagination .page-link i {
        font-size: 1.2rem;
        color: black !important;  /* Match the text color */
    }
    .input-wrapper {
        display: flex !important;
        border-radius: 30px !important;
        overflow: hidden !important;
        width: 25% !important;
    }

    .search-input {
        flex: 1 !important;
        padding: 10px !important;
        border: 1px solid #ccc !important;
        border-right: none !important;
        border-radius: 30px 0 0 30px !important;
        font-size: 16px !important;
    }

    .search-btn {
        background-color: black !important;
        border: 1px solid #ccc !important;
        border-left: none !important;
        border-radius: 0 30px 30px 0 !important;
        padding: 10px !important;
        cursor: pointer !important;
    }

    .search-btn i {
        font-size: 18px !important;
        color: #333 !important;
    }

    /* Optional: Add hover effect for the button */
    .search-btn:hover {
        background-color: #f1f1f1 !important;
    }
</style>

    <script>
        // Time and Date Display Function
        function display_ct7() {
            var x = new Date();
            var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            var day = days[x.getDay()];
            var ampm = x.getHours() >= 12 ? ' PM' : ' AM';
            var hours = x.getHours() % 12 || 12;
            var minutes = x.getMinutes().toString().padStart(2, '0');
            var seconds = x.getSeconds().toString().padStart(2, '0');
            var month = (x.getMonth() + 1).toString().padStart(2, '0');
            var dt = x.getDate().toString().padStart(2, '0');
            var dayOfWeekElement = document.getElementById('dayOfWeek');
            dayOfWeekElement.innerHTML = day;

            var x1 = `${month}-${dt}-${x.getFullYear()} - ${hours}:${minutes} ${ampm}`;
            document.getElementById('ct7').innerHTML = x1;
            display_c7();
        }

        function display_c7() {
            setTimeout(display_ct7, 1000);
        }
        display_c7();
    </script>

    <script>
        $(document).ready(function () {
            // Sidebar Toggle
            $("#navbar-toggler").click(function () {
                $(".sidebar").toggleClass("active");
                $("body").toggleClass("sidebar-active");
            });
        });

        $(window).on('load', function () {
            $(".loader").fadeOut("slow");
        });
    </script>

</head>

<body>

    @include('admin.partials.header', ['style' => 'position: fixed; top: 0; left: 0; width: 100%; z-index: 9999;'])

    {{-- notify --}}
    @include('notify::components.notify')

    <!-- Sidebar -->
    @include('admin.partials.sidebar')

    <!-- Main Content --><div style="width: 97%; margin: 0 auto; margin-top:1%; ">
        @yield('content') <!-- This will now have a width of 95% -->
    </div>


    <!-- Footer --><footer style="position: fixed; bottom: 0; left: 0; width: 100%; text-align: center; padding: 10px 0;">
    <div>
        <p class="mb-0">Copyright © All Rights Reserved 2024 Human Resource OCNHS</p>
    </div>
</footer>


    @notifyJs

</body>

</html>