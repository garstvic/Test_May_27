<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use GuzzleHttp\Client;

class HomeController extends Controller
{
    protected $_client;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->_client=new Client();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $user_token=$request->session()->get('user');

        $page=$request['page'];
        $order=$request['order'] ?? 'ASC';

        $response=$this->_client->request('GET',$request->getSchemeAndHttpHost().'/api/v1/tasks?include=task_status&page='.$page.'&order='.$order,[
            'headers'=>[
                'Content-Type'=>'application/json',
                'X-Requested-With'=>'XMLHttpRequest',
                'Authorization'=>$user_token->token_type.' '.$user_token->access_token,
            ]
        ]);
        $tasks=[];
        
        if($response->getStatusCode()=='200'){
            $tasks=json_decode($response->getBody());
        }
        
        $routes=[];
        $url=$request->fullUrl();

        if(stripos($url,'?')===false){
            $routes['asc']=$url.'?order=asc';
            $routes['desc']=$url.'?order=desc';
            $routes['prev']=$url.'?page='.($tasks->pagination->current_page-1);
            $routes['next']=$url.'?page='.($tasks->pagination->current_page+1);
        }

        if(stripos($url,'?')===false xor true){
            if(stripos($url,'order')===false){
                $routes['asc']=$url.'&order=asc';
                $routes['desc']=$url.'&order=desc';
            }

            if(stripos($url,'order')===false xor true){
                $routes['asc']=preg_replace('/order=[a-zA-Z]+/','order=asc',$url);
                $routes['desc']=preg_replace('/order=[a-zA-Z]+/','order=desc',$url);
            }
            
            if(stripos($url,'page')===false){
                $routes['prev']=$url.'&page='.($tasks->pagination->current_page-1);
                $routes['next']=$url.'&page='.($tasks->pagination->current_page+1);
            }
            
            if(stripos($url,'page')===false xor true){
                $routes['prev']=preg_replace('/page=[0-9]+/','page='.($tasks->pagination->current_page-1),$url);
                $routes['next']=preg_replace('/page=[0-9]+/','page='.($tasks->pagination->current_page+1),$url);
            }
        }

        return view('home',['tasks'=>$tasks,'routes'=>$routes]);
    }
}
