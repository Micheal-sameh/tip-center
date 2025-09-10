<?php

namespace App\Http\Controllers;

use App\Enums\StagesEnum;
use App\Http\Requests\ResetYearRequest;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ResetController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:reset_year')->only('resetYearly');
    }

    public function resetYearly(ResetYearRequest $request)
    {
        if (! Hash::check($request->password, auth()->user()->password)) {
            return back()->withErrors(['password' => 'Incorrect password.']);
        }

        info('Yearly reset triggered by: '.auth()->user()->name);

        try {
            // Disable FK checks
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            DB::table('session_extras')->truncate();
            DB::table('session_students')->truncate();
            DB::table('sessions')->truncate();

            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            // Students handling
            Student::query()->where('stage', StagesEnum::SEC_THREE)->delete();

            Student::query()
                ->whereNotIn('stage', [StagesEnum::BAC, StagesEnum::SEC_THREE])
                ->increment('stage');

            return redirect()->back()->with('success', 'Yearly reset completed successfully!');
        } catch (\Throwable $e) {
            report($e);

            return back()->withErrors(['error' => 'Reset failed: '.$e->getMessage()]);
        }
    }
}
