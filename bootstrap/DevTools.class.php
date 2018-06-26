<?php
/**
 *
 */
class Dev
{
    static $channel=null;
    static $slackUrl='https://hooks.slack.com/services/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';
    static $active=false;
    static $logMethod='slack';
    /*
    * Config methods
    */
    public static function __callStatic($method, $args)
    {
        // if(self::$active) {
        if(true) {
            if($method=='log') $method = self::$logMethod;
            return call_user_func_array(__CLASS__ . '::' . $method, $args);
        }
    }
    public static function setSlack ( $slackUrl=null)
    {

        // self::$slackUrl = $slackUrl;
    }
    public static function setActive($active)
    {
        self::$active = $active;
    }
    public static function setLog($method)
    {
        self::$logMethod = $method;
    }
    /*
    * Callable methods
    */
    protected static function slack($text, $title = NULL, $block = FALSE, $color = NULL)
    {
        if (empty(self::$slackUrl))
            return FALSE;
        $title = ($title ? '*'.$title.':*'."\n" : '');
        if (gettype($text) == 'boolean')
            $text = ($text ? 'TRUE' : 'FALSE').' (boolean)';
        $text = print_r($text, TRUE);
        if($block)
        {
            $data =
                [
                    "text"        => "```".$title. "```",
                    "username"    => "Log",
                    // "channel"     => self::$channel,
                    "mrkdwn"      => true,
                    "attachments" =>
                        [
                            "fields" =>
                                [
                                    "text"  => $text,
                                    "color" => (!empty($color) ? $color : self::randRGB())
                                ],
                        ]
                ];
        }
        else
            $data =
                [
                    "text"      => $title.$text,
                    "username"  => "Log",
                    // "channel"   => self::$channel,
                    "mrkdwn"    => true
                ];
        $string = json_encode($data);
        $ch=curl_init();
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch,CURLOPT_VERBOSE,false);
        curl_setopt($ch,CURLOPT_HEADER,true);
        curl_setopt($ch,CURLOPT_URL,self::$slackUrl);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$string);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,60);
        curl_setopt($ch,CURLOPT_TIMEOUT,60);
        curl_exec($ch);
    }
    protected static function initErrorHandling()
    {
        error_reporting(E_ALL & ~E_STRICT);
        set_error_handler(function($errno, $errstr, $errfile, $errline, $errcontext)
        {
            $ignoreErrors =
                [
                    E_DEPRECATED,
                    E_NOTICE
                ];
            $humanErrors =
                [
                    E_ERROR             => "E_ERROR",               // 1
                    E_WARNING           => "E_WARNING",             // 2
                    E_PARSE             => "E_PARSE",               // 4
                    E_NOTICE            => "E_NOTICE",              // 8
                    E_CORE_ERROR        => "E_CORE_ERROR",          // 16
                    E_CORE_WARNING      => "E_CORE_WARNING",        // 32
                    E_COMPILE_ERROR     => "E_COMPILE_ERROR",       // 64
                    E_COMPILE_WARNING   => "E_COMPILE_WARNING",     // 128
                    E_USER_ERROR        => "E_USER_ERROR",          // 256
                    E_USER_WARNING      => "E_USER_WARNING",        // 512
                    E_USER_NOTICE       => "E_USER_NOTICE",         // 1024
                    E_STRICT            => "E_STRICT",              // 2048
                    E_RECOVERABLE_ERROR => "E_RECOVERABLE_ERROR",   // 4096
                    E_DEPRECATED        => "E_DEPRECATED",          // 8192
                    E_USER_DEPRECATED   => "E_USER_DEPRECATED",     // 16384
                    E_ALL               => "E_ALL"                  // 32767
                ];
            if (!in_array($errno, $ignoreErrors))
            {
                self::slack(
                    [
                        'Error',
                        'Error Number'   => $errno,
                        'Error Number H' => $humanErrors[$errno],
                        'Error Message'  => $errstr,
                        'Error File'     => $errfile,
                        'Error Line'     => $errline,
                        // 'Err Context'    => $errcontext
                    ]);
            }
        });
    }
    protected static function puke($var, $color = NULL)
    {
        if (empty($color))
            $color = self::randRGBA();
        // $str = substr(print_r($var, TRUE), 1, -1);
        $str = print_r($var, TRUE);
        $style =
            'style="'.
            "background-color: rgba($color,0.2); ".
            "min-width: 400px; ".
            "min-height: 1rem;".
            "padding: 1.6rem;".
            '"';
        $msg = "<div $style ><pre>".$str."</pre></div>";
        echo $msg;
    }
    private static function randRGB()
    {
        for ($i=0; $i < 3; $i++)
        {
            $color[$i] = str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
        }
        $color = '#'.implode($color);
        return $color;
    }
    private static function randRGBA()
    {
        for ($i=0; $i < 3; $i++)
        {
            $color[$i] = rand(0,255);
        }
        $color = implode(',', $color);
        return $color;
    }
}