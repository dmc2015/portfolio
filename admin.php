<?php
require_once( dirname(__FILE__).'/form.lib.php' );

define( 'PHPFMG_USER', "mclamb.donald@gmail.com" ); // must be a email address. for sending password to you.
define( 'PHPFMG_PW', "a4b9bc" );

?>
<?php
/**
 * GNU Library or Lesser General Public License version 2.0 (LGPLv2)
*/

# main
# ------------------------------------------------------
error_reporting( E_ERROR ) ;
phpfmg_admin_main();
# ------------------------------------------------------




function phpfmg_admin_main(){
    $mod  = isset($_REQUEST['mod'])  ? $_REQUEST['mod']  : '';
    $func = isset($_REQUEST['func']) ? $_REQUEST['func'] : '';
    $function = "phpfmg_{$mod}_{$func}";
    if( !function_exists($function) ){
        phpfmg_admin_default();
        exit;
    };

    // no login required modules
    $public_modules   = false !== strpos('|captcha|', "|{$mod}|", "|ajax|");
    $public_functions = false !== strpos('|phpfmg_ajax_submit||phpfmg_mail_request_password||phpfmg_filman_download||phpfmg_image_processing||phpfmg_dd_lookup|', "|{$function}|") ;
    if( $public_modules || $public_functions ) {
        $function();
        exit;
    };

    return phpfmg_user_isLogin() ? $function() : phpfmg_admin_default();
}

function phpfmg_ajax_submit(){
    $phpfmg_send = phpfmg_sendmail( $GLOBALS['form_mail'] );
    $isHideForm  = isset($phpfmg_send['isHideForm']) ? $phpfmg_send['isHideForm'] : false;

    $response = array(
        'ok' => $isHideForm,
        'error_fields' => isset($phpfmg_send['error']) ? $phpfmg_send['error']['fields'] : '',
        'OneEntry' => isset($GLOBALS['OneEntry']) ? $GLOBALS['OneEntry'] : '',
    );

    @header("Content-Type:text/html; charset=$charset");
    echo "<html><body><script>
    var response = " . json_encode( $response ) . ";
    try{
        parent.fmgHandler.onResponse( response );
    }catch(E){};
    \n\n";
    echo "\n\n</script></body></html>";

}


function phpfmg_admin_default(){
    if( phpfmg_user_login() ){
        phpfmg_admin_panel();
    };
}



function phpfmg_admin_panel()
{
    phpfmg_admin_header();
    phpfmg_writable_check();
?>
<table cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td valign=top style="padding-left:280px;">

<style type="text/css">
    .fmg_title{
        font-size: 16px;
        font-weight: bold;
        padding: 10px;
    }

    .fmg_sep{
        width:32px;
    }

    .fmg_text{
        line-height: 150%;
        vertical-align: top;
        padding-left:28px;
    }

</style>

<script type="text/javascript">
    function deleteAll(n){
        if( confirm("Are you sure you want to delete?" ) ){
            location.href = "admin.php?mod=log&func=delete&file=" + n ;
        };
        return false ;
    }
</script>


<div class="fmg_title">
    1. Email Traffics
</div>
<div class="fmg_text">
    <a href="admin.php?mod=log&func=view&file=1">view</a> &nbsp;&nbsp;
    <a href="admin.php?mod=log&func=download&file=1">download</a> &nbsp;&nbsp;
    <?php
        if( file_exists(PHPFMG_EMAILS_LOGFILE) ){
            echo '<a href="#" onclick="return deleteAll(1);">delete all</a>';
        };
    ?>
</div>


<div class="fmg_title">
    2. Form Data
</div>
<div class="fmg_text">
    <a href="admin.php?mod=log&func=view&file=2">view</a> &nbsp;&nbsp;
    <a href="admin.php?mod=log&func=download&file=2">download</a> &nbsp;&nbsp;
    <?php
        if( file_exists(PHPFMG_SAVE_FILE) ){
            echo '<a href="#" onclick="return deleteAll(2);">delete all</a>';
        };
    ?>
</div>

<div class="fmg_title">
    3. Form Generator
</div>
<div class="fmg_text">
    <a href="http://www.formmail-maker.com/generator.php" onclick="document.frmFormMail.submit(); return false;" title="<?php echo htmlspecialchars(PHPFMG_SUBJECT);?>">Edit Form</a> &nbsp;&nbsp;
    <a href="http://www.formmail-maker.com/generator.php" >New Form</a>
</div>
    <form name="frmFormMail" action='http://www.formmail-maker.com/generator.php' method='post' enctype='multipart/form-data'>
    <input type="hidden" name="uuid" value="<?php echo PHPFMG_ID; ?>">
    <input type="hidden" name="external_ini" value="<?php echo function_exists('phpfmg_formini') ?  phpfmg_formini() : ""; ?>">
    </form>

		</td>
	</tr>
</table>

<?php
    phpfmg_admin_footer();
}



function phpfmg_admin_header( $title = '' ){
    header( "Content-Type: text/html; charset=" . PHPFMG_CHARSET );
?>
<html>
<head>
    <title><?php echo '' == $title ? '' : $title . ' | ' ; ?>PHP FormMail Admin Panel </title>
    <meta name="keywords" content="PHP FormMail Generator, PHP HTML form, send html email with attachment, PHP web form,  Free Form, Form Builder, Form Creator, phpFormMailGen, Customized Web Forms, phpFormMailGenerator,formmail.php, formmail.pl, formMail Generator, ASP Formmail, ASP form, PHP Form, Generator, phpFormGen, phpFormGenerator, anti-spam, web hosting">
    <meta name="description" content="PHP formMail Generator - A tool to ceate ready-to-use web forms in a flash. Validating form with CAPTCHA security image, send html email with attachments, send auto response email copy, log email traffics, save and download form data in Excel. ">
    <meta name="generator" content="PHP Mail Form Generator, phpfmg.sourceforge.net">

    <style type='text/css'>
    body, td, label, div, span{
        font-family : Verdana, Arial, Helvetica, sans-serif;
        font-size : 12px;
    }
    </style>
</head>
<body  marginheight="0" marginwidth="0" leftmargin="0" topmargin="0">

<table cellspacing=0 cellpadding=0 border=0 width="100%">
    <td nowrap align=center style="background-color:#024e7b;padding:10px;font-size:18px;color:#ffffff;font-weight:bold;width:250px;" >
        Form Admin Panel
    </td>
    <td style="padding-left:30px;background-color:#86BC1B;width:100%;font-weight:bold;" >
        &nbsp;
<?php
    if( phpfmg_user_isLogin() ){
        echo '<a href="admin.php" style="color:#ffffff;">Main Menu</a> &nbsp;&nbsp;' ;
        echo '<a href="admin.php?mod=user&func=logout" style="color:#ffffff;">Logout</a>' ;
    };
?>
    </td>
</table>

<div style="padding-top:28px;">

<?php

}


function phpfmg_admin_footer(){
?>

</div>

<div style="color:#cccccc;text-decoration:none;padding:18px;font-weight:bold;">
	:: <a href="http://phpfmg.sourceforge.net" target="_blank" title="Free Mailform Maker: Create read-to-use Web Forms in a flash. Including validating form with CAPTCHA security image, send html email with attachments, send auto response email copy, log email traffics, save and download form data in Excel. " style="color:#cccccc;font-weight:bold;text-decoration:none;">PHP FormMail Generator</a> ::
</div>

</body>
</html>
<?php
}


function phpfmg_image_processing(){
    $img = new phpfmgImage();
    $img->out_processing_gif();
}


# phpfmg module : captcha
# ------------------------------------------------------
function phpfmg_captcha_get(){
    $img = new phpfmgImage();
    $img->out();
    //$_SESSION[PHPFMG_ID.'fmgCaptchCode'] = $img->text ;
    $_SESSION[ phpfmg_captcha_name() ] = $img->text ;
}



function phpfmg_captcha_generate_images(){
    for( $i = 0; $i < 50; $i ++ ){
        $file = "$i.png";
        $img = new phpfmgImage();
        $img->out($file);
        $data = base64_encode( file_get_contents($file) );
        echo "'{$img->text}' => '{$data}',\n" ;
        unlink( $file );
    };
}


