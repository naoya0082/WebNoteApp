<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Memo;
use App\Models\Tag;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // 全てのメソッドが呼ばれる前に実行されるメソッド
        view()->composer('*', function($view) {
            // get the current user
            $user = \Auth::user();
            // インスタンス化
            $memo_model = new Memo();
            $memos = $memo_model->myMemo(\Auth::id());
            // タグ取得
            //インスタンス化
            $tag_model = new Tag();
            $tags = $tag_model->where('user_id', \Auth::id())->get();

            $view->with('user', $user)->with('memos', $memos)->with('tags', $tags);
        });
    }
}
