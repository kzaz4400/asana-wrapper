<?php

namespace kzaz4400\AsanaWrapper\libs;


/**
 * View作成クラス
 */
class View
{
    /** @var string $base_dir VIEWファイルのディレクトリ */
    protected string $base_dir;

    /**
     * @var array
     */
    protected array $defaults;

    /**@var array $layout_variables レイアウトファイルに渡す変数 */
    protected array $layout_variables = [];

    /**
     * @param string $base_dir Viewファイルのあるディレクトリ
     * @param array  $defaults 全てのViewに持たせる変数の配列
     */
    public function __construct(string $base_dir, array $defaults = [])
    {
        $this->base_dir = $base_dir;
        $this->defaults = $defaults;
    }

    /**
     * レイアウトに渡す変数をプロパティにセット
     * @param string $name  変数名
     * @param mixed  $value VIEWファイルで展開する値
     * @return void
     */
    public function setLayoutVar(string $name, mixed $value): void
    {
        $this->layout_variables[$name] = $value;
    }

    /**
     * ビューファイルの読み込み、コンテンツ内に変数を展開して返す
     * @param string      $_path      Viewファイルのパス
     * @param array       $_variables Viewファイルに渡す変数
     * @param bool|string $_layout    レイアウトファイル名
     * @return string 表示されるHTML
     */
    public function render(string $_path, array $_variables = [], bool|string $_layout = false): string
    {
        // テンプレートファイル指定
        $_file = $this->base_dir . '/' . $_path . '.php';

        //ビューに渡す変数をマージしてエスケープして変数展開
        $_array = array_merge($this->defaults, $_variables);
        extract($_array);

        //バッファに保存開始
        ob_start();
        ob_implicit_flush(false);

        //requireの方が？？
        require_once $_file;

        // 変数にコンテンツを展開
        $content = ob_get_clean();

        // レイアウト変数が指定されている
        if ($_layout) {
            //再度メソッドを実行、その際先に読み込んだ内容は$_contentとして引数に渡す 再帰
            $content = $this->render($_layout, array_merge($this->layout_variables, ['_content' => $content]));
        }

        return $content;
    }


}
