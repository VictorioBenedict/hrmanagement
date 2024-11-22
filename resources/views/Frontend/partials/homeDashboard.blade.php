@extends('Frontend.master')

@section('content')
<!-- Slider Section -->
<div class="item">
    <div class="slider-img">
        <img src="{{ asset('assests/image/service 9.jpg') }}" alt="Service Image">
    </div>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="slider-captions text-center">
                    <h1 class="slider-title fw-bold text-white">Olongapo City National High School Human Resources Management Service</h1>
                    <p class="text-white">Empowering businesses through strategic talent solutions and seamless HR management.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Services Section -->
<div class="space-medium bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
            <div class="map-container">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3856.8211239193965!2d120.28162300000001!3d14.835293999999998!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3396711dbbb41993%3A0xee90f905c0945d8c!2sOlongapo%20City%20National%20High%20School!5e0!3m2!1sen!2sph!4v1723205452997!5m2!1sen!2sph" width="800" height="600" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
            </div>
        </div>
        <div class="row">
            <!-- Display the first 4 services -->
            @foreach ($services->take(4) as $item)
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="service-block">
                    <div class="service-img">
                        <img src="{{ url('/uploads/' . $item->service_image) }}" alt="{{ $item->service_name }}">
                    </div>
                    <div class="service-content">
                        <h3 class="service-title"><a href="{{ route('services.details', $item->id) }}" class="title">{{ $item->service_name }}</a></h3>
                        <p>{{ $item->description }}</p>
                        <a href="{{ route('services.details', $item->id) }}">More Details</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="row hidden-service-cards" style="display: none;">
            <!-- Display the remaining services hidden initially -->
            @foreach ($services->slice(4) as $item)
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 hidden-card">
                <div class="service-block">
                    <div class="service-img">
                        <img src="{{ url('/uploads/' . $item->service_image) }}" alt="{{ $item->service_name }}">
                    </div>
                    <div class="service-content">
                        <h3 class="service-title"><a href="{{ route('services.details', $item->id) }}" class="title">{{ $item->service_name }}</a></h3>
                        <p>{{ $item->description }}</p>
                        <a href="{{ route('services.details', $item->id) }}">More Details</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Features Section -->
<div class="space-medium">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <div class="section-title">
                    <h2>Why Choose Us</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <!-- Feature 1 -->
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                <div class="feature-center line">
                    <div class="feature-icon">
                        <i class="fa-solid fa-briefcase fa-xl"></i>
                    </div>
                    <div class="feature-content">
                        <h3>Human Resource Department</h3>
                        <p>Olongapo City National High School</p>
                    </div>
                </div>
            </div>
            <!-- Feature 2 -->
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                <div class="feature-center line">
                    <div class="feature-icon">
                        <i class="fa-solid fa-thumbs-up fa-xl"></i>
                    </div>
                    <div class="feature-content">
                        <h3>Mission and Values</h3>
                        <p>Guided by our commitment to innovation and sustainability, our ethos upholds integrity, values, and inclusivity, ensuring ethical alignment in all endeavors.</p>
                    </div>
                </div>
            </div>
            <!-- Feature 3 -->
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                <div class="feature-center">
                    <div class="feature-icon">
                        <i class="fa-solid fa-people-group fa-xl"></i>
                    </div>
                    <div class="feature-content">
                        <h3>Employee Satisfaction</h3>
                        <p>Our culture prioritizes growth, balance, and inclusivity, nurturing a devoted team for consistent excellence.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Clients Section -->
<div class="space-medium">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <div class="section-title">
                    <h2>We Succeed Because Our Clients Succeed</h2>
                </div>
            </div>
        </div>
        <div class="row">
            @foreach ($clients as $item)
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="client-block">
                    <div class="client-head">
                        <a href="#" class="client-img"><img src="{{ url('/uploads/' . $item->client_image) }}" alt="{{ $item->client_name }}"></a>
                    </div>
                    <div class="client-content">
                        <h4><a href="#">{{ $item->client_name }}</a></h4>
                        <p>{{ $item->details }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <hr>
    </div>
</div>
@endsection
