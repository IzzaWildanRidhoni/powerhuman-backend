<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateResponsibilityRequest;
use App\Models\Responsibility;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResponsibilityController extends Controller
{
    public function fetch(Request $request)
    {
        $id = $request->input('id');
        $name = $request->input('name');
        $limit = $request->input('limit', 10);

        $responsibilityQuery = Responsibility::query();

        // get single data
        if ($id) {
            $responsibility = $responsibilityQuery->find($id);

            if ($responsibility) {
                return ResponseFormatter::success($responsibility, 'Responsibility found');
            }
            return ResponseFormatter::error('Responsibility Not Found', 404);
        }

        // get multiple data
        $responsibilities = $responsibilityQuery->where('role_id', $request->role_id);

        if ($name) {
            $responsibilities->where('name', 'like', '%' . $name . '%');
        }

        // Responsibility::with(['users'])->where('name','like','%Kunde%')->paginate(10);
        return ResponseFormatter::success(
            $responsibilities->paginate($limit),
            'Responsibilities found'
        );
    }

    public function create(CreateResponsibilityRequest $request)
    {
        try {
            // create responsibility
            $responsibility = Responsibility::create([
                'name' => $request->name,
                'role_id' => $request->role_id
            ]);

            if (!$responsibility) {
                throw new Exception('Responsibility Not Created');
            };

            return ResponseFormatter::success($responsibility, 'Responsibility Created');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            // get responsibility
            $responsibility = Responsibility::find($id);

            // check if responsibility exists
            if (!$responsibility) {
                throw new Exception('Responsibility not found');
            }

            // delete responsibility
            $responsibility->delete();

            return ResponseFormatter::success('Responsibility Deleted');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }
}
