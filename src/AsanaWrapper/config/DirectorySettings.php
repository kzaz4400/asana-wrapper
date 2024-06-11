<?php

namespace kzaz4400\AsanaWrapper\config;

/**
 * ディレクトリの設定
 * @author Kazuhiko.azezaki<azezaki@oceans-group.com>
 */
class DirectorySettings
{
    /**
     * @var string
     */
    public const string DS = DIRECTORY_SEPARATOR;
    /**
     * @var string
     */
    public const string DOCUMENT_ROOT_DIR = __DIR__ . self::DS . '..' . self::DS . '..' . self::DS;

    /**
     * @var string
     */
    public const string DOCUMENT_APP_DIR = __DIR__ . self::DS . '..' . self::DS;

    /**
     * @var string
     */
    public const string LOG_DIR = __DIR__ . self::DS . '..' . self::DS . 'logs' . self::DS;
}
