<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ListingController extends Controller
{
    public function index()
    {
        return view('listings.index', [
            'listings' => Listing::latest()->filter(request(['tag', 'search']))->paginate(6),
        ]);
    }

    public function show(Listing $listing)
    {
        return view('listings.show', [
            'listing' => $listing,
        ]);
    }

    public function create()
    {
        return view('listings.create');
    }

    public function store(Request $request)
    {
        $formData = $request->validate([
            'title' => 'required',
            'company' => ['required', Rule::unique('listings', 'company',)],
            'location' => 'required',
            'website' => 'required',
            'email' => ['required', 'email', Rule::unique('listings', 'email')],
            'tags' => 'required',
            'description' => 'required',
        ]);

        $formData['user_id']= auth()->user()->id;

        if ($request->hasFile('logo')) {
            $formData['logo'] = $request->file('logo')->store('logos', 'public');
        }
        Listing::create($formData);
        return redirect('/')->with('message', 'listing created successfully!');
    }

    public function edit(Listing $listing)
    {
        return view('listings.edit', [
            'listing' => $listing
        ]);
    }

    public function update(Request $request, Listing $listing)
    {
        $formData = $request->validate([
            'title' => 'required',
            'company' => 'required',
            'location' => 'required',
            'website' => 'required',
            'email' => ['required', 'email'],
            'tags' => 'required',
            'description' => 'required',
        ]);
        if ($request->hasFile('logo')) {
            $formData['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $listing->update($formData);
        return back()->with('message', 'listing updated successfully!');
    }

    public function destroy(Listing $listing)
    {
        $listing->delete();
        return redirect('/')->with('message', 'listing deleted successfully!');
    }

    public function manage(){
        return view('listings.manage',[
            'listings'=>auth()->user()->listings()->get()
        ]);
    }
}
