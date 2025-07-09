<?php
use App\Models\News\MediaFile;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaController extends Controller
{
     /* Danh sách JSON */
    public function index()
    {
        return MediaFile::latest()->get(['id','filename','filepath'])->map(fn($m)=>[
            'id'=>$m->id,
            'url'=>Storage::url($m->filepath),
            'name'=>$m->filename,
        ]);
    }

    /* Upload đơn (drag‑drop CKEditor) */
    public function store(Request $request)
    {
        $request->validate(['file'=>'required|image|max:5120']);
        $file = $request->file('file');
        $path = $file->store('media','public');

        $media = MediaFile::create([
            'filename'=>$file->getClientOriginalName(),
            'filepath'=>'/storage/'.$path,
            'type'=>$file->getMimeType(),
            'size'=>$file->getSize(),
            'alt_text'=>Str::before($file->getClientOriginalName(),'.'),
            'title'=>$file->getClientOriginalName(),
            'used_in'=>'news',
        ]);
        return ['id'=>$media->id,'url'=>Storage::url($media->filepath),'name'=>$media->filename];
    }

    /* Upload nhiều ảnh (popup bulk) */
    public function upload(Request $request)
    {
        $results=[];
        foreach($request->file('files',[]) as $file){
            $path=$file->store('media','public');
            $media=MediaFile::create([
                'filename'=>$file->getClientOriginalName(),
                'filepath'=>'/storage/'.$path,
                'type'=>$file->getMimeType(),
                'size'=>$file->getSize(),
                'alt_text'=>Str::before($file->getClientOriginalName(),'.'),
                'title'=>$file->getClientOriginalName(),
                'used_in'=>'news',
            ]);
            $results[]=['id'=>$media->id,'filename'=>$media->filename,'filepath'=>$media->filepath];
        }
        return response()->json(['success'=>true,'files'=>$results]);
    }

    /* Cập nhật SEO meta */
    public function updateMeta(Request $request, MediaFile $media)
    {
        $media->update($request->only(['alt_text','title','caption','description']));
        return response()->json(['success'=>true]);
    }

    /* Xoá 1 ảnh */
    public function destroy(MediaFile $media)
    {
        $rel=Str::after($media->filepath,'/storage/');
        if(Storage::disk('public')->exists($rel)) Storage::disk('public')->delete($rel);
        $media->delete();
        return response()->json(['success'=>true]);
    }

    /* Xoá nhiều ảnh */
    public function bulkDestroy(Request $request)
    {
        $ids=$request->input('ids',[]);
        foreach(MediaFile::whereIn('id',$ids)->get() as $m){
            $rel=Str::after($m->filepath,'/storage/');
            if(Storage::disk('public')->exists($rel)) Storage::disk('public')->delete($rel);
            $m->delete();
        }
        return response()->json(['success'=>true]);
    }
}