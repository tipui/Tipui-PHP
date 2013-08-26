<?php
/** DB MySQL Class
 *
 *   company: JapanCase - Digital Business
 *   autor: Daniel Omine
 *   email: danielomine@gmail.com
 *   website: www.japancase.com
 *   updated: 2013-03-03 21:10:00 - Daniel Omine
 *
 *   Methods
        Connect
        Close
        Error
        SelectDB
		SetCharset
        Query
		AffectedRows
		NumRows
        FetchArray
        SqlQueryJoin
		SintaxQuoteNames
		SintaxQuoteValues
		QuoteNames
        QueryExec
        FetchResult
		ExecQuery
        
        LastID
        Begin
        Back
        DateNow
		DateAdd
		Between
        QueryStart
        ParseValue
		DATE_FORMAT
        EscapeIn
        EscapeOut
		NewID
*/

class DBMySQL{

    private $conn        = false;
    public  $error       = false;
    private $query       = false;
    public  $Result      = false;
    public  $sql         = false;
    public  $dbInfo      = false;
    public  $debug       = false;
    public  $AutoClose   = false;
    
    private $option      = array();
    private $fields      = false;
    private $fields_case = false;
    private $tables      = false;
	private $join        = false;
    private $values      = false;
    private $cont        = false;
    public  $SqlQuery    = false;
    private $multiple    = false;
	public  $PrintQuery  = false;
	public  $QuoteNames  = true;


    function Reset()
    {
        $this -> error       = false;
        $this -> query       = false;
        $this -> Result      = false;
        $this -> sql         = false;

        $this -> option      = array();
        $this -> fields      = false;
        $this -> fields_case = false;
        $this -> tables      = false;
		$this -> join        = false;
        $this -> values      = false;
        $this -> cont        = false;
        $this -> SqlQuery    = false;
        $this -> multiple    = false;
		$this -> PrintQuery  = false;
		$this -> QuoteNames  = true;
    }

    function Connect( )
	{

        if( !$this -> conn )
        {

		
			$this -> conn = new mysqli( 
						$this -> dbInfo['dns']['hostspec']
						, $this -> dbInfo['dns']['username']
						, $this -> dbInfo['dns']['password']
						, $this -> dbInfo['dns']['database']
						, $this -> dbInfo['dns']['port']
						);

            if( $this -> conn -> connect_error )
            {
                $this -> error  = $this -> Error();
            }else{
				$this -> SetCharset();
			}
        
        }else{
            // already listening
        }

        return null;
    }

    function Close( ){
        
        if( $this -> conn )
        {
            mysqli_close( $this -> conn );
            $this -> conn = false;
			DB::Reset();
        }
        return null;
    
    }

    function Error(){
        return $this -> conn -> connect_errno . ':' . $this -> conn -> connect_error;
    }

	// deprecated
    function SelectDB( ){

        //mb_internal_encoding(CHARSET); 
        //mysql_set_charset(CHARSET,$this -> conn);
        if( $this -> dbInfo['system']['set_name'] )
        {
        mysql_query( "SET NAMES " . $this -> dbInfo['system']['set_name'], $this -> conn );
        }

        if( !@mysql_select_db( $this -> dbInfo['dns']['database'], $this -> conn ) ){
            $this -> error  = $this -> Error();
        }

        return null;
    
    }

    function SetCharset( ){

        if( !$this -> dbInfo['system']['set_name'] )
        {
			return false;
		}

		if (!$this -> conn -> set_charset( $this -> dbInfo['system']['set_name'] ))
		{
			$this -> error  = $this -> Error();
		}else{
			//printf("Current character set: %s\n", $this -> conn -> character_set_name() );
		}
	}

    function Query( )
	{
		// for debug purposes only.
		// print sql query and force php stop the script.
		if( $this -> PrintQuery )
		{
			echo 'DEBUG MySQL: ' . $this -> SqlQuery; exit;
		}
        if( !$this -> query = $this -> conn -> query( $this -> SqlQuery ) ){
            $this -> error  = $this -> Error();
        }
        
        return null;

    }
	
    function AffectedRows( ){
		return $this -> conn -> affected_rows;
		//return mysqli_affected_rows();
	}
	
