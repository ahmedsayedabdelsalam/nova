<?php

namespace Code95\MediaLibrary\Http\Controllers;

use Code95\MediaLibrary\Http\Resources\MediaResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Code95\MediaLibrary\CustomMedia;
use Code95\MediaLibrary\DataMedia;
use Illuminate\Support\Facades\DB;

class MediaLibraryController extends Controller
{

    public function store(Request $request)
    {
        if ($media = DataMedia::find(1)) {
            $media
                ->addMedia($request->file)
                ->withCustomProperties(['user' => request()->user()])
                ->toMediaCollection();
        } else {
            $media = new DataMedia;
            $media->save();
            $media
                ->addMedia($request->file)
                ->withCustomProperties(['user' => request()->user()])
                ->toMediaCollection();
        }

        return response()->json([
            'media' => new MediaResource($media->getMedia()->last())
        ], 200);
    }

    public function delete(Request $request, CustomMedia $media)
    {
        $attachedmedia = DB::table('attachedmediables')->where('attached_media_id', $media->id);
        $attachedmedia->delete();
        $media->delete();
        return response()->json([
            'message' => 'no ya naaaas'
        ], 200);
    }

    public function update(Request $request, CustomMedia $media)
    {
        $data = [
            'user' => $media->custom_properties['user'] ?? null,
            'information' => $request->information ?? null,
        ];
        \Log::info($data);
        $media->custom_properties = $data;
        if ($data['information']['title']) {
            $media->name = $data['information']['title'];
            $media->save();
        }
        $media->update();


        return response()->json([
            'media' => new MediaResource($media)
        ], 200);
    }

    public function mediaData(Request $request)
    {
        $all_media = CustomMedia::filterName($request->name)
            ->filterType($request->type)
            ->filterDate($request->date)
            ->filterStatus($request->status)
            ->filterUploadedBy($request->uploaded_by)
            // ->with('users', 'posts')
            ->paginate(40);


        return mediaResource::collection($all_media);
    }
}
