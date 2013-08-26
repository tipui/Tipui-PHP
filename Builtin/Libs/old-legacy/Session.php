<?php
/** Session Class
 *
 *   company: JapanCase - Digital Business
 *   autor: Daniel Omine
 *   email: danielomine@gmail.com
 *   website: www.japancase.com
 *   updated: 2010-03-25 00:49:00 - Daniel Omine
 *
 *   Methods
        Init
        Set
        Get
        Del
        Destroy
*/

class Session
{

    public  $data    = false;
    public  $exists  = false;

    function Init()
    {
        if( session_id() == '' )
        {
            session_start();
        }
    }

    function Set( $key, $value )
    {

        $_SESSION[ $key ] = $value;

        return null;
    
    }

    function Get( $key = false )
    {

        if( $key )
        {

            if( isset( $_SESSION[ $key ] ) )
            {

                $this -> data   = $_SESSION[ $key ];
                $this -> exists = true;

            }else{

                $this -> exists = false;
            }

        }else{
            // return entire array

            if( isset( $_SESSION ) )
            {
                $this -> data = $_SESSION;
                $this -> exists = true;
            }else{
                $this -> exists = false;
            }

        }

        return null;

    }

    function Del( $key = false )
    {

        //print_r( $_SESSION[$key] ); exit;
        if( $key )
        {
            if( isset( $_SESSION[ $key ] ) )
            {
                unset( $_SESSION[ $key ] );
            }

        }else{
            // unset entire array
            foreach( $_SESSION as $k => $v )
            {
                unset( $_SESSION[$k] );
            }

        }

        return null;

    }

    function Destroy()
    {
        if( session_id() != '' )
        {
            session_destroy();
        }
    }



}
?>