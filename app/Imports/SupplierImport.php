<?php

namespace App\Imports;

use DB;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
// use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SupplierImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    public function model(array $row)
    {
        // echo "<pre>";
        // print_r($row);
        // die;

        $email = $row['email'];

        $check = DB::table('users')->where('email', $email)->first();
        if (empty($check)) {

            $firstname = strtolower($row['firstname']);
            $lastname = strtolower($row['lastname']);

            $user_id = DB::table('users')->insertGetId(array(
                'firstname' => $firstname,
                'lastname' => $lastname,
                'email' => $email,
                'password' => Hash::make($row['password']),
                'companyname' => $row['companyname'],
                'mobile' => $row['mobile'],
                'user_type' => 3,
                'email_verified_at' => date('Y-m-d H:i:s'),
                'is_active' => ($row['is_pending'] == 0) ? 1 : 0,
                'added_by' => $row['sales_person'],
                'created_at' => date('Y-m-d H:i:s', strtotime($row['created_date'])),
            ));

            $number = DB::table('suppliers')->insert(array(
                'sup_id' => $user_id,
                'upload_mode' => $row['upload_mode'],
                'diamond_type' => $row['type'],
                'stock_status' => $row['stock_status'],
                'city' => $row['city'],
                'state' => $row['state'],
                'country' => $row['country'],
                'website' => $row['website'],
                'hold_allow' => $row['can_hold'],
                'markup' => $row['discount'],
                'ftp_host' => $row['ftp_host'],
                'ftp_username' => $row['ftp_username'],
                'ftp_password' => $row['ftp_password'],
                'ftp_port' => $row['ftp_port'],
                'folder_name' => $row['folder_name'],
                'cron_link' => $row['link'],
            ));
        }
    }
}
