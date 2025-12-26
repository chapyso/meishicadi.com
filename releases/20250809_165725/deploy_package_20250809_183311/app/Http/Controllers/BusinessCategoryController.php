<?php

namespace App\Http\Controllers;

use App\Models\BusinessCategory;
use Illuminate\Http\Request;
use Auth;

class BusinessCategoryController extends Controller
{
    public function index()
    {
        $categories = BusinessCategory::where('created_by', Auth::user()->creatorId())->get();
        return view('business_category.index', compact('categories'));
    }

    public function create()
    {
        return view('business_category.create');
    }

    public function store(Request $request)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'name' => 'required|max:120',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }

        $category = new BusinessCategory();
        $category->name = $request->name;
        $category->created_by = Auth::user()->creatorId();
        $category->save();

        return redirect()->route('business_category.index')->with('success', __('Business Category successfully created.'));
    }

    public function edit($id)
    {
        $category = BusinessCategory::find($id);
        return view('business_category.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'name' => 'required|max:120',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }

        $category = BusinessCategory::find($id);
        $category->name = $request->name;
        $category->save();

        return redirect()->route('business_category.index')->with('success', __('Business Category successfully updated.'));
    }

    public function destroy($id)
    {
        $category = BusinessCategory::find($id);
        $category->delete();

        return redirect()->route('business_category.index')->with('success', __('Business Category successfully deleted.'));
    }
}
