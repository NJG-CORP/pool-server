  <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse" aria-expanded="false">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/') }}">
                        {{ config('app.name', 'Laravel') }}
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">

                        <li >

                        <a href="{{ route('get:users:data')}}"  >Users </a>

                        

                       </li>     

                        <li class="dropdown">

                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" >Events <span class="caret"></span>  </a>

                        <ul class="dropdown-menu">
                            <li>
                                <a href="{{ route('get:all:events')}}">List All</a>
                                <a href="{{ route('get:event:formAdd')}}">Add New</a>

                            </li>


                        </ul>


                       </li>     

            
                        <li class="dropdown">

                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" >Clubs <span class="caret"></span>  </a>

                        <ul class="dropdown-menu">
                            <li>
                                <a href="{{ route('get:club:data')}}">List All</a>
                                <a href="{{ route('get:club:create')}}">Add New</a>
                            </li>


                        </ul>


                       </li>  

                       <li class="dropdown">

                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" >News <span class="caret"></span>  </a>

                        <ul class="dropdown-menu">
                            <li>
                                <a href="{{ route('get:all:news')}}">List All</a>
                                <a href="{{ route('get:news:formAdd')}}">Add New</a>
                            </li>


                        </ul>


                       </li>     
    

                        <li class="dropdown">

                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" >Blog <span class="caret"></span>  </a>

                        <ul class="dropdown-menu">
                            <li>
                                <a href="{{ route('get:all:blogs')}}">List All</a>
                                <a href="{{ route('get:blog:formAdd')}}">Add New</a>
                            </li>


                        </ul>


                       </li>  
    
                        <li >

                        <a href="{{ route('get:rating:data')}}"  >Reviews  </a>

                        

                       </li>     





                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @guest
                            {{-- <li><a href="#">Login</a></li>
                            <li><a href="#">Register</a></li> --}}
                        @else
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true">
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>