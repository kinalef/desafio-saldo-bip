<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\JsonResponse;   

use Goutte\Client;



class TarjetaController extends Controller
{
    /**
    * @api {get} /tarjeta 2. Muestra el listado de todas las tarjetas
    * @apiGroup Tarjeta
    * @apiName Index
    * @apiDescription <b>Esta llamada no fue implementada</b> y solo se incluye para mostrar el trato a un recurso en una API REST
    * 
    */
    public function index()
    {
        $statusCode = 404;
        $response = ["code" => 404, "message" =>"No es posible hacer esta llamada. Esta API solo obtiene el saldo para una tarjeta en particular"];
        return (new JsonResponse( $response, $statusCode));
        
    }

    /**
     * @api {post} /tarjeta 3. Crea un nuevo recurso tarjeta
     * @apiGroup Tarjeta
     * @apiDescription <b>Esta llamada no fue implementada</b>  y solo se incluye para mostrar el trato a un recurso en una API REST
     */
    public function store(Request $request)
    {
        //
    }

    /**
    *
    * @api {get} /tarjeta/:id 1. Retorna la formación de una tarjeta Bip! según id 
    * @apiGroup Tarjeta
    * @apiName Show
    * @apiParam {Number} id número identificador de tarjeta bip.
    *
    * @apiSuccess {String} code Código de respuesta de la llamada
    * @apiSuccess {String} num-tarjeta  Número de la tarjeta.
    * @apiSuccess {String} estado  Detalle del estado del contrato de la tarjeta.
    * @apiSuccess {String} saldo  Saldo disponible en la tarjeta.
    * @apiSuccess {String} fecha-saldo  Feccha de la última carga.
    *
    * @apiSuccessExample Success-Response:
    *     HTTP/1.1 200 OK
    *     {
    *       "code": "John",
    *       "num-tarjeta": "15470725",
    *       "estado": "Contrato Activo",
    *       "saldo": "$2.530",
    *       "fecha-saldo": "10/09/2015 13:07",
    *     }
    * @apiError (Error 404) 404 Número de tarjeta ingresado sin saldo asociado.
    * @apiErrorExample {json} Error-Response:
    *     HTTP/1.1 404 Not Found
    *     {
    *       "code": 404,
    *       "num-tarjeta": "111",
    *       "message": "Esta tarjeta no tiene un saldo asociado"
    *     }
    * @apiError (Error 400)  400 Número de tarjeta ingresado con formato incorrecto.
    * @apiErrorExample {json} Error-Response:
    *     HTTP/1.1 404 Not Found
    *     {
    *       "code": 400,
    *       "num-tarjeta": "1547AAA725",
    *       "message": "El valor ingresado no es válido"
    *     }
    */
    public function show($id)
    {
        try{

            if(!is_numeric($id)){
                $statusCode = 404;
                $response = [ "code" => 404, "num-tarjeta" => $id, "message" => "El valor ingresado no es válido"];
                return (new JsonResponse( $response, $statusCode));
            }
            
            $client = new Client();
            $crawler = $client->request('POST', 'http://pocae.tstgo.cl/PortalCAE-WAR-MODULE/SesionPortalServlet?accion=6&NumDistribuidor=99&NomUsuario=usuInternet&NomHost=AFT&NomDominio=aft.cl&Trx=&RutUsuario=0&NumTarjeta='.$id.'&bloqueable=');

            $nodeValues = $crawler->filter('td.verdanabold-ckc')->each(function ($node, $i) {
                return $node->text();
            }); 

            if(count($nodeValues)>7){
                $statusCode = 200;
                $response = [   "code" => 200,
                                "num-tarjeta" => $id,
                                "estado" => $nodeValues[3],
                                "saldo" => $nodeValues[5],
                                "fecha-saldo" => $nodeValues[7]
                            ];

            }
            else{

                $statusCode = 404;
                $response = ["code" => 404, "num-tarjeta" => $id, "message"=> "Esta tarjeta no tiene un saldo asociado"];

            }
            
            return (new JsonResponse( $response, $statusCode));
     
        }
        catch (Exception $e){
            $statusCode = 500;
            $response = ["code" => 500, "num-tarjeta" => $id, "message"=> "Error del servidor"];
            return (new JsonResponse( null, $statusCode));
        }

    }

   
    /**
     * @apiGroup Tarjeta
     * @api {put} /tarjeta/:id 4. Actualiza información de una tarejeta
     * @apiName Update
     * @apiDescription <b>Esta llamada no fue implementada</b>  y solo se incluye para mostrar el trato a un recurso en una API REST
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * @apiGroup Tarjeta
     * @api {delete} /tarjeta/:id 5. Borra una tarjeta
     * @apiDescription <b>Esta llamada no fue implementada</b>  y solo se incluye para mostrar el trato a un recurso en una API REST
     * @apiName Delete
     */
    public function destroy($id)
    {
        //
    }
}
