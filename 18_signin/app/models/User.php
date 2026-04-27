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
        try {
            // パスワードのハッシュ化
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
            $pdo = Database::getInstance();
            $sql = "INSERT INTO users (account_name, email, display_name, password) 
                    VALUES (:account_name, :email, :display_name, :password)";
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute($data);
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
            $pdo = Database::getInstance();

            $sql = "UPDATE users
                    SET display_name = :display_name,
                        profile = :profile
                    WHERE id = :id;";

            $stmt = $pdo->prepare($sql);

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
        // TODO: SQL作成: アカウント名でユーザを検索
        $sql = "";
        try {
            // SQL用意
            $stmt = $pdo->prepare($sql);
            // SQL実行
            $stmt->execute(['account_name' => $account_name]);
            // 結果取得
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            // TODO: パスワード検証して、$user を返す
            return null;
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
