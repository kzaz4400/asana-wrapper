<?php

namespace kzaz4400\AsanaWrapper\errors;

use DateTime;
use DateTimeImmutable;
use Exception;
use kzaz4400\AsanaWrapper\config\DirectorySettings;
use kzaz4400\AsanaWrapper\libs\Status;
use Throwable;


/**
 * エラーハンドラー
 * @author  Kazuhiko Azezaki <kazuhiko.azezaki@gmail.com>
 */
class ErrorHandler extends Exception
{
    /**
     * @var array|string[]
     */
    public static array $severities = [
        E_ERROR             => 'ERROR',
        E_WARNING           => 'WARNING',
        E_PARSE             => 'PARSING ERROR',
        E_NOTICE            => 'NOTICE',
        E_CORE_ERROR        => 'CORE ERROR',
        E_CORE_WARNING      => 'CORE WARNING',
        E_COMPILE_ERROR     => 'COMPILE ERROR',
        E_COMPILE_WARNING   => 'COMPILE WARNING',
        E_USER_ERROR        => 'USER ERROR',
        E_USER_WARNING      => 'USER WARNING',
        E_USER_NOTICE       => 'USER NOTICE',
        E_STRICT            => 'STRICT NOTICE',
        E_RECOVERABLE_ERROR => 'RECOVERABLE ERROR',
        E_DEPRECATED        => 'DEPRECATED',
        E_USER_DEPRECATED   => 'USER DEPRECATED',
    ];


    /**
     * @var string
     */
    private static string $user = 'unknown';

    /**
     * エラーログのパス
     * @var string
     */
    public const string LOG_PATH = DirectorySettings::LOG_DIR . 'error.log';

    /**
     * @var string
     */
    public string $return_msg;

    /**
     * エラー処理
     * @param string|null    $message
     * @param int|null       $code
     * @param Throwable|null $previous
     */
    public function __construct(?string $message = null, ?int $code = null, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        //ユーザー定義の例外ハンドラ関数を設定する
        set_exception_handler([$this, 'exceptionHandler']);
        // ユーザー定義のエラーハンドラ関数を設定する
        set_error_handler([$this, 'errorHandler']);
        // シャットダウン時に実行する関数を登録する
        register_shutdown_function([$this, 'onShutdown']);

        // エラーの発生したユーザーを特定するため、セッションから個人名を取得
        if (!empty($_SESSION['user_name'])) {
            self::$user = $_SESSION['user_name'];
        }

        // エラーメッセージがない
        $this->return_msg = $message ?? 'Un Caught Error';
        // エラーコードがない
        $this->code = $code ?? 256;
    }

    /**
     * set_exception_handlerのコールバック関数
     * エラーログを吐き、エラーページをレンダリングする
     * @param Throwable $throwable
     * @return bool
     */
    public function exceptionHandler(Throwable $throwable): bool
    {
        $this->return_msg = self::buildMsgForThrowable($throwable);
        self::putLog($this->return_msg);
        return true;
    }


    /**
     * set_error_handlerのコールバック関数
     * エラーログを吐き、エラーページをレンダリングする
     * @param int    $errno
     * @param string $errstr
     * @param string $errfile
     * @param int    $errline
     * @return bool
     */
    public function errorHandler(int $errno, string $errstr, string $errfile, int $errline): bool
    {
        if (self::isFatal($errno)) {
            // FATAL ERRORは処理の続行が不可能なので、exceptionHandler()に処理を引き継ぐ
            return false;
        }

        $message = self::buildMsgForError(
            ['type' => $errno, 'message' => $errstr, 'file' => $errfile, 'line' => $errline,]
        );
        self::putLog($message);

        // @演算子でエラーを抑制している場合は error_reporting() の返り値が0になるので、エラーを出力しない
        if (error_reporting()) {
            // @演算子でエラーを抑制した場合でも
            // Shutdownハンドラ内で error_get_last() を呼び出すとエラーが取れてしまう。
            // その場合にエラーレポートしないよう、 @演算子の有無にかかわらず$messageは保存しておく
            $this->return_msg = $message;
            return true;
        }

        // $this->render500page($message);
        // trueを返すとPHPのエラーハンドラはバイパスされる
        return true;
    }

