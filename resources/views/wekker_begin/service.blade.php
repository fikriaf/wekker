@extends('layouts_begin_wekker::app')

@section('content')
<section class="service_section layout_padding">
  <div class="service_container">
    <div class="container ">
      <div class="heading_container heading_center">
        <h2>
          Our <span>Services</span>
        </h2>
        <p>
          Explore a variety of professional tools and services designed to streamline your web development process.
        </p>
      </div>
      <div class="row">
        <div class="col-md-4 ">
          <div class="box ">
            <div class="img-box d-flex justify-content-center">
              <ion-icon class="text-success" name="logo-html5" style="font-size: 5rem;"></ion-icon>
            </div>
            <div class="detail-box">
              <h5>
                HTML
              </h5>
              <p>
                Easily generate websites powered by an HTML engine, tailored to your needs.                </p>
              <!-- <a href="">
                Read More
              </a> -->
            </div>
          </div>
        </div>
        <div class="col-md-4 ">
          <div class="box">
            <div class="img-box d-flex justify-content-center">
              <ion-icon class="text-primary" name="logo-css3" style="font-size: 5rem;"></ion-icon>
            </div>
            <div class="detail-box">
              <h5>
                CSS
              </h5>
              <p>
                Craft beautiful, responsive designs using our CSS-enhanced layout generator.
              </p>
              <!-- <a href="">
                Read More
              </a> -->
            </div>
          </div>
        </div>
        <div class="col-md-4 ">
          <div class="box d-flex justify-content-center">
            <div class="img-box d-flex justify-content-center text-warning">
              <ion-icon name="logo-javascript" style="font-size: 5rem;"></ion-icon>
            </div>
            <div class="detail-box">
              <h5>
                Javascript
              </h5>
              <p>
                Bring interactivity to life with JavaScript-enabled functionality in your webpages.
              </p>
              <!-- <a href="">
                Read More
              </a> -->
            </div>
          </div>
        </div>
      </div>
      <div class="btn-box">
        <!-- <a href="">
          View All
        </a> -->
      </div>
    </div>
  </div>
</section>

@endsection
  