function phpfmg_dd_lookup(){
    $paraOk = ( isset($_REQUEST['n']) && isset($_REQUEST['lookup']) && isset($_REQUEST['field_name']) );
    if( !$paraOk )
        return;

    $base64 = phpfmg_dependent_dropdown_data();
    $data = @unserialize( base64_decode($base64) );
    if( !is_array($data) ){
        return ;
    };


    foreach( $data as $field ){
        if( $field['name'] == $_REQUEST['field_name'] ){
            $nColumn = intval($_REQUEST['n']);
            $lookup  = $_REQUEST['lookup']; // $lookup is an array
            $dd      = new DependantDropdown();
            echo $dd->lookupFieldColumn( $field, $nColumn, $lookup );
            return;
        };
    };

    return;
}


function phpfmg_filman_download(){
    if( !isset($_REQUEST['filelink']) )
        return ;

    $info =  @unserialize(base64_decode($_REQUEST['filelink']));
    if( !isset($info['recordID']) ){
        return ;
    };

    $file = PHPFMG_SAVE_ATTACHMENTS_DIR . $info['recordID'] . '-' . $info['filename'];
    phpfmg_util_download( $file, $info['filename'] );
}


class phpfmgDataManager
{
    var $dataFile = '';
    var $columns = '';
    var $records = '';

    function phpfmgDataManager(){
        $this->dataFile = PHPFMG_SAVE_FILE;
    }

    function parseFile(){
        $fp = @fopen($this->dataFile, 'rb');
        if( !$fp ) return false;

        $i = 0 ;
        $phpExitLine = 1; // first line is php code
        $colsLine = 2 ; // second line is column headers
        $this->columns = array();
        $this->records = array();
        $sep = chr(0x09);
        while( !feof($fp) ) {
            $line = fgets($fp);
            $line = trim($line);
            if( empty($line) ) continue;
            $line = $this->line2display($line);
            $i ++ ;
            switch( $i ){
                case $phpExitLine:
                    continue;
                    break;
                case $colsLine :
                    $this->columns = explode($sep,$line);
                    break;
                default:
                    $this->records[] = explode( $sep, phpfmg_data2record( $line, false ) );
            };
        };
        fclose ($fp);
    }

    function displayRecords(){
        $this->parseFile();
        echo "<table border=1 style='width=95%;border-collapse: collapse;border-color:#cccccc;' >";
        echo "<tr><td>&nbsp;</td><td><b>" . join( "</b></td><td>&nbsp;<b>", $this->columns ) . "</b></td></tr>\n";
        $i = 1;
        foreach( $this->records as $r ){
            echo "<tr><td align=right>{$i}&nbsp;</td><td>" . join( "</td><td>&nbsp;", $r ) . "</td></tr>\n";
            $i++;
        };
        echo "</table>\n";
    }

    function line2display( $line ){
        $line = str_replace( array('"' . chr(0x09) . '"', '""'),  array(chr(0x09),'"'),  $line );
        $line = substr( $line, 1, -1 ); // chop first " and last "
        return $line;
    }

}
# end of class



# ------------------------------------------------------
class phpfmgImage
{
    var $im = null;
    var $width = 73 ;
    var $height = 33 ;
    var $text = '' ;
    var $line_distance = 8;
    var $text_len = 4 ;

    function phpfmgImage( $text = '', $len = 4 ){
        $this->text_len = $len ;
        $this->text = '' == $text ? $this->uniqid( $this->text_len ) : $text ;
        $this->text = strtoupper( substr( $this->text, 0, $this->text_len ) );
    }

    function create(){
        $this->im = imagecreate( $this->width, $this->height );
        $bgcolor   = imagecolorallocate($this->im, 255, 255, 255);
        $textcolor = imagecolorallocate($this->im, 0, 0, 0);
        $this->drawLines();
        imagestring($this->im, 5, 20, 9, $this->text, $textcolor);
    }

    function drawLines(){
        $linecolor = imagecolorallocate($this->im, 210, 210, 210);

        //vertical lines
        for($x = 0; $x < $this->width; $x += $this->line_distance) {
          imageline($this->im, $x, 0, $x, $this->height, $linecolor);
        };

        //horizontal lines
        for($y = 0; $y < $this->height; $y += $this->line_distance) {
          imageline($this->im, 0, $y, $this->width, $y, $linecolor);
        };
    }

    function out( $filename = '' ){
        if( function_exists('imageline') ){
            $this->create();
            if( '' == $filename ) header("Content-type: image/png");
            ( '' == $filename ) ? imagepng( $this->im ) : imagepng( $this->im, $filename );
            imagedestroy( $this->im );
        }else{
            $this->out_predefined_image();
        };
    }

    function uniqid( $len = 0 ){
        $md5 = md5( uniqid(rand()) );
        return $len > 0 ? substr($md5,0,$len) : $md5 ;
    }

    function out_predefined_image(){
        header("Content-type: image/png");
        $data = $this->getImage();
        echo base64_decode($data);
    }

