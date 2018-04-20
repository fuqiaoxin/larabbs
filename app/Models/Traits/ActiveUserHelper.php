<?php
namespace App\Models\Traits;
use App\Models\Topic;
use App\Models\Reply;
use App\Models\User;
use Carbon\Carbon;
use Cache;
use DB;


trait ActiveUserHelper
{

    protected $cache_key            = 'active_users';   // 缓存KEY
    protected $cache_expire_minutes = 65;    //缓存时间 65分钟

    protected $users        = [];  // 存放用户数据
    protected $topic_weight = 4;    // 一篇帖子所占分数
    protected $reply_weight = 1;    // 一篇评论所占分数
    protected $pass_day     = 7;    // 7天内
    protected $user_number  = 6;    // 获取前N个用户

    // 尝试从缓存中取出 cache_key 对应的数据。如果能取到，便直接返回数据。
    // 否则运行匿名函数中的代码来取出活跃用户数据，返回的同时做了缓存。
    public function getActiveUsers(){
        return Cache::remember($this->cache_key,$this->cache_expire_minutes,function (){
            return $this->active_users();
        });
    }

    /**
     * 先从数据库拿数据，然后放在缓存
     */
    public function getActiveUsersAndputCache()
    {
        $active_user = $this->active_users();
        $this->putCache($active_user);
    }

    /**
     * 查询7天内发布话题和评论的用户，计算出对应用户的分数
     */
    private function user_score(){
        $topic_user = Topic::query()->select(DB::raw('user_id,count(*) as topic_count'))
                                    ->where('created_at','>=',Carbon::now()->subDays($this->pass_day))
                                    ->groupBy('user_id')
                                    ->get();
        if($topic_user){
            foreach ($topic_user as $value){
                $user_id = $value['user_id'];
                $this->users[$user_id]['score'] = $value['topic_count'] * $this->topic_weight;
            }
        }

        $reply_user = Reply::query()->select(DB::raw('user_id,count(*) as reply_count'))
                                    ->where('created_at','>=',Carbon::now()->subDays($this->pass_day))
                                    ->groupBy('user_id')
                                    ->get();

        if($reply_user){
            foreach ($reply_user as $value){
                $user_id = $value['user_id'];
                $_score = $value['reply_count'] * $this->reply_weight;
                if(isset($this->users[$user_id])){
                    $this->users[$user_id]['score'] += $_score;
                }else{
                    $this->users[$user_id]['score'] = $_score;
                }
            }
        }


    }

    private function active_users(){
        $this->user_score();
        if(!empty($this->users)){
            // 以分数高低排序
            $users = array_sort($this->users,function ($val){
                return $val['score'];
            });

            $users = array_reverse($users,true);

            // 只取需要的用户数量
            $users = array_slice($users,0,$this->user_number,true);

            // 新建一个空集合
            $active_users = collect();

            foreach ($users as $user_id => $user) {
                $user = User::find($user_id);
                if($user){
                    $active_users->push($user);
                }
            }

            return $active_users;

        }
        return false;

    }

    // 将数据放在缓存中
    private function putCache($activeUsers){
        Cache::put($this->cache_key,$activeUsers,$this->cache_expire_minutes);
    }


}