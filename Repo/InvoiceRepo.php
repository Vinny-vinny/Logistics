<?php


namespace Repo;


use App\BtnLine;
use App\Customer;
use App\Invnum;
use App\Jobcard;
use Carbon\Carbon;

class InvoiceRepo
{
    static function init()
    {
        return new self();
    }

    public function generateInvoice($id)
    {
    $jobcard = Jobcard::find($id);
    $customer_details = Customer::find($jobcard->customer->dc_link);
        InvNum::create([
                'AccountID' => $jobcard->customer->dc_link ? $jobcard->customer->dc_link: '',
                'Address1' => $customer_details ? $customer_details->Physical1 : '',
                'Address2' => $customer_details ? $customer_details->Physical2 : '',
                'Address3' => $customer_details ? $customer_details->Physical3 : '',
                'Address4' => $customer_details ? $customer_details->Physical4 : '',
                'Address5' => $customer_details ? $customer_details->Physical4 : '',
                'Address6' => $customer_details ? $customer_details->Physical5 : '',
                'DeliveryDate' => Carbon::now(),
                'Description' =>  $proforma != null ? $proforma->customer->Name : $invoiceData->customer->Name,
                'DocFlag' => 0,
                'DocRepID' => 0,
                'DocState' => 1,
                'DocType' => 0,
                'DocVersion' => 1,
                'DueDate' => Carbon::now(),
                'ForeignCurrencyID' => $proforma != null ? $proforma->customer->iCurrencyID : $invoiceData->customer->iCurrencyID,//
                'InvDate' => Carbon::now(),
                'InvNumber' => 'INV00'.(Invnum::count()+1),
                //total invoice line
                'InvTotExcl' => $proforma != null ? $proforma->services->sum('total') :  $invoiceData->quote->services->sum('total'),
                'InvTotExclDEx' => $proforma != null ? $proforma->services->sum('total') :  $invoiceData->quote->services->sum('total'),
                'InvTotIncl' => 0,
                'InvTotInclDEx' => $proforma != null ? ($proforma->services->sum('total') + 0) : ($invoiceData->quote->services->sum('total') + 0),
                'InvTotInclExRounding' => $proforma != null ? $proforma->services->sum('total') : $invoiceData->quote->services->sum('total'),

                'InvTotTax' => 0,
                'InvTotTaxDEx' => 0,
                'OrdTotExcl' => $proforma != null ? $proforma->services->sum('total') : $invoiceData->quote->services->sum('total'),
                'OrdTotExclDEx' => $proforma != null ? $proforma->services->sum('total') : $invoiceData->quote->services->sum('total'),
                'OrdTotIncl' => $proforma != null ? ($proforma->services->sum('total') + 0) :  ($invoiceData->quote->services->sum('total') + 0),
                'OrdTotInclDEx' => $proforma != null ? ($proforma->services->sum('total') + 0) :  ($invoiceData->quote->services->sum('total') + 0),
                'OrdTotInclExRounding' => $proforma != null ? $proforma->services->sum('total') : $invoiceData->quote->services->sum('total'),
                'OrdTotTax' => 0,
                'OrdTotTaxDEx' => 0,
                'OrderDate' => Carbon::now(),
                'OrigDocID' =>0,
                'PAddress1' =>  $proforma != null ? $proforma->customer->Physical1 : $invoiceData->customer->Physical1,
                'PAddress2' =>  $proforma != null ? $proforma->customer->Physical2 : $invoiceData->customer->Physical2,
                'PAddress3' =>  $proforma != null ? $proforma->customer->Physical3 : $invoiceData->customer->Physical3,
                'PAddress4' =>  $proforma != null ? $proforma->customer->Physical4 : $invoiceData->customer->Physical4,
                'PAddress5' =>  $proforma != null ? $proforma->customer->Physical5 : $invoiceData->customer->Physical5,
                'PAddress6'=>  $proforma != null ? $proforma->customer->Physical6 : $invoiceData->customer->Physical5,
                'ProjectID' => $invoiceData->quote->project_id,
                'TaxInclusive' => 0,
                'bInvRounding' => 1,
                'bTaxPerLine' => 1,
                'cAccountName' =>  $proforma != null ? $proforma->customer->Name : $invoiceData->customer->Name,
                'fExchangeRate' => 103,
                'fInvTotExclDExForeign' => $proforma != null ? $proforma->services->sum('total') : $invoiceData->quote->services->sum('total'),
                'fInvTotExclForeign' => $proforma != null ? $proforma->services->sum('total') : $invoiceData->quote->services->sum('total'),
                'fInvTotInclDExForeign' => $proforma != null ? ($proforma->services->sum('total') + 0) : ($invoiceData->quote->services->sum('total') + 0),
                'fInvTotInclForeign' => $proforma != null ? ($proforma->services->sum('total') + 0) : ($invoiceData->quote->services->sum('total') + 0),
                'fInvTotTaxDExForeign' => 0,
                'fInvTotTaxForeign' => 0,
                'fOrdTotExclDExForeign' => $proforma != null ? $proforma->services->sum('total') : $invoiceData->quote->services->sum('total'),
                'fOrdTotExclForeign' => $proforma != null ? $proforma->services->sum('total') : $invoiceData->quote->services->sum('total'),
                'fOrdTotInclDExForeign' => $proforma != null ? ($proforma->services->sum('total') + 0) : ($invoiceData->quote->services->sum('total') + 0),
                'fOrdTotInclForeign' => $proforma != null ? ($proforma->services->sum('total') + 0) : ($invoiceData->quote->services->sum('total') + 0),
                'fOrdTotTaxDExForeign' => 0,
                'fOrdTotTaxForeign' => 0,
                'iINVNUMAgentID' => 1,
                'ucIDInvBLNo' => $invoiceData->quote->cargos ? $invoiceData->quote->cargos->first()->bl_no : '',
                'ucIDInvVoyageNo' => $invoiceData->quote->voyage ? $invoiceData->quote->voyage->voyage_no : '',
                'ucIDInvVessel' => $invoiceData->vessel ? $invoiceData->vessel->name : '',
                'ucIDInvQty' => $invoiceData->quote->cargos ? $invoiceData->quote->cargos->sum('weight') : '',
                'ucIDInvConsignee' => $invoiceData->quote->consignee ? $invoiceData->quote->consignee->consignee_name : '',
                'ucIDInvClientRef' => '',
                'ucIDInvCargoType' => $invoiceData->quote->cargos ? $invoiceData->quote->cargos->first()->name : '',
                'ucIDInvCheckedBy' => $invoiceData->quote->checkedBy ? $invoiceData->quote->checkedBy->name : ''
            ]
        );
        $invumID = InvNum::orderBy('AutoIndex','DESC')->first()->AutoIndex;
        self::makeInvoiceLines($invoiceData->quote->services, $invumID);
        return true;
    }

