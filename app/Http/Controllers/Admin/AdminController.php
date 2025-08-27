<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pet;
use App\Models\Scan;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $stats = [
            'total_pets' => Pet::count(),
            'total_users' => User::where('is_admin', false)->count(),
            'total_scans' => Scan::count(),
            'lost_pets' => Pet::where('is_lost', true)->count()
        ];

        return view('admin.dashboard', compact('stats'));
    }

    public function stats()
    {
        $scans = Scan::with('qrCode.pet')->latest()->take(10)->get();
        $popularPets = Pet::withCount('scans')->orderBy('scans_count', 'desc')->take(5)->get();
        
        return view('admin.stats', compact('scans', 'popularPets'));
    }

    public function users()
    {
        $users = User::where('is_admin', false)->withCount('pets')->paginate(10);
        return view('admin.users', compact('users'));
    }

    public function pets()
    {
        $pets = Pet::with('user', 'qrCode')->paginate(10);
        return view('admin.pets', compact('pets'));
    }
}