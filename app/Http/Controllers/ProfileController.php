<?php

namespace App\Http\Controllers;
use App\Models\DiscordUser;
use App\Http\Requests\ProfileUpdateRequest;
use Discord\Discord;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = Auth::user();
        $discordUsers = DiscordUser::all();
        //$discordUsers = $user->discordUsers;
        return view('profile.edit', [
            'user' => $user,
            'discordUsers' => $discordUsers
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function updateDiscordUser(Request $request)
    {
        $request->validate([
            'discord_user_id' => 'required|exists:discord_users,id',
        ]);

        $user = Auth::user();
        $duser = DiscordUser::find($request->discord_user_id);
        $duser->user_id = $user->id;
        $duser->save();


        // Here you can perform any additional logic needed for updating the selected Discord user

        return redirect()->route('profile.edit')->with('status', 'Discord user updated successfully!');
    }
}
