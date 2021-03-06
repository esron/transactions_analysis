<?php

namespace App\Http\Controllers;

use App\Mail\UserCreated;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::whereNot('email', env('ADMIN_EMAIL'))->get();
        return view('components.users.index', ['users' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('components.users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
        ]);
        $password = $this->generateNumericStringWithLength(6);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($password),
        ]);
        Mail::to($user)->send(new UserCreated($user->name, $password));
        return redirect()->route('users.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $this->abortIfAdminUser($user, 'editado');
        return view('components.users.edit', ['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $this->abortIfAdminUser($user, 'editado');
        $request->validate([
            'name' => 'required|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($user->id),
            ],
        ]);
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);
        return back()->with('message', 'Usu??rio editado com sucesso');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $this->abortIfAdminUser($user, 'exclu??do');
        if (Auth::class === $user->id) {
            abort(403, "Um usu??rio n??o pode excluir a si pr??prio");
        }
        User::destroy($user->id);
        return back();
    }

    private function generateNumericStringWithLength(int $length): string
    {
        $characters = collect()->range(0, 9)->map(fn($c) => (string) $c);
        $password = '';
        for ($i=0; $i < $length; $i++) {
            $password .= $characters->random();
        }
        return $password;
    }

    private function abortIfAdminUser(User $user, string $verb)
    {
        if ($user->email === env('ADMIN_EMAIL')) {
            abort(403, "O usu??rio $user->name n??o pode ser $verb");
        }
    }
}
