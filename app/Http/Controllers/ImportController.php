<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\imports\ImportExcel;
use Maatwebsite\Excel\Facades\Excel;
use App\DataTables\Master_Product_PropDataTable;
use App\Models\Master_Product_Prop;


class ImportController extends Controller
{
    /** index */
    public function index(Master_Product_PropDataTable $dataTable)
    {
        $prop_count = Master_Product_Prop::count();
        return $dataTable->render('admin.import.index', ['prop_count' => $prop_count]);
    }

    /** Edit */
    public function edit ($id) {
        dd($id);
    }

    /** Delete */
    public function destroy ($id) {
        dd($id);
    }

    public function import(Request $request)
    {
        if (!$request->hasFile('excel_file')) {
            return response()->json([
                'success' => false,
                'message' => 'กรุณาเลือกไฟล์ที่ต้องการนำเข้า'
            ], 400);
        }

        try {
            $file = $request->file('excel_file');

            // ตรวจสอบความถูกต้องของไฟล์
            if (!$file->isValid()) {
                return response()->json([
                    'success' => false,
                    'message' => 'เกิดข้อผิดพลาดในการอัพโหลดไฟล์'
                ], 400);
            }

            $extension = $file->getClientOriginalExtension();
            if (!in_array(strtolower($extension), ['xlsx', 'xls', 'csv'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'รูปแบบไฟล์ไม่ถูกต้อง รองรับเฉพาะ .xlsx, .xls และ .csv'
                ], 400);
            }

            if ($file->getSize() > 10 * 1024 * 1024) {
                return response()->json([
                    'success' => false,
                    'message' => 'ขนาดไฟล์เกิน 10MB'
                ], 400);
            }

            // สร้างโฟลเดอร์ชั่วคราว
            $tempPath = storage_path('app/temp/imports');
            if (!file_exists($tempPath)) {
                mkdir($tempPath, 0755, true);
            }

            // บันทึกไฟล์
            $fileName = uniqid('import_') . '.' . $extension;
            $filePath = $tempPath . '/' . $fileName;
            $file->move($tempPath, $fileName);

            // ทำการ import ข้อมูลทันที
            $import = new ImportExcel();
            Excel::import($import, $filePath);

            // เก็บข้อมูลผลลัพธ์
            $result = [
                'success' => true,
                'message' => 'นำเข้าข้อมูลเรียบร้อยแล้ว',
                'data' => [
                    'total_success' => $import->successCount,
                    'total_fail' => $import->failureCount,
                    'imported_at' => now()->format('Y-m-d H:i:s')
                ]
            ];

            // ลบไฟล์ชั่วคราว
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            return response()->json($result);

        } catch (\Exception $e) {
            \Log::error('Import error: ' . $e->getMessage());

            if (isset($filePath) && file_exists($filePath)) {
                unlink($filePath);
            }

            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาดในการนำเข้าข้อมูล: ' . $e->getMessage()
            ], 500);
        }
    }
}
