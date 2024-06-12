<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Job;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ApplicationReceived;

class ApplicationController extends Controller
{
    public function store(Request $request)
    {
        $application = Application::create([
            'user_id' => auth()->id(),
            'job_id' => $request->job_id,
            'cv' => $request->cv,
        ]);

        $admin = User::where('is_admin', true)->first();
        Mail::to($admin->email)->send(new ApplicationReceived($application));

        return response()->json($application, 201);
    }

    public function index()
    {
        $applications = Application::with(['user', 'job'])->get();
        return response()->json($applications);
    }

    public function show($id)
    {
        $application = Application::with(['user', 'job'])->find($id);
        return response()->json($application);
    }
}
