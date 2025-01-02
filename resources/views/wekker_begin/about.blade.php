@extends('layouts_begin_wekker::app')

@section('content')
<section class="about_section layout_padding">
  <div class="container  ">
    <div class="heading_container heading_center">
      <h2>
        About <span>Us</span>
      </h2>
      <p>
        Wekker adalah platform yang memudahkan pembuatan dan pengelolaan halaman web secara cepat, intuitif, dan fleksibel.
      </p>
    </div>
    <div class="row">
      <div class="col-md-6 ">
        <div class="img-box">
          <img width="87%" src="{{asset('wekker_begin/images/about.png')}}" alt="">
        </div>
      </div>
      <div class="col-md-6">
        <div class="detail-box">
          <h3>
            We Are Wekker
          </h3>
          <p style="text-align: justify;">
            Wekker adalah platform inovatif berbasis Laravel yang dirancang untuk mempermudah pembuatan, pengeditan, dan pengelolaan halaman web secara otomatis. Diciptakan untuk pengguna dengan berbagai tingkat keahlian, Wekker memungkinkan Anda membangun berbagai jenis halaman web dengan cepat dan efisien, tanpa perlu keahlian teknis yang mendalam.
          </p>
          <p style="text-align: justify;">
            Dengan fitur-fitur canggih seperti Main Builder, Developer Tools, dan API Management, pengguna dapat dengan mudah menyesuaikan desain dan fungsionalitas backend sesuai kebutuhan. Antarmuka yang intuitif dan dukungan pustaka populer seperti jQuery dan Prism.js menjadikan pengalaman menggunakan Wekker menyenangkan dan produktif.
            Kami percaya bahwa setiap individu dan bisnis berhak memiliki kehadiran online yang profesional.
          </p>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection