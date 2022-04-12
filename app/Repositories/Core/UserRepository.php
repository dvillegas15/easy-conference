<?php

namespace App\Repositories\Core;

use App\Events\Core\UserCreated;
use App\Models\Core\Person;
use App\Models\Core\User;
use App\Repositories\BaseRepository;
use App\Interfaces\Core\UserRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{

    public function __construct(User $user)
    {
        $this->model = $user;
    }

    // @override
    public function get(array $params = [], $related = null, $appends = null)
    {

        $query = $this->getQuery($params, $related)
            ->when(isset($params['name']), function ($query) use ($params) {
                return $query->where('users.name', 'like', '%' . $params['name'] . '%');
            })
            ->when(isset($params['document']), function ($query) use ($params) {
                return $query->where('users.document', 'like', '%' . $params['document'] . '%');
            })
            ->when(isset($params['exact_document']), function ($query) use ($params) {
                return $query->where('users.document', $params['exact_document']);
            })
            ->when(isset($params['username']), function ($query) use ($params) {
                return $query->where('users.username', 'like', '%' . $params['username'] . '%');
            })
            ->when(isset($params['email']), function ($query) use ($params) {
                return $query->where('users.email', 'like', '%' . $params['email'] . '%');
            })
            ->when(isset($params['administrative']), function ($query) use ($params) {
                return $query->whereHas('profiles', function($query) {
                    return $query->where('profiles.administrative', 1);
                });
            })
            ->when(isset($params['profile_slug']), function ($query) use ($params) {
                return $query->whereHas('profiles', function ($query) use ($params) {
                    return $query->where('slug', $params['profile_slug']);
                });
            })
            ->when(isset($params['profile_id']), function ($query) use ($params) {
                return $query->whereHas('profiles', function ($query) use ($params) {
                    return $query->where('profiles.id', $params['profile_id']);
                });
            })
            ->when(isset($params['client_id']), function ($query) use ($params) {
                return $query->whereHas('clients', function ($q) use ($params) {
                    return $q->where('clients.id', $params['client_id']);
                });
            })
            ->when(isset($params['role_slug']), function ($query) use ($params) {
                return $query->whereHas('profiles', function ($q) use ($params) {
                    return $q->where('slug', $params['role_slug']);
                });
            })
            ->when(isset($params['venue_id']), function ($query) use ($params) {
                return $query->whereHas('venues', function ($q) use ($params) {
                    return $q->where('venues.id', $params['venue_id']);
                });
            })
            ->when(isset($params['venue_guard_id']), function ($query) use ($params) {
                return $query->whereHas('guards', function ($q) use ($params) {
                    return $q->where('guards.venue_id', $params['venue_guard_id']);
                });
            });

        if (isset($params['current_page'])) {
            $users = $query->paginate($params['per_page']);
        } else {
            $users = $query->get();
        }

        if($appends) {
            $users->each->appends($appends);
        }

        return $users;
    }

    // @override
    public function create(array $data)
    {
        $data = $this->filterData($data);
        $data['api_token'] = Str::random(80);
        $data['remember_token'] = Str::random(80);

        $pass = isset($data['password']) ? $data['password'] : $data['document'];
        $data['password'] = isset($data['password']) ? $data['password'] : bcrypt($data['document']);

        $user = $this->model->create($data);

        return $user;
    }

    public function isUnique($document, $email): bool
    {
        return !User::where('document', $document)->orWhere('email', $email)->count();
    }

    public function getByDocument($document, $trashed = false): ?User
    {
        return $this->model->where('document', $document)->when($trashed, function($query) {
            return $query->withTrashed();
        })->first();
    }


}
