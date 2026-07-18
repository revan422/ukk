<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RajaOngkirService
{
    protected string $apiKey;

    protected string $baseUrl;

    protected string $origin;

    protected string $courier;

    protected string $service;

    public function __construct()
    {
        $this->apiKey = config('services.rajaongkir.api_key');
        $this->baseUrl = config('services.rajaongkir.base_url');
        $this->origin = config('services.rajaongkir.origin');
        $this->courier = config('services.rajaongkir.courier');
        $this->service = config('services.rajaongkir.service');
    }

    /**
     * Get list of provinces from RajaOngkir Komerce API.
     *
     * @return array
     */
    public function getProvinces(): array
    {
        try {
            $response = Http::timeout(30)
                ->withHeaders(['key' => $this->apiKey])
                ->get("{$this->baseUrl}/destination/province");

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('RajaOngkir Komerce: getProvinces failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return [
                'success' => false,
                'message' => 'Gagal mengambil data provinsi dari RajaOngkir.',
                'status_code' => $response->status(),
            ];
        } catch (RequestException $e) {
            Log::error('RajaOngkir Komerce: getProvinces HTTP exception: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'message' => 'Terjadi kesalahan koneksi saat mengambil data provinsi.',
            ];
        } catch (\Exception $e) {
            Log::error('RajaOngkir Komerce: getProvinces unexpected error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'message' => 'Terjadi kesalahan sistem saat mengambil data provinsi.',
            ];
        }
    }

    /**
     * Search domestic destination (city/district) from RajaOngkir Komerce API.
     *
     * @param  string  $search
     * @return array
     */
    public function getDomesticDestination(string $search): array
    {
        try {
            $response = Http::timeout(30)
                ->withHeaders(['key' => $this->apiKey])
                ->get("{$this->baseUrl}/destination/domestic-destination", [
                    'search' => $search,
                ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('RajaOngkir Komerce: getDomesticDestination failed', [
                'search' => $search,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return [
                'success' => false,
                'message' => 'Gagal mencari tujuan pengiriman.',
                'status_code' => $response->status(),
            ];
        } catch (RequestException $e) {
            Log::error('RajaOngkir Komerce: getDomesticDestination HTTP exception: ' . $e->getMessage(), [
                'search' => $search,
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'message' => 'Terjadi kesalahan koneksi saat mencari tujuan pengiriman.',
            ];
        } catch (\Exception $e) {
            Log::error('RajaOngkir Komerce: getDomesticDestination unexpected error: ' . $e->getMessage(), [
                'search' => $search,
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'message' => 'Terjadi kesalahan sistem saat mencari tujuan pengiriman.',
            ];
        }
    }

    /**
     * Calculate domestic shipping cost from RajaOngkir Komerce API.
     *
     * @param  string  $origin
     * @param  string  $destination
     * @param  int     $weight
     * @param  string  $courier
     * @param  string  $service
     * @return array
     */
    public function calculateDomesticCost(
        string $origin,
        string $destination,
        int $weight,
        string $courier,
        string $service = 'lowest'
    ): array {
        try {
            $response = Http::timeout(30)
                ->withHeaders(['key' => $this->apiKey])
                ->post("{$this->baseUrl}/calculate/domestic-cost", [
                    'origin'      => $origin,
                    'destination' => $destination,
                    'weight'      => $weight,
                    'courier'     => $courier,
                    'price'       => $service,
                ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('RajaOngkir Komerce: calculateDomesticCost failed', [
                'origin'      => $origin,
                'destination' => $destination,
                'weight'      => $weight,
                'courier'     => $courier,
                'service'     => $service,
                'status'      => $response->status(),
                'body'        => $response->body(),
            ]);

            return [
                'success' => false,
                'message' => 'Gagal menghitung biaya pengiriman.',
                'status_code' => $response->status(),
            ];
        } catch (RequestException $e) {
            Log::error('RajaOngkir Komerce: calculateDomesticCost HTTP exception: ' . $e->getMessage(), [
                'origin'      => $origin,
                'destination' => $destination,
                'weight'      => $weight,
                'courier'     => $courier,
                'service'     => $service,
                'trace'       => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'message' => 'Terjadi kesalahan koneksi saat menghitung biaya pengiriman.',
            ];
        } catch (\Exception $e) {
            Log::error('RajaOngkir Komerce: calculateDomesticCost unexpected error: ' . $e->getMessage(), [
                'origin'      => $origin,
                'destination' => $destination,
                'weight'      => $weight,
                'courier'     => $courier,
                'service'     => $service,
                'trace'       => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'message' => 'Terjadi kesalahan sistem saat menghitung biaya pengiriman.',
            ];
        }
    }
}
