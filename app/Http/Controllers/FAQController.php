<?php

namespace App\Http\Controllers;

use App\Models\FAQ;
use Illuminate\Http\Request;

class FAQController extends Controller
{
    public function index()
    {
        return FAQ::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|string',
            'answer' => 'required|string',
            'keyword' => 'required|string'
        ]);

        FAQ::create($request->all());

        return response()->json(['message' => 'FAQ created successfully'], 201);
    }

    public function update(Request $request, $id)
    {
        $faq = FAQ::findOrFail($id);

        $request->validate([
            'question' => 'sometimes|string',
            'answer' => 'sometimes|string',
            'keyword' => 'sometimes|string'
        ]);

        $faq->update($request->all());

        return response()->json(['message' => 'FAQ updated successfully'], 200);
    }

    public function destroy($id)
    {
        FAQ::destroy($id);

        return response()->json(['message' => 'FAQ deleted successfully'], 200);
    }
}
