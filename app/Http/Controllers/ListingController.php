<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ListingController extends Controller
{
    public function index(){
        return view('listings.index',[
            'listings'=>Listing::latest()->filter(request(['tag', 'search']))->paginate(6),
        ]);
    }
    public function show(Listing $listing){
        return view('listings.show',[
            'listing'=> $listing,
        ]);
    }

    public function create(){
        return view('listings.create');
    }

    public function store(Request $request){
        $formData=$request->validate([
            'title'=>'required',
            'company'=>['required',Rule::unique('listings','company',)],
            'location'=>'required',
            'website'=>'required',
            'email'=>['required','email',Rule::unique('listings','email')],
            'tags'=>'required',
            'description'=>'required'
        ]);
        Listing::create($formData);
        return redirect('/')->with('message','listing created successfully..');
    }
}
