<?php

namespace App\Http\Controllers\Api\Users;

use App\Entities\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Transformers\Users\UserTransformer;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

/**
 * Class UsersController
 * @package App\Http\Controllers\Users
 */
class UsersController extends Controller
{

    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;
        $this->middleware('permission:List users')->only('index');
        $this->middleware('permission:List users')->only('show');
        $this->middleware('permission:Create users')->only('store');
        $this->middleware('permission:Update users')->only('update');
        $this->middleware('permission:Delete users')->only('destroy');
    }


    /**
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $paginator = $this->model->with('roles.permissions')
            ->paginate($request->get('limit', env('PAGINATE_LIMIT', 20)));
        return fractal()->collection($paginator->getCollection(), new UserTransformer())
            ->paginateWith(new IlluminatePaginatorAdapter($paginator))
            ->respond();
    }


    /**
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        $user = $this->model->with('roles.permissions')->byUuid($id)->firstOrFail();
        return fractal($user, new UserTransformer())->respond();
    }


    /**
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed'
        ]);
        $user = $this->model->create($request->all());
        return fractal($user, new UserTransformer())
            ->respond(201)
            ->header('location', url('api/users/'.$user->uuid));
    }


    /**
     * @param Request $request
     * @param $uuid
     * @return mixed
     */
    public function update(Request $request, $uuid)
    {

    }

    /**
     * @param Request $request
     * @param $uuid
     * @return mixed
     */
    public function partialUpdate(Request $request, $uuid)
    {

    }


    /**
     * @param Request $request
     * @param $uuids
     * @return mixed
     */
    public function destroy(Request $request, $uuids)
    {

    }
}
