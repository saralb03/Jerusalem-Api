<?php

namespace App\Http\Controllers;

use App\Enums\Status;
use App\Http\Requests\IndexEmployeeRequest;
use App\Services\EmployeeService;
use Illuminate\Http\File;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;

/**
 *  * @OA\Info(
 *     title="Employee API",
 *     version="1.0.0",
 *     description="API documentation for managing employees",
 *     @OA\Contact(
 *         email="contact@example.com",
 *         name="API Support"
 *     )
 * )
 * @OA\Schema(
 *     schema="Employee",
 *     required={"personal_id", "personal_number", "first_name", "surname", "rank", "division", "date_of_birth", "age", "phone_number"},
 *     @OA\Property(property="personal_id", type="string", example="00305948276"),
 *     @OA\Property(property="personal_number", type="string", example="3259689"),
 *     @OA\Property(property="first_name", type="string", example="אדם"),
 *     @OA\Property(property="surname", type="string", example="הראשון"),
 *     @OA\Property(property="user_name", type="string", example="army\s3259687"),
 *     @OA\Property(property="population", type="string", example="קבע"),
 *     @OA\Property(property="prefix", type="string", example="s"),
 *     @OA\Property(property="rank", type="string", example="סגן"),
 *     @OA\Property(property="department", type="string", example=null),
 *     @OA\Property(property="branch", type="string", example=null),
 *     @OA\Property(property="section", type="string", example=null),
 *     @OA\Property(property="division", type="string", example="בסיס 128 לשכות"),
 *     @OA\Property(property="date_of_birth", type="string", format="date", example="2005-04-10"),
 *     @OA\Property(property="security_class_start_date", type="string", format="date", example="2012-09-04"),
 *     @OA\Property(property="age", type="integer", example=19),
 *     @OA\Property(property="classification", type="integer", example=3),
 *     @OA\Property(property="classification_name", type="string", example="סודי ביותר"),
 *     @OA\Property(property="phone_number", type="string", example="0559254116"),
 *     @OA\Property(property="profession", type="string", example="פסנתרן"),
 *     @OA\Property(property="gender", type="string", example="זכר"),
 *     @OA\Property(property="religion", type="string", example="יהודי"),
 *     @OA\Property(property="country_of_birth", type="string", example="ישראל"),
 *     @OA\Property(property="release_date", type="string", format="date", example="2013-09-04"),
 *     @OA\Property(property="employee_id", type="integer", example=1)
 * )
 */
class EmployeeController extends Controller
{
    // protected $employeeService;

    // public function __construct(EmployeeService $employeeService)
    // {
    //     $this->employeeService = $employeeService;
    // }

    public function __construct(public readonly EmployeeService $employeeService)
    {
    }

    /**
     * @OA\Get(
     *     path="/api/employees",
     *     summary="Get employees with optional filtering of columns",
     *     tags={"Employees"},
     *     @OA\Parameter(
     *         name="columns",
     *         in="query",
     *         description="Comma-separated list of columns to retrieve",
     *         required=false,
     *         example="personal_id,personal_number,rank,surname,first_name,department,division,date_of_birth,age,phone_number",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="List of employees",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Employee")
     *         )
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response="403",
     *         description="Forbidden"
     *     )
     * )
     */

    public function index(IndexEmployeeRequest $request): JsonResponse
    {
        $requestedColumns = $request->query('columns') ? explode(',', $request->query('columns')) : [];
        $employees =  $this->employeeService->index($requestedColumns);
        return response()->json($employees, Response::HTTP_OK);
    }

    /**
     * @OA\Post(
     *     path="/api/employees/import",
     *     summary="Import employees from a CSV file",
     *     tags={"Employees"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="file",
     *                     type="string",
     *                     format="binary",
     *                     description="CSV file to be uploaded"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="CSV file imported successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="CSV file imported successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="No file uploaded",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="No file uploaded.")
     *         )
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Error importing CSV file",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Error importing CSV file: [error message]")
     *         )
     *     )
     * )
     */
    public function import(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt|max:2048',
        ]);

        $file = $request->file('file');
        $extractedFile = new File($file->getPathname());

        $result = $this->employeeService->update($extractedFile, false);

        return match ($result) {
            Status::NOT_FOUND => response()->json(['error' => 'No file uploaded.'], Response::HTTP_NOT_FOUND),
            Status::OK => response()->json(['message' => 'CSV file imported successfully'], Response::HTTP_OK),
            default => response()->json(['error' => 'Error importing CSV file: ' . $result], Response::HTTP_INTERNAL_SERVER_ERROR),
        };
    }


    public function update(): JsonResponse
    {
        $result = $this->employeeService->update(env('DAILY_FILE_ADDRESS'), true);
        return match ($result) {
            Status::NOT_FOUND => response()->json(['error' => 'File not found.'], Response::HTTP_NOT_FOUND),
            Status::OK => response()->json(['message' => 'CSV file imported successfully'], Response::HTTP_OK),
            default => response()->json(['error' => 'Error importing CSV file: ' . $result], Response::HTTP_INTERNAL_SERVER_ERROR),
        };
    }
}