    // Use predefined captcha random images if web server doens't have GD graphics library installed
    function getImage(){
        $images = array(
			'7D19' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcUlEQVR4nGNYhQEaGAYTpIn7QkNFQximMEx1QBZtFWllCGEICEAVa3QMYXQQQRabItLoMAUuBnFT1LSVWdNWRYUhuQ+oAqiOYSqyXtYGsFgDspgIRAzFjoAGoFumoLoloEE0hDHUAdXNAxR+VIRY3AcA5qnMM7Kbq60AAAAASUVORK5CYII=',
			'01E3' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAX0lEQVR4nGNYhQEaGAYTpIn7GB0YAlhDHUIdkMRYAxgDWIEyAUhiIlNYgWJAGkksoJUBLBaA5L6opUAUumppFpL70NShiImg2IEpxhrAgOEWRgfWUHQ3D1T4URFicR8AnkzJW3oxVZoAAAAASUVORK5CYII=',
			'354A' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcUlEQVR4nGNYhQEaGAYTpIn7RANEQxkaHVqRxQKmiDQwtDpMdUBW2QoUm+oQEIAsNkUkhCHQ0UEEyX0ro6YuXZmZmTUN2X1TGBpdG+HqoOYBxUIDQ0NQ7Wh0QFMXMIUVqBJVTDSAMQRdbKDCj4oQi/sAnlfMaADEuDIAAAAASUVORK5CYII=',
			'88E0' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAWUlEQVR4nGNYhQEaGAYTpIn7WAMYQ1hDHVqRxUSmsLayNjBMdUASC2gVaXRtYAgIwFDH6CCC5L6lUSvDloauzJqG5D40dUjmYRPDZgeqW7C5eaDCj4oQi/sAqO/L0DTovWIAAAAASUVORK5CYII=',
			'11B1' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXElEQVR4nGNYhQEaGAYTpIn7GB0YAlhDGVqRxVgdGANYGx2mIouJOrAGsDYEhGLobXSA6QU7aWXWqqiloauWIrsPTR1CrCGAODE0vaIhrKFAN4cGDILwoyLE4j4AcEXHthOsNNoAAAAASUVORK5CYII=',
			'331B' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZElEQVR4nGNYhQEaGAYTpIn7RANYQximMIY6IIkFTBFpZQhhdAhAVtnK0OgIFBNBFpsCFJ0CVwd20sqoVWGrpq0MzUJ2H6o6uHkOU9DMwyIGdguaXpCbGUMdUdw8UOFHRYjFfQAPx8qW//yLQwAAAABJRU5ErkJggg==',
			'1170' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZ0lEQVR4nGNYhQEaGAYTpIn7GB0YAlhDA1qRxVgdGAMYGgKmOiCJiTqwgsQCAtD0MjQ6OogguW9l1qqoVUtXZk1Dch9Y3RRGmDqEWACmGAij28HawIDqlhDWUKAYipsHKvyoCLG4DwDZ+sbwZ8PhLwAAAABJRU5ErkJggg==',
			'0485' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdElEQVR4nGNYhQEaGAYTpIn7GB0YWhlCGUMDkMRYAximMjo6OiCrE5nCEMraEIgiFtDK6ApU5+qA5L6opUuXrgpdGRWF5L6AVpFWRkeHBhEUvaKhrg0BKGJAO1pBdoigugWkNwDZfRA3M0x1GAThR0WIxX0AdMzKQ/RbxG4AAAAASUVORK5CYII=',
			'C259' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAd0lEQVR4nGNYhQEaGAYTpIn7WEMYQ1hDHaY6IImJtLK2sjYwBAQgiQU0ijS6NjA6iCCLNTA0uk6Fi4GdFLVq1dKlmVlRYUjuA6qbAiSnoukNAJEoYo2MDqwNASh2AN3SwOjogOIW1hDRUIdQBhQ3D1T4URFicR8AM47MNAVa3N8AAAAASUVORK5CYII=',
			'C90B' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpIn7WEMYQximMIY6IImJtLK2MoQyOgQgiQU0ijQ6Ojo6iCCLNYg0ujYEwtSBnRS1aunS1FWRoVlI7gtoYAxEUgcVYwDrRTGvkQXDDmxuwebmgQo/KkIs7gMA8DHL+izU43EAAAAASUVORK5CYII=',
			'A86E' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYUlEQVR4nGNYhQEaGAYTpIn7GB0YQxhCGUMDkMRYA1hbGR0dHZDViUwRaXRtQBULaGVtZQWagOy+qKUrw5ZOXRmaheQ+sDo080JDQeYFopmHTQzTLQGtmG4eqPCjIsTiPgCykcqIpWPKpQAAAABJRU5ErkJggg==',
			'8028' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcUlEQVR4nGNYhQEaGAYTpIn7WAMYAhhCGaY6IImJTGEMYXR0CAhAEgtoZW1lbQh0EEFRJ9Lo0BAAUwd20tKoaSuzVmZNzUJyH1hdKwOaeUCxKYwo5oHsYAhgRLMD6BYHVL0gN7OGBqC4eaDCj4oQi/sAkYPLpO4zPuYAAAAASUVORK5CYII=',
			'BBE7' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAWklEQVR4nGNYhQEaGAYTpIn7QgNEQ1hDHUNDkMQCpoi0sgJpEWSxVpFGV3QxqLoAJPeFRk0NWxq6amUWkvug6loZMM2bgkUsgAHDDkYHLG5GERuo8KMixOI+AI6ozQ5DwolxAAAAAElFTkSuQmCC',
			'A0D5' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbUlEQVR4nGNYhQEaGAYTpIn7GB0YAlhDGUMDkMRYAxhDWBsdHZDViUxhbWVtCEQRC2gVaXRtCHR1QHJf1NJpK1NXRUZFIbkPoi6gQQRJb2goplhAK8QOVDGQWxwCAlDEQG5mmOowCMKPihCL+wAeY8ycUAszXwAAAABJRU5ErkJggg==',
			'54A6' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdUlEQVR4nGNYhQEaGAYTpIn7QkMYWhmmMEx1QBILaGCYyhDKEBCAKhbK6OjoIIAkFhjA6MraEOiA7L6waUuXLl0VmZqF7L5WkVagOhTzGFpFQ11DAx1EkO1oZQCpQxETmQISC0DRyxoAFkNx80CFHxUhFvcBABp2zEoK/9l6AAAAAElFTkSuQmCC',
			'E427' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7QkMYWhlCGUNDkMQCGhimMjo6NIigioWyAklUMUZXEBmA5L7QqKVLV63MWpmF5D6grlYGEETRKxrqMIVhCqoYUE0AEKKJMTowOqC7mTU0EEVsoMKPihCL+wBCF8v69pGC4QAAAABJRU5ErkJggg==',
			'7EF5' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYklEQVR4nGNYhQEaGAYTpIn7QkNFQ1lDA0MDkEVbRRpYGxgdGAiJTQGLuToguy9qatjS0JVRUUjuY3QAqWNoEEHSy9qAKSbSALEDWSwAoi4gAEUM6OYGhqkOgyD8qAixuA8AWcrKJWvzJ44AAAAASUVORK5CYII=',
			'E88C' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAVklEQVR4nGNYhQEaGAYTpIn7QkMYQxhCGaYGIIkFNLC2Mjo6BIigiIk0ujYEOrBgqHN0QHZfaNTKsFWhK7OQ3YemDsU8bGKYdqC6BZubByr8qAixuA8AAUvMGHXyj+wAAAAASUVORK5CYII=',
			'3743' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdElEQVR4nGNYhQEaGAYTpIn7RANEQx0aHUIdkMQCpjA0OrQ6OgQgq2wFik11aBBBFpsCFA10aAhAct/KqFXTVmZmLc1Cdt8UhgDWRrg6qHmMDqyhAajmtbI2AG1BEQuYAuQ1orpFNAAkhurmgQo/KkIs7gMAGQfNmGvlfvAAAAAASUVORK5CYII=',
			'4C0E' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAX0lEQVR4nGNYhQEaGAYTpI37pjCGgnAAslgIa6NDKKMDsjrGEJEGR0dHFDHWKSINrA2BMDGwk6ZNm7Zq6arI0Cwk9wWgqgPD0FBMMYYpmHYwTMF0C1Y3D1T4UQ9icR8AprzKQojG8VUAAAAASUVORK5CYII=',
			'2040' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAc0lEQVR4nGNYhQEaGAYTpIn7WAMYAhgaHVqRxUSmMIYwtDpMdUASC2hlbWWY6hAQgKy7VaTRIdDRQQTZfdOmrczMzMyahuy+AJFG10a4OjBkdACKhQaiiLE2AO1oRLVDpAHolkZUt4SGYrp5oMKPihCL+wATXMwfnPOXdQAAAABJRU5ErkJggg==',
			'6977' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcElEQVR4nGNYhQEaGAYTpIn7WAMYQ1hDA0NDkMREprC2MjQENIggiQW0iDQ6oIs1AMXAogj3RUYtXZq1dNXKLCT3hUxhDHSYwtCKbG9AK0OjQwDDFFQxlkZHB4YABjS3sDYwOmC4GU1soMKPihCL+wA9BMyLKZcK1AAAAABJRU5ErkJggg==',
			'C856' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcklEQVR4nGNYhQEaGAYTpIn7WEMYQ1hDHaY6IImJtLK2sjYwBAQgiQU0ijS6NjA6CCCLNQDVTWV0QHZf1KqVYUszM1OzkNwHUsfQEIhqXoNIo0NDoIMIhh2oYiC3MDo6oOgFuZkhlAHFzQMVflSEWNwHABbQzCzSrF9nAAAAAElFTkSuQmCC',
			'4C6C' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpI37pjCGMoQyTA1AFgthbXR0dAgQQRJjDBFpcG1wdGBBEmOdItLA2sDogOy+adOmrVo6dWUWsvsCQOqABiLbGxoK0hvogOoWkB2BKHYwTMF0C1Y3D1T4UQ9icR8AGXXLccKNVpMAAAAASUVORK5CYII=',
			'8FBA' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYElEQVR4nGNYhQEaGAYTpIn7WANEQ11DGVqRxUSmiDSwNjpMdUASC2gFijUEBARgqHN0EEFy39KoqWFLQ1dmTUNyH5o6JPMCQ0MwxVDUYdPLGgAUC2VEERuo8KMixOI+AMw1zIOCrVutAAAAAElFTkSuQmCC',
			'5073' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdUlEQVR4nM2QsQ2AMAwEncIbZCCP8EgJQ2QKU2QDlA0oYEoClS0oQeCXXJys18m0XUbpT3nFLycCZ2QxDBoS6SBwjOuxo2ED4iSTKIzf2Npalm0p1q/2u5nU9p0M5PpQuQbxLM4hsQbnwujOSs75q/89mBu/HVrbzMPN6mgqAAAAAElFTkSuQmCC',
			'1EC8' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAW0lEQVR4nGNYhQEaGAYTpIn7GB1EQxlCHaY6IImxOogAxQMCApDERIFirA2CQBJZL0iMAaYO7KSVWVPDlq5aNTULyX1o6pDEGLGYh2kHhltCMN08UOFHRYjFfQD4D8jYBUOsnwAAAABJRU5ErkJggg==',
			'74DA' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbElEQVR4nGNYhQEaGAYTpIn7QkMZWlmBGEW0lWEqa6PDVAdUsVDWhoCAAGSxKYyurA2BDiLI7otaunTpqsisaUjuY3QQaUVSB4asDaKhrg2BoSFIYkB5DHUBILFGR0yxUEYUsYEKPypCLO4DAKi6y8CsG8p+AAAAAElFTkSuQmCC',
			'0319' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcUlEQVR4nGNYhQEaGAYTpIn7GB1YQximMEx1QBJjDRBpZQhhCAhAEhOZwtDoGMLoIIIkFtDK0MowBS4GdlLU0lVhq6atigpDch9EHcNUNL2NDlOA5qLZARRDsQPslimobgG5mTHUAcXNAxV+VIRY3AcAZMLK7IF6BTYAAAAASUVORK5CYII=',
			'3D88' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAWklEQVR4nGNYhQEaGAYTpIn7RANEQxhCGaY6IIkFTBFpZXR0CAhAVtkq0ujaEOgggiw2RaTREaEO7KSVUdNWZoWumpqF7D5UdbjNwyKGzS3Y3DxQ4UdFiMV9AOGIzMXNokGlAAAAAElFTkSuQmCC',
			'E1A1' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXElEQVR4nGNYhQEaGAYTpIn7QkMYAhimMLQiiwU0MAYwhDJMRRVjDWB0dAhFFWMIYG0IgOkFOyk0alXUUhBCch+aOoRYKBYxbOrQxEJDWEOBYqEBgyD8qAixuA8AS2TLw63RsPcAAAAASUVORK5CYII=',
			'C527' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdklEQVR4nM2QwQ2AMAhFP4dugPvgBpi0F0dwCnroBjqCBzuleivRo0b5CYdHCC+gXsrwp7ziF2KXkCjFhnFho16MG6aZLZh6ZhzPro3fWJe1btM2NX7HPEtBgds92IwZ/kYWhcK5hEJC4p0phjQ49tX/HsyN3w4egcvcUt73OAAAAABJRU5ErkJggg==',
			'1021' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpIn7GB0YAhhCGVqRxVgdGEMYHR2mIouJOrC2sjYEhKLqFWl0aAiA6QU7aWXWtJVZK7OWIrsPrK4V1Q6w2BR0MdZWoGvQxIBucUAVEw1hCGANDQgNGAThR0WIxX0ARsfITL1QK/8AAAAASUVORK5CYII=',
			'C84C' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7WEMYQxgaHaYGIImJtLK2MrQ6BIggiQU0igBVOTqwIIs1ANUFOjoguy9q1cqwlZmZWcjuA6ljbYSrg4qJNLqGBqKKgexoRLUD7JZGVLdgc/NAhR8VIRb3AQCz3Myikm1TewAAAABJRU5ErkJggg==',
			'B0C8' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYElEQVR4nGNYhQEaGAYTpIn7QgMYAhhCHaY6IIkFTGEMYXQICAhAFmtlbWVtEHQQQVEn0ujawABTB3ZSaNS0lamrVk3NQnIfmjqoeSAxRlTzsNqB6RZsbh6o8KMixOI+AKIszVXWbuunAAAAAElFTkSuQmCC',
			'9E1D' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZklEQVR4nGNYhQEaGAYTpIn7WANEQxmmMIY6IImJTBFpYAhhdAhAEgtoFWlgBIqJoIkB9cLEwE6aNnVq2KppK7OmIbmP1RVFHQS2YooJYBEDu2UKqltAbmYMdURx80CFHxUhFvcBAEm9yeCdbk4wAAAAAElFTkSuQmCC',
			'8D73' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbUlEQVR4nGNYhQEaGAYTpIn7WANEQ1hDA0IdkMREpoi0MjQEOgQgiQW0ijQ6NAQ0iKCqa3QAiyLctzRq2sqspauWZiG5D6xuCkMDhnkBDCjmgcQcHRjQ7WhlbWBEcQvYzQ0MKG4eqPCjIsTiPgDrKM4pIK2ZKgAAAABJRU5ErkJggg==',
			'9CB3' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYUlEQVR4nGNYhQEaGAYTpIn7WAMYQ1lDGUIdkMREprA2ujY6OgQgiQW0ijS4NgQ0iKCJsTY6NAQguW/a1GmrloauWpqF5D5WVxR1EAjSi2aeABY7sLkFm5sHKvyoCLG4DwCHo84AXEBwRQAAAABJRU5ErkJggg==',
			'AED5' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZ0lEQVR4nGNYhQEaGAYTpIn7GB1EQ1lDGUMDkMRYA0QaWBsdHZDViUwBijUEoogFtILFXB2Q3Be1dGrY0lWRUVFI7oOoC2gQQdIbGoopBjXPAUOs0SEgAEUM5GaGqQ6DIPyoCLG4DwALwcyL01sDCAAAAABJRU5ErkJggg==',
			'BF44' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYUlEQVR4nGNYhQEaGAYTpIn7QgNEQx0aHRoCkMQCpog0MLQ6NKKItQLFpjq0YqgLdJgSgOS+0KipYSszs6KikNwHUsfa6OiAbh5raGBoCLod2NyCJhYagCk2UOFHRYjFfQBlxdBcsmT79wAAAABJRU5ErkJggg==',
			'0587' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdklEQVR4nM2QsRGAMAhFocgGuE9S2FMkjSM4BRbZII6Qwkxp0sFpqaf87h3/eAe0ywj8Ka/4oZ8SJExRMcckGLyQYlRInLBhnCmOPVZ+S91rS+1YlR9n2ELwGUwXtlm4gL0xGINxcRl72Tpj7M6GffW/B3PjdwIaIMspXiMoiwAAAABJRU5ErkJggg==',
			'2A74' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAc0lEQVR4nGNYhQEaGAYTpIn7WAMYAlhDAxoCkMREpjCGMDQENCKLBbSytoJIZDGGVpFGh0aHKQHI7ps2bWXW0lVRUcjuCwCqm8LogKyX0UE01CGAMTQE2S0NIo2ODgyobgGKuTagioWGYooNVPhREWJxHwCLMM4jLyQywwAAAABJRU5ErkJggg==',
			'D297' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcElEQVR4nGNYhQEaGAYTpIn7QgMYQxhCGUNDkMQCprC2Mjo6NIggi7WKNLo2BKCJMYDFApDcF7V01dKVmVErs5DcB1Q3hSEESKLqDQDZhCrG6MDYEBDAgOqWBkZHRwdUN4uGOoQyoogNVPhREWJxHwDrFc1G1KislQAAAABJRU5ErkJggg==',
			'2CBE' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAY0lEQVR4nGNYhQEaGAYTpIn7WAMYQ1lDGUMDkMREprA2ujY6OiCrC2gVaXBtCEQRYwCKsSLUQdw0bdqqpaErQ7OQ3ReAog4MGR2AYmjmsTZg2gHSie6W0FBMNw9U+FERYnEfAHxwytI8SPNCAAAAAElFTkSuQmCC',
			'4D95' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcElEQVR4nGNYhQEaGAYTpI37poiGMIQyhgYgi4WItDI6Ojogq2MMEWl0bQhEEWOdAhZzdUBy37Rp01ZmZkZGRSG5LwCoziEkoEEESW9oKFCsAVWMAajOEWgHmhjQLQ4BKO4Du5lhqsNgCD/qQSzuAwC96cwfO9x61AAAAABJRU5ErkJggg==',
			'5579' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdElEQVR4nGNYhQEaGAYTpIn7QkNEQ1lDA6Y6IIkFNIiAyIAADLFABxEkscAAkRCGRkeYGNhJYdOmLl21dFVUGLL7WhkaHaYwTEXWCxYLYGhAFgtoFQGaxoBih8gU1lbWBgYUt7AGMIYAxVDcPFDhR0WIxX0A2xrMb++lTLsAAAAASUVORK5CYII=',
			'D4BB' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYUlEQVR4nGNYhQEaGAYTpIn7QgMYWllDGUMdkMQCpjBMZW10dAhAFmtlCGVtCHQQQRFjdEVSB3ZS1FIgCF0ZmoXkvoBWkVZM80RDXTHMA7oFXWwKA4ZebG4eqPCjIsTiPgCzws1yZoZYFgAAAABJRU5ErkJggg==',
			'097D' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdUlEQVR4nGNYhQEaGAYTpIn7GB0YQ1hDA0MdkMRYA1hbGRoCHQKQxESmiDQ6AMVEkMQCWoFijY4wMbCTopYuXZq1dGXWNCT3BbQyBjpMYUTTy9DoEIAqJjKFBWgaqhjILaxAVyK7BezmBkYUNw9U+FERYnEfAPiEyviN7y5mAAAAAElFTkSuQmCC',
			'A768' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcUlEQVR4nGNYhQEaGAYTpIn7GB1EQx1CGaY6IImxBjA0Ojo6BAQgiYlMYWh0bXB0EEESC2hlaGVtYICpAzspaumqaUunrpqaheQ+oLoAVjTzQkMZHVgbAtHMY23AFBNpYETTCxJjQHPzQIUfFSEW9wEAqpnMvv7j8fgAAAAASUVORK5CYII=',
			'FE91' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAWklEQVR4nGNYhQEaGAYTpIn7QkNFQxlCGVqRxQIaRBoYHR2moouxNgSEYhGD6QU7KTRqatjKzKilyO4DqWMICcCwg6EBU4wRm5ijA5oY2M2hAYMg/KgIsbgPAAPEzPQ++4JvAAAAAElFTkSuQmCC',
			'49F7' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpI37pjCGsIYGhoYgi4WwtrICaREkMcYQkUZXNDHWKRCxACT3TZu2dGlq6KqVWUjuC5jCGAhU14psb2goA0jvFFS3sIDEAlDFQG5hdMBwM7rYQIUf9SAW9wEAOGzLN8SYycMAAAAASUVORK5CYII=',
			'ED5C' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZ0lEQVR4nGNYhQEaGAYTpIn7QkNEQ1hDHaYGIIkFNIi0sjYwBIigijW6NjA6sKCLTWV0QHZfaNS0lamZmVnI7gOpc2gIdGBA04tNzBUohmZHK6OjA4pbQG5mCGVAcfNAhR8VIRb3AQBVoc0cD6PLFwAAAABJRU5ErkJggg==',
			'D2FB' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZklEQVR4nGNYhQEaGAYTpIn7QgMYQ1hDA0MdkMQCprC2sjYwOgQgi7WKNLoCxURQxBjAYgFI7otaumrp0tCVoVlI7gOqm4JpHkMAK4Z5jA4YYkCd6HpDA0RDgfaiuHmgwo+KEIv7AGgvzEAdjsBZAAAAAElFTkSuQmCC',
			'6D0E' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAX0lEQVR4nGNYhQEaGAYTpIn7WANEQximMIYGIImJTBFpZQhldEBWF9Ai0ujo6Igq1iDS6NoQCBMDOykyatrK1FWRoVlI7guZgqIOorcVuxi6Hdjcgs3NAxV+VIRY3AcA39LLKXCTJskAAAAASUVORK5CYII=',
			'F767' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpIn7QkNFQx1CGUNDkMQCGhgaHR0dGkTQxFwbMMRaWSE03H2hUaumLZ26amUWkvuA8gGsjg6tDCh6GR1YGwKmoIqxAmFAAKqYSAMj0DHoYgyhjChiAxV+VIRY3AcAlVLM/zQ2ucwAAAAASUVORK5CYII=',
			'7AB9' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcklEQVR4nGNYhQEaGAYTpIn7QkMZAlhDGaY6IIu2MoawNjoEBKCIsbayNgQ6iCCLTRFpdG10hIlB3BQ1bWVq6KqoMCT3MTqA1DlMRdbL2iAa6toQ0IAsJtIAVNcQgGJHQANYL4pbwGLobh6g8KMixOI+AAu/zVYpE33xAAAAAElFTkSuQmCC',
			'5BF7' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZ0lEQVR4nGNYhQEaGAYTpIn7QkNEQ1hDA0NDkMQCGkRaWYG0CKpYoyuaWGAARF0AkvvCpk0NWxq6amUWsvtawepaUWxuBZs3BVksACIWgCwmMgWkl9EBWYw1AOhmNLGBCj8qQizuAwAUAMuzKU3qHgAAAABJRU5ErkJggg==',
			'8CEF' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAWklEQVR4nGNYhQEaGAYTpIn7WAMYQ1lDHUNDkMREprA2ujYwOiCrC2gVaUAXE5ki0sCKEAM7aWnUtFVLQ1eGZiG5D00d3DxsYph2YLoF6mYUsYEKPypCLO4DAGyAydqqjvIzAAAAAElFTkSuQmCC',
			'49F9' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAb0lEQVR4nGNYhQEaGAYTpI37pjCGsIYGTHVAFgthbWVtYAgIQBJjDBFpdG1gdBBBEmOdgiIGdtK0aUuXpoauigpDcl/AFMZA1waGqch6Q0MZgHoZGkRQ3MICEnNAFcN0C9jNQPNQ3DxQ4Uc9iMV9AB0Gy2zCeG4EAAAAAElFTkSuQmCC',
			'EE04' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAW0lEQVR4nGNYhQEaGAYTpIn7QkNEQxmmMDQEIIkFNIg0MIQyNKKLMTo6tKKLsTYETAlAcl9o1NSwpauioqKQ3AdRF+iAqTcwNATTDmxuQRHD5uaBCj8qQizuAwAHbc5+bclpfgAAAABJRU5ErkJggg==',
			'12AE' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbElEQVR4nGNYhQEaGAYTpIn7GB0YQximMIYGIImxOrC2MoQyOiCrE3UQaXR0dHRA1cvQ6NoQCBMDO2ll1qqlS1dFhmYhuQ+obgorQh1MLIA1FF2M0QFTHWsDuphoiGgo0F4UNw9U+FERYnEfAFN4x7elE3nHAAAAAElFTkSuQmCC',
			'4712' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAb0lEQVR4nM2Quw3AIAwFH0U2IPuYDVzgJtNAkQ0chmDK4M75lIkETzQnf05Gf7yCmfKPn65CioM8y6iUwexYGCzlQNGxRbFDUaLza61b+ub8WMGjrvodIoFg/ReXZXyb6Fk0xncWJEme4X7f5cXvBHqYy52XF0ukAAAAAElFTkSuQmCC',
			'2E7A' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbUlEQVR4nGNYhQEaGAYTpIn7WANEQ1lDA1qRxUSmiADJgKkOSGIBrWCxgABk3SCxRkcHEWT3TZsatmrpyqxpyO4LAKqYwghTB4ZgXgBjaAiyWxpEgOKo6kSAkLUBVSw0FOhmNLGBCj8qQizuAwCa+MqAWQKHCgAAAABJRU5ErkJggg==',
			'F377' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYElEQVR4nGNYhQEaGAYTpIn7QkNZQ1hDA0NDkMQCGkRawSSKGEOjA6ZYK0QU4b7QqFVhq5auWpmF5D6wuilAjG5eAFAUTczRgSGAAc0trA2MDqhiQDejiQ1U+FERYnEfAOzozSguQjPXAAAAAElFTkSuQmCC',
			'AECA' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaUlEQVR4nGNYhQEaGAYTpIn7GB1EQxlCHVqRxVgDRIDiAVMdkMREpog0sDYIBAQgiQW0gsQYHUSQ3Be1dGrY0lUrs6YhuQ9NHRiGhoLFQkMwzBNEUQcSY3QIRBMDudkRRWygwo+KEIv7AB+sy3AThDdYAAAAAElFTkSuQmCC',
			'D9C8' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYklEQVR4nGNYhQEaGAYTpIn7QgMYQxhCHaY6IIkFTGFtZXQICAhAFmsVaXRtEHQQwRBjgKkDOylq6dKlqatWTc1Ccl9AK2MgkjqoGANQLyOaeSyYdmBxCzY3D1T4URFicR8AxXzONQAcvqgAAAAASUVORK5CYII=',
			'E15F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYElEQVR4nGNYhQEaGAYTpIn7QkMYAlhDHUNDkMQCGhgDWBsYHRhQxFixiAH1ToWLgZ0UGrUqamlmZmgWkvtA6hgaAjH0YhNjxSLG6OiIIhYawhrKEIrqloEKPypCLO4DAH/myFJW2KJ4AAAAAElFTkSuQmCC',
			'2C50' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcklEQVR4nGNYhQEaGAYTpIn7WAMYQ1lDHVqRxUSmsDa6NjBMdUASC2gVaQCKBQQg6waKsU5ldBBBdt+0aauWZmZmTUN2XwBIRSBMHRhCdKGKsTaA7AhAsQNoQ6OjowOKW0JDGUMZQhlQ3DxQ4UdFiMV9APxQzAa8dPH2AAAAAElFTkSuQmCC',
			'4F23' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcElEQVR4nGNYhQEaGAYTpI37poiGOoQyhDogi4WINDA6OjoEIIkxAsVYGwIaRJDEWKeAeAENAUjumzZtatiqlVlLs5DcFwBS18rQgGxeaChQbAoDinkMIHUBmGKMDowobgGJsYYGoLp5oMKPehCL+wABJ8wQlNcKWwAAAABJRU5ErkJggg==',
			'8C08' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYklEQVR4nGNYhQEaGAYTpIn7WAMYQxmmMEx1QBITmcLa6BDKEBCAJBbQKtLg6OjoIIKiTqSBtSEApg7spKVR01YtXRU1NQvJfWjq4OaxNgSimIfdDky3YHPzQIUfFSEW9wEASy7NFbQerdgAAAAASUVORK5CYII=',
			'AF6E' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYUlEQVR4nGNYhQEaGAYTpIn7GB1EQx1CGUMDkMRYA0QaGB0dHZDViUwRaWBtQBULaAWJMcLEwE6KWjo1bOnUlaFZSO4Dq0MzLzQUpDcQi3mYYuhuAYkxoLl5oMKPihCL+wCg38ptvP6D4wAAAABJRU5ErkJggg==',
			'2E46' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAb0lEQVR4nGNYhQEaGAYTpIn7WANEQxkaHaY6IImJTBFpYGh1CAhAEgtoBYpNdXQQQNYNEgt0dEBx37SpYSszM1OzkN0XINLA2uiIYh6jA1AsNBBIIrmlAchrdEQREwGLobolNBTTzQMVflSEWNwHAIViy7FF5rmPAAAAAElFTkSuQmCC',
			'7128' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcElEQVR4nGNYhQEaGAYTpIn7QkMZAhhCGaY6IIu2MgYwOjoEBKCIsQawNgQ6iCCLTQHqbQiAqYO4KQoIV2ZNzUJyH6MDUF0rA4p5rA1AsSmMKOaJgMQCUMWAegJA+gNQxFhDWUMDUN08QOFHRYjFfQBfDckqZ2ux2gAAAABJRU5ErkJggg==',
			'318B' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYElEQVR4nGNYhQEaGAYTpIn7RAMYAhhCGUMdkMQCpjAGMDo6OgQgq2xlDWBtCHQQQRabwoCsDuyklVGrolaFrgzNQnYfqjqoeQyY5mERC8CiVzSANRTdzQMVflSEWNwHAIz+yILrIe/RAAAAAElFTkSuQmCC',
			'03AF' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZUlEQVR4nGNYhQEaGAYTpIn7GB1YQximMIaGIImxBoi0MoQyOiCrE5nC0Ojo6IgiFtDK0MraEAgTAzspaumqsKWrIkOzkNyHpg4m1ugaGohhhyuaOpBb0PWC3IwuNlDhR0WIxX0ACKTJzWmwTtAAAAAASUVORK5CYII=',
			'02DD' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpIn7GB0YQ1hDGUMdkMRYA1hbWRsdHQKQxESmiDS6NgQ6iCCJBbQyIIuBnRS1dNXSpasis6YhuQ+obgorpt4AdDGRKYwO6GJAtzSgu4XRQTTUFc3NAxV+VIRY3AcAObnLZeSL0gMAAAAASUVORK5CYII=',
			'6620' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdElEQVR4nGNYhQEaGAYTpIn7WAMYQxhCGVqRxUSmsLYyOjpMdUASC2gRaWRtCAgIQBZrEAGSgQ4iSO6LjJoWtmplZtY0JPeFTBFtZWhlhKmD6G0VaXSYgkUsgAHFDrBbHBhQ3AJyM2toAIqbByr8qAixuA8AohnLxN/sHeIAAAAASUVORK5CYII=',
			'CBA6' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcElEQVR4nGNYhQEaGAYTpIn7WENEQximMEx1QBITaRVpZQhlCAhAEgtoFGl0dHR0EEAWA6pkbQh0QHZf1KqpYUtXRaZmIbkPqg7VvAaRRtfQQAcRNDtcG1DFQG5hbQhA0QtyM1AMxc0DFX5UhFjcBwBSNc1huW1Q8AAAAABJRU5ErkJggg==',
			'0F80' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAY0lEQVR4nGNYhQEaGAYTpIn7GB1EQx1CGVqRxVgDRBoYHR2mOiCJiUwRaWBtCAgIQBILaAWpc3QQQXJf1NKpYatCV2ZNQ3Ifmjq4GGtDIIoYNjuwuYURpAvNzQMVflSEWNwHAAHYy0aNWQOPAAAAAElFTkSuQmCC',
			'AC5C' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdElEQVR4nGNYhQEaGAYTpIn7GB0YQ1lDHaYGIImxBrA2ujYwBIggiYlMEWlwBapmQRILaBVpYJ3K6IDsvqil01YtzczMQnYfSB1DQ6ADsr2hoZhiIHWuQDFUO1gbHR0dUNwS0MoYyhDKgOLmgQo/KkIs7gMA0i7MK/f/pD0AAAAASUVORK5CYII=',
			'0282' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdUlEQVR4nGNYhQEaGAYTpIn7GB0YQxhCGaY6IImxBrC2Mjo6BAQgiYlMEWl0bQh0EEESC2hlaHR0dGgQQXJf1NJVS1eFAmkk9wHVTQGa1+iAqjeAFUSi2MHoABSbwoDqlgaQW1DdLBrqEMoYGjIIwo+KEIv7AFFKy22YOW+cAAAAAElFTkSuQmCC',
			'C92E' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAb0lEQVR4nGNYhQEaGAYTpIn7WEMYQxhCGUMDkMREWllbGR0dHZDVBTSKNLo2BKKKNYg0OiDEwE6KWrV0adbKzNAsJPcFNDAGOrQyoullaHSYgibWyNLoEIAqBnaLA6oYyM2soYEobh6o8KMixOI+AMrKyi0dzq+BAAAAAElFTkSuQmCC',
			'172B' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcklEQVR4nGNYhQEaGAYTpIn7GB1EQx1CGUMdkMRYHRgaHR0dHQKQxESBYq4NgQ4iKHoZWhmAYgFI7luZtWraqpWZoVlI7gOqC2BoZUQxj9EBKDqFEc081gaGAHQxEbBaFLeEiDSwhgaiuHmgwo+KEIv7AO1/x9Czt/vtAAAAAElFTkSuQmCC',
			'A343' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcElEQVR4nGNYhQEaGAYTpIn7GB1YQxgaHUIdkMRYA0RaGVodHQKQxESmAFVNdWgQQRILAKpiCHRoCEByX9TSVWErM7OWZiG5D6SOtRGuDgxDQxkaXUMD0M1rdGhEtwPolkZUtwS0Yrp5oMKPihCL+wCBGs5ektt2oQAAAABJRU5ErkJggg==',
			'F7C8' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpIn7QkNFQx1CHaY6IIkFNDA0OjoEBASgibk2CDqIoIq1sjYwwNSBnRQatWra0lWrpmYhuQ8oH4CkDirG6MAKxKjmsQIhuh0iQFXobgGqQHPzQIUfFSEW9wEA79zNeQfKqm8AAAAASUVORK5CYII=',
			'2059' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAeklEQVR4nGNYhQEaGAYTpIn7WAMYAlhDHaY6IImJTGEMYW1gCAhAEgtoZW1lbWB0EEHW3SrS6DoVLgZx07RpK1Mzs6LCkN0XINLo0BAwFVkvUBdIrAFZjLUBZEcAih0iDYwhjI4OKG4JDWUIYAhlQHHzQIUfFSEW9wEAhSbK6RfyKAMAAAAASUVORK5CYII=',
			'B3E6' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAWklEQVR4nGNYhQEaGAYTpIn7QgNYQ1hDHaY6IIkFTBFpZW1gCAhAFmtlaHRtYHQQQFHHAFTH6IDsvtCoVWFLQ1emZiG5D6oOq3kihMSwuAWbmwcq/KgIsbgPAJ9FzJSp6sfVAAAAAElFTkSuQmCC',
			'5D7B' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcklEQVR4nGNYhQEaGAYTpIn7QkNEQ1hDA0MdkMQCGkRaGRoCHQJQxRodgGIiSGKBAUCxRkeYOrCTwqZNW5m1dGVoFrL7WoHqpjCimAcWC2BEMS8AKObogComMkWklbUBVS9rANDNDYwobh6o8KMixOI+ABeJzIlTPxD7AAAAAElFTkSuQmCC',
			'720E' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbElEQVR4nGNYhQEaGAYTpIn7QkMZQximMIYGIIu2srYyhDI6oKhsFWl0dHREFZvC0OjaEAgTg7gpatXSpasiQ7OQ3Ac0aQorQh0YsjYwBKCLiQBVMqLZEQBSieaWgAbRUAd0Nw9Q+FERYnEfALmwyXiot20aAAAAAElFTkSuQmCC',
			'85CD' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7WANEQxlCHUMdkMREpog0MDoEOgQgiQW0ijSwNgg6iKCqC2EFqhRBct/SqKlLl65amTUNyX0iUxgaXRHqoOZhExMBiqHbwdqK7hbWAMYQdDcPVPhREWJxHwAzDctxAssccwAAAABJRU5ErkJggg==',
			'6C68' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpIn7WAMYQxlCGaY6IImJTGFtdHR0CAhAEgtoEWlwbXB0EEEWaxBpYG1ggKkDOykyatqqpVNXTc1Ccl/IFKA6dPNaQXoDUc1rBdmBKobNLdjcPFDhR0WIxX0AdlXNPhsBceQAAAAASUVORK5CYII=',
			'0F25' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAb0lEQVR4nGNYhQEaGAYTpIn7GB1EQx1CGUMDkMRYA0QaGB0dHZDViUwRaWBtCEQRC2gVAZKBrg5I7otaOjVs1crMqCgk94HVtQLNQNc7BVUMZAdDAKMDshjYLQ4MAcjuA6lgDQ2Y6jAIwo+KEIv7AMfrylqD100AAAAAAElFTkSuQmCC',
			'9B53' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcUlEQVR4nGNYhQEaGAYTpIn7WANEQ1hDHUIdkMREpoi0sjYwOgQgiQW0ijS6guRQxVpZpwJpJPdNmzo1bGlm1tIsJPexuoq0glQhm8cANM8BKIJsngDYDlQxkFsYHR1R3AJyM0MoA4qbByr8qAixuA8ADnHM1wYqrNMAAAAASUVORK5CYII=',
			'D456' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAc0lEQVR4nGNYhQEaGAYTpIn7QgMYWllDHaY6IIkFTGGYytrAEBCALNbKEMrawOgggCLG6Mo6ldEB2X1RS4EgMzM1C8l9Aa0irQwNgWjmiYY6NAQ6iKDa0cqKLjaFoZXR0QFFL8jNDKEMKG4eqPCjIsTiPgAG0szti2b2cAAAAABJRU5ErkJggg==',
			'A8CE' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYklEQVR4nGNYhQEaGAYTpIn7GB0YQxhCHUMDkMRYA1hbGR0CHZDViUwRaXRtEEQRC2hlbWUFmoDsvqilK8OWrloZmoXkPjR1YBgaCjKPEc087HaguyWgFdPNAxV+VIRY3AcArtvKekGyESsAAAAASUVORK5CYII=',
			'0322' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAc0lEQVR4nGNYhQEaGAYTpIn7GB1YQxhCGaY6IImxBoi0Mjo6BAQgiYlMYWh0bQh0EEESC2hlaAWSDSJI7otauips1cqsVVFI7gOra2VodEDV2+gwBaQf1Q6HAIYpDOhucWAIQHcza2hgaMggCD8qQizuAwAZxss5hmPdzQAAAABJRU5ErkJggg==',
			'1997' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcUlEQVR4nGNYhQEaGAYTpIn7GB0YQxhCGUNDkMRYHVhbGR0dGkSQxEQdRBpdGwJQxBihYgFI7luZtXRpZmbUyiwk9wHtCHQICWhFtZeh0aEhYAqqGEujY0NAAKoYyC2ODshioiFgN6OIDVT4URFicR8AloPJGkhrGecAAAAASUVORK5CYII=',
			'78A5' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAc0lEQVR4nGNYhQEaGAYTpIn7QkMZQximMIYGIIu2srYyhDI6oKhsFWl0dHREFZvC2sraEOjqgOy+qJVhS1dFRkUhuY/RAaQuoEEESS9rg0ijayiqmAhIrCHQAVksoAGsNyAARYwxBCg21WEQhB8VIRb3AQBtSswbbLliiAAAAABJRU5ErkJggg==',
			'60A9' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdklEQVR4nGNYhQEaGAYTpIn7WAMYAhimMEx1QBITmcIYwhDKEBCAJBbQwtrK6OjoIIIs1iDS6NoQCBMDOykyatrK1FVRUWFI7guZAlIXMBVFbytQLBRoAooYaytrQwCKHSC3AMVQ3AJyM1AMxc0DFX5UhFjcBwCJs8zGPl/JuAAAAABJRU5ErkJggg==',
			'884C' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYklEQVR4nGNYhQEaGAYTpIn7WAMYQxgaHaYGIImJTGFtZWh1CBBBEgtoFQGqcnRgQVcX6OiA7L6lUSvDVmZmZiG7D6SOtRGuDm6ea2gghphDIxY7GlHdgs3NAxV+VIRY3AcAcLHMcFFlHGsAAAAASUVORK5CYII='
        );
        $this->text = array_rand( $images );
        return $images[ $this->text ] ;
    }

