{{-- Layout ที่ใช้เป็นแม่แบบสำหรับทุกหน้าของเว็บไซต์ --}}
@extends('frontend.layouts.master')

{{-- กำหนดชื่อหัวเรื่องของหน้า --}}
@section('title', 'เข้าสู่ระบบ')

{{-- ส่วนสำหรับการใส่ CSS --}}
@section('css')
<style>

</style>
@endsection

{{-- เนื้อหาหลักของหน้าเข้าสู่ระบบ --}}
@section('content')

<!--=============================
        BREADCRUMB START
    ==============================-->
    <section class="fp__breadcrumb" style="background: url(images/counter_bg.jpg); background-color: #FFD700; background-size: 50%; height: 150px; position: relative;">
    <div class="fp__breadcrumb_overlay">
        <div class="container">
            <div class="fp__breadcrumb_text" style="color: black; position: absolute; top: 25%; left: 50%; transform: translate(-50%, -50%);">
                <h1 style="color: black;">ลงชื่อเข้าใช้</h1>
                <ul>
                    <li><a href="index.html" style="color: black;">หน้าแรก</a></li>
                    <li><a href="#" style="color: black;">ลงชื่อเข้าใช้</a></li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!--=============================
        BREADCRUMB END
    ==============================-->

<!--=========================
        SIGNIN START
    ==========================-->
    <section class="fp__signin" style="background: url(images/login_bg.jpg);">
    <div class="fp__signin_overlay pt_125 xs_pt_95 pb_100 xs_pb_70">
        <div class="container">
            <div class="row wow fadeInUp" data-wow-duration="1s">
                <div class="col-xxl-5 col-xl-6 col-md-9 col-lg-7 m-auto">
                    <div class="fp__login_area">
                        <h2>ยินดีต้อนรับ</h2> <!-- แสดงข้อความต้อนรับ -->
                        <p>กรุณาเข้าสู่ระบบเพื่อดำเนินการต่อ</p> <!-- ข้อความบอกให้ล็อกอินเพื่อดำเนินการต่อ -->
                        
                        <!-- ฟอร์มสำหรับการเข้าสู่ระบบ -->
                        <form action="{{ route('login') }}" method="POST">
                            @csrf <!-- ป้องกัน CSRF -->
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="fp__login_imput">
                                        <label>อีเมล</label> <!-- ป้ายชื่อสำหรับช่องกรอกอีเมล -->
                                        <input type="email" id="email" name="email" placeholder="อีเมล" value="{{ old('email') }}" required>
                                    </div>
                                </div>
                                <div class="col-xl-12">
                                    <div class="fp__login_imput">
                                        <label>รหัสผ่าน</label> <!-- ป้ายชื่อสำหรับช่องกรอกรหัสผ่าน -->
                                        <input type="password" placeholder="รหัสผ่าน" name="password" required>
                                    </div>
                                </div>
                                <div class="col-xl-12">
                                    <div class="fp__login_imput fp__login_check_area">
                                        <!-- ตัวเลือกสำหรับ "Remember me" -->
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value=""
                                                name="remember" id="flexCheckDefault">
                                            <label class="form-check-label" for="flexCheckDefault">
                                                จดจำรหัสผ่าน
                                            </label>
                                        </div>
                                        <a href="{{ route('password.request') }}">ลืมรหัสผ่าน ?</a> <!-- ลิงก์สำหรับการลืมรหัสผ่าน -->
                                    </div>
                                </div>
                                <div class="col-xl-12">
                                    <div class="fp__login_imput">
                                        <button type="submit" class="common_btn">เข้าสู่ระบบ</button> <!-- ปุ่มเข้าสู่ระบบ -->
                                    </div>
                                </div>
                            </div>
                        </form>
                        
                        <!-- ข้อความแยกออปชั่น เช่น ใช้บัญชีอื่นๆ -->
                        <p class="or"><span>หรือ</span></p>
                        
                        <!-- ลิงก์สำหรับการสร้างบัญชีใหม่ -->
                        <p class="create_account">ยังไม่มีบัญชีใช่ไหม? <a href="{{ route('register') }}">สร้างบัญชี</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--=========================
        SIGNIN END
    ==========================-->
@endsection