    function NumRows( ){
		return $this -> query -> num_rows;
		//return mysqli_num_rows();
	}

    function FetchArray( ){

        $i = 0;
        while( $this -> Result[$i] = $this -> query -> fetch_array( MYSQL_ASSOC ) ){
            if( !$this -> Result[$i++] )
			{
                return $this -> error = $this -> Error();
                break;
            }
        }
		
		// remove empty row
		if( $this -> Result and is_array( $this -> Result ) )
		{
			unset( $this -> Result[ count( $this -> Result ) - 1 ] ); 
		}

		// clean result conection
		$this -> query -> free();
        //mysqli_free_result( $this -> query );

		// check if is valid resource.
		if( !$this -> Result or !is_array( $this -> Result ) or count( $this -> Result ) < 1 )
		{
            $this -> Result = false;
        }

        return null;

    }

    // method for friendly debug purposes only
    // need $this -> debug TRUE
    function SqlQueryJoin( ){
        if( is_array( $this -> SqlQuery ) )
        {
            $this -> SqlQuery = join( PHP_EOL, $this -> SqlQuery );
        }
    }
    
	function SintaxQuoteNames( $str )
	{
		return '`' . $str . '`';
	}
	function SintaxQuoteValues( $str )
	{
		return '\'' . $str . '\'';
	}

	// Apply SQL sintax especial quotes for table names and field names.
	// Not compatible with JOIN and WHERE sintaxes
    function QuoteNames( $a )
    {

		$a = ( is_array( $a ) )? join( PHP_EOL . ', ', $a ) : $a;

		if( !$this -> QuoteNames )
		{
			return $a;
		}

		$rs = $a;
        $comma = false;
        $alias = false;

        $a = trim( $a );
        $a = preg_replace('/\s\s+/', ' ', $a); // remove excess of spaces
        $a = str_ireplace( ' as ', ' ', $a );
        $a = str_replace( '`', '', $a );

        switch( TRUE )
        {
    		case ( strpos( $a, ',' ) ):
                $a = explode( ',', $a );
                $comma = true;
            break;
    		case ( strpos( $a, ' ' ) ):
                $a = explode( ' ', $a );
                $alias = true;
            break;
        }

        if( is_array( $a ) )
        {
            switch( TRUE )
            {
                case ( $comma ):
                    foreach( $a as $k => $v )
                    {
                		$v = trim( $v );
                		if( $p = strpos( strtolower( $v ), ' ' ) )
                		{
                            $pt = substr( $v, 0, $p );
                            if( !strpos( strtolower( $pt ), '.' ) )
                            {
                			    $pt  = $this -> SintaxQuoteNames( $pt );
                            }
                            $v = $pt . ' AS ' .  $this -> SintaxQuoteNames( substr( $v, $p+1 ) );
                		}else{
                            if( !strpos( strtolower( $v ), '.' ) )
                            {
                                $v = $this -> SintaxQuoteNames( $v );
                            }
                        }
                        $a[$k] = $v;
                    }
                    $t  = implode( ', ', $a );
                    $rs = $t;
                break;
                case ( $alias ):
                    $t = '';
                    foreach( $a as $k => $v )
                    {
                        $v = trim( $v );
                        if( ( $k % 2 ) != 0 )
                        {
                            $t .= ' AS ';
                        }
                        if( !strpos( strtolower( $v ), '.' ) )
                        {
                            $t .= $this -> SintaxQuoteNames( $v );
                        }else{
                            $t .= $v; 
                        }
                    }
                    $rs = $t;
                break;
            }
        }else{
			if( !strpos( strtolower( $a ), '.' ) )
			{
				$rs = $this -> SintaxQuoteNames( $a );
			}else{
				$rs = $a; 
			}
		}

        return $rs;
    }

