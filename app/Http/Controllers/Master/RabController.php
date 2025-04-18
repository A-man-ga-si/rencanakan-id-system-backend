<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\CountableItemController;
use App\Models\MasterRab;
use Illuminate\Http\Request;
use Vinkla\Hashids\Facades\Hashids;

class RabController extends CountableItemController
{

    const ALLOWED_SEARCH_CRITERIA = ['header', 'item'];

    public function index(Request $request)
    {
        if ($request->has('q') && $request->q != '' && $request->has('category') && $request->category != '' && in_array($request->category, self::ALLOWED_SEARCH_CRITERIA)) {
            $rabs = MasterRab::query();

            if ($request->category == 'header') {
                $rabs = $rabs->where('name', 'LIKE', '%' . $request->q . '%');
            } else {
                // TODO: Implement search by header feature
            }

            $rabs = $rabs->with(['masterRabItemHeader.masterRabItem'])
                ->with('masterRabItem', function($q) {
                    $q->where('master_rab_item_header_id', NULL);
                    $q->with(['ahs']);
                });

            if ($request->has('master-rab-category-id') && $request->post('master-rab-category-id')) {
                $rabs = $rabs->where('master_rab_category_id', $request->post('master-rab-category-id'));
            }

            $rabs = $rabs->get();

        } else {
            $rabs = MasterRab::with(['masterRabItemHeader.masterRabItem.ahs'])
              ->with('masterRabItem', function($q) {
                  $q->where('master_rab_item_header_id', NULL);
                  $q->with(['ahs']);
              });
            if ($request->has('master-rab-category-id') && $request->post('master-rab-category-id')) {
                $rabs = $rabs->where('master_rab_category_id', $request->post('master-rab-category-id'));
            }
            $rabs = $rabs->get();
        }

        // Decode province id request

        $decodedProvinceId = Hashids::decode($request->province);
        if (empty($decodedProvinceId)) {
          return response()->json([
            'status' => 'fail',
            'message' => 'Data provinsi tidak ditemukan'
          ], 400);
        }
        $provinceId = $decodedProvinceId[0];

        foreach ($rabs as $rab) {
          // Calculate main section rab items subtotal price based on selected province
          $this->calculateRabItemPrice($rab, $rab->masterRabItem, $provinceId);

          // Calculate sub section rab items subtotal price based on selected province
          foreach ($rab->masterRabItemHeader as $masterRabItemHeader) {
            $this->calculateRabItemPrice($rab, $masterRabItemHeader->masterRabItem, $provinceId);
          }
        }

        return response()->json([
            'status' => 'success',
            'data' => compact('rabs')
        ]);
    }
  
    private function calculateRabItemPrice($rab, $masterRabItem, $provinceId) {
      foreach ($masterRabItem as $masterRabItem) {
        $masterRabItemPrice = 0;
        foreach ($masterRabItem->ahs?->ahsItem ?? [] as $ahsItem) {
          $priceByProvince = $ahsItem->ahsItemable->price
            ->where('province_id', $provinceId)
            ->pluck('price')
            ->first();
          $masterRabItemPrice += $priceByProvince;
        }
        if ($masterRabItem->ahs) {
          $masterRabItem->price = $masterRabItemPrice;
        }
        $masterRabItem->total = $masterRabItem->price * ($masterRabItem->volume ?? 0);
        $rab->subtotal += $masterRabItem->total;
        unset($masterRabItem->ahs); // Remove AHS from master rab item response
      }
    }

    public function store(Request $request)
    {
        $rab = MasterRab::create($request->only(['name', 'master_rab_category_id']));
        return response()->json([
            'status' => 'success',
            'data' => compact('rab')
        ]);
    }

    public function update(Request $request, MasterRab $masterRab)
    {
        $masterRab->update($request->only(['name']));

        return response()->json([
            'status' => 'success',
            'data' => compact('masterRab'),
        ]);
    }

    public function destroy(MasterRab $masterRab)
    {
        $masterRab->delete();
        return response()->json([
            'status' => 'success',
        ], 204);
    }

    public function show(MasterRab $masterRab, Request $request)
    {
        if ($request->has('q') && $request->q != '' && $request->has('category') && $request->category != '' && in_array($request->category, self::ALLOWED_SEARCH_CRITERIA)) {
            $rabs = MasterRab::query();

            if ($request->category == 'header') {
                $rabs = $rabs->where('name', 'LIKE', '%' . $request->q . '%');
            } else {
                // TODO: Implement search by header feature
            }

            $rabs = $rabs->with(['masterRabItemHeader.masterRabItem'])
                ->where('id', $masterRab->id)
                ->with('masterRabItem', function($q) {
                    $q->where('master_rab_item_header_id', NULL);
                    $q->with(['ahs']);
                })->get();

        } else {
            $rabs = MasterRab::with(['masterRabItemHeader.masterRabItem'])
                ->where('id', $masterRab->id)
                ->with('masterRabItem', function($q) {
                    $q->where('master_rab_item_header_id', NULL);
                    $q->with(['ahs']);
                })
                ->get();
        }

        $rabSubtotal = 0;

        foreach ($rabs as $key => $rab) {
            if ($rab->masterRabItem || ($rab->masterRabItemHeader && $rab->masterRabItemHeader->rabItem)) {
                foreach ($rab->masterRabItem as $key2 => $masterRabItem) {
                    if ($masterRabItem->ahs) {
                        $countedAhs = $this->countAhsSubtotal($masterRabItem->ahs, Hashids::decode($request->province)[0]);
                        // return response()->json([
                        //     'counted_ahs' => $countedAhs
                        // ]);
                        $countedAhs->price = $countedAhs->subtotal;
                        $countedAhs->subtotal = $countedAhs->subtotal * ($masterRabItem->volume ?? 0);
                        $rabs[$key]->masterRabItem[$key2]['custom_ahs'] = $countedAhs;
                        $rabSubtotal += $countedAhs->subtotal;
                    } else {
                        $masterRabItem->subtotal = $masterRabItem->price * ($masterRabItem->volume ?? 0);
                        $rabs[$key]->masterRabItem[$key2] = $masterRabItem;
                        $rabSubtotal += $masterRabItem->subtotal;
                    }
                }

                foreach ($rab->masterRabItemHeader as $key3 => $masterRabItemHeader) {
                    foreach ($masterRabItemHeader->masterRabItem as $key4 => $masterRabItem) {
                        if ($masterRabItem->ahs) {
                            $countedAhs = $this->countAhsSubtotal($masterRabItem->ahs, Hashids::decode($request->province)[0]);
                            $countedAhs->price = $countedAhs->subtotal;
                            $countedAhs->subtotal = $countedAhs->subtotal * ($masterRabItem->volume ?? 0);
                            $masterRabItem['custom_ahs'] = $countedAhs;
                            // if ($masterRabItem->name == 'Pekerja (Null)') {
                            //     return response()->json([
                            //         'd' => $rabs[$key]->masterRabItem[$key3],
                            //     ]);
                            // }
                            $rabSubtotal += $countedAhs->subtotal;
                        } else {
                            $masterRabItem->subtotal = $masterRabItem->price * ($masterRabItem->volume ?? 0);
                            $rabSubtotal += $masterRabItem->subtotal;
                        }
                    }
                }
            } else {
                $rabSubtotal += 0;
            }

            $rabs[$key]->subtotal = $rabSubtotal;
            $rabSubtotal = 0;
        }

        return response()->json([
            'status' => 'success',
            'data' => compact('rabs')
        ]);
    }
}
