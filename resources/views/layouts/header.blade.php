

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <p class="navbar-brand" href="javascript:void(0);"></p>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <h5><a class="nav-link {{(request()->is('/')) ? 'active' : '' }}" href="{{ route('homePage')}}">Home</a></h5>
            </li>
            <li class="nav-item">
                <h5><a class="nav-link {{(request()->is('users*')) ? 'active' : '' }}" href="{{route('usersIndex')}}">Users</a></h5>
            </li>
            <li class="nav-item">
                <h5><a class="nav-link {{(request()->is('company*')) ? 'active' : '' }}" href="{{route('companiesIndex')}}">Company</a></h5>
            </li>
        </ul>
    </div>
</nav>
