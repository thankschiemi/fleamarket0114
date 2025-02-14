<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# フリーマーケットアプリ - FleaMarket0114

**FleaMarket0114**は、簡単に商品を出品・購入できるフリーマーケットアプリです。お気に入り機能やレビュー投稿機能を提供します。

![トップ画面](resources/diagrams/fleamarket.png)

## 作成した目的

実務に近い開発経験を積むために作成しました。

## アプリケーション URL

http://localhost （または本番 URL）

## 機能一覧

-   会員登録・ログイン（メール認証）
-   商品検索機能
-   商品詳細表示
-   商品出品・購入機能
-   レビュー投稿・閲覧機能
-   お気に入り登録機能
-   決済機能

**追加機能**

-   バリデーション
-   管理画面
-   環境の切り分け（ローカル・本番）
-   レスポンシブ対応
-   AWS を利用したデプロイ

## 使用技術

-   **フレームワーク**: Laravel 8.x
-   **データベース**: MySQL 8.x
-   **サーバー**: Nginx
-   **PHP**: 8.4.2
-   **Node.js**: 18.x
-   **Composer**: 2.x
-   **Docker**
-   **Stripe**（決済）
-   **OS**: Ubuntu 20.04
-   **その他**: AWS（S3, EC2, RDS）

## テーブル設計

![テーブル設計](resources/diagrams/table.png)

## ER 図

以下は、本プロジェクトで使用しているデータベースの ER 図です：

![ER図](resources/diagrams/index.drawio.png)

## 環境構築

1. **リポジトリをクローン**
    ```bash
    git clone https://github.com/thankschiemi/fleamarket0114.git
    ```
2. **環境設定ファイルを作成**
    ```bash
    cp .env.example .env
    ```
3. **必要な依存関係をインストール**
    ```bash
    composer install
    npm install
    npm run dev
    ```
4. **データベースをマイグレーション**
    ```bash
    php artisan migrate --seed
    ```

## ダミーユーザー情報

-   **管理者**
    -   Email: `admin@example.com`
    -   Password: `adminpassword`
-   **一般ユーザー**
    -   Email: `user@example.com`
    -   Password: `userpassword`

## ライセンス

このプロジェクトは、MIT ライセンスの下で公開されています。

## 作者

-   **名前**: 鈴木 智恵美
