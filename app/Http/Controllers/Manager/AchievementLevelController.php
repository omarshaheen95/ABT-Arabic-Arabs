<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\AchievementLevelRequest;
use App\Models\AchievementLevel;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class AchievementLevelController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:show achievement levels')->only('index');
        $this->middleware('permission:add achievement levels')->only(['create','store']);
        $this->middleware('permission:edit achievement levels')->only(['edit','update']);
        $this->middleware('permission:delete achievement levels')->only('destroy');
    }

    public function index(Request $request)
    {
        if (request()->ajax()) {
            $achievementLevels = AchievementLevel::query()->filter($request)->latest();
            return DataTables::make($achievementLevels)
                ->escapeColumns([])
                ->addColumn('name', function ($achievementLevel) {
                    return $achievementLevel->name;
                })
                ->addColumn('required_points', function ($achievementLevel) {
                    return number_format($achievementLevel->required_points);
                })
                ->addColumn('badge_icon', function ($achievementLevel) {
                    if ($achievementLevel->badge_icon) {
                        return '<img src="' . asset($achievementLevel->badge_icon) . '" alt="Badge" style="width: 40px; height: 40px;">';
                    }
                    return '-';
                })
                ->addColumn('description', function ($achievementLevel) {
                    return $achievementLevel->description ? \Str::limit($achievementLevel->description, 50) : '-';
                })
                ->addColumn('actions', function ($row) {
                    return $row->action_buttons;
                })
                ->make();
        }
        return view('manager.achievement_level.index');
    }

    public function create()
    {
        return view('manager.achievement_level.edit');
    }

    public function store(AchievementLevelRequest $request)
    {
        $data = $request->validated();

        // Handle badge icon upload
        if ($request->hasFile('badge_icon')) {
            $data['badge_icon'] = uploadFile($request->file('badge_icon'),'achievement_badges')['path'];
        }

        AchievementLevel::create($data);

        return redirect()->route('manager.achievement_levels.index')->with('message', t('Successfully Created'));
    }

    public function edit($id)
    {
        $achievementLevel = AchievementLevel::findOrFail($id);
        return view('manager.achievement_level.edit', compact('achievementLevel'));
    }

    public function update(AchievementLevelRequest $request, $id)
    {
        $achievementLevel = AchievementLevel::findOrFail($id);
        $data = $request->validated();

        // Handle badge icon upload
        if ($request->hasFile('badge_icon')) {
            $data['badge_icon'] = uploadFile($request->file('badge_icon'),'achievement_badges');
        }

        $achievementLevel->update($data);

        return redirect()->route('manager.achievement_levels.index')->with('message', t('Successfully Updated'));
    }

    public function destroy(Request $request)
    {
//        $request->validate(['row_id' => 'required|array']);
//
//        foreach ($request->get('row_id') as $achievementLevelId) {
//            // Check if there are users associated with this achievement level
//            $usersCount = UserAchievementLevel::where('achievement_level_id', $achievementLevelId)->count();
//
//            if ($usersCount > 0) {
//                $achievementLevel = AchievementLevel::find($achievementLevelId);
//                return $this->sendError(t('Cannot delete this achievement level, because there are users associated with this level') . ' (' . $achievementLevel->name . ')');
//            } else {
//                $achievementLevel = AchievementLevel::find($achievementLevelId);
//
//                // Delete badge icon file if exists
//                if ($achievementLevel && $achievementLevel->badge_icon && file_exists(public_path($achievementLevel->badge_icon))) {
//                    unlink(public_path($achievementLevel->badge_icon));
//                }
//
//                AchievementLevel::destroy($achievementLevelId);
//            }
//        }
//
//        return $this->sendResponse(null, t('Successfully Deleted'));
    }
}
