<?php

namespace App\Repository;

use App\Http\Resources\StoryResource;
use App\Models\Story;
use App\Models\Views;
use http\Env\Request;

class StoriesRepository implements StoriesRepositoryInterface
{
    public function index() {
        $user = auth()->user();
        $story = Story::where('id', '!=', $user->id)->get();
        return StoryResource::collection($story);
    }

    public function store($request) {
        $user = auth()->user();
        $story = Story::create(array_merge(['user_id'=>$user->id],$request->all()));
        return StoryResource::make($story);
    }

    public function showMyStory() {
        $user = auth()->user();
        $stories = Story::where('user_id', $user->id)->get();

        $data = [];
        foreach ($stories as $story) {
            $viewsCount = $story->views()->count();
            $data[] = [
                'story' => StoryResource::make($story),
                'views_count' => $viewsCount
            ];
        }

        return response()->json($data);
    }




    public function seeStory($request) {
        $user = auth()->user();
        $story = Story::findOrFail($request->story_id);
        $view = Views::where('story_id', $story->id)
            ->where('user_id', $user->id)
            ->first();
        if (!$view) {
            $view = Views::create([
                'story_id' => $story->id,
                'user_id' => $user->id
            ]);
        }
        return StoryResource::make($story);
    }


    public function delete($story)
    {
        $story->delete();
        return response()->json([
            'Story Deleted SuccessFully',
            'Story' => $story
        ]);
    }
}
