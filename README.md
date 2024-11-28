# Temporal PHP サンプルプロジェクト

このプロジェクトは、Temporal PHPを使用した基本的なワークフローの実装例です。

## 概要

このサンプルは以下の機能を実装しています：

- 基本的なワークフロー（GreetingWorkflow）
- アクティビティの実装（GreetingActivity）
- ワーカーとクライアントの設定

## 前提条件

- PHP 8.0以上
  - PHP-GRPC拡張機能
  - PHP-Protobuf拡張機能
- Composer 2.x
- 実行中のTemporalサーバー（localhost:7233）

### gRPCとProtobufについて

- gRPC
  - Google が開発した高性能なオープンソースのRPCフレームワークです
  - クライアント/サーバー間の通信を効率的に行うことができます
  - 主な特徴:
    - HTTP/2ベースで双方向ストリーミングをサポート
    - 異なる言語・プラットフォーム間での相互運用が可能
    - 高いパフォーマンスと低いレイテンシー
    - サービス定義が簡単で自動コード生成が可能

- Protocol Buffers (protobuf)
  - これもGoogleが開発した、構造化データのシリアライズフォーマットです
  - gRPCのデフォルトのデータ形式として使用されます
  - 主な特徴:
    - JSONやXMLと比べて非常にコンパクトなデータ形式
    - 高速な処理が可能
    - 言語に依存しない形式定義
    - バージョニングと下位互換性のサポート

使用例:
```
// サービス定義の例（.protoファイル）
service Greeter {
    rpc SayHello (HelloRequest) returns (HelloReply) {}
}

message HelloRequest {
    string name = 1;
}

message HelloReply {
    string message = 1;
}
```

使用例:
```php
$client = new GreeterClient();
$request = new HelloRequest();
$request->setName("World");
list($reply, $status) = $client->SayHello($request)->wait();
echo $reply->getMessage();
```

## 環境セットアップ

### Docker Composeを使用した環境構築（推奨）

2. Docker Composeでサービスを起動：
```bash
docker-compose up -d
```

これにより以下のサービスが起動します：
- Temporalサーバー（ポート7233）
- Temporal Web UI（ポート8080）
- PostgreSQLデータベース（ポート5432）
- PHPワーカー
- アプリケーションコンテナ

3. クライアントの実行：
```bash
docker-compose exec app php client.php
```

※PostgreSQLでも稼働させることも可能です。
staging/production環境ではAuroraPostgreSQLを使用し、保守性を高くできる可能性があります。
Amazon Keyspaces (Apache Cassandra 向け) が利用できるかもしれません。

- Cassandra について
  - 分散型のNoSQLデータベースです
  - 高い可用性とスケーラビリティを持ちます
  - 主な特徴:
    - 分散型データベース
    - 高い可用性とスケーラビリティ
    - データの一貫性と耐久性を重視
  - 開発元と歴史
    - Facebookで開発され、後にApache Software Foundationのプロジェクトに
    - 現在も活発に開発が続けられているオープンソースプロジェクト

## プロジェクト構造

```
.
├── README.md
├── composer.json
├── client.php
├── worker.php
└── src/
    ├── Activities/
    │   ├── GreetingActivity.php
    │   └── GreetingActivityImpl.php
    └── Workflows/
        ├── GreetingWorkflow.php
        └── GreetingWorkflowImpl.php
```

## 実行方法

1. まず、Temporalサーバーが実行されていることを確認してください。

2. ワーカーを起動します：
```bash
php worker.php
```

3. 別のターミナルでクライアントを実行します：
```bash
php client.php
```

## コードの説明

### ワークフロー

`GreetingWorkflow`は単純な挨拶を返すワークフローを定義しています。このワークフローは：

- 名前を受け取り
- アクティビティを呼び出して挨拶メッセージを生成
- 結果を返します

### アクティビティ

`GreetingActivity`は実際の挨拶メッセージを生成するロジックを実装しています：

- 与えられた名前と現在時刻を組み合わせて挨拶メッセージを作成
- フォーマットされたメッセージを返します

## トラブルシューティング

### Composer のアップデートでパーミッションエラーが発��する場合

以下のコマンドを使用してください：
```bash
sudo composer self-update --2
```

### GRPC拡張機能のインストールに失敗する場合

システムの開発ツールが必要な場合があります：

```bash
# Ubuntu/Debian の場合
sudo apt-get install build-essential

# CentOS/RHEL の場合
sudo yum groupinstall 'Development Tools'
```

### Temporal サーバーの接続エラー

サーバーの接続を確認:
```bash
# gRPC ポートのテスト
nc -zv localhost 7233

# Web UI ポートのテスト
nc -zv localhost 8233
```

### Docker 関連の問題

Docker デーモンが実行されていない場合:
```bash
# macOS
open -a Docker

# Linux
sudo systemctl start docker
```

### Temporal サーバーのログの確認

```bash
# Docker の場合
docker logs temporal

# Temporal CLI の場合
temporal server logs
```
