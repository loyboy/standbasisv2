@extends('layouts.app')

@section('content')
            <!--gx-wrapper-->
            <div class="gx-wrapper">
                <div class="login-container d-flex justify-content-center align-items-center animated slideInUpTiny animation-duration-3">
                    <div class="login-content">

                        <div class="login-header">
                            <a class="site-logo" href="javascript:void(0)" title="Standbasis">
                                <img src="http://via.placeholder.com/140x24" alt="Standbasis" title="standbasis">
                            </a>
                        </div>
                        
                        <div class="login-form">
                            <form method="POST" action="{{ route('login') }}">
                            @csrf
                                <fieldset>
                                    <div class="form-group">
                                        <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="username" autofocus>

                                        @error('username')
                                           <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                        @error('password')                                            
                                            <small class="form-text text-muted">{{ $message }}.</small>
                                        @enderror
                                       
                                    </div>
                                   

                                    <div class="form-group text-row-between">
                                        <div class="custom-control custom-checkbox mr-2">
                                            <input type="checkbox" class="custom-control-input" id="customControlInline">
                                            <label class="custom-control-label" for="customControlInline">Remember me</label>
                                        </div>                                        
                                    </div>
                                   <!-- <a href="javascript:void(0)" class="gx-btn gx-btn-rounded gx-btn-primary">Sign In</a> -->
                                    <button type="submit" class="gx-btn gx-btn-rounded gx-btn-primary">
                                       Sign In
                                    </button>
                                </fieldset>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!--/gx-wrapper-->
@endsection
