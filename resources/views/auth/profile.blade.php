@extends('template.layouts.app')

@section('title', 'Profile - Yummy')

@section('content')
@extends('template.layouts.session')
    <x-forms.form method="post" action="{{ route('user.profile.update') }}" enctype="multipart/form-data">
        <x-forms.input label="Name" name="name" :value="old('name', $user->name)" />
        <x-forms.input label="Email" name="email" type="email" :value="old('email', $user->email)" />
        <x-forms.input label="Password" name="password" type="password"/>
        <x-forms.input label="Password Confirmation" name="password_confirmation" type="password"/>

        <x-forms.divider/>

        <x-forms.button>Update Account</x-forms.button>
    </x-forms.form>
@endsection