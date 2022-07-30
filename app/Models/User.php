<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable
        = [
            'name',
            'email',
            'password',
        ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden
        = [
            'password',
            'remember_token',
        ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts
        = [
            'email_verified_at' => 'datetime',
        ];

    public static function store_user($input)
    {
        DB::beginTransaction();
        try {
            $user            = new User();
            $user->name      = $input['user_name'];
            $user->email     = $input['user_email'];
            $user->user_type = $input['user_type'];
            $user->password  = encrypt($input['password']);
            $user->save();

            $personnel_details = [
                'name'    => $input['user_name'],
                'email'   => $input['user_email'],
                'mobile'  => $input['mobile'],
                'user_id' => $user->id,
            ];
            DB::table('personnel_details')->insert($personnel_details);

            $education_details = [
                'passed_out_year' => $input['passed_out_year'],
                'branch'          => $input['branch'],
                'college'         => $input['college'],
                'user_id'         => $user->id,
            ];
            DB::table('education_details')->insert($education_details);


            if ($input['user_type'] == 'experience') {
                $job_details = [
                    'current_company_name' => $input['current_company_name'],
                    'designation'          => $input['designation'],
                    'location'             => $input['location'],
                    'user_id'              => $user->id,
                ];
                DB::table('job_details')->insert($job_details);
            }
            DB::commit();
            return true;
        } catch (\Exception $e) {
            dd($e->getMessage());
            DB::rollback();
            return false;
            // something went wrong
        }
    }

    public static function user_list()
    {
        return DB::table('users as user')
            ->select('user.id as user_id', 'user.user_type', 'personal.name', 'personal.email', 'personal.mobile', 'edu.passed_out_year', 'edu.branch', 'edu.college', 'job.current_company_name', 'job.designation', 'job.location')
            ->leftJoin('personnel_details as personal', 'personal.user_id', 'user.id')
            ->leftJoin('education_details as edu', 'edu.user_id', 'user.id')
            ->leftJoin('job_details as job', 'job.user_id', 'user.id')
            ->get();
    }
}
