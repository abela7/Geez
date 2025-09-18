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
            ->orderBy('first_name')
            ->orderBy('last_name');

        $staff = $query->paginate(12)->withQueryString();
        $staffTypes = StaffType::active()->orderBy('display_name')->get();

        return view('admin.staff.directory', compact('staff', 'staffTypes'));
    }
}
