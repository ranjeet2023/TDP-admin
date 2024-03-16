<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExportInvoice extends Model
{
    use HasFactory;

    protected $table = 'export_invoice';

    protected $primaryKey = 'exoprt_invoice_id';

}
