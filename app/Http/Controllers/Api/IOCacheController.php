<?php

namespace App\Http\Controllers\Api;

use App\Models\Iocache;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

/**
* @OA\Info(title="API CacheService", version="1.0")
*
* @OA\Server(url="http://viajesmexicoparadise/")
*
*/
class IOCacheController extends Controller
{
    public function index()
    {

    }

    /**
    * @OA\Post(
    *     path="/api/setcache",
    *     tags={"CacheService"},
    *     summary="Inserta un objeto en cache",
    *     operationId="setcachebykeyvalue",
    *     @OA\RequestBody(
    *       @OA\JsonContent(
    *           type="object",
    *           @OA\Property(property="keycache", type="string"),
    *           @OA\Property(property="valueText", type="string"),
    *           @OA\Property(property="keyClient", type="string"),
    *           @OA\Property(property="timer", type="integer", format="int")
    *       )
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="Inserta un objeto en cache."
    *     ),
    *     @OA\Response(
    *         response="default",
    *         description="Ha ocurrido un error."
    *     )
    * )
    */
    public function SetCache(Request $cache)
    {
        $cacheObj = new Iocache;
        try{
            $timenow = time();
            $cacheObj->keycache = $cache->keycache;
            $cacheObj->valueText = $cache->valueText;
            $cacheObj->keyClient = $cache->keyClient;
            $cacheObj->dateSet = date('Y-m-d h:i:s', time());
            $cacheObj->dateExpired = date('Y-m-d h:i:s', strtotime('+'.$cache->timer.' minutes', $timenow));
            $cacheObj->save();
        }
        catch(\Exception $ex)
        {
            $cacheObj = null;
        }
        if($cacheObj != null)
        {
            return response()->json([$cacheObj]);
        }
        else{
            return response('', 404);
        }
    }

    /**
    * @OA\Get(
    *     path="/api/getcache/{client}/{keyvalue}",
    *     tags={"CacheService"},
    *     summary="Obtener objeto almacenado en cache",
    *     operationId="getcachebykeyvalue",
    *     @OA\Parameter(
    *       name="client",
    *       in="path",
    *       description="Identificador del cliente",
    *       required=true,
    *       @OA\Schema(
    *           type="string"
    *       )
    *     ),
    *     @OA\Parameter(
    *       name="keyvalue",
    *       in="path",
    *       description="keyvalue llave unica para obtener el valor almacenado en el servicio de cache",
    *       required=true,
    *       @OA\Schema(
    *           type="string"
    *       )
    *     ),    
    *     @OA\Response(response=200, description="Obtener objeto almacenado en cache."),
    *     @OA\Response(response="default", description="Ha ocurrido un error.")
    * )
    */
    public function GetCache($client,$keyvalue)
    {           
        // $cache = Iocache::all();
        $dateNow = date('Y-m-d h:i:s', time());
        $cache = DB::table('iocaches')->where('keycache',$keyvalue)->where('keyClient', $client)->first();
        Iocache::where('dateExpired','<', $dateNow)->delete();
        //DB::delete('iocaches')->where('dateExpired','<', $dateNow); 
        return response()->json([$cache]);
    }
}
