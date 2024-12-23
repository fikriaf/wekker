<?php
namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function store(Request $request, $uuid)
    {
        $validated = $request->validate([
            'html' => 'nullable|string',
            'css' => 'nullable|string',
            'javascript' => 'nullable|string',
        ]);

        $project = Project::where('uuid', $uuid)->first();

        if ($project) {
            $project->update([
                'html' => $validated['html'] ?? '',
                'css' => $validated['css'] ?? '',
                'javascript' => $validated['javascript'] ?? '',
            ]);
        }

        return redirect()->route('projects.show', $project->uuid);
    }

    public function create(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'html' => 'nullable|string',
            'css' => 'nullable|string',
            'javascript' => 'nullable|string',
        ]);

        $project = Project::create([
            'name' => $validated['name'],
            'html' => $validated['html'] ?? '',
            'css' => $validated['css'] ?? '',
            'javascript' => $validated['javascript'] ?? '',
            'uuid' => Str::uuid(),
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('projects.show', $project->uuid);
    }

    public function destroy($uuid)
    {
        $project = Project::where('uuid', $uuid)->first();
        if ($project) {
            $project->delete();
            return response()->json(['message' => 'Project deleted successfully.'], 200);
        }
        return response()->json(['message' => 'Project not found.'], 404);
    }


    public function showMainBuilder($id_unik = null)
    {
        $user = Auth::user();
        if ($id_unik) {
            $project = Project::where('uuid', $id_unik)
                            ->where('user_id', Auth::id())->first();
            if (!$project) {
                return redirect('/dashboard/main-builder')->with('error', 'Proyek tidak ditemukan');
            }
            return view('wekker_dashboard.main_builder', compact('project'));
        }

        $project = Project::where('user_id', Auth::id())->latest()->first();
        if (!$project) {
            $project = Project::create([
                'name' => 'Proyek Baru',
                'html' => '',
                'css' => '',
                'javascript' => '',
                'uuid' => Str::uuid(),
                'user_id' => Auth::id(),
            ]);
        }

        return redirect()->route('projects.show', $project->uuid);
    }

    public function getProjectData($uuid)
    {
        $project = Project::where('uuid', $uuid)
                          ->where('user_id', Auth::id())
                          ->first();

        if (!$project) {
            return response()->json(['error' => 'Proyek tidak ditemukan'], 404);
        }

        return response()->json([
            'html' => $project->html,
            'css' => $project->css,
            'javascript' => $project->javascript,
        ]);
    }

    public function GetListProject()
    {
        $projects = Project::where('user_id', Auth::id())
                       ->select('uuid', 'name', 'updated_at')
                       ->orderBy('created_at', 'desc')
                       ->get();
        if (!$projects) {
            return response()->json(['error' => 'No projects found'], 404);
        }

        return response()->json($projects);
    }
}
