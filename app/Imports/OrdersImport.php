<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Query;
use App\Models\OrderItem;
use App\Models\OrderItemColor;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\ToModel;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class OrdersImport implements ToModel, WithStartRow, WithMultipleSheets, WithCalculatedFormulas
{
    protected $queryId;
    protected $currentRow = 0;
    protected $currentOrder;
    protected $currentOrderItem;
    protected $totalQuantity = 0; // Track the total quantity

    public function __construct($queryId)
    {
        $this->queryId = $queryId;
    }

    public function sheets(): array
    {
        return [
            0 => $this,
        ];
    }

    public function startRow(): int
    {
        return 3;
    }

    public function model(array $row)
    {
        $this->currentRow++;

        if (!$this->currentOrder && $row[13] != null) {
            $this->currentOrder = Order::create([
                'query_id' => $this->queryId,
                'order_date' => Carbon::now()->toDateString(),
                'order_no' => Query::find($this->queryId)->product_type->name . '-' . Carbon::now()->year . '-' . Carbon::now()->month . '-' . Order::whereYear('order_date', Carbon::now()->year)->whereMonth('order_date', Carbon::now()->month)->count() + 1
            ]);
        }

        if ($this->currentRow % 10 == 1 && $this->currentOrder && $row[13] != null) {
            $disculpeImagePath = $this->handleImage($row[0], 'disculpe');
            $brandImagePath = $this->handleImage($row[1], 'brand');

            $this->currentOrderItem = OrderItem::create([
                'order_id' => $this->currentOrder->id,
                'disculpe' => $disculpeImagePath,
                'brand' => $brandImagePath,
                'code' => $row[2],
                'function' => $row[3],
                'model' => $row[4],
                'details' => $row[5],
                'fit' => $row[6],
                'fabric' => $row[7],
                'weight' => $row[8],
                'pieces' => $row[13],
                'shipment_date' => $this->parseDate($row[14]),
            ]);

            $this->totalQuantity += $row[13];

            $this->currentOrder->update(['total_quantity' => $this->totalQuantity]);
        }

        if (!empty($row[10])) {
            OrderItemColor::create([
                'order_item_id' => $this->currentOrderItem->id,
                'color' => $row[10],           
                'color_details' => $row[11],   
                'quantity' => $row[12],        
            ]);
        }

        return $this->currentOrder;
    }



    private function handleImage($cellValue, $fieldName)
    {
        if ($cellValue instanceof MemoryDrawing) {
            $extension = $this->getImageExtension($cellValue->getMimeType());
            $fileName = uniqid($fieldName . '_') . '.' . $extension;
            $content = $cellValue->getImageResource();
            
            ob_start();
            switch ($extension) {
                case 'jpg':
                case 'jpeg':
                    imagejpeg($content);
                    break;
                case 'png':
                    imagepng($content);
                    break;
                case 'gif':
                    imagegif($content);
                    break;
            }
            $imageContent = ob_get_clean();
            
            Storage::disk('public')->put('order_images/' . $fileName, $imageContent);
            return 'order_images/' . $fileName;
        }
        
        return $cellValue;
    }

    private function getImageExtension($mimeType)
    {
        $mimeToExt = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
        ];

        return $mimeToExt[$mimeType] ?? 'jpg';
    }

    private function parseDate($value)
    {
        if (is_numeric($value)) {
            return Date::excelToDateTimeObject($value)->format('Y-m-d');
        }
        return $value;
    }
}