    // ease access method for INSERT, REPLACE, UPDATE and DELETE
    function QueryExec( )
    {


        $this -> Connect( );
		
		//deprecated
        if( !$this -> error ){
            //$this -> SelectDB( );
        }

        $this -> option = array(
                        'INSERT'   => true,
                        'REPLACE'  => true,
                        'UPDATE'   => true,
                        'DELETE'   => true,
                        'SELECT'   => true
                        );

        if( !$this -> error 
            and isset( $this -> sql['option'] )
            and isset( $this -> option[ strtoupper( $this -> sql['option'] ) ] )
        ){

            if( !isset( $this -> sql['table'] ) ){
                $this -> error = 'QueryExec: invalid table';
            }

            if( !$this -> error ){

				if( isset( $this -> sql['where'] ) )
				{
					if( is_array( $this -> sql['where'] ) )
					{
						$this -> sql['where'] = implode( PHP_EOL . ' ', $this -> sql['where'] );
					}
				}
                switch( strtoupper( $this -> sql['option'] ) ){
                    case 'INSERT':
                    case 'REPLACE':

                        if( isset( $this -> sql['fields'] ) and is_array( $this -> sql['fields'] ) ){
                            $this -> fields = $this -> QuoteNames( $this -> sql['fields'] );
                        }
    
                        if( isset( $this -> sql['values'] ) and is_array( $this -> sql['values'] ) ){

                            // support for multiple INSERTS
                            reset( $this -> sql['values'] );
                            $ini = key( $this -> sql['values'] );
                            if( is_array( $this -> sql['values'][ $ini ] ) )
                            {

                                $i = 0;
                                foreach( $this -> sql['values'][ $ini ] as $k => $v )
                                {

                                    $this -> values .= ( $i > 0 ) ? ',' : PHP_EOL;
                                    $this -> values .= '(';
                                    $this -> values .= $v;

                                    foreach( $this -> sql['values'] as $key => $val )
                                    {

                                        if( $key != $ini )
                                        {
                                            $this -> values .=  ',';
                                            $this -> values .=  $val[$k];
                                        }

                                    }
                                    $this -> values .=  ')';
                                    $this -> values .=  PHP_EOL;

                                    $i++;

                                }

                                //print_r( $this -> sql['values'][ $ini ] ); exit;
                                $this -> multiple = true;

                            }else{

                                $this -> multiple = true;
                                $this -> values = ' (' . join( ', ', $this -> sql['values'] ) . ')';

                            }

                        }else{
                            $this -> error = 'QueryExec: invalid ' . $this -> sql['option'] . ' [values]';
                            break;
                        }
    
                        $this -> SqlQuery  = $this -> sql['option'];
                        $this -> SqlQuery .= ' INTO ';
                        $this -> SqlQuery .= $this -> QuoteNames( $this -> sql['table'] );

                        if( $this -> fields ){

                            if( count( $this -> sql['fields'] ) != count( $this -> sql['values'] ) ){
                                $this -> error = 'QueryExec: invalid ' . $this -> sql['option'] . ' values and fields doesnt match';
                                break;
                            }

                            $this -> SqlQuery .= ' (' . $this -> fields . ') ';

                        }

                        $this -> SqlQuery .= ' VALUES ';
                        $this -> SqlQuery .= $this -> values;
                        //echo $this -> SqlQuery; exit;

                    break;
                    case 'UPDATE':
    
                        if( isset( $this -> sql['values'] ) && is_array( $this -> sql['values'] ) ){
                            $this -> values = join( ', ', $this -> sql['values'] );                            
                        }else{
                            $this -> error = 'QueryExec: invalid ' . $this -> sql['option'] . ' [values]';
                            break;
                        }

                        $this -> SqlQuery[] = $this -> sql['option'];
                        $this -> SqlQuery[] = $this -> QuoteNames( $this -> sql['table'] );
                        $this -> SqlQuery[] = ' SET ';
                        $this -> SqlQuery[] = $this -> values;
                        if( isset( $this -> sql['where'] ) ){
                            $this -> SqlQuery[] = ' WHERE ' . $this -> sql['where'];
                        }
    
                    break;
                    case 'DELETE':

                        $this -> SqlQuery[] = $this -> sql['option'];
                        $this -> SqlQuery[] = ' FROM ';
                        $this -> SqlQuery[] = $this -> QuoteNames(  $this -> sql['table'] );
                        if( isset( $this -> sql['where'] ) ){
                            $this -> SqlQuery[] = ' WHERE ' . $this -> sql['where'];
                        }

                    break;


                    case 'SELECT':

                        if( isset( $this -> sql['table'] ) )
                        {
							$this -> tables = $this -> QuoteNames( $this -> sql['table'] );
                        }

                        if( isset( $this -> sql['fields'] ) )
                        {
							$this -> fields = $this -> QuoteNames( $this -> sql['fields'] );
                        }

                        if( isset( $this -> sql['fields_case'] ) and is_array( $this -> sql['fields_case'] ) ){
                            $this -> fields_case = implode( PHP_EOL, $this -> sql['fields_case'] );
                        }
						if( !empty( $this -> fields_case ) )
						{
							$this -> fields_case = PHP_EOL . ', ' . $this -> fields_case;
						}
						$this -> fields .= $this -> fields_case;

                        if( isset( $this -> sql['count'] ) )
                        {

                            $this -> count  = '(';
                            $this -> count .= ' SELECT ';
                            $this -> count .= 'COUNT(' . $this -> sql['count'] . ') ';
                            $this -> count .= ' FROM ';
                            $this -> count .= $this -> tables;

							if( isset( $this -> sql['join'] ) )
							{
								if( is_array( $this -> sql['join'] ) )
								{
									$this -> count .= PHP_EOL . join( PHP_EOL, $this -> sql['join'] );
								}else{
									$this -> count .= PHP_EOL . $this -> sql['join'];
								}
							}

                            if( isset( $this -> sql['where'] ) )
                            {
                                $this -> count .= ' WHERE ' . $this -> sql['where'];
                            }
                            $this -> count .= ' ) AS _total';

                            $this -> fields .= PHP_EOL . ', ' . $this -> count;

                        }


                        if( isset( $this -> sql['join'] ) )
                        {
                            if( is_array( $this -> sql['join'] ) )
                            {
                                $this -> join = PHP_EOL . join( PHP_EOL, $this -> sql['join'] );
                            }else{
                                $this -> join = PHP_EOL . $this -> sql['join'];
                            }
                        }


                        $this -> SqlQuery[] = 'SELECT ';
                        $this -> SqlQuery[] = $this -> fields;
                        $this -> SqlQuery[] = ' FROM ';
                        $this -> SqlQuery[] = $this -> tables;

						if( $this -> join )
						{
							$this -> SqlQuery[] = $this -> join;
						}

                        if( isset( $this -> sql['where'] ) )
                        {
                            $this -> SqlQuery[] = ' WHERE ' . $this -> sql['where'];
                        }

                        if( isset( $this -> sql['order'] ) )
                        {
                            $this -> SqlQuery[] = $this -> sql['order'];
                        }

                        if( isset( $this -> sql['limit'] ) )
                        {
                            $this -> SqlQuery[] = ' LIMIT ' . $this -> sql['limit'];
                        }

                    break;


                }

            }

        }else{
            if( !$this -> error )
            {
                $this -> error = 'QueryExec: invalid or empty [option]';
            }
        }

        /*
        $this -> SqlQueryJoin();
        echo $this -> SqlQuery . '<br>';
        echo time(); exit;
        */

        if( !$this -> error ){

            $this -> SqlQueryJoin();
            $this -> Query();

            if( !$this -> error )
            {
                if( strtoupper( $this -> sql['option'] ) == 'SELECT' )
                {
                    $this -> FetchArray();
                }
            }

        }
        
        if( $this -> AutoClose ){
        $this -> Close();
        }

        return null;

    }


    function FetchResult(){

        $this -> Connect();

		//deprecated
        if( !$this -> error ){
            //$this -> SelectDB();
        }

        if( !$this -> error ){
            $this -> ExecQuery( );
        }

        if( !$this -> error ){
            $this -> FetchArray();
        }

        if( $this -> AutoClose ){
            $this -> Close();
        }

        return null;
        
    }

    function ExecQuery(){

        $this -> Connect();

		// deprecated
        if( !$this -> error ){
            //$this -> SelectDB();
        }

        if( !$this -> error ){
			$this -> SqlQueryJoin();
            $this -> Query( );
        }

        if( $this -> AutoClose ){
            $this -> Close();
        }

        return null;
        
    }
   
    
    function LastId()
	{
		return $this -> conn -> insert_id;
        //return mysqli_insert_id(); 
    }

    /** deprecated
    function Beggin(){
        return $this -> Exec( 'BEGGIN WORK;' );
    }

    function Back(){
        return $this -> Exec( 'ROLLBACK;' );
    }
    */

    function DateNow(){

        if( GMT_hours == 0 and GMT_minute == 0 )
        {
            return "NOW()";
        }
        return "DATE_ADD( NOW(), INTERVAL '" . GMT_hours . " " . GMT_minute . "' HOUR_MINUTE)"; 

    }

    function DateAdd( $f = 'NOW()', $a, $v ){

        return "DATE_ADD( " . $f . ", INTERVAL '" . implode( ' ', $v ) . "' " . implode( '_', $a ) . ")"; 
    }

	function Between( $f, $c1, $c2 )
	{
		return $f . ' BETWEEN ' . $c1 . ' AND ' . $c2;
	}
    
    function QueryStart( $sql_c, $clause ){
        if( trim( strtoupper( $sql_c ) ) != trim( strtoupper( $clause ) ) ){
        return true;
        }else{
        return false;
        }
    }

    function ParseValue( $str = '', $type = false )
    {
        switch( $type )
        {
            default:
            case 'text':
                return "'" . $this -> EscapeIn( $str ) . "'";
            break;
            case '%like%':
                return "LIKE '%" . $this -> EscapeIn( $str ) . "%'";
            break;
            case 'like%':
                return "LIKE '" . $this -> EscapeIn( $str ) . "%'";
            break;
            case '%like':
                return "LIKE '%" . $this -> EscapeIn( $str ) . "'";
            break;
            case 'number':
                return Strings::NumbersOnly( $str );
            break;
            case 'datetime':
				return "'" . DateAndTime::Format( $str, 'datetime_array_to_string' ) . "'";
            break;
            case 'date':
				if( !is_array( $str ) and strpos( $str, '-' ) )
				{
					$str = explode( '-', substr( $str, 0, 10 ) );
				}else{
					if( isset( $str['Y'] ) )
					{
						$d[0] = $str['Y'];
						$d[1] = $str['m'];
						$d[2] = $str['d'];
					}else{
						$d = $str;
					}
				}
                return "'" . sprintf( '%04d', $str[0] ) . '-' . sprintf( '%02d', $str[1] ) . '-' . sprintf( '%02d', $str[2] ) . "'";
            break;
            case 'time':
                return "'" . sprintf( '%02d', $str[0] ) . ':' . sprintf( '%02d', $str[1] ) . ':' . sprintf( '%02d', $str[2] ) . "'";
            break;
			
			case 'timestamp':
				// $str must be timestamp value
                return "'" . date( 'Y-m-d H:i:s', $str ) . "'";
            break;
			
            case 'datetime_sqlserver':
                return "'" . str_replace( 'T', ' ', substr( $str, 0, 19 ) ) . "'";
            break;
            case 'boolean':
                $str == 'true' ? $str = 1 : $str = '0';
                return $str;
            break;
        }
    }

	function DATE_FORMAT( $f, $r = '%Y-%m-%d' )
	{
		return 'DATE_FORMAT(' . $this -> QuoteNames( $f ) . ',' . $this -> SintaxQuoteValues( $r ) . ' )';
	}

    function EscapeIn( $str ){
        //return str_replace( "'", "\'", $str );
        //return mysql_real_escape_string( $str );
        //return mysqli_real_escape_string( $str );
        return addslashes( $str );
    }
    function EscapeOut( $str ){
        return Strings::Escape( $str, 'quotes' );

    }
	
	function NewID( )
	{

		/*
		$id  = microtime();
		$a   = explode( ' ', $id );
		$id1 = base_convert( $a[0], 16, 36 );
		$id2 = base_convert( $a[1], 16, 36 );
		*/
		//echo '<br />id: ' . $id;
		//echo '<br />id1: ' . $id1;
		//echo '<br />id2: ' . $id2;
		//echo '<br />id: ' . base_convert( $id1, 36, 16 ) . base_convert( $id2, 36, 16 );
		//echo '<br />tiny: ' . $id1 . $id2;

		//return uniqid( time() );

		$id   = microtime();
		$a    = explode( ' ', $id );
		$a[0] = str_replace( '.', '', $a[0] );
		$id1  = Strings::alphaID( $a[0] );
		$id2  = Strings::alphaID( $a[1] );

		return $id1 . $id2;
	}


//mysql_client_encoding
    
}


?>