    function out_processing_gif(){
        $image = dirname(__FILE__) . '/processing.gif';
        $base64_image = "R0lGODlhFAAUALMIAPh2AP+TMsZiALlcAKNOAOp4ANVqAP+PFv///wAAAAAAAAAAAAAAAAAAAAAAAAAAACH/C05FVFNDQVBFMi4wAwEAAAAh+QQFCgAIACwAAAAAFAAUAAAEUxDJSau9iBDMtebTMEjehgTBJYqkiaLWOlZvGs8WDO6UIPCHw8TnAwWDEuKPcxQml0Ynj2cwYACAS7VqwWItWyuiUJB4s2AxmWxGg9bl6YQtl0cAACH5BAUKAAgALAEAAQASABIAAAROEMkpx6A4W5upENUmEQT2feFIltMJYivbvhnZ3Z1h4FMQIDodz+cL7nDEn5CH8DGZhcLtcMBEoxkqlXKVIgAAibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkphaA4W5upMdUmDQP2feFIltMJYivbvhnZ3V1R4BNBIDodz+cL7nDEn5CH8DGZAMAtEMBEoxkqlXKVIg4HibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkpjaE4W5tpKdUmCQL2feFIltMJYivbvhnZ3R0A4NMwIDodz+cL7nDEn5CH8DGZh8ONQMBEoxkqlXKVIgIBibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkpS6E4W5spANUmGQb2feFIltMJYivbvhnZ3d1x4JMgIDodz+cL7nDEn5CH8DGZgcBtMMBEoxkqlXKVIggEibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkpAaA4W5vpOdUmFQX2feFIltMJYivbvhnZ3V0Q4JNhIDodz+cL7nDEn5CH8DGZBMJNIMBEoxkqlXKVIgYDibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkpz6E4W5tpCNUmAQD2feFIltMJYivbvhnZ3R1B4FNRIDodz+cL7nDEn5CH8DGZg8HNYMBEoxkqlXKVIgQCibbK9YLBYvLtHH5K0J0IACH5BAkKAAgALAEAAQASABIAAAROEMkpQ6A4W5spIdUmHQf2feFIltMJYivbvhnZ3d0w4BMAIDodz+cL7nDEn5CH8DGZAsGtUMBEoxkqlXKVIgwGibbK9YLBYvLtHH5K0J0IADs=";
        $binary = is_file($image) ? join("",file($image)) : base64_decode($base64_image);
        header("Cache-Control: post-check=0, pre-check=0, max-age=0, no-store, no-cache, must-revalidate");
        header("Pragma: no-cache");
        header("Content-type: image/gif");
        echo $binary;
    }

}
# end of class phpfmgImage
# ------------------------------------------------------
# end of module : captcha


