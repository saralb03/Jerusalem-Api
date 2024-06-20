<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexEmployeeRequest;
use App\Services\EmployeeService;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;
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
 *     required={"id", "personal_id", "personal_number", "ranks", "surname", "first_name", "department", "division", "service_type", "date_of_birth", "service_type_code", "security_class_start_date", "service_start_date", "solider_type", "age", "classification", "classification_name", "phone_number", "deleted_at"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="personal_id", type="string", example="209959501"),
 *     @OA\Property(property="personal_number", type="string", example="5252568"),
 *     @OA\Property(property="prefix", type="string", example="S"),
 *     @OA\Property(property="ranks", type="string", example="Ravet"),
 *     @OA\Property(property="surname", type="string", example="Gorinstein"),
 *     @OA\Property(property="first_name", type="string", example="Sarah Leah"),
 *     @OA\Property(property="department", type="string", example=""),
 *     @OA\Property(property="division", type="string", example="Chetz"),
 *     @OA\Property(property="service_type", type="string", example="חובה"),
 *     @OA\Property(property="date_of_birth", type="string", format="date", example="2003-06-03"),
 *     @OA\Property(property="service_type_code", type="integer", example=1),
 *     @OA\Property(property="security_class_start_date", type="string", format="date", example="2006-05-04"),
 *     @OA\Property(property="service_start_date", type="string", format="date", example="2008-07-04"),
 *     @OA\Property(property="solider_type", type="string", example="חייל"),
 *     @OA\Property(property="age", type="integer", example=21),
 *     @OA\Property(property="classification", type="integer", example=4),
 *     @OA\Property(property="classification_name", type="string", example="סודי"),
 *     @OA\Property(property="population_id", type="integer", example=1),
 *     @OA\Property(property="phone_number", type="string", example="055-9254116"),
 *     @OA\Property(property="deleted_at", type="string", nullable=true, example=null),
 * )
 */
class EmployeeController extends Controller
{
    protected $employeeService;

    public function __construct(EmployeeService $employeeService)
    {
        $this->employeeService = $employeeService;
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
     *         example="id,personal_id,personal_number,ranks,surname,first_name,department,division,service_type,date_of_birth,service_type_code,security_class_start_date,service_start_date,solider_type,age,classification,classification_name,phone_number,deleted_at",
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
    public function index(IndexEmployeeRequest $request)
    {
        $requestedColumns = $request->query('columns') ? explode(',', $request->query('columns')) : [];
        return $this->employeeService->index($requestedColumns);
    }

    public function update()
    {
        return $this->employeeService->update();
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt|max:2048',
        ]);

        $file = $request->file('file');
        
        $symfonyFile = new File($file->getPathname());

        return $this->employeeService->import($symfonyFile);
    }
}
