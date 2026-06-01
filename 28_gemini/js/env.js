// 現在のホストとプロトコルを取得
const HOST = `${window.location.protocol}//${window.location.host}`;

// 現在のパスからベースディレクトリを抽出（例: /myapp）
const BASE_PATH = window.location.pathname.split('/').slice(0, -1).join('/');