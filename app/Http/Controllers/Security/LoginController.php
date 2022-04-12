<?php

namespace App\Http\Controllers\Security;

use Exception;
use App\Lib\ResultBag;
use Illuminate\Http\Request;
use App\Services\Core\FileService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Facades\Core\SystemParamsFacade;
use App\Repositories\Core\PersonRepository;
use App\Interfaces\Core\UserRepositoryInterface;
use App\Interfaces\Core\VenueRepositoryInterface;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Events\Communications\Meetings\MeetingUpdated;
use App\Exceptions\MeetingNotAvailableException;
use App\Interfaces\Communications\Survey\SurveyServiceInterface;
use App\Interfaces\Communications\Meetings\MeetingRepositoryInterface;
use App\Http\Resources\Firebase\Communications\Meetings\MeetingResource;
use App\Interfaces\Management\Council\CouncilMeetingRepositoryInterface;
use App\Http\Resources\Firebase\Management\Council\CouncilMeetingResource;
use App\Interfaces\Communications\Meetings\MeetingRoleRepositoryInterface;
use App\Interfaces\Management\Council\CouncilMeetingPersonRepositoryInterface;
use App\Interfaces\Communications\Meetings\MeetingParticipantRepositoryInterface;
use App\Interfaces\Communications\Survey\SurveyRepositoryInterface;
use App\Http\Resources\Mobile\Core\PersonResource;
use App\Http\Resources\Mobile\Core\UserResource;

class LoginController extends Controller
{
    use AuthenticatesUsers;


    private $userRepository;

    public function __construct(
        UserRepositoryInterface $userRepository
    ) {
        $this->userRepository = $userRepository;
    }

    
    public function username()
    {
        return 'username';
    }


    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|min:6',
            'password' => 'required|min:6',
        ]);

        $status = false;
        if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            $user = $this->userRepository->find(Auth::id(), [
                'profiles.menuItems.menu',
                'clients.units.venues'
            ]);

            $status = true;
        } else {
            $user = null;
        }
        return compact('status', 'user');
    }

    public function logout()
    {
        Auth::logout();
        return response()->json(true);
    }

}
