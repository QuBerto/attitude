<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
            <!-- New section for selecting Discord user -->
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <h3 class="font-semibold text-lg text-gray-800 dark:text-gray-200 leading-tight">
                        {{ __('Select Discord User') }}
                    </h3>
                    <form method="POST" action="{{ route('profile.updateDiscordUser') }}">
                        @csrf


                        <div class="mt-4">
                            
                            <label for="discord_user_id" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Discord User') }}</label>
                            <select id="discord_user_id" name="discord_user_id" class="block mt-1 w-full">
                                @foreach($discordUsers as $discordUser)
                                    <option value="{{ $discordUser->id }}">{{ $discordUser->username }}#{{ $discordUser->discriminator }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>
                                {{ __('Update') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