# module user
# ------------------------------------------------------
function phpfmg_user_isLogin(){
    return ( isset($_SESSION['authenticated']) && true === $_SESSION['authenticated'] );
}


function phpfmg_user_logout(){
    session_destroy();
    header("Location: admin.php");
}

function phpfmg_user_login()
{
    if( phpfmg_user_isLogin() ){
        return true ;
    };

    $sErr = "" ;
    if( 'Y' == $_POST['formmail_submit'] ){
        if(
            defined( 'PHPFMG_USER' ) && strtolower(PHPFMG_USER) == strtolower($_POST['Username']) &&
            defined( 'PHPFMG_PW' )   && strtolower(PHPFMG_PW) == strtolower($_POST['Password'])
        ){
             $_SESSION['authenticated'] = true ;
             return true ;

        }else{
            $sErr = 'Login failed. Please try again.';
        }
    };

    // show login form
    phpfmg_admin_header();
?>
<form name="frmFormMail" action="" method='post' enctype='multipart/form-data'>
<input type='hidden' name='formmail_submit' value='Y'>
<br><br><br>

<center>
<div style="width:380px;height:260px;">
<fieldset style="padding:18px;" >
<table cellspacing='3' cellpadding='3' border='0' >
	<tr>
		<td class="form_field" valign='top' align='right'>Email :</td>
		<td class="form_text">
            <input type="text" name="Username"  value="<?php echo $_POST['Username']; ?>" class='text_box' >
		</td>
	</tr>

	<tr>
		<td class="form_field" valign='top' align='right'>Password :</td>
		<td class="form_text">
            <input type="password" name="Password"  value="" class='text_box'>
		</td>
	</tr>

	<tr><td colspan=3 align='center'>
        <input type='submit' value='Login'><br><br>
        <?php if( $sErr ) echo "<span style='color:red;font-weight:bold;'>{$sErr}</span><br><br>\n"; ?>
        <a href="admin.php?mod=mail&func=request_password">I forgot my password</a>
    </td></tr>
</table>
</fieldset>
</div>
<script type="text/javascript">
    document.frmFormMail.Username.focus();
</script>
</form>
<?php
    phpfmg_admin_footer();
}


