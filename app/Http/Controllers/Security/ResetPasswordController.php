<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Lib\ResultBag;
use App\Interfaces\Core\UserRepositoryInterface;
use Illuminate\Support\Facades\Crypt;
use App\Services\Core\EmailService;
use Illuminate\Support\Str;
use App\Mail\Security\ResetPasswordMail;
use Illuminate\Support\Facades\DB;

class ResetPasswordController extends Controller
{
    private $userRepository;
    private $emailService;

    public function __construct(
        UserRepositoryInterface $userRepository,
        EmailService $emailService
    ) {
        $this->userRepository = $userRepository;
        $this->emailService = $emailService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function resetPassword(Request $request)
    {
        DB::beginTransaction();
        try {
            $user = $this->userRepository->first(['email' => $request->email]);
            if (!empty($user)) {
                $random =  Str::random(9);
                $passBcrypt = bcrypt($random);
                $data['change_password_required'] = 1;
                $data['password'] = $passBcrypt;
                $data['encrypted_password'] = Crypt::encrypt($random);
                $this->userRepository->update($user['id'], $data);
                $mail = new ResetPasswordMail($user, $random);
                $this->emailService->send($user->email, $mail);
                $rb = ResultBag::result(true, 'Se ha enviado el correo exitosamente');
                DB::commit();
            } else {
                $rb = ResultBag::result(false, 'No existe una cuenta asociada a este correo');
            }
        } catch (\Throwable $th) {
            DB::rollback();
            $rb = ResultBag::fail($th);
        }
        return $rb;
    }
}