    public function makeInvoiceLines()
    {
        foreach ($services as $key => $service){
            BtnLine::create([
                'bChargeCom' => 1,
                'cDescription' => $service->description,
                'dDeliveryDate' => Carbon::now(),
                'fQtyChange' => (is_numeric($service->grt_loa) ? $service->grt_loa : 1),
                'fQtyDeliver'=>(is_numeric($service->grt_loa) ? $service->grt_loa : 1) ,
                'fQtyDeliverUR'=>(is_numeric($service->grt_loa) ? $service->grt_loa : 1),
                'fQtyForDelivery' => (is_numeric($service->grt_loa) ? $service->grt_loa : 1),
                'fQtyForDeliveryUR' => (is_numeric($service->grt_loa) ? $service->grt_loa : 1),
                //qty
                'fQtyLastProcess' => (is_numeric($service->grt_loa) ? $service->grt_loa : 1),
                'fQtyLastProcessLineTaxAmount' => 0,
                'fQtyLastProcessLineTaxAmountForeign'=>0,
                'fQtyLastProcessLineTaxAmountNoDisc' => 0,
                'fQtyLastProcessLineTaxAmountNoDiscForeign'=>0,
                'fQtyLastProcessLineTotExcl' => (($service->agency_sp * $service->units) * (is_numeric($service->grt_loa) ? $service->grt_loa : 1)),
                'fQtyLastProcessLineTotExclForeign' =>(($service->agency_sp * $service->units) * (is_numeric($service->grt_loa) ? $service->grt_loa : 1)),
                'fQtyLastProcessLineTotExclNoDisc' => (($service->agency_sp * $service->units) * (is_numeric($service->grt_loa) ? $service->grt_loa : 1)),
                'fQtyLastProcessLineTotExclNoDiscForeign' => (($service->agency_sp * $service->units) * (is_numeric($service->grt_loa) ? $service->grt_loa : 1)),
                'fQtyLastProcessLineTotIncl' => (($service->agency_sp * $service->units) * (is_numeric($service->grt_loa) ? $service->grt_loa : 1)) + 0,
                'fQtyLastProcessLineTotInclForeign' => (($service->agency_sp * $service->units) * (is_numeric($service->grt_loa) ? $service->grt_loa : 1)) + 0,
                'fQtyLastProcessLineTotInclNoDisc' => (($service->agency_sp * $service->units) * (is_numeric($service->grt_loa) ? $service->grt_loa : 1)) + 0,
                'fQtyLastProcessLineTotInclNoDiscForeign' => (($service->agency_sp * $service->units) * (is_numeric($service->grt_loa) ? $service->grt_loa : 1)) + 0,
                'fQtyLastProcessUR' => (is_numeric($service->grt_loa) ? $service->grt_loa : 1),
                'fQtyLinkedUsed' => (is_numeric($service->grt_loa) ? $service->grt_loa : 1),
                'fQtyLinkedUsedUR' => (is_numeric($service->grt_loa) ? $service->grt_loa : 1),
                //qty
                'fQtyProcessed' => (is_numeric($service->grt_loa) ? $service->grt_loa : 1),
                'fQtyProcessedLineTaxAmount' => 0,
                'fQtyProcessedLineTaxAmountForeign' => 0,
                'fQtyProcessedLineTaxAmountNoDisc' => 0,
                'fQtyProcessedLineTaxAmountNoDiscForeign' => 0,
                'fQtyProcessedLineTotExcl' => (($service->agency_sp * $service->units) * (is_numeric($service->grt_loa) ? $service->grt_loa : 1)),
                'fQtyProcessedLineTotExclForeign' => (($service->agency_sp * $service->units) * (is_numeric($service->grt_loa) ? $service->grt_loa : 1)),
                'fQtyProcessedLineTotExclNoDisc' => (($service->agency_sp * $service->units) * (is_numeric($service->grt_loa) ? $service->grt_loa : 1)),
                'fQtyProcessedLineTotExclNoDiscForeign' => (($service->agency_sp * $service->units) * (is_numeric($service->grt_loa) ? $service->grt_loa : 1)),
                'fQtyProcessedLineTotIncl' => (($service->agency_sp * $service->units) * (is_numeric($service->grt_loa) ? $service->grt_loa : 1)) + 0,
                'fQtyProcessedLineTotInclForeign' =>(($service->agency_sp * $service->units) * (is_numeric($service->grt_loa) ? $service->grt_loa : 1)) + 0 ,
                'fQtyProcessedLineTotInclNoDisc' => (($service->agency_sp * $service->units) * (is_numeric($service->grt_loa) ? $service->grt_loa : 1)) + 0,
                'fQtyProcessedLineTotInclNoDiscForeign' => (($service->agency_sp * $service->units) * (is_numeric($service->grt_loa) ? $service->grt_loa : 1)) + 0,
                'fQtyProcessedUR' => (is_numeric($service->grt_loa) ? $service->grt_loa : 1),
                'fQtyToProcess' => (is_numeric($service->grt_loa) ? $service->grt_loa : 1),
                'fQtyToProcessLineTaxAmount' =>0,
                'fQtyToProcessLineTaxAmountForeign' => 0,
                'fQtyToProcessLineTaxAmountNoDisc' => 0,
                'fQtyToProcessLineTaxAmountNoDiscForeign' => 0,
                'fQtyToProcessLineTotExcl' => (($service->agency_sp * $service->units) * (is_numeric($service->grt_loa) ? $service->grt_loa : 1)),
                'fQtyToProcessLineTotExclForeign' => (($service->agency_sp * $service->units) * (is_numeric($service->grt_loa) ? $service->grt_loa : 1)),
                'fQtyToProcessLineTotExclNoDisc' => (($service->agency_sp * $service->units) * (is_numeric($service->grt_loa) ? $service->grt_loa : 1)),
                'fQtyToProcessLineTotExclNoDiscForeign' => (($service->agency_sp * $service->units) * (is_numeric($service->grt_loa) ? $service->grt_loa : 1)),
                'fQtyToProcessLineTotIncl' => (($service->agency_sp * $service->units) * (is_numeric($service->grt_loa) ? $service->grt_loa : 1)) + 0,
                'fQtyToProcessLineTotInclForeign' => (($service->agency_sp * $service->units) * (is_numeric($service->grt_loa) ? $service->grt_loa : 1)) + 0,
                'fQtyToProcessLineTotInclNoDisc' => (($service->agency_sp * $service->units) * (is_numeric($service->grt_loa) ? $service->grt_loa : 1)) + 0,
                'fQtyToProcessLineTotInclNoDiscForeign' => (($service->agency_sp * $service->units) * (is_numeric($service->grt_loa) ? $service->grt_loa : 1)) + 0,
                'fQtyToProcessUR' => (is_numeric($service->grt_loa) ? $service->grt_loa : 1),
                //quantity
                'fQuantity' => (is_numeric($service->grt_loa) ? $service->grt_loa : 1),
                'fQuantityLineTaxAmount' => 0,
                'fQuantityLineTaxAmountForeign' => 0,
                'fQuantityLineTaxAmountNoDisc' => 0,
                'fQuantityLineTaxAmountNoDiscForeign' => 0,
                'fQuantityLineTotExcl' => (($service->agency_sp * $service->units) * (is_numeric($service->grt_loa) ? $service->grt_loa : 1)),
                'fQuantityLineTotExclForeign' => (($service->agency_sp * $service->units) * (is_numeric($service->grt_loa) ? $service->grt_loa : 1)),
                'fQuantityLineTotExclNoDisc' => (($service->agency_sp * $service->units) * (is_numeric($service->grt_loa) ? $service->grt_loa : 1)),
                'fQuantityLineTotExclNoDiscForeign' => (($service->agency_sp * $service->units) * (is_numeric($service->grt_loa) ? $service->grt_loa : 1)),
                'fQuantityLineTotIncl' => ((($service->agency_sp * $service->units) * (is_numeric($service->grt_loa) ? $service->grt_loa : 1)) + 0),
                'fQuantityLineTotInclForeign' => (($service->agency_sp * $service->units) * (is_numeric($service->grt_loa) ? $service->grt_loa : 1)) + 0,
                'fQuantityLineTotInclNoDisc' => (($service->agency_sp * $service->units) * (is_numeric($service->grt_loa) ? $service->grt_loa : 1)) + 0,
                'fQuantityLineTotInclNoDiscForeign' => (($service->agency_sp * $service->units) * (is_numeric($service->grt_loa) ? $service->grt_loa : 1)) + 0,
                'fQuantityUR' => (is_numeric($service->grt_loa) ? $service->grt_loa : 1),
                'fTaxRate' => 0,
                'fUnitCost' =>($service->agency_sp * $service->units),
                'fUnitCostForeign' => ($service->agency_sp * $service->units),
                //price single
                'fUnitPriceExcl' => ($service->agency_sp * $service->units),
                'fUnitPriceExclForeign' => ($service->agency_sp * $service->units),
                'fUnitPriceIncl' => (($service->agency_sp * $service->units) + 0),
                'fUnitPriceInclForeign' => ($service->agency_sp * $service->units) + 0,
                'iInvoiceID' => $invumid,
                'iLineID' => 1,
                'iLineProjectID' => 1,
                'iLineRepID' => 1,
                'iModule' => 0,
                //item id
                'iStockCodeID' => $service->stk_id,
                'iTaxTypeID' => $service->tax_code,

            ]);
        }
        return true;
    }

}
