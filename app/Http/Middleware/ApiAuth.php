<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\API\Login\Login;

class ApiAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();
        if(empty($token)){
            return response(["status"=>403,"msg"=>"Token not found!"],403);
        }else{
            $userInfo = Login::getUserInfo($token);
            if(empty($userInfo)){
                return response(["status"=>403,"msg"=>"Invalid Authorization"],403);
            }else{

                $userInfo = array("id"=>$userInfo->id,
                                  "name"=>$userInfo->name);
                app()->instance('userData', $userInfo);

                //if everything is ok
                return $next($request);
            }

            //end else
        }

    }

}
