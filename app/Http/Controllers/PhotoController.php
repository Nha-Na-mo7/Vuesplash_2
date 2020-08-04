<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Http\Requests\StoreComment;
use App\Http\Requests\StorePhoto;
use App\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
    public function __construct()
    {
      // コンストラクタで認証してしまう
      $this->middleware('auth')->except(['index', 'download', 'show']);
    }
    
    /**
     * 写真投稿
     * @param StorePhoto $request
     * @return \Illuminate\Http\Response
     */
    public function create(StorePhoto $request)
    {
      // 投稿写真の拡張子を取得する
      $extension = $request->photo->extension();
      
      $photo = new Photo();
      
      // インスタンス生成時に割り振られたランダムなID値($photo->id)と、
      // 投稿された写真の元々の拡張子($extension)を組み合わせて、ファイル名にする
      $photo->filename =  $photo->id . '.' . $extension;
      
      
      // storage/app/publicの配下に写真を保存する
      // Storage::putFileAs('photos', $photo->filename, 'public);
      // S3にファイルを保存するときは、Storage::cloud()->putFileAsという感じになる
      $request->photo->storeAs('photos', $photo->filename, 'public');
      
      // DBエラー時にファイル削除を行うため、
      // トランザクションを利用する
      DB::beginTransaction();
      
      try{
        Auth::User()->photos()->save($photo);
        DB::commit();
      }catch (\Exception $exception) {
        DB::rollBack();
        // アップロードしたファイルを削除する
        // DBとの不整合を避けるための措置
        Storage::disk()->delete($photo->filename);
        throw $exception;
      }
      
      // リソースの新規作成なので
      // レスポンスコードは 201 (CREATED) を返却する
      return response($photo, 201);
    }
    
    /*
     * 写真一覧
     */
    public function index()
    {
      $photos = Photo::with(['owner'])
          ->orderBy(Photo::CREATED_AT, 'desc')->paginate();
  
      return $photos;
    }
    
    
    /*
     * 写真のダウンロード
     * @param Photo $photo
     * @return \Illuminate\Http\Response
     */
    public function download(Photo $photo)
    {
      // 写真の存在チェック
//      if (! Storage::exists($photo->filename)) {
//        abort(404);
//      }
      $filePath = 'public/photos/' . $photo->filename;
      $mimeType = Storage::mimeType($filePath);
      $disposition = 'attachment; filename="' . $photo->filename . '"';
      $headers = [
          'Content-Type' => $mimeType,
          'Content-Disposition' => $disposition,
      ];
      return Storage::download($filePath, $photo->filename, $headers);
    }
    
    
    /**
     * 写真詳細
     * @param string $id
     * @return Photo
     */
    public function show(string $id)
    {
      //引数で受け取ったパスパラメータ"$id"を元に写真データを受け取る
      $photo = Photo::where('id', $id)->with(['owner', 'comments.author'])->first();
      
      //写真データが見つからなかった場合、404を返す
      return $photo ?? abort(404);
    }
  
    /**
     * コメント投稿
     * @param Photo $photo
     * @param StoreComment $request
     * @return \Illuminate\Http\Response
     */
    public function addComment(Photo $photo, StoreComment $request)
    {
      $comment = new Comment();
      $comment->content = $request->get('content');
      $comment->user_id = Auth::user()->id;
      $photo->comments()->save($comment);
      
      // authorリレーションをロードするためにコメントを取得しなおす
      $new_comment = Comment::where('id', $comment->id)->with('author')->first();
      
      return response($new_comment, 201);
    }
    
    
    /**
     * いいね
     * @param string $id
     * @return array
     */
    public function like(string $id)
    {
      $photo = Photo::where('id', $id)->with('likes')->first();
      
      if(! $photo) {
        abort(404);
      }
      
      //1個しかいいねがつかないように、いいねを削除→新たに追加する
      $photo->likes()->detach(Auth::user()->id);
      $photo->likes()->attach(Auth::user()->id);
      
      return ["photo_id" => $id];
    }
    
    /**
     * いいねの解除
     * @param string $id
     * @return array
     */
    public function unlike(string $id)
    {
      $photo = Photo::where('id', $id)->with('likes')->first();
      
      if(! $photo) {
        abort(404);
      }
      
      //いいねの解除
      $photo->likes()->detach(Auth::user()->id);
      
      return ["photo_id" => $id];
    }
    
}
