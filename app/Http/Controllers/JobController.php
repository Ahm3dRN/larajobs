<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Job;
use Illuminate\Contracts\Session\Session;
use Illuminate\Validation\Rule;

class JobController extends Controller
{   
    // show all page
    public function index()
    {
        return view('jobs.index', [
            "jobs" => Job::latest()->filter(request(['tag', 'query']))->paginate(6)
        ]);
    }

    // show job page
    public function show(Job $job)
    {
        return view('jobs.show', [
            'job' => $job
        ]);
    }

    // show create form
    public function create()
    {
        return view('jobs.create');
    }

    // store create data
    public function store(Request $request)
    {
        $form_fields = $request->validate([
            'title' => 'required', 
            'company' => [
                            'required',
                            Rule::unique('jobs', 'company'),
                        ],
            
            'location' => 'required',
            'website' => 'required',
            'email' => ['required', 'email'],
            'tags' => 'required',
            'description' => 'required',
        ]);
        if($request->hasFile('logo')){
            $form_fields['logo'] = $request->file('logo')->store('logos', 'public');
        }
        $form_fields['user_id'] = auth()->id();
        // dd($form_fields);
        Job::create($form_fields);
        return redirect('/')->with('message', 'Job Created Successfully.');
    }

    // Show edit form
    public function edit(Job $job)
    {
        if ($job->user_id != auth()->id()) {
            abort(403, 'Unauthorized Action');
        }
        return view('jobs.edit', ['job' => $job]);
    }

    // Update Job Action
    public function update(Request $request, Job $job)
    {
        // Make sure Login user is owner
        if ($job->user_id != auth()->id()) {
            abort(403, 'Unauthorized Action');
        }

        $form_fields = $request->validate([
            'title' => 'required', 
            'company' => ['required',],
            'location' => 'required',
            'website' => 'required',
            'email' => ['required', 'email'],
            'tags' => 'required',
            'description' => 'required',
        ]);
        if($request->hasFile('logo')){
            $form_fields['logo'] = $request->file('logo')->store('logos', 'public');
        }
        // dd($form_fields);
        $job->update($form_fields);
        return back()->with('message', 'Job Updated Successfully.');
    }

    // Delete job
    public function destroy(Job $job)
    {   
        if ($job->user_id != auth()->id()) {
            abort(403, 'Unauthorized Action');
        }
        $job->delete();
        return redirect('/')->with('message', 'Job Deleted Successfully.');
    }

    // Manage Jobs
    public function manage()
    {
        return view('jobs.manage', ['jobs' => auth()->user()->jobs()->get()]);
    }
}
