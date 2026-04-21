<?php

namespace App\Models;

use PDO;
use PDOException;
use Lib\Database;
use Lib\File;
use Lib\Model;

class User extends Model
{
    /**
     * コンストラクタ
     *
     * インスタンス生成時にプロパティ等の初期化が必要であれば行います。
     */
    public function __construct()
    {
        // 必要に応じた初期化処理を実装
    }

    /**
     * ユーザデータを取得
     *
     * @param int $id ユーザID
     * @return array|null ユーザデータの連想配列、もしくは該当するユーザがなければ null
     */
    public function find(int $id)
    {
        // IDがなければ終了
        if (!$id) return;
        try {
            $pdo = self::pdo();
            $sql = "SELECT * FROM users WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['id' => $id]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            return $user;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return null;
        }
    }

    /**
     * ユーザデータを取得
     *
     * @param string $account_name ユーザのアカウント名
     * @return array|null ユーザデータの連想配列、もしくは該当するユーザがなければ null
     */
    public function findForExists($posts)
    {
        // データがなければ終了
        if (empty($posts)) return;
        try {
            $account_name = $posts['account_name'];
            $email = $posts['email'];

            $pdo = self::pdo();
            $sql = "SELECT * FROM users WHERE account_name = :account_name OR email = :email";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['account_name' => $account_name, 'email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            return $user;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return null;
        }
    }

    /**
     * ユーザデータをDBに登録する
     *
     * @param array $data 登録するユーザデータ
     * @return mixed 登録成功時はユーザID、失敗時は null
     */
    public function insert($data)
    {
        // データがなければ終了
        if (empty($data)) return;
        try {
            // パスワードのハッシュ化
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
            // DB接続
            $pdo = Database::getInstance();
            // SQL作成
            $sql = "INSERT INTO users (account_name, email, display_name, password) 
                    VALUES (:account_name, :email, :display_name, :password)";
            // SQL準備
            $stmt = $pdo->prepare($sql);
            // SQL実行
            $result = $stmt->execute($data);
            // 登録成功時はユーザIDを返す
            if ($result) {
                return $pdo->lastInsertId();
            }
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return null;
        }
        return;
    }

    /**
     * ユーザデータを更新する
     *
     * @param int $id ユーザID
     * @param array $data 更新するユーザデータ
     * @return mixed 更新成功時はユーザデータの連想配列、失敗時は null
     */
    public function update($id, $data)
    {
        try {
            // DB接続
            $pdo = Database::getInstance();
            // SQL作成
            $sql = "UPDATE users
                    SET display_name = :display_name,
                        profile = :profile
                    WHERE id = :id;";
            // SQL準備
            $stmt = $pdo->prepare($sql);
            // SQL実行
            return $stmt->execute([
                'id' => $id,
                'display_name' => $data['display_name'],
                'profile' => $data['profile'],
            ]);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return null;
        }
    }

    /**
     * ユーザ認証
     *
     * @param string $account_name ユーザのアカウント名
     * @param string $password 入力されたパスワード
     * @return mixed 認証成功時はユーザデータの連想配列、失敗時はnull
     */
    public function auth($account_name, $password)
    {
        // DB接続
        $pdo = self::pdo();
        // SQL作成: アカウント名でユーザを検索
        $sql = "SELECT * FROM users WHERE account_name = :account_name";
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['account_name' => $account_name]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            // TODO: password_verify() を使ってパスワードを検証し、ユーザを返す
            // if ($user && password_verify($password, $user['password'])) {
            //     return $user;
            // }
        } catch (PDOException $e) {
            error_log($e->getMessage());
        }
        return;
    }

    /**
     * ユーザのプロフィール画像をアップロードする
     *
     * @param int $user_id ユーザID
     * @return string|null アップロードされた画像のパス、失敗時は null
     */
    public function uploadProfileImage($user_id)
    {
        $profile_image = File::upload(PROFILE_BASE, $user_id);
        if (!$profile_image) {
            return null;
        }
        try {
            $pdo = Database::getInstance();
            $sql = "UPDATE users SET profile_image = :profile_image WHERE id = :id;";
            $stmt = $pdo->prepare($sql);

            return $stmt->execute([
                'id' => $user_id,
                'profile_image' => $profile_image
            ]);
        } catch (PDOException $e) {
            error_log($e->getMessage());
        }
    }

    /**
     * プロフィール画像の保存先パスを取得する
     *
     * @param int $user_id ユーザID
     * @return string プロフィール画像の保存先パス
     */
    public static function profileImage($profile_image)
    {
        // プロフィール画像のパスを取得
        $localPath = BASE_DIR . '/' . $profile_image;
        if ($profile_image && file_exists($localPath)) {
            return $profile_image . "?" . filemtime($localPath);
        }
        return "images/me.png";
    }
}
