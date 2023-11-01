<?php

namespace App\Http\Controllers\chat;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStoriesRequest;
use App\Http\Resources\StoryResource;
use App\Models\Story;
use App\Repository\StoriesRepositoryInterface;
use Illuminate\Http\Request;

class StoriesController extends Controller
{
    protected $stories;

    public function __construct(StoriesRepositoryInterface $stories) {
        $this->stories = $stories;
    }
    public function index() {
        return $this->stories->index();
    }

    public function showMyStory() {
        return $this->stories->showMyStory();
    }

    public function seeStory(Request $request) {
        return $this->stories->seeStory($request);
    }

    public function store(StoreStoriesRequest $request) {
        return $this->stories->store($request);
    }

    public function delete(Story $story) {
        return $this->stories->delete($story);
    }
}
