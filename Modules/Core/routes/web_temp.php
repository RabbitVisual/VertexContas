<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Access: Auth, Verified, Role:User
|
*/

// Move Core routes to here or include them if necessary.
// For now, removing the redundant middleware call in CoreController or ensuring BaseController handles it is the fix.
// But to be safe, I will also explicitly register Core routes if they are not being picked up correctly,
// although Dynamic Loading should handle it.
// Wait, the user error was about CoreController middleware, which implies the route WAS hit and controller WAS instantiated.
