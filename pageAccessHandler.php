<?php



    // No-login --> only non logged users can access

    // Login --> page for login and logout

    // Public --> page for everyone

    // UusJvalUs --> page for logged users

    // UusJval --> page for logged customers



    if($access == 'no-login' && $UusJvalUs == null && $UusJval == null) 

        require_once $page;

    else if($access == 'login')

    {

        if($UusJvalUs == null && $UusJval == null)

            require_once $page;

        else if($UusJvalUs == null && $UusJval != null)

        {

            if($page == 'Features/Pages/Login/login.php')

                require_once $page;

            else

                require_once 'Features/Pages/NotFound/notFound.php';

        }

        else if($UusJvalUs != null && $UusJval == null)

        {

            if($page == 'Features/Pages/UserLogin/userLogin.php')

                require_once $page;

            else

                require_once 'Features/Pages/NotFound/notFound.php';

        }

    }

    else if($access == 'public')

        require_once $page;

    else if($access == 'UusJval' && $UusJval != null)

        require_once $page;

    else if($access == 'UusJvalUs' && $UusJvalUs != null)

        require_once $page;

    else
        require_once "Features/Pages/NotFound/notFound.php";



?>