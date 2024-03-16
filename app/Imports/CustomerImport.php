<?php

namespace App\Imports;

use DB;
use Illuminate\Http\Request;
// use Hash;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
// use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CustomerImport implements ToModel, WithHeadingRow
{
    /**
     *
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
                'id' => $row['id'],
                'firstname' => $firstname,
                'lastname' => $lastname,
                'email' => $email,
                'password' => Hash::make($row['password']),
                'companyname' => $row['companyname'],
                'mobile' => $row['mobile'],
                'user_type' => 2,
                'email_verified_at' => date('Y-m-d H:i:s'),
                'is_active' => $row['is_approved'],
                'added_by' => ($row['created_by'] == 5 ? 1 : 0),
                'created_at' => date('Y-m-d H:i:s', strtotime($row['created_date'])),
            ));

            $number = DB::table('customers')->insert(array(
                'cus_id' => $user_id,
                'api_key' => $row['api_key'],
                'api_enable' => $row['api_live_mode'],
                'api_created_date' => $row['ap_created_date'],
                'city' => $row['city'],
                'state' => $row['state'],
                'country' => $row['country'],
                'website' => $row['website'],
                'passport_id' => $row['passport_id'],
                'passport_file' => $row['passport_file'],
                'shipping_address' => $row['shipping_address'],
                'customer_type' => $row['v_customer'],
                'discount' => $row['discount'],
                'lab_discount' => $row['lab_discount'],
                'com_reg_no' => $row['com_reg_no'],
                'source' => $row['source'],
            ));
        }
    }
}
