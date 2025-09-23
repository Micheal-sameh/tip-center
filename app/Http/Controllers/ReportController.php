<?php

namespace App\Http\Controllers;

use App\Enums\ReportType;
use App\Enums\StagesEnum;
use App\Http\Requests\incomeFilterRequest;
use App\Http\Requests\MonthlyIncomeRequest;
use App\Http\Requests\ParentReportRequest;
use App\Http\Requests\ReportIndexRequest;
use App\Http\Requests\SessionReportRequest;
use App\Http\Requests\StudentReportRequest;
use App\Services\ProfessorService;
use App\Services\ReportService;
use App\Services\SessionService;
use App\Services\SessionStudentService;
use App\Services\StudentService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Mpdf\Mpdf;

class ReportController extends Controller
{
    public function __construct(
        protected SessionService $sessionservice,
        protected StudentService $studentService,
        protected ProfessorService $professorService,
        protected ReportService $reportService,
        protected SessionStudentService $sessionStudentService,
    ) {
        $this->middleware('permission:students_report')->only(['student', 'downloadStudentReport']);
        $this->middleware('permission:sessions_report')->only(['session', 'index', 'downloadSessionReport']);
        $this->middleware('permission:income_report')->only(['income', 'incomePdf']);
        $this->middleware('permission:monthly_income')->only(['monthlyIncome']);
        $this->middleware('permission:special_room_report')->only('specialRooms', 'downloadSpecialRooms');
        $this->middleware('permission:monthly_special_rooms')->only('monthlyTenAndEleven');
    }

    public function index(ReportIndexRequest $request)
    {
        $sessions = $this->reportService->index($request->validated());

        return view('reports.index', compact('sessions'));
    }

    public function session(SessionReportRequest $request)
    {
        $session = $this->sessionservice->report($request->validated());
        $reports = $this->reportService->session($request->validated());
        $selected_type = $request->type ?? ReportType::ALL;
        $attendedCount = $reports->where('is_attend', true)->count();

        return view('reports.session', compact('reports', 'session', 'selected_type', 'attendedCount'));
    }

    public function student(StudentReportRequest $request)
    {
        if ($request['student_id']) {
            $reports = $this->reportService->student($request->validated());

            return view('reports.student', compact('reports'));
        } elseif ($request['search']) {
            $students = $this->studentService->index($request->validated());

            return view('reports.student', compact('students'));
        }

        return view('reports.student');
    }

    public function parent(ParentReportRequest $request)
    {
        $reports = $this->reportService->parent($request->validated());
        if ($reports == false) {
            return redirect()->back()->with('error', 'No student found');
        }

        return view('parents.student', compact('reports'));
    }

    public function downloadStudentReport(StudentReportRequest $request)
    {
        $reports = $this->reportService->student($request->validated());

        $pdf = Pdf::loadView('reports.student-pdf', compact('reports'));
        $student = $reports?->first()?->student?->name;

        return $pdf->download("$student.pdf");
    }

    public function downloadSessionReport(SessionReportRequest $request)
    {
        $session = $this->sessionservice->report($request->validated());
        $reports = $this->reportService->session($request->validated());
        $selected_type = $request->type;

        // إنشاء وتكوين mPDF
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'default_font' => 'tajawal',
            'direction' => 'rtl',
            'margin_right' => 15,
            'margin_left' => 15,
            'margin_top' => 16,
            'margin_bottom' => 16,
            'margin_header' => 9,
            'margin_footer' => 9,
        ]);

        // تحميل وعرض view مع البيانات
        $html = view('reports.session-pdf', [
            'reports' => $reports,
            'session' => $session,
            'selected_type' => $selected_type,
        ])->render();

        // إضافة المحتوى إلى PDF
        $mpdf->WriteHTML($html);

        // إنشاء اسم الملف
        $filename = Str::slug($session->professor->name).' - '.
                   StagesEnum::getStringValue($session->stage).'.pdf';

        // تحميل الملف
        return response()->streamDownload(function () use ($mpdf) {
            echo $mpdf->Output('', 'S');
        }, $filename);
    }

    public function income(incomeFilterRequest $request)
    {
        $data = $this->reportService->income($request->validated());
        $sessions = $data['sessions'];
        $totals = $data['totals'];
        $charges = $data['charges'];
        $gap = $data['gap'];
        $settle = $data['settle'];

        return view('reports.income', compact('sessions', 'totals', 'charges', 'gap', 'settle'));
    }

    public function incomePdf(incomeFilterRequest $request)
    {
        $data = $this->reportService->income($request->validated());
        $sessions = $data['sessions'];
        $totals = $data['totals'];
        $charges = $data['charges'];
        $gap = $data['gap'];
        $settle = $data['settle'];
        $date_from = Carbon::parse($request->date_from) ?? today();
        $date_to = Carbon::parse($request->date_to) ?? today();

        $pdf = Pdf::loadView('reports.income-pdf', compact('sessions', 'totals', 'date_from', 'date_to', 'charges', 'gap', 'settle'));
        $filename = Str::slug('income').'.pdf';

        return $pdf->download($filename);
    }

    public function monthlyIncome(MonthlyIncomeRequest $request)
    {
        $month = $request->month;
        $data = $this->reportService->monthlyIncome($month);
        $reports = $data['reports'];
        $totals = [
            'center' => $data['center'],
            'copies' => $data['copies'],
            'markers' => $data['markers'],
            'total_income' => $data['total_income'],
            'gap' => $data['gap'],
            'charges_center' => $data['charges_center'],
            'charges_markers' => $data['charges_markers'],
            'charges_others' => $data['charges_others'],
            'charges_copies' => $data['charges_copies'],
            'total_charges' => $data['total_charges'],
            'total_difference' => $data['total_difference'],
            'net_center' => $data['net_center'],
            'net_copies' => $data['net_copies'],
            'net_markers' => $data['net_markers'],
            'net_others' => $data['net_others'],
        ];

        return view('reports.monthly-income', compact('reports', 'totals', 'month'));
    }

    public function monthlyTenAndEleven(MonthlyIncomeRequest $request)
    {
        $month = $request->month;
        $data = $this->reportService->monthlyTenAndEleven($month);
        $reports = $data['reports'];
        $totals = $data['totals'];

        return view('reports.monthly-special-rooms', compact('reports', 'totals', 'month'));
    }

    public function specialRooms(incomeFilterRequest $request)
    {
        $data = $this->reportService->specialRooms($request);
        $sessions = $data['sessions'];
        $totals = $data['totals'];
        $settle = $data['settle'];
        $charges = $data['charges'];

        return view('reports.special-rooms', compact('sessions', 'totals', 'settle', 'charges'));
    }

    public function downloadSpecialRooms(incomeFilterRequest $request)
    {
        $data = $this->reportService->specialRooms($request);
        $sessions = $data['sessions'];
        $totals = $data['totals'];
        $settle = $data['settle'];
        $charges = $data['charges'];

        $pdf = Pdf::loadView('reports.special-rooms-pdf', compact('sessions', 'totals', 'settle', 'charges'));
        $filename = Str::slug('Room 10 & 11').'.pdf';

        return $pdf->download($filename);
    }
}
