<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BillController extends Controller
{
    public function index(): JsonResponse
    {
        $bills =  Bill::orderBy('due_date')->paginate(10);

        return response()->json([
            'status' => true,
            'bills' => $bills
        ], 200);
    }

    public function show(Bill $bill): JsonResponse
    {
        return response()->json([
            'status' => true,
            'bill' => $bill
        ], 200);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $bill = Bill::create([
                'name' => $request->name,
                'bill_value' => $request->bill_value,
                'due_date' => $request->due_date,
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'bill' => $bill,
                'message' => 'Conta Cadastrada com sucesso',
            ], 201);
        } catch (Exception $err) {
            DB::rollBack();
            return response()->json([
                'status' => true,
                'message' => 'Erro ao cadastrar conta'
            ], 400);
        }
    }
}
