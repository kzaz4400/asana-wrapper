<?php

namespace kzaz4400\AsanaWrapper\libs;


/**
 * HTTP ステータス
 */
class Status
{
    /**
     * ステータスコードの数字からステータステキストを返す
     * @param int $status_code
     * @return string
     */
    public static function text(int $status_code): string
    {
        return empty(self::TEXT[$status_code]) ? "" : self::TEXT[$status_code];
    }


    /**
     * @var int
     */
    public const int HTTP_200_OK = 200;
    /**
     * @var int
     */
    public const int HTTP_201_CREATED = 201;
    /**
     * @var int
     */
    public const int HTTP_202_ACCEPTED = 202;
    /**
     * @var int
     */
    public const int HTTP_203_NON_AUTHORITATIVE_INFORMATION = 203;
    /**
     * @var int
     */
    public const int HTTP_204_NO_CONTENT = 204;
    /**
     * @var int
     */
    public const int HTTP_205_RESET_CONTENT = 205;
    /**
     * @var int
     */
    public const int HTTP_206_PARTIAL_CONTENT = 206;
    /**
     * @var int
     */
    public const int HTTP_207_MULTI_STATUS = 207;
    /**
     * @var int
     */
    public const int HTTP_208_ALREADY_REPORTED = 208;
    /**
     * @var int
     */
    public const int HTTP_226_IM_USED = 226;

    # Redirect
    /**
     * @var int
     */
    public const int HTTP_300_MULTIPLE_CHOICES = 300;
    /**
     * @var int
     */
    public const int HTTP_301_MOVED_PERMANENTLY = 301;
    /**
     * @var int
     */
    public const int HTTP_302_FOUND = 302;
    /**
     * @var int
     */
    public const int HTTP_303_SEE_OTHER = 303;
    /**
     * @var int
     */
    public const int HTTP_304_NOT_MODIFIED = 304;
    /**
     * @var int
     */
    public const int HTTP_305_USE_PROXY = 305;
    /**
     * @var int
     */
    public const int HTTP_306_RESERVED = 306;
    /**
     * @var int
     */
    public const int HTTP_307_TEMPORARY_REDIRECT = 307;
    /**
     * @var int
     */
    public const int HTTP_308_PERMANENT_REDIRECT = 308;

    # Client Error
    /**
     * @var int
     */
    public const int HTTP_400_BAD_REQUEST = 400;
    /**
     * @var int
     */
    public const int HTTP_401_UNAUTHORIZED = 401;
    /**
     * @var int
     */
    public const int HTTP_402_PAYMENT_REQUIRED = 402;
    /**
     * @var int
     */
    public const int HTTP_403_FORBIDDEN = 403;
    /**
     * @var int
     */
    public const int HTTP_404_NOT_FOUND = 404;
    /**
     * @var int
     */
    public const int HTTP_405_METHOD_NOT_ALLOWED = 405;
    /**
     * @var int
     */
    public const int HTTP_406_NOT_ACCEPTABLE = 406;
    /**
     * @var int
     */
    public const int HTTP_407_PROXY_AUTHENTICATION_REQUIRED = 407;
    /**
     * @var int
     */
    public const int HTTP_408_REQUEST_TIMEOUT = 408;
    /**
     * @var int
     */
    public const int HTTP_409_CONFLICT = 409;
    /**
     * @var int
     */
    public const int HTTP_410_GONE = 410;
    /**
     * @var int
     */
    public const int HTTP_411_LENGTH_REQUIRED = 411;
    /**
     * @var int
     */
    public const int HTTP_412_PRECONDITION_FAILED = 412;
    /**
     * @var int
     */
    public const int HTTP_413_REQUEST_ENTITY_TOO_LARGE = 413;
    /**
     * @var int
     */
    public const int HTTP_414_REQUEST_URI_TOO_LONG = 414;
    /**
     * @var int
     */
    public const int HTTP_415_UNSUPPORTED_MEDIA_TYPE = 415;
    /**
     * @var int
     */
    public const int HTTP_416_REQUESTED_RANGE_NOT_SATISFIABLE = 416;
    /**
     * @var int
     */
    public const int HTTP_417_EXPECTATION_FAILED = 417;
    /**
     * @var int
     */
    public const int HTTP_422_UNPROCESSABLE_ENTITY = 422;
    /**
     * @var int
     */
    public const int HTTP_423_LOCKED = 423;
    /**
     * @var int
     */
    public const int HTTP_424_FAILED_DEPENDENCY = 424;
    /**
     * @var int
     */
    public const int HTTP_426_UPGRADE_REQUIRED = 426;
    /**
     * @var int
     */
    public const int HTTP_428_PRECONDITION_REQUIRED = 428;
    /**
     * @var int
     */
    public const int HTTP_429_TOO_MANY_REQUESTS = 429;
    /**
     * @var int
     */
    public const int HTTP_431_REQUEST_HEADER_FIELDS_TOO_LARGE = 431;
    /**
     * @var int
     */
    public const int HTTP_451_UNAVAILABLE_FOR_LEGAL_REASONS = 451;

    # Server Error
    /**
     * @var int
     */
    public const int HTTP_500_INTERNAL_SERVER_ERROR = 500;
    /**
     * @var int
     */
    public const int HTTP_501_NOT_IMPLEMENTED = 501;
    /**
     * @var int
     */
    public const int HTTP_502_BAD_GATEWAY = 502;
    /**
     * @var int
     */
    public const int HTTP_503_SERVICE_UNAVAILABLE = 503;
    /**
     * @var int
     */
    public const int HTTP_504_GATEWAY_TIMEOUT = 504;
    /**
     * @var int
     */
    public const int HTTP_505_HTTP_VERSION_NOT_SUPPORTED = 505;
    /**
     * @var int
     */
    public const int HTTP_506_VARIANT_ALSO_NEGOTIATES = 506;
    /**
     * @var int
     */
    public const int HTTP_507_INSUFFICIENT_STORAGE = 507;
    /**
     * @var int
     */
    public const int HTTP_508_LOOP_DETECTED = 508;
    /**
     * @var int
     */
    public const int HTTP_509_BANDWIDTH_LIMIT_EXCEEDED = 509;
    /**
     * @var int
     */
    public const int HTTP_510_NOT_EXTENDED = 510;
    /**
     * @var int
     */
    public const int HTTP_511_NETWORK_AUTHENTICATION_REQUIRED = 511;

    /**
     * @var array{int:string}
     */
    public const array TEXT = [
        200 => 'Ok',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi Status',
        208 => 'Already Reported',
        226 => 'Im Used',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => 'Reserved',
        307 => 'Temporary Redirect',
        308 => 'Permanent Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request Uri Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        422 => 'Unprocessable Entity',
        423 => 'Locked',
        424 => 'Failed Dependency',
        426 => 'Upgrade Required',
        428 => 'Precondition Required',
        429 => 'Too Many Requests',
        431 => 'Request Header Fields Too Large',
        451 => 'Unavailable For Legal Reasons',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'Http Version Not Supported',
        506 => 'Variant Also Negotiates',
        507 => 'Insufficient Storage',
        508 => 'Loop Detected',
        509 => 'Bandwidth Limit Exceeded',
        510 => 'Not Extended',
        511 => 'Network Authentication Required',
    ];
}