    /**
     * @throws FatalErrorException
     * @return void
     */
    public function onShutdown(): void
    {
        $lastError = error_get_last();

        if (!empty($lastError) && $this->return_msg === $lastError['message']) {
            // すでにこのエラーはログ出力済みなので何もしない
            return;
        }

        // エラーメッセージが空の場合、エラーlogに残す
        if (empty($this->return_msg)) {
            $this->return_msg = self::buildMsgForError($lastError);
            self::putLog($this->return_msg);
        }

        // 処理続行不可能なE_ERRORが発生して、errorHandler()が拾えなかった場合
        // ここにくる
        if (!headers_sent()) {
            self::renderErrors(
                throw new FatalErrorException('A fatal error has occurred. The process cannot continue.')
            );
        }
    }

    /**
     * エラーをエラーページに書き出す
     * @param Throwable $e
     * @param int       $http_code
     * @return void
     */
    public static function renderErrors(Throwable $e, int $http_code = 500): void
    {
        $msg = self::buildMsgForThrowable($e);

        $params['_error'] = nl2br($msg);
        $params['_title'] = $http_code . ' ' . Status::text($http_code);
        $params['_debug'] = false;
        extract($params);
        ob_start();
        require __DIR__ . '/../views/_http_errors.php';
        // ob_end_clean();
        // バッファをOFFにする
        while (ob_get_level() > 0) {
            ob_end_flush();
        }
        if (!headers_sent()) {
            header('HTTP/1.1 ' . Status::text($http_code));
        }
    }


    /**
     * エラーをログに残す
     * @param string $message
     * @return void
     */
    public static function putLog(string $message): void
    {
        // ログ出力
        error_log($message, 3, self::LOG_PATH);
    }

    /**
     * プログラムの実行が中断される重大なエラーかどうか
     * @param $status
     * @return bool
     */
    public static function isFatal($status): bool
    {
        return (bool)($status & (E_ERROR | E_PARSE | E_CORE_ERROR | E_COMPILE_ERROR | E_USER_ERROR));
    }

    /**
     * 配列からエラーメッセージを組み立てる
     * @param array $error
     * @return string
     */
    public static function buildMsgForError(array $error): string
    {
        $today = new DateTime('now');
        $message = $today->format('Y-m-d H:i:s');
        $message .= '(' . self::$user . ')' . PHP_EOL;
        $message .= self::$severities[$error['type']] . ' => ';
        $message .= '<strong>' . $error['message'] . '</strong>' . PHP_EOL;
        $message .= $error['file'] . '(Line:' . $error['line'] . ')' . PHP_EOL;
        $message .= PHP_EOL . PHP_EOL;

        return $message;
    }

    /**
     * throwableからエラーメッセージを組み立てる
     * @param Throwable $throwable
     * @return string
     */
    public static function buildMsgForThrowable(Throwable $throwable): string
    {
        $today = new DateTimeImmutable('now');
        $message = $today->format('Y-m-d H:i:s');
        $message .= '(' . self::$user . ')' . PHP_EOL;
        $message .= get_class($throwable) . ' => ';
        if ($throwable->getCode() === 0) {
            $message .= self::$severities[2] . PHP_EOL;
        } elseif (array_key_exists($throwable->getCode(), self::$severities)) {
            $message .= self::$severities[$throwable->getCode()] . PHP_EOL;
        } else {
            $message .= self::$severities[1] . PHP_EOL;
        }
        $message .= '<strong>' . $throwable->getMessage() . '</strong>' . PHP_EOL;
        $message .= $throwable->getFile() . '(Line:' . $throwable->getLine() . ')' . PHP_EOL;
        //バックトレースをダンプ
        // $message .= $this->outputVar_dump($throwable->getTrace());
        // バックトレースを文字列
        $message .= $throwable->getTraceAsString();
        $message .= PHP_EOL . PHP_EOL;
        return $message;
    }

    /**
     * @param $var
     * @return false|string
     */
    public function outputVar_dump($var): false|string
    {
        // 出力バッファリング開始
        ob_start();
        var_dump($var);
        // バッファの内容を変数へ格納
        // 出力バッファを消去してバッファリング終了
        return ob_get_clean();
    }
}