function phpfmg_mail_request_password(){
    $sErr = '';
    if( $_POST['formmail_submit'] == 'Y' ){
        if( strtoupper(trim($_POST['Username'])) == strtoupper(trim(PHPFMG_USER)) ){
            phpfmg_mail_password();
            exit;
        }else{
            $sErr = "Failed to verify your email.";
        };
    };

    $n1 = strpos(PHPFMG_USER,'@');
    $n2 = strrpos(PHPFMG_USER,'.');
    $email = substr(PHPFMG_USER,0,1) . str_repeat('*',$n1-1) .
            '@' . substr(PHPFMG_USER,$n1+1,1) . str_repeat('*',$n2-$n1-2) .
            '.' . substr(PHPFMG_USER,$n2+1,1) . str_repeat('*',strlen(PHPFMG_USER)-$n2-2) ;


    phpfmg_admin_header("Request Password of Email Form Admin Panel");
?>
<form name="frmRequestPassword" action="admin.php?mod=mail&func=request_password" method='post' enctype='multipart/form-data'>
<input type='hidden' name='formmail_submit' value='Y'>
<br><br><br>

<center>
<div style="width:580px;height:260px;text-align:left;">
<fieldset style="padding:18px;" >
<legend>Request Password</legend>
Enter Email Address <b><?php echo strtoupper($email) ;?></b>:<br />
<input type="text" name="Username"  value="<?php echo $_POST['Username']; ?>" style="width:380px;">
<input type='submit' value='Verify'><br>
The password will be sent to this email address.
<?php if( $sErr ) echo "<br /><br /><span style='color:red;font-weight:bold;'>{$sErr}</span><br><br>\n"; ?>
</fieldset>
</div>
<script type="text/javascript">
    document.frmRequestPassword.Username.focus();
</script>
</form>
<?php
    phpfmg_admin_footer();
}


