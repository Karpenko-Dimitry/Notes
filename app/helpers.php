<?php

use \Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

if (!function_exists('auth_user')) {
    /**
     * Get the available user instance.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null|\App\Models\User
     */
    function auth_user()
    {
        return auth()->user();
    }
}

if (!function_exists('auth_model')) {
    /**
     * @param bool $abortOnFail
     * @param int $errorCode
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    function auth_model(
        $abortOnFail = false,
        $errorCode = 403
    ) {
        $authUser = auth()->user();

        if ($abortOnFail && (!$authUser || !method_exists($authUser, 'getProxyId'))) {
            abort($errorCode);
        }

        return $authUser;
    }
}

if (!function_exists('format_datetime_locale')) {
    /**
     * @param $date
     * @param string $format
     * @return string|null
     */
    function format_datetime_locale($date, string $format = 'short_date_time_locale') {
        try {
            if (is_string($date)) {
                $date = new Carbon($date);
            }

            return $date->formatLocalized(
                config("formats.$format") ?: $format
            );
        } catch (Throwable $throwable) {
            return is_string($date) ? $date : null;
        }
    }
}

if (!function_exists('format_date_locale')) {
    /**
     * @param null $date
     * @param string $format
     * @return string|null
     */
    function format_date_locale(
        $date = null,
        string $format = 'short_date_locale'
    ) {
        if (is_null($date)) {
            return null;
        }

        try {
            if (is_string($date)) {
                $date = new Carbon($date);
            }

            return $date->formatLocalized(
                config("formats.$format") ?: $format
            );
        } catch (Throwable $throwable) {
            return is_string($date) ? $date : null;
        }
    }
}

if (!function_exists('currency_format')) {
    /**
     * @param $number
     * @param int $decimals
     * @param string $dec_point
     * @param string $thousands_sep
     * @return string
     */
    function currency_format($number, $decimals = 2, $dec_point = '.', $thousands_sep = '') {
        return number_format($number, $decimals, $dec_point, $thousands_sep);
    }
}

if (!function_exists('currency_format_locale')) {
    /**
     * @param $number
     * @return string
     */
    function currency_format_locale($number) {
        return ($number % 1 === 0 ? (int) $number : currency_format($number)) . ',-';
    }
}


if (!function_exists('rule_number_format')) {
    /**
     * @param $number
     * @param int $decimals
     * @param string $dec_point
     * @param string $thousands_sep
     * @return string
     */
    function rule_number_format(
        $number,
        $decimals = 2,
        $dec_point = '.',
        $thousands_sep = ''
    ) {
        return number_format(
            (float) (is_numeric($number) ? $number : 0),
            $decimals,
            $dec_point,
            $thousands_sep
        );
    }
}

if (!function_exists('str_terminal_color')) {
    function str_terminal_color(
        string $text,
        string $color = 'green'
    ) {
        $colors = [
            'black' => '30',
            'blue' => '34',
            'green' => '32',
            'cyan' => '36',
            'red' => '31',
            'purple' => '35',
            'brown' => '33',
            'light_gray' => '37',
            'dark_gray' => '30',
            'light_blue' => '34',
            'light_green' => '32',
            'light_cyan' => '36',
            'light_red' => '31',
            'light_purple' => '35',
            'yellow' => '33',
            'white' => '37',
        ];

        $color = $colors[$color] ?? $colors['white'];

        return "\033[{$color}m{$text}\033[0m";
    }
}

if (!function_exists('cache_optional')) {
    /**
     * Try to cache $callback response for $minutes in case of exception skip cache
     *
     * @param string $key
     * @param callable $callback
     * @param float $minutes
     * @param string|null $driver
     * @param bool $reset
     * @return mixed
     */
    function cache_optional(
        string $key,
        callable $callback,
        float $minutes = 1,
        string $driver = null,
        bool $reset = false
    ) {
        try {
            $reset && cache()->driver()->delete($key);
            return cache()->driver($driver)->remember($key, $minutes * 60, $callback);
        } catch (\Psr\SimpleCache\CacheException $throwable) {
            return $callback();
        } catch (\Throwable $throwable) {
            return $callback();
        }
    }
}

if (!function_exists('pretty_file_size')) {
    /**
     * Human readable file size
     * @param $bytes
     * @param int $precision
     * @return string
     */
    function pretty_file_size(
        $bytes,
        $precision = 2
    ) {
        for ($i = 0; ($bytes / 1024) > 0.9; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) .
            ['','k','M','G','T','P','E','Z','Y'][$i] . 'B';
    }
}

