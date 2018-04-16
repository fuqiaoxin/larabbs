<?php

namespace App\Observers;

use App\Models\Topic;
use App\Jobs\TranslateSlug;
// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class TopicObserver
{
    public function creating(Topic $topic)
    {
        //
    }

    public function updating(Topic $topic)
    {
        //
    }

    public function saving(Topic $topic)
    {
        // 根据过滤规则过滤body字段中非法字符 config/purifier.php user_topic_body
        $topic->body = clean($topic->body,'user_topic_body');
        $topic->excerpt = make_excerpt($topic->body);


    }

    public function saved(Topic $topic)
    {
        // 如 slug 字段无内容，即使用翻译器对title翻译
        if(!$topic->slug){
            //$topic->slug = app(SlugTranslateHandler::class)->translate($topic->title);

            // 推送任务队列
            dispatch(new TranslateSlug($topic));
        }
    }
}