@extends('frontend.layouts.master')

@section('title',trans('web.Checkout'))

@section('content')



    <div class="bread-crumbs-wrapper">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#" title="" itemprop="url">Home</a></li>
                <li class="breadcrumb-item active">Register Success</li>
            </ol>
        </div>
    </div>

    <!-- Register Success -->
    <section class="reg_success">
        <div class="container">
            <div class="desc_reg text-center">
                <img src="assets/img/chk-ok.png" class="img-responsive" alt="">
                <h2>You have been successfully Order to be a partner of RAALEAT</h2>
            </div>
        </div>
    </section>



@endsection