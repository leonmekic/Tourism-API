
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Reset Password') }}</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                    @endif
                    <form method="POST"">
                        @csrf

                        <input type="hidden" name="token" value="">

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email"  name="email" value="{{$passwordToken['email']}}" required autofocus>


                                <span class="invalid-feedback">
                                        <strong>Email</strong>
                                    </span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right"></label>

                            <div class="col-md-6">
                                <input id="password" type="password" name="password" required>


                                <span class="invalid-feedback">
                                        <strong>Password</strong>
                                    </span>

                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right"></label>
                            <div class="col-md-6">
                                <input id="password-confirm" type="password" name="password_confirmation" required>


                                <span class="invalid-feedback">
                                        <strong>Password Confirmation</strong>
                                    </span>

                            </div>
                        </div>

                    <div class="form-group row">
                        <label for="password-confirm" class="col-md-4 col-form-label text-md-right"></label>
                        <div class="col-md-6">
                            <input id="token" type="token" name="token" value="{{$passwordToken['token']}}" required>


                            <span class="invalid-feedback">
                                        <strong>Token</strong>
                                    </span>

                        </div>
                    </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                            Submit
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

