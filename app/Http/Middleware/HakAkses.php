<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HakAkses
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $route = explode("-", $request->route()->uri);
        $hakakses = \MyApp::hakakses("/" . $route[0]);
        $allow = true;
        if (!$hakakses['r'])
            $allow = false;

        if ($allow && isset($route[1])) {
            if ($route[1] == "update" && !$hakakses['u'])
                $allow = false;
            elseif ($route[1] == "delete" && !$hakakses['d'])
                $allow = false;
            elseif ($route[1] == "create" && (!$hakakses['c'] && !$hakakses['u']))
                $allow = false;
            elseif ($route[1] == "upload" && (!$hakakses['u']))
                $allow = false;
        }

        if (!$allow) {
            if ($request->ajax()) {
                $retval = array("status" => false, "insert" => true, "messages" => ["akses ditolak"]);
                return response()->json($retval);
            } else {
                return redirect()->route('login')->with('error', 'Opps, access denied');
            }
        }

        $request->merge(array("hakakses" => $hakakses));
        return $next($request);
    }
}
