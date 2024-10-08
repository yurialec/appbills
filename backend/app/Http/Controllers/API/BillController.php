<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBillRequest;
use App\Http\Requests\UpdateBillRequest;
use App\Models\Bill;
use DB;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BillController extends Controller
{
    /**
     * Summary of index
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        $bills = Bill::orderByDesc('id')->paginate(10);

        return response()->json(data: [
            'status' => true,
            'bills' => $bills
        ], status: 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBillRequest $request)
    {
        DB::beginTransaction();

        try {
            $bill = Bill::create($request->all());
            DB::commit();
            return response()->json(data: [
                'status' => true,
                'bill' => $bill,
                'message' => 'Conta cadastrada com sucesso!',
            ], status: 200);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(data: [
                'status' => false,
                'message' => 'Erro ao cadastrar conta!',
            ], status: 201);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Bill $bill): JsonResponse
    {
        return response()->json(data: [
            'status' => true,
            'bill' => $bill
        ], status: 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBillRequest $request, Bill $bill): JsonResponse
    {
        DB::beginTransaction();

        try {
            $bill->update([
                'name' => $request->name,
                'bill_value' => $request->bill_value,
                'due_date' => $request->due_date,
            ]);

            DB::commit();

            return response()->json(data: [
                'status' => true,
                'bill' => $bill,
                'message' => 'Conta atualizada com sucesso!',
            ], status: 200);

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(data: [
                'status' => false,
                'message' => 'Erro ao alterar conta!',
            ], status: 201);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