function phpfmg_mail_password(){
    phpfmg_admin_header();
    if( defined( 'PHPFMG_USER' ) && defined( 'PHPFMG_PW' ) ){
        $body = "Here is the password for your form admin panel:\n\nUsername: " . PHPFMG_USER . "\nPassword: " . PHPFMG_PW . "\n\n" ;
        if( 'html' == PHPFMG_MAIL_TYPE )
            $body = nl2br($body);
        mailAttachments( PHPFMG_USER, "Password for Your Form Admin Panel", $body, PHPFMG_USER, 'You', "You <" . PHPFMG_USER . ">" );
        echo "<center>Your password has been sent.<br><br><a href='admin.php'>Click here to login again</a></center>";
    };
    phpfmg_admin_footer();
}


function phpfmg_writable_check(){

    if( is_writable( dirname(PHPFMG_SAVE_FILE) ) && is_writable( dirname(PHPFMG_EMAILS_LOGFILE) )  ){
        return ;
    };
?>
<style type="text/css">
    .fmg_warning{
        background-color: #F4F6E5;
        border: 1px dashed #ff0000;
        padding: 16px;
        color : black;
        margin: 10px;
        line-height: 180%;
        width:80%;
    }

    .fmg_warning_title{
        font-weight: bold;
    }

</style>
<br><br>
<div class="fmg_warning">
    <div class="fmg_warning_title">Your form data or email traffic log is NOT saving.</div>
    The form data (<?php echo PHPFMG_SAVE_FILE ?>) and email traffic log (<?php echo PHPFMG_EMAILS_LOGFILE?>) will be created automatically when the form is submitted.
    However, the script doesn't have writable permission to create those files. In order to save your valuable information, please set the directory to writable.
     If you don't know how to do it, please ask for help from your web Administrator or Technical Support of your hosting company.
</div>
<br><br>
<?php
}


