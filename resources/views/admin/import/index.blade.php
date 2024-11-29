@extends('admin.layouts.master')
@section('title', 'ถ่ายโอนข้อมูล')
@section('css')
    <style>
        .card {
            border-radius: 10px;
            transition: all 0.3s;
        }

        .card:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .btn {
            border-radius: 5px;
        }

        .loading-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.9);
            z-index: 9999;
        }

        .loading-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
        }

        /* เพิ่ม Animation */
        .loading-animation {
            width: 150px;
            /* ปรับขนาดตามที่ต้องการ */
            height: 150px;
            /* ปรับขนาดตามที่ต้องการ */
            margin: 0 auto;
            position: relative;
        }

        .loading-animation img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            /* เพิ่มเพื่อรักษาอัตราส่วนของรูป */
            animation: bounce 1s infinite;
        }

        @keyframes bounce {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        /* สไตล์ข้อความ */
        .loading-text {
            margin-top: 20px;
            font-size: 18px;
            color: #333;
            font-weight: 500;
        }

        .loading-progress {
            margin-top: 15px;
            font-size: 14px;
            color: #666;
        }

        /* เพิ่ม Progress Bar */
        .progress-bar-custom {
            width: 200px;
            height: 4px;
            background: #f0f0f0;
            border-radius: 10px;
            margin: 10px auto;
            overflow: hidden;
        }

        .progress-bar-fill {
            height: 100%;
            background: #4CAF50;
            width: 0%;
            border-radius: 10px;
            animation: progress 2s infinite;
        }

        @keyframes progress {
            0% {
                width: 0%;
            }

            100% {
                width: 100%;
            }
        }
    </style>
@endsection

@section('content')
    <section class="section">
        <div class="section-header">
            <h1><i class="fas fa-exchange-alt mr-2"></i>นำเข้าข้อมูล</h1>
        </div>
    </section>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-content">
            <div class="loading-animation">
                <img src="{{ asset('frontend/images/noodles.gif') }}" alt="Loading">
            </div>
            <div class="loading-text">
                กำลังนำเข้าข้อมูล
            </div>
            <div class="progress-bar-custom">
                <div class="progress-bar-fill"></div>
            </div>
            <div class="loading-progress">
                กรุณารอสักครู่...
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h4><i class="fas fa-file-import mr-2"></i>นำเข้าข้อมูล</h4>
                    <div class="card-header-action">
                        <button class="btn btn-danger" id="truncateBtn">
                            <i class="fas fa-trash-alt mr-1"></i> ลบข้อมูลทั้งหมด
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">นำเข้าไฟล์ Excel</h5>
                                    <form action="{{ route('import.excel') }}" method="POST" enctype="multipart/form-data"
                                        id="importForm">
                                        @csrf
                                        <div class="form-group">
                                            @if ($errors->any())
                                                <div class="alert alert-danger">
                                                    <ul class="mb-0">
                                                        @foreach ($errors->all() as $error)
                                                            <li>{{ $error }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif

                                            <div class="custom-file">
                                                <input type="file" name="excel_file"
                                                    class="custom-file-input @error('excel_file') is-invalid @enderror"
                                                    id="customFile" accept=".xlsx,.xls,.csv">
                                                <label class="custom-file-label" for="customFile">เลือกไฟล์</label>
                                            </div>

                                            <div id="fileInfo" class="mt-2 small text-muted"></div>
                                        </div>

                                        <button class="btn btn-success btn-block" type="submit" id="submitBtn">
                                            <i class="fas fa-file-upload mr-1"></i>
                                            <span id="submitText">นำเข้าข้อมูล</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">รูปแบบการนำเข้า</h5>
                                    <button class="btn btn-info btn-block" id="downloadFile" type="button">
                                        <i class="fas fa-download mr-1"></i> ดาวน์โหลดไฟล์ตัวอย่าง
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if (session('import_result'))
                        <div class="alert alert-{{ session('import_result.success') ? 'success' : 'danger' }} mt-3">
                            <h5 class="alert-heading">{{ session('import_result.message') }}</h5>
                            @if (session('import_result.success'))
                                <p class="mb-0">
                                    วันที่และเวลาที่นำเข้า: {{ session('import_result.imported_at') }}
                                </p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-table mr-2"></i>รายการข้อมูลทั้งหมด จำนวน : {{ $prop_count }} รายการ</h4>
                </div>
                <div class="card-body">
                    {{ $dataTable->table(['class' => 'table table-striped table-bordered']) }}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.7.14/lottie.min.js"></script>
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
    <script>
        $(document).ready(function() {
            // ตั้งค่า Lottie Animation
            const animation = lottie.loadAnimation({
                container: document.getElementById('lottie-animation'),
                renderer: 'svg',
                loop: true,
                autoplay: true,
                path: 'path/to/your/animation.json' // ใส่ path ของไฟล์ animation
            });

            // แสดง loading
            function showLoading() {
                $("#loadingOverlay").fadeIn(300);
            }

            // ซ่อน loading
            function hideLoading() {
                $("#loadingOverlay").fadeOut(300);
            }
            // จัดการการแสดงชื่อไฟล์และการตรวจสอบ
            $("#customFile").on("change", function(e) {
                const file = e.target.files[0];
                if (file) {
                    const fileName = file.name;
                    const fileExt = fileName.split('.').pop().toLowerCase();

                    // ตรวจสอบนามสกุลไฟล์
                    if (!['xlsx', 'xls', 'csv'].includes(fileExt)) {
                        alert('กรุณาเลือกไฟล์ Excel (.xlsx, .xls) หรือ CSV เท่านั้น');
                        this.value = '';
                        return;
                    }

                    // แสดงชื่อไฟล์และขนาด
                    const fileSize = (file.size / 1024 / 1024).toFixed(2);
                    $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
                    $("#fileInfo").html(`ชื่อไฟล์: ${fileName}<br>ขนาด: ${fileSize} MB`);
                }
            });

            // จัดการการ submit form
            $("#importForm").on("submit", function(e) {
                e.preventDefault(); // ป้องกันการ submit แบบปกติ

                const file = $("#customFile")[0].files[0];
                if (!file) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'กรุณาเลือกไฟล์',
                        text: 'คุณยังไม่ได้เลือกไฟล์สำหรับนำเข้าข้อมูล'
                    });
                    return false;
                }

                const formData = new FormData(this);

                // แสดง loading
                $("#loadingOverlay").show();
                $("#submitBtn").prop('disabled', true);
                $("#submitText").text('กำลังนำเข้าข้อมูล...');

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'นำเข้าข้อมูลสำเร็จ',
                                html: `
                            <p>${response.message}</p>
                            <p>จำนวนนำเข้าสำเร็จ: ${response.data.total_success} รายการ</p>
                            <p>จำนวนนำเข้าไม่สำเร็จ: ${response.data.total_fail} รายการ</p>
                            <p>เวลาที่นำเข้า: ${response.data.imported_at}</p>
                        `,
                                showConfirmButton: true,
                                confirmButtonText: 'ตกลง'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    location.reload();
                                }
                            });

                            // อัพเดทจำนวนในหน้า
                            $("#total_import_success").text(response.data.total_success);
                            $("#total_import_fail").text(response.data.total_fail);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'เกิดข้อผิดพลาด',
                                text: response.message,
                                confirmButtonText: 'ปิด'
                            });
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'เกิดข้อผิดพลาดในการนำเข้าข้อมูล';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาด',
                            text: errorMessage,
                            confirmButtonText: 'ปิด'
                        });
                    },
                    complete: function() {
                        // ซ่อน loading และ reset form
                        $("#loadingOverlay").hide();
                        $("#submitBtn").prop('disabled', false);
                        $("#submitText").text('นำเข้าข้อมูล');
                        $("#customFile").val('');
                        $(".custom-file-label").text('เลือกไฟล์');
                        $("#fileInfo").html('');
                    }
                });
            });

            // เพิ่ม function สำหรับ format ตัวเลข (ถ้าต้องการ)
            function formatNumber(num) {
                return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
            }

            // ดาวน์โหลดไฟล์ตัวอย่าง
            $("#downloadFile").on("click", function() {
                window.location.href = "{{ route('download.sample') }}";
            });

            // จัดการการลบข้อมูลทั้งหมด
            $("#truncateBtn").on("click", function() {
                Swal.fire({
                    title: 'คุณแน่ใจหรือไม่?',
                    text: "การลบข้อมูลทั้งหมดไม่สามารถย้อนกลับได้!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'ใช่, ลบทั้งหมด!',
                    cancelButtonText: 'ยกเลิก'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $("#loadingOverlay").show();
                        $.ajax({
                            type: "POST",
                            url: "{{ route('truncate.table') }}",
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                $("#loadingOverlay").hide();
                                if (response.success) {
                                    Swal.fire(
                                        'สำเร็จ!',
                                        response.message,
                                        'success'
                                    ).then(() => {
                                        window.location.reload();
                                    });
                                } else {
                                    Swal.fire(
                                        'ผิดพลาด!',
                                        response.message,
                                        'error'
                                    );
                                }
                            },
                            error: function() {
                                $("#loadingOverlay").hide();
                                Swal.fire(
                                    'ผิดพลาด!',
                                    'เกิดข้อผิดพลาดในการเชื่อมต่อกับเซิร์ฟเวอร์',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
