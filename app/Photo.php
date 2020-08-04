<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class Photo extends Model
{
    /** プライマリキーの型 */
    protected $keyType = 'string';
    
    /** JSONに含める属性 */
    protected $visible = [
        'id', 'owner', 'url', 'comments', 'likes_count', 'liked_by_user',
    ];
    
    /** JSONに含める属性 */
    protected $appends = [
        'url', 'likes_count', 'liked_by_user',
    ];
    
    /** 1ページあたりのアイテム数 */
    protected $perPage = 15;
    
    /** IDの桁数 */
    const ID_LENGTH = 12;
    
    /** photo作成時に忘れないように、コンストラクタで自動的にsetIdを呼び出すようにしている。 */
    public function __construct(array $attributes = [])
    {
      parent::__construct($attributes);
      
      if (! Arr::get($this->attributes, 'id')) {
        $this->setId();
      }
    }
  
    /**
     * ランダムなID値をid属性に代入する
     */
    private function setId()
    {
      $this->attributes['id'] = $this->getRandomId();
    }
    
    /**
     * ランダムなID値を生成する
     * @return string
     */
    private function getRandomId()
    {
      $characters = array_merge(
          range(0, 9), range('a', 'z'),
          range('A', 'Z'), ['-', '_']
      );
      
      $length = count($characters);
      
      $id = "";
      
      for ($i = 0; $i < self::ID_LENGTH; $i++) {
        $id .= $characters[random_int(0, $length - 1)];
      }
      
      return $id;
    }
    
    /**
     * リレーションシップ - usersテーブル
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     *
     * リレーションのメソッド名は任意の値を定義できます。
     * User モデルとのリレーションだからといって user というメソッド名でなくてはいけないというルールはありません。
     * 意味を考えて分かりやすい名前にしましょう。
     * ただし今回のようにリレーション先のモデル名と関係のない名前を付ける場合は
     * belongsTo などのメソッドの引数は省略せずに記述する必要があります。
     * そしてモデルクラスがコントローラーからレスポンスされて JSON に変換されるとき、
     * このリレーション名 "owner" が反映されます。
     */
    public function owner()
    {
      return $this->belongsTo('App\User', 'user_id', 'id', 'users');
    }
  
    /**
     * リレーションシップ - commentsテーブル
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
      return $this->hasMany('App\Comment')->orderBy('id', 'desc');
    }
    
    /**
     * リレーションシップ - usersテーブル
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function likes()
    {
      return $this->belongsToMany('App\User', 'likes')->withTimestamps();
    }
    
    /**
     * アクセサ - url
     * @return string
     */
    public function getUrlAttribute()
    {
      return Storage::url('photos/' . $this->attributes['filename']);
    }
    
    /**
     * アクセサ - likes_count
     * @return int
     */
    public function getLikesCountAttribute()
    {
      return $this->likes->count();
    }
    
    /**
     * アクセサ - liked_by_user
     * @return boolean
     */
    public function getLikedByUserAttribute()
    {
      //未認証ならfalseが返ってくる。
      if (Auth::guest()) {
        return false;
      }
      
      // containsでログインユーザーのIDと合致するいいねが含まれるかをチェック
      return $this->likes->contains(function ($user) {
        return $user->id === Auth::user()->id;
      });
    }
}