function phpfmg_log_view(){
    $n = isset($_REQUEST['file'])  ? $_REQUEST['file']  : '';
    $files = array(
        1 => PHPFMG_EMAILS_LOGFILE,
        2 => PHPFMG_SAVE_FILE,
    );

    phpfmg_admin_header();

    $file = $files[$n];
    if( is_file($file) ){
        if( 1== $n ){
            echo "<pre>\n";
            echo join("",file($file) );
            echo "</pre>\n";
        }else{
            $man = new phpfmgDataManager();
            $man->displayRecords();
        };


    }else{
        echo "<b>No form data found.</b>";
    };
    phpfmg_admin_footer();
}


function phpfmg_log_download(){
    $n = isset($_REQUEST['file'])  ? $_REQUEST['file']  : '';
    $files = array(
        1 => PHPFMG_EMAILS_LOGFILE,
        2 => PHPFMG_SAVE_FILE,
    );

    $file = $files[$n];
    if( is_file($file) ){
        phpfmg_util_download( $file, PHPFMG_SAVE_FILE == $file ? 'form-data.csv' : 'email-traffics.txt', true, 1 ); // skip the first line
    }else{
        phpfmg_admin_header();
        echo "<b>No email traffic log found.</b>";
        phpfmg_admin_footer();
    };

}


function phpfmg_log_delete(){
    $n = isset($_REQUEST['file'])  ? $_REQUEST['file']  : '';
    $files = array(
        1 => PHPFMG_EMAILS_LOGFILE,
        2 => PHPFMG_SAVE_FILE,
    );
    phpfmg_admin_header();

    $file = $files[$n];
    if( is_file($file) ){
        echo unlink($file) ? "It has been deleted!" : "Failed to delete!" ;
    };
    phpfmg_admin_footer();
}


function phpfmg_util_download($file, $filename='', $toCSV = false, $skipN = 0 ){
    if (!is_file($file)) return false ;

    set_time_limit(0);


    $buffer = "";
    $i = 0 ;
    $fp = @fopen($file, 'rb');
    while( !feof($fp)) {
        $i ++ ;
        $line = fgets($fp);
        if($i > $skipN){ // skip lines
            if( $toCSV ){
              $line = str_replace( chr(0x09), ',', $line );
              $buffer .= phpfmg_data2record( $line, false );
            }else{
                $buffer .= $line;
            };
        };
    };
    fclose ($fp);



    /*
        If the Content-Length is NOT THE SAME SIZE as the real conent output, Windows+IIS might be hung!!
    */
    $len = strlen($buffer);
    $filename = basename( '' == $filename ? $file : $filename );
    $file_extension = strtolower(substr(strrchr($filename,"."),1));

    switch( $file_extension ) {
        case "pdf": $ctype="application/pdf"; break;
        case "exe": $ctype="application/octet-stream"; break;
        case "zip": $ctype="application/zip"; break;
        case "doc": $ctype="application/msword"; break;
        case "xls": $ctype="application/vnd.ms-excel"; break;
        case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
        case "gif": $ctype="image/gif"; break;
        case "png": $ctype="image/png"; break;
        case "jpeg":
        case "jpg": $ctype="image/jpg"; break;
        case "mp3": $ctype="audio/mpeg"; break;
        case "wav": $ctype="audio/x-wav"; break;
        case "mpeg":
        case "mpg":
        case "mpe": $ctype="video/mpeg"; break;
        case "mov": $ctype="video/quicktime"; break;
        case "avi": $ctype="video/x-msvideo"; break;
        //The following are for extensions that shouldn't be downloaded (sensitive stuff, like php files)
        case "php":
        case "htm":
        case "html":
                $ctype="text/plain"; break;
        default:
            $ctype="application/x-download";
    }


    //Begin writing headers
    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: public");
    header("Content-Description: File Transfer");
    //Use the switch-generated Content-Type
    header("Content-Type: $ctype");
    //Force the download
    header("Content-Disposition: attachment; filename=".$filename.";" );
    header("Content-Transfer-Encoding: binary");
    header("Content-Length: ".$len);

    while (@ob_end_clean()); // no output buffering !
    flush();
    echo $buffer ;

    return true;


}
?>
