<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\RajaOngkirService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RajaOngkirController extends Controller
{
    protected $rajaOngkirService;

    public function __construct(RajaOngkirService $rajaOngkirService)
    {
        $this->rajaOngkirService = $rajaOngkirService;
    }

    public function getProvinces()
    {
        $response = $this->rajaOngkirService->getProvinces();
        if (!isset($response['rajaongkir'])) {
            Log::error('Raja Ongkir API Error: "rajaongkir" key not found in response.', ['response' => $response]);
            return response()->json(['error' => 'Failed to retrieve provinces from Raja Ongkir.'], 500);
        }
        return response()->json($response['rajaongkir']);
    }

    public function getCities(Request $request)
    {
        $provinceId = $request->query('province_id');
        $response = $this->rajaOngkirService->getCities($provinceId);
        if (!isset($response['rajaongkir'])) {
            Log::error('Raja Ongkir API Error: "rajaongkir" key not found in response.', ['response' => $response]);
            return response()->json(['error' => 'Failed to retrieve cities from Raja Ongkir.'], 500);
        }
        return response()->json($response['rajaongkir']);
    }

    public function getSubdistricts(Request $request)
    {
        $cityId = $request->query('city_id');
        $response = $this->rajaOngkirService->getSubdistricts($cityId);
        if (!isset($response['rajaongkir'])) {
            Log::error('Raja Ongkir API Error: "rajaongkir" key not found in response.', ['response' => $response]);
            return response()->json(['error' => 'Failed to retrieve subdistricts from Raja Ongkir.'], 500);
        }
        return response()->json($response['rajaongkir']);
    }

    public function calculateCost(Request $request)
    {
        $request->validate([
            'destination' => 'required|numeric',
            'weight' => 'required|numeric|min:1',
            'courier' => 'required|string',
            'originType' => 'sometimes|string|in:city,subdistrict',
            'destinationType' => 'sometimes|string|in:city,subdistrict',
        ]);

        $originCityId = config('rajaongkir.origin_city_id');
        $destinationCityId = $request->input('destination');
        $weight = $request->input('weight');
        $courier = $request->input('courier');
        $originType = $request->input('originType', 'city');
        $destinationType = $request->input('destinationType', 'city');

        if (!$originCityId) {
            return response()->json(['error' => 'Origin city is not configured.'], 500);
        }

        $response = $this->rajaOngkirService->getCost(
            $originCityId,
            $destinationCityId,
            $weight,
            $courier,
            $originType,
            $destinationType
        );

        if (!isset($response['rajaongkir'])) {
            Log::error('Raja Ongkir API Error: "rajaongkir" key not found in response.', ['response' => $response]);
            return response()->json(['error' => 'Failed to calculate shipping cost from Raja Ongkir.'], 500);
        }

        return response()->json($response['rajaongkir'])->header('Access-Control-Allow-Origin', '*');
    }
}