if (!function_exists('json_pretty')) {
    /**
     * @param $value
     * @param int $options
     * @param int $depth
     * @return false|string
     */
    function json_pretty($value, $options = 0, $depth = 512) {
        return json_encode($value, $options + JSON_PRETTY_PRINT, $depth);
    }
}

if (!function_exists('log_debug')) {
    /**
     * @param $message
     * @param array $context
     */
    function log_debug($message, array $context = []) {
        if (!is_null($logger = logger())) {
            $logger->debug(is_string($message) ? $message : json_pretty($message), $context);
        }
    }
}

if (!function_exists('filter_bool')) {
    /**
     * @param $value
     * @return bool
     */
    function filter_bool($value) {
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }
}

if (!function_exists('url_extend_get_params')) {
    /**
     * @param string $url
     * @param array $params
     * @return string
     */
    function url_extend_get_params(string $url, array $params = []) {
        $urlData = explode('?', rtrim($url, '/'));

        $urlParams = [];
        parse_str($urlData[1] ?? "", $urlParams);

        return sprintf("%s?%s", rtrim($urlData[0], '/'), http_build_query(array_merge(
            $params, $urlParams
        )));
    }
}

if (!function_exists('http_resolve_url')) {
    /**
     * @param string $url
     * @param string $uri
     * @return string
     */
    function http_resolve_url(string $url, string $uri = ''): string {
        return url(sprintf('%s/%s', rtrim($url, '/'), ltrim($uri, '/')));
    }
}

if (!function_exists('range_between_dates')) {
    /**
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @param null $countDates
     * @return \Illuminate\Support\Collection|Carbon[]
     */
    function range_between_dates(
        Carbon $startDate,
        Carbon $endDate,
        $countDates = null
    ) {
        $dates = collect();
        $diffBetweenDates = $startDate->diffInDays($endDate);

        if ($startDate->isSameDay($endDate)) {
            return $dates->push($endDate);
        }

        if (!$countDates) {
            for ($i = 0; $i <= $diffBetweenDates; $i++) {
                $dates->push($startDate->copy()->addDays($i));
            }

            return $dates;
        }

        $countDates--;
        $countDates = min($countDates, $diffBetweenDates);
        $interval = $diffBetweenDates / $countDates;

        if ($diffBetweenDates > 1) {
            for ($i = 0; $i < $countDates; $i++) {
                $dates->push($startDate->copy()->addDays($i * $interval));
            }
        }

        $dates->push($endDate);

        return $dates;
    }
}

if (!function_exists('trans_fb')) {
    /**
     * Translate the given message with a fallback string if none exists.
     *
     * @param string $id
     * @param string $fallback
     * @param array $parameters
     * @param null $locale
     * @return \Symfony\Component\Translation\TranslatorInterface|string
     */
    function trans_fb($id, $fallback, $parameters = [], $locale = null)
    {
        return ($id === ($translation = trans($id, $parameters, $locale))) ? $fallback : $translation;
    }
}

if (!function_exists('str_var_replace')) {
    function str_var_replace($string, $replace)
    {
        foreach ($replace as $key => $value) {
            $string = str_replace(
                [':'.$key, ':' . Str::upper($key), ':' . Str::ucfirst($key)],
                [$value, Str::upper($value), Str::ucfirst($value)],
                $string
            );
        }

        return $string;
    }
}
if (!function_exists('query_with_trashed')) {
    /**
     * @param Builder|\Illuminate\Database\Eloquent\SoftDeletes|\Illuminate\Database\Eloquent\Relations\Relation $builder
     * @return mixed
     */
    function query_with_trashed($builder)
    {
        return $builder->withTrashed();
    }
}

if (!function_exists('url_locale')) {
    /**
     * @param null $path
     * @param array $parameters
     * @param null $locale
     * @return string
     */
    function url_locale($path = null, $parameters = [], $locale = null)
    {
        $locale = $locale ?? App::getLocale();

        if ($locale === 'en') {
            $prefix = '';
        } else {
            $prefix = "/$locale/";
        }

        if ($path !== null) {
            $path = $locale === 'en' ? $path : "/$locale/" . ltrim($path, '/');
        } else {
            $path = $locale === App::getLocale() ? request()->path() : $prefix . preg_replace('/^' . App::getLocale() . '/', '', request()->path());
        }

        return url($path, $parameters);
    }
}

if (!function_exists('avatarPath')) {
    /**
     * @param null $path
     * @param array $parameters
     * @param null $locale
     * @return string
     */
    function avatarPath($path = null)
    {
        if (!$path) {
            return asset('/assets/img/no-image.png');
        }

        return url(Storage::url($path));
    }
}
