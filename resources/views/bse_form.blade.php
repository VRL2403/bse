<x-app-layout>
    <main class="main-content  mt-0">
        <section>
            <div class="page-header min-vh-100">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="position-absolute w-40 top-0 start-0 h-100 d-md-block d-none">
                                <div class="oblique-image position-absolute d-flex fixed-top ms-auto h-100 z-index-0 bg-cover me-n8" style="background-image:url('../assets/img/image-sign-up.jpg')">
                                    {{-- some content--}}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 d-flex flex-column mx-auto">
                            <div class="card card-plain mt-8">
                                <div class="card-header pb-0 text-left bg-transparent">
                                    <h3 class="font-weight-black text-dark display-6">Register</h3>
                                    <p class="mb-0">Welcome! Please enter your details.</p>
                                </div>
                                <div class="card-body">
                                    <form role="form" method="POST" action="bse_form">
                                        @csrf
                                        <label>Name</label>
                                        <div class="mb-3">
                                            <input type="text" id="name" name="name" class="form-control" placeholder="Enter your name" value="{{old("name")}}" aria-label="Name" aria-describedby="name-addon">
                                            @error('name')
                                            <span class="text-danger text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <label>Email Address</label>
                                        <div class="mb-3">
                                            <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email address" value="{{old("email")}}" aria-label="Email" aria-describedby="email-addon">
                                            @error('email')
                                            <span class="text-danger text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <label>Mobile Number</label>
                                        <div class="mb-3">
                                            <input type="mobile" id="mobile" name="mobile" class="form-control" placeholder="Enter your mobile number" value="{{old("mobile")}}" aria-label="Mobile" aria-describedby="mobile-addon">
                                            @error('mobile')
                                            <span class="text-danger text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <label>Native Place</label>
                                        <div class="mb-3">
                                            <input type="text" id="native_place" name="native_place" class="form-control" placeholder="Enter your Native Place" value="{{old("native_place")}}" aria-label="native_place" aria-describedby="name-addon">
                                            @error('native_place')
                                            <span class="text-danger text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <label>KVO member</label>
                                        <div class="mb-3">
                                            <div class="dropdown">
                                                <a href="javascript:;" class="btn bg-gradient-dark dropdown-toggle " data-bs-toggle="dropdown" id="navbarDropdownMenuLink2">
                                                    Select Option
                                                </a>
                                                <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink2">
                                                    <li>Yes</li>
                                                    <li>No</li>
                                                </ul>
                                            </div>
                                            @error('native_place')
                                            <span class="text-danger text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-check form-check-info text-left mb-0">
                                            <input class="form-check-input" type="checkbox" name="terms" id="terms" required>
                                            <label class="font-weight-normal text-dark mb-0" for="terms">
                                                I agree the <a href="javascript:;" class="text-dark font-weight-bold">Terms and Conditions</a>.
                                            </label>
                                            @error('terms')
                                            <span class="text-danger text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-dark w-100 mt-4 mb-3">Sign up</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

</x-app-layout>