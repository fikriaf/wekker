@extends('layouts_begin_wekker::app')
@section('content')
<!-- slider section -->
<section class="slider_section ">
  <div id="customCarousel1" class="carousel slide" data-ride="carousel">
    <div class="carousel-inner">
      <div class="carousel-item active">
        <div class="container ">
          <div class="row">
            <div class="col-md-6 ">
              <div class="detail-box">
                <h1>
                  Website <br>
                  Maker
                </h1>
                <p class="mt-3">Wekker menyediakan layanan inovatif yang memungkinkan pengguna membuat website dengan bantuan kecerdasan buatan.
                  Dengan teknologi AI, Wekker mempermudah proses desain dan pengembangan website, sehingga lebih cepat dan efisien.
                  </p>
                    <div class="description-box">
                      <input id="descriptionInput" type="text" class="form-control" placeholder="Type something..">
                    </div>
                    <a id="createButton" class="btn btn-primary btn-custom mt-3" style="font-weight: 700;">
                      <i class="bi bi-stars"></i> Generate
                    </a>
                <div class="btn-box">
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="img-box">
                <img src="{{asset('wekker_begin/images/heading.png')}}" alt="">
              </div>
            </div>
          </div>
        </div>
      </div>
</section>
<!-- end slider section -->
@endsection