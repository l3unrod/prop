@extends('frontend.layouts.master')

@section('title', 'สมัครสมาชิก')

@section('css')

@endsection

@section('content')

    <!--=============================
        BREADCRUMB START
    ==============================-->
    <section class="fp__breadcrumb" style="background: ; url({{ asset('frontend/images/counter_bg.jpg') }});">
        <div class="fp__breadcrumb_overlay">
            <div class="container">
                <div class="fp__breadcrumb_text">
                    <h1>สมัครสมาชิก</h1>
                    <ul>
                    <li><a href="{{ url('/main') }}">หน้าหลัก</a></li>
                        <li><a href="#">สมัครสมาชิก</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!--=============================
        BREADCRUMB END
    ==============================-->


    <!--=========================
        SIGN UP START
    ==========================-->
    <section class="fp__signup" style="background: url({{ asset('frontend/images/login_bg.jpg') }});">
        <div class="fp__signup_overlay pt_125 xs_pt_95 pb_100 xs_pb_70">
            <div class="container">
                <div class="row wow fadeInUp" data-wow-duration="1s">
                    <div class="col-xxl-5 col-xl-6 col-md-9 col-lg-7 m-auto">
                        <div class="fp__login_area">
                            <h2>ยินดีต้อนรับ!</h2>
                            <p>สมัครสมาชิกเพื่อดำเนินการต่อ</p>
                            <form action="{{ route('register') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-xl-12">
                                        <div class="fp__login_imput">
                                            <label>ชื่อ</label>
                                            <input type="text" placeholder="ชื่อ" name="name" value="{{ old('name') }}">
                                        </div>
                                    </div>
                                    <div class="col-xl-12">
                                        <div class="fp__login_imput">
                                            <label>อีเมล</label>
                                            <input type="email" placeholder="อีเมล" name="email" value="{{ old('email') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-xl-12">
                                        <div class="fp__login_imput">
                                            <label>รหัสผ่าน</label>
                                            <input type="password" placeholder="รหัสผ่าน" name="password" required>
                                        </div>
                                    </div>
                                    <div class="col-xl-12">
                                        <div class="fp__login_imput">
                                            <label>ยืนยันรหัสผ่าน</label>
                                            <input type="password" placeholder="ยืนยันรหัสผ่าน" name="password_confirmation" required>
                                        </div>
                                    </div>
                                    <div class="col-xl-12">
                                        <div class="fp__login_imput">
                                            <button type="submit" class="common_btn">สมัครสมาชิก</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <p class="or"><span>หรือ</span></p>
                            {{-- <ul class="d-flex">
                                <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                                <li><a href="#"><i class="fab fa-linkedin-in"></i></a></li>
                                <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                                <li><a href="#"><i class="fab fa-google-plus-g"></i></a></li>
                            </ul> --}}
                            <p class="create_account">ยังไม่มีบัญชี ? <a href="{{ route('login') }}">เข้าสู่ระบบ</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--=========================
        SIGN UP END
    ==========================-->
@endsection

@section('scripts')
    <script>

    </script>
@endsection
