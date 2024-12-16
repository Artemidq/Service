<?php

namespace App\Http\Controllers;

use App\Models\Appeal;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppealController extends Controller
{
    public function index()
    {
        $appeals = Appeal::all();
        $appeals->map(function ($item) {
            $item->makeHidden(["status_id", "type_id", "user_id"]);
            return [...$item->toArray(),
                "status" => $item->status,
                "type" => $item->type,
                "user" => $item->user,
            ];
        });

        return $appeals;
    }

    public function personal() {
        $appeals =  Appeal::where('user_id', \Auth::user()->id)->get();
        $appeals->map(function ($item) {
            $item->makeHidden(["status_id", "type_id", "user_id"]);
            return [...$item->toArray(),
                "status" => $item->status,
                "type" => $item->type,
                "user" => $item->user,
            ];
        });

        return $appeals;
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'message' => ['required'],
            'type_id' => ['required'],
        ]);

        return Appeal::create([...$data, 'user_id' => Auth::user()->id]);
    }

    public function show(Appeal $appeal)
    {
        return $appeal;
    }

    public function update(Request $request, Appeal $appeal)
    {
        $data = $request->validate([
            'message' => ['exclude'],
            'type_id' => ['exclude'],
            'user_id' => ['exclude'],
            'status_id' => ['required'],
        ]);

        $appeal->update($request->all());

        return $appeal;
    }

    public function destroy(Appeal $appeal)
    {
        $appeal->delete();

        return response()->json();
    }

    public function search(Request $request)
    {
        $appeals = Appeal::where('id', "LIKE","%{$request->input("search")}%")
            ->orWhere('message', "LIKE","%{$request->input("search")}%")
            ->orWhereHas('type', function(Builder $query) use($request) {
                $query->where('name', "LIKE", "%{$request->input("search")}%");
            } )
            ->orWhereHas('category', function (Builder $query) use ($request) {
                $query->where('category_services.name', 'LIKE', "%{$request->input("search")}%");
            });

        if ($request->input('type') === 'all') {
            if (Auth::user()->role->code === 'client') {
                return response()->json('forbidden', 403);
            }
            return $appeals->get()->map(function ($item) {
                $item->makeHidden(["status_id", "type_id", "user_id"]);
                return [...$item->toArray(),
                    "status" => $item->status,
                    "type" => $item->type,
                    "user" => $item->user,
                ];
            });
        }
        return $appeals->where('user_id', Auth::id())->get()->map(function ($item) {
            $item->makeHidden(["status_id", "type_id", "user_id"]);
            return [...$item->toArray(),
                "status" => $item->status,
                "type" => $item->type,
                "user" => $item->user,
            ];
        });
    }
}
