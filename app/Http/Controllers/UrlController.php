<?php

namespace App\Http\Controllers;

use App\Models\Url;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
/**
 * @OA\Info(
 *    title="API de Acortador de URLs",
 *    version="1.0.0",
 *    description="Esta es la documentación de la API de acortamiento de URLs"
 * )
 * @OA\Components(
 *     @OA\Schema(
 *         schema="Url",
 *         type="object",
 *         title="Modelo de URL",
 *         properties={
 *             @OA\Property(property="id", type="integer", example=1),
 *             @OA\Property(property="code", type="string", example="8fXye7tD"),
 *             @OA\Property(property="original_url", type="string", example="https://www.ejemplo.com"),
 *             @OA\Property(property="created_at", type="string", format="date-time", example="2023-09-15T00:00:00Z"),
 *             @OA\Property(property="updated_at", type="string", format="date-time", example="2023-09-15T00:00:00Z")
 *         }
 *     )
 * )
 * @OA\PathItem(
 *    path="/api/urls"
 * )
 */
class UrlController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/urls",
     *     summary="Crea una nueva URL acortada",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"originalUrl"},
     *             @OA\Property(property="originalUrl", type="string", example="https://www.ejemplo.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="URL acortada creada con éxito"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error de validación"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'originalUrl' => 'required|url',
        ]);

        // Generar un código único de 8 caracteres
        do {
            $code = Str::random(8);
        } while (Url::where('code', $code)->exists());

        // Crear y almacenar la URL en la base de datos
        $url = Url::create([
            'code' => $code,
            'original_url' => $request->originalUrl,
        ]);

        return response()->json([
            'shortUrl' => url("/r/{$url->code}")
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/urls",
     *     summary="Obtiene todas las URLs acortadas",
     *     @OA\Response(
     *         response=200,
     *         description="Lista de URLs acortadas",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Url")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor"
     *     )
     * )
     */
    public function index()
    {
        $urls = Url::all();
        return response()->json($urls);
    }

    /**
     * @OA\Get(
     *     path="/api/urls/{code}",
     *     summary="Obtiene una URL acortada por su código",
     *     @OA\Parameter(
     *         name="code",
     *         in="path",
     *         description="Código de la URL acortada",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="La URL acortada",
     *         @OA\JsonContent(ref="#/components/schemas/Url")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="URL no encontrada"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor"
     *     )
     * )
     */
    public function show($code)
    {
        $url = Url::where('code', $code)->firstOrFail();
        return response()->json($url);
    }
    /**
     * @OA\Get(
     *     path="/api/urls/all",
     *     summary="Obtiene todas las URLs acortadas con paginación",
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Número de página para la paginación",
     *         required=false,
     *         @OA\Schema(type="integer", default=1)
     *     ),
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="Cantidad de URLs por página",
     *         required=false,
     *         @OA\Schema(type="integer", default=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de URLs acortadas con paginación",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="current_page",
     *                 type="integer",
     *                 description="Página actual"
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Url")
     *             ),
     *             @OA\Property(
     *                 property="total",
     *                 type="integer",
     *                 description="Número total de registros"
     *             ),
     *             @OA\Property(
     *                 property="per_page",
     *                 type="integer",
     *                 description="Cantidad de registros por página"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor"
     *     )
     * )
     */
    public function listAll(Request $request)
    {
        $limit = $request->input('limit', 10); // Número de elementos por página
        $urls = Url::paginate($limit); // Paginación de resultados
        return response()->json($urls);
    }

    /**
     * @OA\Get(
     *     path="/r/{code}",
     *     summary="Redirige a la URL original usando el código acortado",
     *     @OA\Parameter(
     *         name="code",
     *         in="path",
     *         description="Código de la URL acortada",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=301,
     *         description="Redirección permanente a la URL original"
     *     ),
     *     @OA\Response(
     *         response=302,
     *         description="Redirección temporal a la URL original"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="URL no encontrada"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor"
     *     )
     * )
     */
    public function redirect($code)
    {
        $url = Url::where('code', $code)->firstOrFail();
        return response()->json(['original_url' => $url->original_url])
                     ->header('Access-Control-Allow-Origin', '*');
    }

    /**
     * @OA\Delete(
     *     path="/api/urls/{id}",
     *     summary="Elimina una URL acortada",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la URL acortada",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="URL eliminada con éxito"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="URL no encontrada"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor"
     *     )
     * )
     */
    public function destroy($id)
    {
        $url = Url::findOrFail($id);
        $url->delete();

        return response()->json(['message' => 'URL deleted successfully']);
    }
}
