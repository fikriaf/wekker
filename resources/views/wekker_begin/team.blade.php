@extends('layouts_begin_wekker::app')

@section('content')
<!-- team section -->
<section class="team_section layout_padding">
  <div class="container-fluid">
    <div class="heading_container heading_center">
      <h2 class="">
        Our <span> Team</span>
      </h2>
    </div>

    <div class="team_container">
      <div class="row">
          <div class="col-lg-6 col-sm-6">
          <div class="box ">
            <div class="img-box">
              <img src="https://blue.kumparan.com/image/upload/fl_progressive,fl_lossy,c_fill,q_auto:best,w_640/v1634025439/01ja7g5dtn40t6wc9z0x5kczgk.jpg" class="img1" alt="">
            </div>
            <div class="detail-box">
              <h5>
                Al Fariz
              </h5>
              <p>
                Chief Executive Officer
              </p>
            </div>
            <div class="social_box">
              <a href="#">
                <i class="fa fa-facebook" aria-hidden="true"></i>
              </a>
              <a href="#">
                <i class="fa fa-twitter" aria-hidden="true"></i>
              </a>
              <a href="#">
                <i class="fa fa-linkedin" aria-hidden="true"></i>
              </a>
              <a href="#">
                <i class="fa fa-instagram" aria-hidden="true"></i>
              </a>
              <a href="#">
                <i class="fa fa-youtube-play" aria-hidden="true"></i>
              </a>
            </div>
          </div>
        </div>
        <div class="col-lg-6 col-sm-6">
          <div class="box ">
            <div class="img-box">
              <img src="{{asset('wekker_begin/images/fikri.jpg')}}" class="img1" alt="">
            </div>
            <div class="detail-box">
              <h5>
                Fikri A.F
              </h5>
              <p>
                Chief Technical Officer 
              </p>
            </div>
            <div class="social_box">
              <a href="#">
                <i class="fa fa-facebook" aria-hidden="true"></i>
              </a>
              <a href="#">
                <i class="fa fa-twitter" aria-hidden="true"></i>
              </a>
              <a href="#">
                <i class="fa fa-linkedin" aria-hidden="true"></i>
              </a>
              <a href="#">
                <i class="fa fa-instagram" aria-hidden="true"></i>
              </a>
              <a href="#">
                <i class="fa fa-youtube-play" aria-hidden="true"></i>
              </a>
            </div>
          </div>
        </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection