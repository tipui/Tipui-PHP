<?php
/** SwiftMailer Class
 *
 *   company: JapanCase - Digital Business
 *   autor: Daniel Omine
 *   email: danielomine@gmail.com
 *   website: www.japancase.com
 *   updated: 2010-05-12 19:39:00 - Daniel Omine
 *
 *   Methods
        Init
*/

class SwiftMailer
{

    function Init( $s = false )
    {

		$c = Config::SetMail( $s );
//echo time() . '<hr>';

		require( LIB_DIR . 'Swift-4.2.0' . DS . 'lib' . DS . 'swift_required.php' );
//$ar = new FileSystem; exit;
		if( $c['SMTP_LIB_auth'] )
		{
			$transport = Swift_SmtpTransport::newInstance( $c['SMTP_LIB_host'], $c['SMTP_LIB_port'], $c['SMTP_LIB_ssl'] )
			  ->setUsername( $c['SMTP_LIB_user'] )
			  ->setPassword( $c['SMTP_LIB_pass'] )
			  ;
		}else{
			//echo time(); exit;
			switch( $c['SMTP_LIB_TRANSPORT'] )
			{
				default:
				case 'PHP_mail':
					$transport = Swift_MailTransport::newInstance();
				break;
				case 'sendmail':
					//$c['SMTP_LIB_TRANSPORT'] = '/usr/sbin/sendmail -t -i -db -f no-reply [at} ' . DOMAIN;
					//$c['SMTP_LIB_TRANSPORT'] = '/usr/sbin/sendmail -t -i -db -f no-reply@' . DOMAIN; // mighty server
					$c['SMTP_LIB_TRANSPORT'] = '/usr/sbin/sendmail -t -i no-reply@' . DOMAIN;
					// -bs
					$transport = Swift_SendmailTransport::newInstance( $c['SMTP_LIB_TRANSPORT'] );
				break;
			}
		}

		return $transport;
    }

}
?>