<?php

namespace Database\Seeders;

use App\Models\Tna;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TNASeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tnas = [
            'Order Received',
            'Order Sheet & Details Receiv',
            'TECH / Art Works  Receive',
            'Price Confirmation By Factory',
            'LC / TT  Receiv / Confirmation',
            'SALES  Contract for FACTORY',
            'PRODUCTION  PLAN',
            'Yarn Booking / LC Receive',
            'KNITTING  Start',
            'DYING  Start',
            'Fabric Color LAB DIP',
            'P. Invoice ( fabric / Acces / print)',
            'ACCESSORIES Development',
            'SAMPLE FABRIC Development',
            'LAB  DIP FABRIC : APPROVAL',
            'LAB  DIP  S. THREAD : APPROVAL',
            'LAB  DIP ZIPPER : APPROVAL',
            'PRINT / EMBR  DEVELOPMENT',
            'ACCESSORIES Approval',
            'FIT  SAMPLE  Development',
            'P P  SAMPLE  RECEIVED',
            'P P SAMPLE  COMMENTS',
            'PP MEETING with FACTORY',
            'ACCESSORIES IN-HOUSE',
            'FABRIC IN-HOUSE',
            'FABRIC GSM &  SHADE BANK ',
            'Fabric CUTTING  START',
            'Cutting Send = PRINT / EMBRO',
            'CUTTING FINISHED',
            'Receive PRINT / EMBRO PART',
            'SWEING  START',
            'SWEING FINISH',
            'FINISHING START + POLY',
            'FINISING COMPLETE',
            'INSPECTION',
            'Packaging List',
            'Container Booking',
            'Goods EX-FACTORY',
            'FINAL DOC Send to Buyer'
        ];

        foreach ($tnas as $tna) {
            Tna::create([
                'name' => $tna
            ]);
        }
    }
}
