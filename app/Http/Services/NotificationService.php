<?php

namespace App\Http\Services;

use App\Models\Notification;
use App\Models\Garments\GarmentOrder;
use App\Models\Garments\Material;
use App\Models\Garments\ShipmentDocument;
use App\Traits\ResponseTrait;
use Carbon\Carbon;

class NotificationService
{
    use ResponseTrait;

   public function getNotification()
   {
       $this->generateGarmentAlerts();

       return Notification::where(function ($query) {
           $query->whereNull('user_id')->orWhere('user_id', auth()->id());
       })->latest()->get();
   }

   public function generateGarmentAlerts(): void
   {
       $today = Carbon::today();
       $soon = $today->copy()->addDays(3);

       GarmentOrder::whereNotIn('status', [
           GARMENT_ORDER_STATUS_COMPLETED,
           GARMENT_ORDER_STATUS_CANCELLED,
       ])->whereDate('delivery_date', '<=', $soon)->get()->each(function ($order) use ($today) {
           $title = $order->delivery_date->lt($today)
               ? __('Overdue garment order')
               : __('Garment order deadline approaching');
           $body = __('Order :order is due on :date.', [
               'order' => $order->order_number,
               'date' => $order->delivery_date->format('d M Y'),
           ]);

           $this->createOnce($title, $body, route('admin.garments.orders.index'));
       });

       Material::whereColumn('current_stock', '<=', 'reorder_level')->get()->each(function ($material) {
           $this->createOnce(
               __('Low stock alert'),
               __(':item has reached its reorder level.', ['item' => $material->item_name]),
               route('admin.garments.materials.index')
           );
       });

       ShipmentDocument::where('status', GARMENT_SHIPMENT_STATUS_READY)->get()->each(function ($shipment) {
           $this->createOnce(
               __('Shipment ready for dispatch'),
               __('Shipment document :document is ready for dispatch.', ['document' => $shipment->document_number ?: $shipment->id]),
               route('admin.garments.shipment-documents.index')
           );
       });
   }

   private function createOnce(string $title, string $body, string $link): void
   {
       Notification::firstOrCreate(
           ['user_id' => null, 'title' => $title, 'body' => $body],
           ['link' => $link, 'view_status' => 0, 'status' => STATUS_ACTIVE]
       );
   }
}