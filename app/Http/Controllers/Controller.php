<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\View\View;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function index(): View
    {
        return view('welcome');
    }

    public function get_github_user(Request $request) : Response
    {
        $strResponse = '';
        //basic validation
        $request->validate([
            'name' => 'required|string|max:20'
        ]);

        $response = Http::get("https://api.github.com/users/{$request->get('name')}");
        if(!empty($response->json('id'))){

            $strResponse = "
            <h4>{$response->json('login')}</h4>
            <h5>Follower Count: {$response->json('followers')}</h5>
            ";
            if($response->json('followers') > 0){
                $response2 = Http::get("https://api.github.com/users/{$request->get('name')}/followers?per_page=100&page=1");
                    foreach ($response2->json() as $key => $value) {
                        $strResponse .= " <a target='_blank' href='//github.com/{$value['login']}' ><img width='50' height='50' src='{$value['avatar_url']}' /> </a>";
                    }
                    if($response->json('followers')  > 100){
                        $strResponse .= "<button id='btnMore' onclick='get_github_user_pag(2,{$response->json('followers')})'>LOAD MORE</button>";
                    }
            }

        }

        return Response($strResponse);

    }

    public function get_github_user_followers(Request $request) : Response 
    {
        $strResponse = '';

        $request->validate([
            'name' => 'required|string|max:20',
            'page' => 'required|numeric|min:1|max:1000',
            'followers' => 'required|numeric|min:1'
        ]);

        $intPage = $request->get('page') >= 1 && $request->get('page') <= 1000 ? $request->get('page') : 1;

        $response = Http::get("https://api.github.com/users/{$request->get('name')}/followers?per_page=100&page={$intPage}");
        foreach ($response->json() as $key => $value) {
            $strResponse .= " <a target='_blank' href='//github.com/{$value['login']}' ><img width='50' height='50' src='{$value['avatar_url']}' /> </a>";
        }
        if($request->get('followers') > ( $intPage * 100 ) ){
            $intPage2 = $intPage + 1;
            $strResponse .= "<button id='btnMore' onclick='get_github_user_pag({$intPage2},{$request->get('followers')})'>LOAD MORE</button>";
        }

        return Response($strResponse);
    }

}
