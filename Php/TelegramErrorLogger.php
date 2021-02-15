<?php

class TelegramErrorLogger
{
    private static $self;

    public static function log($result, $content, $use_rt = true)
    {
        try {
            if ($result['ok'] === false) {
                self::$self = new self();
                $e = new \Exception();
                $error = PHP_EOL;
                $error .= '==========[Response]==========';
                $error .= "\n";
                foreach ($result as $key => $value) {
                    if ($value == false) {
                        $error .= $key.":\t\t\tFalse\n";
                    } else {
                        $error .= $key.":\t\t".$value."\n";
                    }
                }
                $array = '=========[Sent Data]==========';
                $array .= "\n";
                if ($use_rt == true) {
                    foreach ($content as $item) {
                        $array .= self::$self->rt($item).PHP_EOL.PHP_EOL;
                    }
                } else {
                    foreach ($content as $key => $value) {
                        $array .= $key.":\t\t".$value."\n";
                    }
                }
                $backtrace = '============[Trace]===========';
                $backtrace .= "\n";
                $backtrace .= $e->getTraceAsString();
                self::$self->_log_to_file($error.$array.$backtrace);
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    private function _log_to_file($error_text)
    {
        try {
            $fileName = __CLASS__.'.txt';
            $myFile = fopen($fileName, 'a+');
            $date = '============[Date]============';
            $date .= "\n";
            $date .= '[ '.date('Y-m-d H:i:s  e').' ] ';
            fwrite($myFile, $date.$error_text."\n\n");
            fclose($myFile);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    private function rt($array, $title = null, $head = true)
    {
        $ref = 'ref';
        $text = '';
        if ($head) {
            $text = "[$ref]";
            $text .= "\n";
        }
        foreach ($array as $key => $value) {
            if($value instanceof CURLFile){
                $text .= $ref.'.'.$key.'= File'.PHP_EOL;
            }else if (is_array($value)) {
                if ($title != null) {
                    $key = $title.'.'.$key;
                }
                $text .= self::rt($value, $key, false);
            } else {
                if (is_bool($value)) {
                    $value = ($value) ? 'true' : 'false';
                }
                if ($title != '') {
                    $text .= $ref.'.'.$title.'.'.$key.'= '.$value.PHP_EOL;
                } else {
                    $text .= $ref.'.'.$key.'= '.$value.PHP_EOL;
                }
            }
        }
        return $text;
    }
}
