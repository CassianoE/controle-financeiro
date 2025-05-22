<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SwaggerController extends Controller
{
    /**
     * @OA\Info(
     *     version="1.0.0",
     *     title="API Controle Financeiro",
     *     description="Documentação da API de Controle Financeiro",
     *     @OA\Contact(
     *         email="kssycs@outlook.com"
     *     ),
     *     @OA\License(
     *         name="MIT",
     *         url="https://opensource.org/licenses/MIT"
     *     )
     * )
     *
     * @OA\Server(
     *     url=L5_SWAGGER_CONST_HOST,
     *     description="API Server"
     * )
     *
     * @OA\SecurityScheme(
     *     securityScheme="sanctum",
     *     type="http",
     *     scheme="bearer",
     *     bearerFormat="JWT"
     * )
     */
    public function docs()
    {
        return redirect('/api/documentation');
    }
}
