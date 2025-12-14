<?php

  namespace SLTK\Core;

  use stdClass;

  class Utility {
    private const int MILLISECONDS_PER_HOUR = 60 * 60 * 1000;
    private const int MILLISECONDS_PER_MINUTE = 60 * 1000;
    private const int MILLISECONDS_PER_SECOND = 1000;

    public static function cast(stdClass $instance, string $className) {
      return unserialize(sprintf(
        'O:%d:"%s"%s',
        strlen($className),
        $className,
        strstr(strstr(serialize($instance), '"'), ':')
      ));
    }

    public static function defineConstant($name, $value = true): void {
      if(!defined($name)) {
        define($name, $value);
      }
    }

    public static function formatTiming(int $timeInMs): string {
      $hoursMs = ($timeInMs - ($timeInMs % self::MILLISECONDS_PER_HOUR));
      $timeInMs -= $hoursMs;
      $minutesMs = ($timeInMs - ($timeInMs % self::MILLISECONDS_PER_MINUTE));
      $timeInMs -= $minutesMs;
      $secondsMs = ($timeInMs - ($timeInMs % self::MILLISECONDS_PER_SECOND));

      $hours = $hoursMs / self::MILLISECONDS_PER_HOUR;
      $minutes = $minutesMs / self::MILLISECONDS_PER_MINUTE;
      $seconds = $secondsMs / self::MILLISECONDS_PER_SECOND;
      $milliseconds = $timeInMs - $secondsMs;

      return self::padZero($minutes) . ':' . self::padZero($seconds) . '.' . $milliseconds;
    }

    public static function padZero(int $number): string {
      if($number < 10) {
        return '0' . $number;
      }

      return $number;
    }

    public static function transformWpEditorContent($content): string {
      $content = wpautop($content);
      $content = str_replace('\"', '&quot;', $content);

      return str_replace("\'", '&apos;', $content);
    }
  }