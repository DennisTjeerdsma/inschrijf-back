
@section('content')
    <h1>Welcome to <a href="{{ config('app.url') }}">{{ config('app.name') }}</a></h1>
    <p>
        Dear {{ $user->name }},
    </p>
    <p>
        Your account has been created. You can now pick a password at our site and login.
    </p>
    <table>
        <tr>
            <td>
                <p>
                    <button href="{{ url('password/reset', [$token]) }}" class="btn-primary">
                       Pick a password
                    </button>
                </p>
            </td>
        </tr>
    </table>

    <p><em>This link is valid until {{ Carbon\Carbon::now()->addMinutes(config('auth.passwords.users.expire'))->format('Y/m/d') }}.</em></p>
@endsection