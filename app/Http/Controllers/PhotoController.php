<?php

namespace App\Http\Controllers;

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
      $this->middleware('auth');
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
    
    
}
