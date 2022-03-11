<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;

class IssuesController extends Controller
{
    public function index(){
        return view('issue.index');
    }

    public function get_issues(Request $request){
        $data = DB::table('bugs')->select('id', 'title', 'content', 'status', 'created_at as date')->orderBy('id', 'desc')->paginate(10);
        return ryuReply('SUCCESS', $data, 200);
    }

    public function admin_index()
    {
        return view('admin.issues');
    }

    public function add(Request $request)
    {
        $title = $request->title;
        $description = $request->description;
        $author = $request->author;
        $status=1;

        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255']
        ]);
        if(!$validator->fails()){
        DB::table('bugs')->insert([
            [
                'title' => $title,
                'content' => $description,
                'author' => $author,
                'status' => $status
            ]
        ]);

        return redirect('/issues');

        }else{

            return redirect('/issues')->with('msg' , 'error');
        }
    }

    public function detailIssue(Request $request){
        $issue = DB::table('bugs')->where('id', $request->id)->first();
        if($issue){
            $data = array(
                'title' => $issue->title,
                'content' => $issue->content,
                'status' => $issue->status,
                'author' => $issue->author,
                'date' => $issue->created_at,
            );
            return view('issue.detail', compact('data'));
        } else {
            return redirect()->back();
        }
    }

    public function update_issue(Request $request){
        DB::table('bugs')->where('id', $request->id)->update([
            'status' => $request->status
        ]);

        return ryuReply('SUCCESS', '', 200);
    }

    public function delete_issue(Request $request){
        DB::table('bugs')->where('id', $request->id)->delete();

        return redirect('/admin/issues');
    }
    
}
