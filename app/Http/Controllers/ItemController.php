<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class ItemController extends Controller
{
    public function index()
    {
        return view('items.index'); // Returns the DataTable view
    }

    public function getData(Request $request)
    {
        $columns = ['id', 'name', 'description'];
        $totalData = Item::count();
        $totalFiltered = $totalData;

        if ($request->has('search') && !empty($request->input('search.value'))) {
            $search = $request->input('search.value');
            $query = Item::where('name', 'LIKE', "%{$search}%")
                ->orWhere('description', 'LIKE', "%{$search}%");

            $totalFiltered = $query->count();
            $items = $query->offset($request->input('start'))
                ->limit($request->input('length'))
                ->get();
        } else {
            $items = Item::offset($request->input('start'))
                ->limit($request->input('length'))
                ->get();
        }

        $data = [];
        foreach ($items as $item) {
            $nestedData = [];
            $nestedData['id'] = $item->id;
            $nestedData['name'] = $item->name;
            $nestedData['description'] = $item->description;
            $nestedData['action'] = '<a href="' . route('items.edit', $item->id) . '" class="btn btn-sm btn-primary">Edit</a>
                                        <form action="' . route('items.destroy', $item->id) . '" method="POST" style="display:inline-block;">
                                        ' . csrf_field() . '
                                        ' . method_field('DELETE') . '
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                        </form>';
            $data[] = $nestedData;
        }

        $json_data = [
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        ];

        return response()->json($json_data);
    }

    public function create()
    {
        return view('items.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Item::create($request->only('name', 'description'));

        return redirect()->route('items.index')->with('success', 'Item created successfully.');
    }

    public function edit($id)
    {
        $item = Item::findOrFail($id);
        return view('items.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $item = Item::findOrFail($id);
        $item->update($request->only('name', 'description'));

        return redirect()->route('items.index')->with('success', 'Item updated successfully.');
    }

    public function destroy($id)
    {
        $item = Item::findOrFail($id);
        $item->delete();

        return redirect()->route('items.index')->with('success', 'Item deleted successfully.');
    }
}
