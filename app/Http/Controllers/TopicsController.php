<?php

namespace App\Http\Controllers;

use App\Handlers\ImageUploadHander;
use App\Models\Category;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\TopicRequest;
use Auth;
use App\Models\Link;

class TopicsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

	public function index(Request $request, Topic $topic, User $user, Link $link)
	{
		$topics = $topic->withOrder($request->order)->paginate(30);
        $active_users = $user->getActiveUsers();
        $links = $link->getAllCached();

		return view('topics.index', compact('topics','active_users','links'));
	}

    public function show(Request $request,Topic $topic)
    {
        if(!empty($topic->slug) && $topic->slug != $request->slug){
            return redirect($topic->link());
        }
        return view('topics.show', compact('topic'));
    }

	public function create(Topic $topic)
	{   $categories = Category::all();
		return view('topics.create_and_edit', compact('topic','categories'));
	}

	public function store(TopicRequest $request,Topic $topic)
	{

		$topic->fill($request->all());
		$topic->user_id = Auth::id();
		$topic->save();
		return redirect()->to($topic->link())->with('message', '成功创建话题.');
	}

	public function edit(Topic $topic)
	{   $categories = Category::all();
        $this->authorize('update', $topic);
		return view('topics.create_and_edit', compact('topic','categories'));
	}

	public function update(TopicRequest $request, Topic $topic)
	{
		$this->authorize('update', $topic);
		$topic->update($request->all());

		return redirect()->to($topic->link())->with('message', 'Updated successfully.');
	}

	public function destroy(Topic $topic)
	{
		$this->authorize('destroy', $topic);
		$topic->delete();

		return redirect()->route('topics.index')->with('message', 'Deleted successfully.');
	}

    /**
     * @param Request $request
     * @param ImageUploadHander $uploader
     * @return array json {
                "success": true/false,
                "msg": "error message", # optional
                "file_path": "[real file path]"
                }
     */
	public function uploadImage(Request $request,ImageUploadHander $uploader){

	    // 初始化返回数据，默认是失败的
        $data = [
            'success'   => false,
            'msg'       => '上传失败!',
            'file_path' => ''
        ];

        if($request->upload_file){

            // 上传图片到服务器
            $result = $uploader->save($request->upload_file,'topics',Auth::id(),1024);

            //上传成功
            if($result){
                $data = [
                    'success'   => true,
                    'msg'       => '上传成功!',
                    'file_path' => $result['path'],
                ];
            }
        }

        return $data;
    }
}