<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\StaffType;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StaffDirectoryController extends Controller
{
    /**
     * Display the staff directory with filters and pagination.
     */
    public function index(Request $request): View
    {
        $viewMode = $request->get('view', 'grid'); // 'grid' or 'list'

        $query = Staff::query()
            ->with(['staffType', 'profile'])
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->string('search');
                $q->where(function ($inner) use ($search) {
                    $inner->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('username', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('staff_type_id'), function ($q) use ($request) {
                $q->where('staff_type_id', $request->string('staff_type_id'));
            })
            ->when($request->filled('status'), function ($q) use ($request) {
                $q->where('status', $request->string('status'));
            })
            ->when($request->filled('department'), function ($q) use ($request) {
                $q->where('department', $request->string('department')); // Assuming 'department' field on Staff; adjust if join needed
            });

        $staff = $query->orderBy('first_name')
            ->orderBy('last_name')
            ->paginate(12)->withQueryString();

        $staffTypes = StaffType::active()->orderBy('display_name')->get();

        // Departments (static for now; fetch from DB if model exists)
        $departments = [
            '' => __('staff.all_departments'),
            'kitchen' => __('staff.kitchen'),
            'service' => __('staff.service'),
            'administration' => __('staff.administration'),
            'maintenance' => __('staff.maintenance'),
        ];

        return view('admin.staff.directory', compact('staff', 'staffTypes', 'viewMode', 'departments'));
    }
}
